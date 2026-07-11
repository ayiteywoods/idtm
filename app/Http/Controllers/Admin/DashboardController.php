<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChangeRequest;
use App\Models\Cohort;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\FacultyProfile;
use App\Models\Faq;
use App\Models\LibraryBook;
use App\Models\Programme;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Specialization;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\PageBlockUploader;
use App\Support\AdminDashboardData;
use App\Support\HomeHeroSettings;
use App\Support\SitePageContent;
use App\Support\SiteSettings;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', AdminDashboardData::compose());
    }

    public function siteSettings(): View
    {
        return view('admin.settings.index', [
            'settings' => SiteSettings::all(),
        ]);
    }

    public function updateSiteSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'footer_intro' => ['required', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:100'],
            'contact_address' => ['required', 'string', 'max:255'],
        ]);

        SiteSettings::save($validated);

        return redirect()
            ->route('admin.settings.index')
            ->with('status', 'Site settings were updated successfully.');
    }

    public function students(Request $request): View
    {
        $perPage = $this->perPage($request);
        [$sort, $direction] = $this->sorting($request, [
            'name' => 'last_name',
            'index' => 'index_number',
            'programme' => 'programme_id',
            'cohort' => 'cohort_id',
            'created' => 'created_at',
        ], 'created');

        $students = StudentProfile::with([
            'user',
            'programme',
            'cohort',
            'firstSpecialization',
            'secondSpecialization',
            'registrations.course',
            'grades.course',
            'paymentPlan',
        ])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'total' => StudentProfile::count(),
            'perPage' => $perPage,
        ]);
    }

    public function createStudent(): View
    {
        return view('admin.students.create', [
            'programmes' => Programme::orderBy('name')->get(),
            'cohorts' => Cohort::orderBy('name')->get(),
            'specializations' => Specialization::orderBy('name')->get(),
        ]);
    }

    public function storeStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'other_names' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'index_number' => ['required', 'string', 'max:255', 'unique:student_profiles,index_number'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'cohort_id' => ['nullable', 'exists:cohorts,id'],
            'first_specialization_id' => ['nullable', 'exists:specializations,id'],
            'second_specialization_id' => ['nullable', 'exists:specializations,id'],
            'gender' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:2'],
            'location' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
        ]);

        $fullName = trim("{$validated['first_name']} ".($validated['other_names'] ?? '')." {$validated['last_name']}");

        $user = User::create([
            'name' => $fullName,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Student,
            'is_active' => true,
        ]);

        StudentProfile::create(collect($validated)->except(['username', 'email', 'password', 'password_confirmation'])->merge([
            'user_id' => $user->id,
        ])->all());

        return redirect()
            ->route('admin.students.index')
            ->with('status', "{$fullName} was created successfully.");
    }

    public function editStudent(StudentProfile $student): View
    {
        $student->load(['user', 'programme', 'cohort', 'firstSpecialization', 'secondSpecialization']);

        return view('admin.students.edit', [
            'student' => $student,
            'programmes' => Programme::orderBy('name')->get(),
            'cohorts' => Cohort::orderBy('name')->get(),
            'specializations' => Specialization::orderBy('name')->get(),
        ]);
    }

    public function printStudent(Request $request, StudentProfile $student): View
    {
        $student->load([
            'user',
            'programme',
            'cohort',
            'firstSpecialization',
            'secondSpecialization',
            'registrations.course',
            'registrations.specialization',
            'grades.course',
            'paymentPlan.installments',
        ]);

        return view('admin.students.print', [
            'student' => $student,
            'mode' => $request->query('mode', 'print'),
            'siteName' => SiteSetting::get('site_name', config('app.name')),
        ]);
    }

    public function updateStudent(Request $request, StudentProfile $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'other_names' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'index_number' => ['required', 'string', 'max:255', Rule::unique('student_profiles', 'index_number')->ignore($student)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'cohort_id' => ['nullable', 'exists:cohorts,id'],
            'first_specialization_id' => ['nullable', 'exists:specializations,id'],
            'second_specialization_id' => ['nullable', 'exists:specializations,id'],
            'gender' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:2'],
            'location' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $student->update(collect($validated)->except(['email', 'is_active'])->all());

        $student->user->update([
            'name' => $student->fresh()->fullName(),
            'email' => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.students.index')
            ->with('status', "{$student->fullName()} was updated successfully.");
    }

    public function destroyStudent(StudentProfile $student): RedirectResponse
    {
        $name = $student->fullName();
        $user = $student->user;

        if ($user) {
            $user->delete();
        } else {
            $student->delete();
        }

        return redirect()
            ->route('admin.students.index')
            ->with('status', "{$name} was deleted successfully.");
    }

    public function faculty(Request $request): View
    {
        $perPage = $this->perPage($request);
        [$sort, $direction] = $this->sorting($request, [
            'name' => 'users.name',
            'employee' => 'employee_id',
            'department' => 'department',
            'title' => 'title',
            'created' => 'faculty_profiles.created_at',
        ], 'created');

        $faculty = FacultyProfile::query()
            ->select('faculty_profiles.*')
            ->join('users', 'users.id', '=', 'faculty_profiles.user_id')
            ->with(['user', 'courses.registrations'])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.faculty.index', [
            'faculty' => $faculty,
            'total' => FacultyProfile::count(),
            'perPage' => $perPage,
        ]);
    }

    public function createFaculty(): View
    {
        return view('admin.faculty.create', [
            'courses' => Course::orderBy('code')->get(),
        ]);
    }

    public function storeFaculty(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'employee_id' => ['required', 'string', 'max:255', 'unique:faculty_profiles,employee_id'],
            'title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Faculty,
            'is_active' => true,
        ]);

        $faculty = FacultyProfile::create([
            'user_id' => $user->id,
            'employee_id' => $validated['employee_id'],
            'title' => $validated['title'] ?? null,
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        $faculty->courses()->sync(
            collect($validated['course_ids'] ?? [])
                ->mapWithKeys(fn ($courseId) => [$courseId => ['assigned_by' => auth()->id(), 'assigned_at' => now()]])
                ->all()
        );

        return redirect()
            ->route('admin.faculty.index')
            ->with('status', "{$validated['name']} was created successfully.");
    }

    public function editFaculty(FacultyProfile $faculty): View
    {
        $faculty->load(['user', 'courses']);

        return view('admin.faculty.edit', [
            'member' => $faculty,
            'courses' => Course::orderBy('code')->get(),
        ]);
    }

    public function updateFaculty(Request $request, FacultyProfile $faculty): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($faculty->user_id)],
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('faculty_profiles', 'employee_id')->ignore($faculty)],
            'title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $faculty->update(collect($validated)->only(['employee_id', 'title', 'department', 'phone'])->all());
        $faculty->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $faculty->courses()->sync(
            collect($validated['course_ids'] ?? [])
                ->mapWithKeys(fn ($courseId) => [$courseId => ['assigned_by' => auth()->id(), 'assigned_at' => now()]])
                ->all()
        );

        return redirect()
            ->route('admin.faculty.index')
            ->with('status', "{$faculty->user->name} was updated successfully.");
    }

    public function destroyFaculty(FacultyProfile $faculty): RedirectResponse
    {
        $name = $faculty->user->name;
        $faculty->user->delete();

        return redirect()
            ->route('admin.faculty.index')
            ->with('status', "{$name} was deleted successfully.");
    }

    public function courses(Request $request): View
    {
        $perPage = $this->perPage($request);
        [$sort, $direction] = $this->sorting($request, [
            'code' => 'code',
            'title' => 'title',
            'programme' => 'programmes.name',
            'students' => 'registrations_count',
            'created' => 'courses.created_at',
        ], 'created');

        $courses = Course::query()
            ->select('courses.*')
            ->leftJoin('programmes', 'programmes.id', '=', 'courses.programme_id')
            ->with(['programme', 'specialization', 'faculty.user'])
            ->withCount('registrations')
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.courses.index', [
            'courses' => $courses,
            'total' => Course::count(),
            'perPage' => $perPage,
        ]);
    }

    public function createCourse(): View
    {
        return view('admin.courses.create', [
            'programmes' => Programme::orderBy('name')->get(),
            'specializations' => Specialization::orderBy('name')->get(),
            'faculty' => FacultyProfile::with('user')->get()->sortBy('user.name'),
        ]);
    }

    public function storeCourse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'code' => ['required', 'string', 'max:255', 'unique:courses,code'],
            'title' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1', 'max:30'],
            'is_core' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'faculty_ids' => ['nullable', 'array'],
            'faculty_ids.*' => ['exists:faculty_profiles,id'],
        ]);

        $course = Course::create([
            'programme_id' => $validated['programme_id'],
            'specialization_id' => $validated['specialization_id'] ?? null,
            'code' => $validated['code'],
            'title' => $validated['title'],
            'credits' => $validated['credits'],
            'is_core' => $request->boolean('is_core'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $course->faculty()->sync(
            collect($validated['faculty_ids'] ?? [])
                ->mapWithKeys(fn ($facultyId) => [$facultyId => ['assigned_by' => auth()->id(), 'assigned_at' => now()]])
                ->all()
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('status', "{$course->code} was created successfully.");
    }

    public function editCourse(Course $course): View
    {
        $course->load('faculty');

        return view('admin.courses.edit', [
            'course' => $course,
            'programmes' => Programme::orderBy('name')->get(),
            'specializations' => Specialization::orderBy('name')->get(),
            'faculty' => FacultyProfile::with('user')->get()->sortBy('user.name'),
        ]);
    }

    public function updateCourse(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'code' => ['required', 'string', 'max:255', Rule::unique('courses', 'code')->ignore($course)],
            'title' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1', 'max:30'],
            'is_core' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'faculty_ids' => ['nullable', 'array'],
            'faculty_ids.*' => ['exists:faculty_profiles,id'],
        ]);

        $course->update([
            'programme_id' => $validated['programme_id'],
            'specialization_id' => $validated['specialization_id'] ?? null,
            'code' => $validated['code'],
            'title' => $validated['title'],
            'credits' => $validated['credits'],
            'is_core' => $request->boolean('is_core'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $course->faculty()->sync(
            collect($validated['faculty_ids'] ?? [])
                ->mapWithKeys(fn ($facultyId) => [$facultyId => ['assigned_by' => auth()->id(), 'assigned_at' => now()]])
                ->all()
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('status', "{$course->code} was updated successfully.");
    }

    public function destroyCourse(Course $course): RedirectResponse
    {
        $code = $course->code;
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('status', "{$code} was deleted successfully.");
    }

    public function changeRequests(Request $request): View
    {
        $perPage = $this->perPage($request);
        [$sort, $direction] = $this->sorting($request, [
            'student' => 'student_profiles.last_name',
            'status' => 'status',
            'created' => 'change_requests.created_at',
        ], 'created');

        $requests = ChangeRequest::query()
            ->select('change_requests.*')
            ->join('student_profiles', 'student_profiles.id', '=', 'change_requests.student_profile_id')
            ->with(['student.user', 'registration.course', 'reviewer'])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.change-requests.index', [
            'requests' => $requests,
            'perPage' => $perPage,
            'stats' => [
                'total' => ChangeRequest::count(),
                'pending' => ChangeRequest::where('status', 'pending')->count(),
                'approved' => ChangeRequest::where('status', 'approved')->count(),
                'rejected' => ChangeRequest::where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function reviewChangeRequest(Request $request, ChangeRequest $changeRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $changeRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($validated['status'] === 'approved') {
            $changeRequest->registration?->update(['status' => 'withdrawn']);
        }

        return redirect()
            ->route('admin.change-requests.index')
            ->with('status', 'Change request was '. $validated['status'] .'.');
    }

    public function destroyChangeRequest(ChangeRequest $changeRequest): RedirectResponse
    {
        $changeRequest->delete();

        return redirect()
            ->route('admin.change-requests.index')
            ->with('status', 'Change request was deleted successfully.');
    }

    public function website(): View
    {
        $pages = SitePage::orderBy('title')->get();

        return view('admin.website.index', [
            'pages' => $pages,
            'faqCount' => Faq::where('is_published', true)->count(),
            'homepageSettings' => HomeHeroSettings::settings(),
            'footerSettings' => SiteSettings::contact(),
            'brandingSettings' => SiteSettings::branding(),
        ]);
    }

    public function editHomepage(): View
    {
        return view('admin.website.homepage', [
            'settings' => HomeHeroSettings::settings(),
        ]);
    }

    public function updateHomepage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:500'],
            'years_of_excellence' => ['required', 'string', 'max:50'],
            'hero_slide_1_alt' => ['required', 'string', 'max:255'],
            'hero_slide_2_alt' => ['required', 'string', 'max:255'],
            'hero_slide_3_alt' => ['required', 'string', 'max:255'],
            'hero_slide_1_image' => ['nullable', 'image', 'max:5120'],
            'hero_slide_2_image' => ['nullable', 'image', 'max:5120'],
            'hero_slide_3_image' => ['nullable', 'image', 'max:5120'],
        ]);

        foreach ([
            'hero_title',
            'hero_subtitle',
            'years_of_excellence',
            'hero_slide_1_alt',
            'hero_slide_2_alt',
            'hero_slide_3_alt',
        ] as $key) {
            $this->saveSetting($key, $validated[$key]);
        }

        foreach ([1, 2, 3] as $index) {
            $field = "hero_slide_{$index}_image";

            if ($request->hasFile($field)) {
                $this->saveSetting($field, $this->storeHeroImage($request->file($field), $index));
            }
        }

        return redirect()
            ->route('admin.website.index')
            ->with('status', 'Homepage hero and images were updated successfully.');
    }

    public function editFooter(): RedirectResponse
    {
        return redirect()->route('admin.settings.index')->withFragment('contact');
    }

    public function updateFooter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'footer_intro' => ['required', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:100'],
            'contact_address' => ['required', 'string', 'max:255'],
        ]);

        SiteSettings::save($validated);

        return redirect()
            ->route('admin.settings.index')
            ->withFragment('contact')
            ->with('status', 'Footer content was updated successfully.');
    }

    public function createWebsitePage(): View
    {
        return view('admin.website.create');
    }

    public function storeWebsitePage(Request $request): RedirectResponse
    {
        $request->merge(['blocks' => $this->decodedBlocks($request)]);

        $validated = $request->validate($this->websitePageRules());

        $slug = $validated['slug'] ?? Str::slug($validated['title']);

        if (SitePage::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(4));
        }

        $blocks = $this->processPageBlocks($request, $validated['blocks'], $slug);

        $page = SitePage::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'eyebrow' => $validated['eyebrow'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'content' => $this->summaryFromBlocks($blocks),
            'blocks' => $blocks,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()
            ->route('admin.website.pages.edit', $page)
            ->with('status', "{$page->title} was created successfully.");
    }

    public function editWebsitePage(SitePage $sitePage): View
    {
        return view('admin.website.edit', [
            'page' => $sitePage,
            'editor' => SitePageContent::editorPayload($sitePage),
        ]);
    }

    public function updateWebsitePage(Request $request, SitePage $sitePage): RedirectResponse
    {
        $request->merge(['blocks' => $this->decodedBlocks($request)]);

        $validated = $request->validate($this->websitePageRules($sitePage));

        $slug = $validated['slug'];
        $blocks = $this->processPageBlocks($request, $validated['blocks'], $slug);

        $sitePage->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'eyebrow' => $validated['eyebrow'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'content' => $this->summaryFromBlocks($blocks),
            'blocks' => $blocks,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.website.pages.edit', $sitePage)
            ->with('status', "{$sitePage->title} was updated successfully.");
    }

    private function websitePageRules(?SitePage $sitePage = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                $sitePage ? 'required' : 'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('site_pages', 'slug')->ignore($sitePage),
            ],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'blocks_json' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'block_images' => ['nullable', 'array'],
            'block_images.*' => ['nullable', 'image', 'max:5120'],
            'block_images.*.*' => ['nullable', 'image', 'max:5120'],
            'blocks' => ['required', 'array'],
        ];
    }

    private function processPageBlocks(Request $request, array $blocks, string $slug): array
    {
        return app(PageBlockUploader::class)->process($request, $blocks, $slug);
    }

    private function summaryFromBlocks(array $blocks): string
    {
        foreach ($blocks as $block) {
            if (($block['type'] ?? null) === 'intro' && filled($block['text'] ?? null)) {
                return (string) $block['text'];
            }

            if (($block['type'] ?? null) === 'rector-profile' && filled($block['intro'] ?? null)) {
                return (string) $block['intro'];
            }
        }

        return '';
    }

    private function decodedBlocks(Request $request): array
    {
        $blocks = json_decode((string) $request->input('blocks_json', '[]'), true);

        return is_array($blocks) ? $blocks : [];
    }

    private function perPage(Request $request): int
    {
        $requested = (int) $request->query('per_page', 20);

        return in_array($requested, [10, 20, 50, 100], true) ? $requested : 20;
    }

    private function sorting(Request $request, array $allowed, string $default): array
    {
        $sortKey = (string) $request->query('sort', $default);
        $direction = (string) $request->query('direction', 'desc');

        if (! array_key_exists($sortKey, $allowed)) {
            $sortKey = $default;
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        return [$allowed[$sortKey], $direction];
    }

    private function saveSetting(string $key, string $value): void
    {
        SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    private function storeHeroImage(UploadedFile $file, int $index): string
    {
        $directory = public_path('images/hero');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = "slide-{$index}-".time().'.'.$file->extension();
        $file->move($directory, $filename);

        return "/images/hero/{$filename}";
    }
}
