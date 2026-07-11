<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Grade;
use App\Models\LearningMaterial;
use App\Models\LibraryBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->facultyProfile()->with(['courses' => fn ($q) => $q->withCount('registrations')])->firstOrFail();

        $totalStudents = $profile->courses->sum('registrations_count');

        return view('faculty.dashboard', [
            'profile' => $profile,
            'stats' => [
                'courses' => $profile->courses->count(),
                'students' => $totalStudents,
                'materials' => $profile->learningMaterials()->count(),
                'grades' => $profile->grades()->count(),
            ],
            'recentGrades' => $profile->grades()->with(['student.user', 'course'])->latest()->limit(5)->get(),
            'courses' => $profile->courses,
        ]);
    }

    public function courses(Request $request): View
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        $courses = $profile->courses()->withCount('registrations')->get();

        return view('faculty.courses.index', compact('profile', 'courses'));
    }

    public function courseStudents(Request $request, Course $course): View
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        abort_unless($profile->courses()->where('courses.id', $course->id)->exists(), 403);

        $course->load(['registrations.student.user', 'registrations.student']);

        return view('faculty.courses.students', compact('profile', 'course'));
    }

    public function materials(Request $request): View
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        $materials = $profile->learningMaterials()->with('course')->latest()->paginate(15);
        $courses = $profile->courses;

        return view('faculty.materials.index', compact('profile', 'materials', 'courses'));
    }

    public function storeMaterial(Request $request): RedirectResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:material,exam'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['required', 'file', 'max:'.LearningMaterial::UPLOAD_MAX_KB, 'mimes:'.LearningMaterial::UPLOAD_MIMES],
        ]);

        abort_unless($profile->courses()->where('courses.id', $validated['course_id'])->exists(), 403);

        $file = $validated['file'];
        $path = $file->store("materials/{$validated['course_id']}", 'local');

        $profile->learningMaterials()->create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'is_published' => true,
        ]);

        return redirect()
            ->route('faculty.materials.index')
            ->with('status', 'Learning material was uploaded successfully.');
    }

    public function destroyMaterial(Request $request, LearningMaterial $material): RedirectResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        abort_unless($material->faculty_profile_id === $profile->id, 403);

        if ($material->file_path) {
            Storage::disk('local')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('faculty.materials.index')
            ->with('status', 'Learning material was deleted successfully.');
    }

    public function downloadMaterial(Request $request, LearningMaterial $material): StreamedResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        abort_unless($material->faculty_profile_id === $profile->id, 403);

        abort_if(blank($material->file_path), 404);
        abort_unless(Storage::disk('local')->exists($material->file_path), 404);

        return Storage::disk('local')->download($material->file_path, $material->original_name);
    }

    public function grades(Request $request): View
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        $grades = $profile->grades()
            ->with(['student.user', 'course'])
            ->latest()
            ->paginate(20);
        $courses = $profile->courses()->with(['registrations.student'])->get();

        return view('faculty.grades.index', compact('profile', 'grades', 'courses'));
    }

    public function storeGrade(Request $request): RedirectResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'student_profile_id' => ['required', 'exists:student_profiles,id'],
            'type' => ['required', 'in:assignment,exam,project,quiz'],
            'title' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'is_resit' => ['nullable', 'boolean'],
        ]);

        abort_unless($profile->courses()->where('courses.id', $validated['course_id'])->exists(), 403);

        $isRegistered = CourseRegistration::query()
            ->where('course_id', $validated['course_id'])
            ->where('student_profile_id', $validated['student_profile_id'])
            ->exists();

        abort_unless($isRegistered, 422, 'Selected student is not registered for this course.');

        Grade::create([
            'student_profile_id' => $validated['student_profile_id'],
            'course_id' => $validated['course_id'],
            'faculty_profile_id' => $profile->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'score' => $validated['score'],
            'max_score' => $validated['max_score'],
            'remarks' => $validated['remarks'] ?? null,
            'is_resit' => $request->boolean('is_resit'),
        ]);

        return redirect()
            ->route('faculty.grades.index')
            ->with('status', 'Grade was recorded successfully.');
    }

    public function assessments(Request $request): View
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        $courses = $profile->courses()->orderBy('code')->get();

        $assessments = Assessment::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->with('course')
            ->withCount('submissions')
            ->latest()
            ->paginate(15);

        return view('faculty.assessments.index', compact('profile', 'courses', 'assessments'));
    }

    public function storeAssessment(Request $request): RedirectResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'type' => ['required', 'in:'.implode(',', Assessment::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string', 'max:5000'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'due_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'max:'.Assessment::UPLOAD_MAX_KB, 'mimes:'.Assessment::UPLOAD_MIMES],
        ]);

        abort_unless($profile->courses()->where('courses.id', $validated['course_id'])->exists(), 403);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('assessments/briefs', 'local');
        }

        $profile->assessments()->create([
            'course_id' => $validated['course_id'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'instructions' => $validated['instructions'] ?? null,
            'max_score' => $validated['max_score'],
            'due_at' => $validated['due_at'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_published' => true,
        ]);

        return redirect()
            ->route('faculty.assessments.index')
            ->with('status', 'Assessment was published successfully.');
    }

    public function showAssessment(Request $request, Assessment $assessment): View
    {
        $profile = $this->authorizeAssessment($request, $assessment);

        $assessment->load('course');
        $registeredCount = CourseRegistration::where('course_id', $assessment->course_id)->count();
        $submissions = $assessment->submissions()
            ->with('student.user')
            ->latest('submitted_at')
            ->get();

        return view('faculty.assessments.show', compact('profile', 'assessment', 'submissions', 'registeredCount'));
    }

    public function destroyAssessment(Request $request, Assessment $assessment): RedirectResponse
    {
        $this->authorizeAssessment($request, $assessment);

        foreach ($assessment->submissions as $submission) {
            Storage::disk('local')->delete($submission->file_path);
        }

        if ($assessment->attachment_path) {
            Storage::disk('local')->delete($assessment->attachment_path);
        }

        $assessment->delete();

        return redirect()
            ->route('faculty.assessments.index')
            ->with('status', 'Assessment was deleted successfully.');
    }

    public function gradeSubmission(Request $request, AssessmentSubmission $submission): RedirectResponse
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        $assessment = $submission->assessment()->firstOrFail();
        $this->authorizeAssessment($request, $assessment);

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:'.$assessment->max_score],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
            'graded_by' => $profile->id,
            'graded_at' => now(),
        ]);

        Grade::updateOrCreate(
            [
                'assessment_id' => $assessment->id,
                'student_profile_id' => $submission->student_profile_id,
            ],
            [
                'course_id' => $assessment->course_id,
                'faculty_profile_id' => $profile->id,
                'type' => $assessment->type,
                'title' => $assessment->title,
                'score' => $validated['score'],
                'max_score' => $assessment->max_score,
                'remarks' => $validated['feedback'] ?? null,
            ]
        );

        return redirect()
            ->route('faculty.assessments.show', $assessment)
            ->with('status', 'Submission graded and recorded to the student\'s results.');
    }

    public function downloadSubmission(Request $request, AssessmentSubmission $submission): StreamedResponse
    {
        $assessment = $submission->assessment()->firstOrFail();
        $this->authorizeAssessment($request, $assessment);

        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        return Storage::disk('local')->download($submission->file_path, $submission->original_name);
    }

    public function downloadAssessmentBrief(Request $request, Assessment $assessment): StreamedResponse
    {
        $this->authorizeAssessment($request, $assessment);

        abort_if(blank($assessment->attachment_path), 404);
        abort_unless(Storage::disk('local')->exists($assessment->attachment_path), 404);

        return Storage::disk('local')->download($assessment->attachment_path, $assessment->attachment_name);
    }

    private function authorizeAssessment(Request $request, Assessment $assessment): \App\Models\FacultyProfile
    {
        $profile = $request->user()->facultyProfile()->firstOrFail();
        abort_unless($profile->courses()->where('courses.id', $assessment->course_id)->exists(), 403);

        return $profile;
    }

    public function library(Request $request): View
    {
        $books = LibraryBook::where('uploaded_by', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('faculty.library.index', compact('books'));
    }

    public function storeLibraryBook(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['required', 'file', 'max:'.LibraryBook::UPLOAD_MAX_KB, 'mimes:'.LibraryBook::UPLOAD_MIMES],
        ]);

        $file = $validated['file'];
        $path = $file->store('library', 'local');

        LibraryBook::create([
            'uploaded_by' => $request->user()->id,
            'title' => $validated['title'],
            'author' => $validated['author'] ?? null,
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'is_published' => true,
        ]);

        return redirect()
            ->route('faculty.library.index')
            ->with('status', 'Library book was added successfully.');
    }

    public function destroyLibraryBook(Request $request, LibraryBook $book): RedirectResponse
    {
        abort_unless($book->uploaded_by === $request->user()->id, 403);

        if ($book->file_path) {
            Storage::disk('local')->delete($book->file_path);
        }

        $book->delete();

        return redirect()
            ->route('faculty.library.index')
            ->with('status', 'Library book was deleted successfully.');
    }

    public function downloadLibraryBook(Request $request, LibraryBook $book): StreamedResponse
    {
        abort_unless($book->is_published || $book->uploaded_by === $request->user()->id, 403);

        abort_if(blank($book->file_path), 404);
        abort_unless(Storage::disk('local')->exists($book->file_path), 404);

        return Storage::disk('local')->download($book->file_path, $book->original_name);
    }
}
