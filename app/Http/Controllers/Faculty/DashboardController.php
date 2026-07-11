<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Grade;
use App\Models\LearningMaterial;
use App\Models\LibraryBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'url' => ['required', 'url', 'max:2048'],
        ]);

        abort_unless($profile->courses()->where('courses.id', $validated['course_id'])->exists(), 403);

        $profile->learningMaterials()->create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'url' => $validated['url'],
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

        $material->delete();

        return redirect()
            ->route('faculty.materials.index')
            ->with('status', 'Learning material was deleted successfully.');
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
            'external_url' => ['nullable', 'url', 'max:2048'],
        ]);

        LibraryBook::create([
            'uploaded_by' => $request->user()->id,
            'title' => $validated['title'],
            'author' => $validated['author'] ?? null,
            'description' => $validated['description'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'is_published' => true,
        ]);

        return redirect()
            ->route('faculty.library.index')
            ->with('status', 'Library book was added successfully.');
    }

    public function destroyLibraryBook(Request $request, LibraryBook $book): RedirectResponse
    {
        abort_unless($book->uploaded_by === $request->user()->id, 403);

        $book->delete();

        return redirect()
            ->route('faculty.library.index')
            ->with('status', 'Library book was deleted successfully.');
    }
}
