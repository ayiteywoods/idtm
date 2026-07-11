<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\ChangeRequest;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Faq;
use App\Models\Grade;
use App\Models\LibraryBook;
use App\Models\PaymentInstallment;
use App\Models\Specialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->studentProfile()
            ->with(['programme', 'cohort', 'firstSpecialization', 'secondSpecialization', 'paymentPlan'])
            ->firstOrFail();

        $registrations = $profile->registrations()->with('course')->get();
        $pendingRequests = $profile->changeRequests()->where('status', 'pending')->count();

        return view('student.dashboard', [
            'profile' => $profile,
            'stats' => [
                'registered_courses' => $registrations->count(),
                'paid_courses' => $registrations->where('is_paid', true)->count(),
                'outstanding' => $profile->paymentPlan ? number_format($profile->paymentPlan->outstanding(), 2) : '0.00',
                'pending_requests' => $pendingRequests,
            ],
            'upcomingInstallment' => $profile->paymentPlan
                ?->installments()
                ->where('status', 'upcoming')
                ->orderBy('due_date')
                ->first(),
            'notificationCount' => $pendingRequests,
        ]);
    }

    public function profile(Request $request): View
    {
        $profile = $request->user()->studentProfile()
            ->with(['programme', 'cohort', 'firstSpecialization', 'secondSpecialization'])
            ->firstOrFail();

        return view('student.profile', compact('profile'));
    }

    public function wallet(Request $request): View
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $paymentPlan = $profile->paymentPlan()->with('installments')->first();

        return view('student.wallet', compact('profile', 'paymentPlan'));
    }

    public function storeWalletDeposit(Request $request): RedirectResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $paymentPlan = $profile->paymentPlan()->with('installments')->firstOrFail();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.max(1, $paymentPlan->outstanding())],
        ]);

        $amount = (float) $validated['amount'];
        $paymentPlan->increment('total_deposited', $amount);

        $remaining = $amount;
        $paymentPlan->installments()
            ->where('status', 'upcoming')
            ->orderBy('due_date')
            ->get()
            ->each(function (PaymentInstallment $installment) use (&$remaining) {
                if ($remaining <= 0) {
                    return false;
                }

                if ($remaining >= (float) $installment->amount) {
                    $installment->update(['status' => 'passed', 'paid_at' => now()]);
                    $remaining -= (float) $installment->amount;
                }
            });

        return redirect()
            ->route('student.wallet')
            ->with('status', 'Deposit of GHS '.number_format($amount, 2).' was recorded successfully.');
    }

    public function registration(Request $request): View
    {
        $profile = $request->user()->studentProfile()
            ->with(['firstSpecialization', 'secondSpecialization', 'registrations.course'])
            ->firstOrFail();

        return view('student.registration', compact('profile'));
    }

    public function registrationCatalog(Request $request): View
    {
        $profile = $request->user()->studentProfile()
            ->with(['programme', 'registrations'])
            ->firstOrFail();

        $validated = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
        ]);

        $specialization = Specialization::findOrFail($validated['specialization_id']);
        $registeredIds = $profile->registrations->pluck('course_id');

        $courses = Course::query()
            ->where('programme_id', $profile->programme_id)
            ->where('is_active', true)
            ->where(function ($query) use ($specialization) {
                $query->where('specialization_id', $specialization->id)
                    ->orWhere('is_core', true);
            })
            ->whereNotIn('id', $registeredIds)
            ->orderBy('code')
            ->get();

        return view('student.registration-catalog', compact('profile', 'specialization', 'courses'));
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();

        $validated = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $specializationId = (int) $validated['specialization_id'];

        if ($specializationId === (int) $profile->second_specialization_id) {
            $firstRequired = $profile->firstSpecialization?->required_courses ?? 12;
            $firstCount = $profile->registrations()
                ->where('specialization_id', $profile->first_specialization_id)
                ->count();

            abort_if($firstCount < $firstRequired, 403, 'Complete your first specialization before registering for the second.');
        }

        foreach ($validated['course_ids'] as $courseId) {
            CourseRegistration::firstOrCreate(
                [
                    'student_profile_id' => $profile->id,
                    'course_id' => $courseId,
                ],
                [
                    'specialization_id' => $specializationId,
                    'status' => 'registered',
                    'is_paid' => false,
                ]
            );
        }

        return redirect()
            ->route('student.registration')
            ->with('status', 'Course registration was submitted successfully.');
    }

    public function grades(Request $request): View
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $grades = Grade::query()
            ->with('course')
            ->where('student_profile_id', $profile->id)
            ->latest()
            ->paginate(20);

        return view('student.grades', compact('profile', 'grades'));
    }

    public function learningMaterials(Request $request): View
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $registrations = $profile->registrations()
            ->with(['course.learningMaterials', 'course.specialization'])
            ->where('is_paid', true)
            ->get();

        return view('student.learning-materials', compact('profile', 'registrations'));
    }

    public function library(): View
    {
        $books = LibraryBook::where('is_published', true)->latest()->paginate(12);

        return view('student.library', compact('books'));
    }

    public function assessments(Request $request): View
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $courseIds = $profile->registrations()->where('is_paid', true)->pluck('course_id');

        $assessments = Assessment::query()
            ->whereIn('course_id', $courseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => fn ($query) => $query->where('student_profile_id', $profile->id)])
            ->orderByRaw('due_at is null, due_at asc')
            ->latest()
            ->get();

        return view('student.assessments', compact('profile', 'assessments'));
    }

    public function submitAssessment(Request $request, Assessment $assessment): RedirectResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();

        abort_unless($assessment->is_published, 404);
        $this->ensureRegisteredForAssessment($profile, $assessment);

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:'.Assessment::UPLOAD_MAX_KB, 'mimes:'.Assessment::UPLOAD_MIMES],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = AssessmentSubmission::where('assessment_id', $assessment->id)
            ->where('student_profile_id', $profile->id)
            ->first();

        abort_if($existing && $existing->isGraded(), 422, 'This submission has already been graded and cannot be changed.');

        $file = $validated['file'];
        $path = $file->store("submissions/{$assessment->id}", 'local');

        if ($existing && $existing->file_path !== $path) {
            Storage::disk('local')->delete($existing->file_path);
        }

        AssessmentSubmission::updateOrCreate(
            [
                'assessment_id' => $assessment->id,
                'student_profile_id' => $profile->id,
            ],
            [
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'note' => $validated['note'] ?? null,
                'submitted_at' => now(),
                'is_late' => $assessment->isPastDue(),
            ]
        );

        return redirect()
            ->route('student.assessments')
            ->with('status', $assessment->isPastDue()
                ? 'Submission received (flagged late).'
                : 'Submission received successfully.');
    }

    public function downloadAssessmentBrief(Request $request, Assessment $assessment): StreamedResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $this->ensureRegisteredForAssessment($profile, $assessment);

        abort_if(blank($assessment->attachment_path), 404);
        abort_unless(Storage::disk('local')->exists($assessment->attachment_path), 404);

        return Storage::disk('local')->download($assessment->attachment_path, $assessment->attachment_name);
    }

    public function downloadSubmission(Request $request, AssessmentSubmission $submission): StreamedResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        abort_unless($submission->student_profile_id === $profile->id, 403);
        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        return Storage::disk('local')->download($submission->file_path, $submission->original_name);
    }

    private function ensureRegisteredForAssessment(\App\Models\StudentProfile $profile, Assessment $assessment): void
    {
        $isRegistered = $profile->registrations()
            ->where('course_id', $assessment->course_id)
            ->where('is_paid', true)
            ->exists();

        abort_unless($isRegistered, 403, 'You are not registered for this course.');
    }

    public function helpDesk(): View
    {
        $faqs = Faq::where('is_published', true)->orderBy('sort_order')->get();
        $categories = $faqs->pluck('category')->unique()->values();

        return view('student.help-desk', compact('faqs', 'categories'));
    }

    public function changeRequests(Request $request): View
    {
        $profile = $request->user()->studentProfile()->firstOrFail();
        $requests = $profile->changeRequests()->with('registration.course')->latest()->get();
        $eligibleRegistrations = $profile->registrations()
            ->with('course')
            ->where('is_paid', true)
            ->whereDoesntHave('changeRequests', fn ($q) => $q->where('status', 'pending'))
            ->get();

        return view('student.change-requests', [
            'profile' => $profile,
            'requests' => $requests,
            'eligibleRegistrations' => $eligibleRegistrations,
            'stats' => [
                'total' => $requests->count(),
                'pending' => $requests->where('status', 'pending')->count(),
                'resolved' => $requests->whereIn('status', ['approved', 'rejected'])->count(),
            ],
        ]);
    }

    public function storeChangeRequest(Request $request): RedirectResponse
    {
        $profile = $request->user()->studentProfile()->firstOrFail();

        $validated = $request->validate([
            'course_registration_id' => ['required', 'exists:course_registrations,id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $registration = CourseRegistration::query()
            ->where('id', $validated['course_registration_id'])
            ->where('student_profile_id', $profile->id)
            ->where('is_paid', true)
            ->firstOrFail();

        $hasPending = ChangeRequest::query()
            ->where('course_registration_id', $registration->id)
            ->where('status', 'pending')
            ->exists();

        abort_if($hasPending, 422, 'A pending request already exists for this course.');

        ChangeRequest::create([
            'student_profile_id' => $profile->id,
            'course_registration_id' => $registration->id,
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('student.change-requests')
            ->with('status', 'Your change request was submitted successfully.');
    }
}
