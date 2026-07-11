<?php

namespace App\Support;

use App\Models\ChangeRequest;
use App\Models\Cohort;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\FacultyProfile;
use App\Models\LibraryBook;
use App\Models\Programme;
use App\Models\SitePage;
use App\Models\StudentProfile;
use Illuminate\Support\Str;

class AdminDashboardData
{
    public static function compose(): array
    {
        $pendingRequests = ChangeRequest::where('status', 'pending')->count();
        $draftPages = SitePage::where('is_published', false)->count();
        $totalStudents = StudentProfile::count();
        $newStudentsThisMonth = StudentProfile::where('created_at', '>=', now()->startOfMonth())->count();
        $newStudentsThisWeek = StudentProfile::where('created_at', '>=', now()->startOfWeek())->count();
        $coursesWithoutFaculty = Course::query()
            ->where('is_active', true)
            ->whereDoesntHave('faculty')
            ->count();

        return [
            'greeting' => self::greeting(),
            'attention' => self::attentionItems(
                $pendingRequests,
                $draftPages,
                $newStudentsThisWeek,
                $coursesWithoutFaculty,
            ),
            'statSections' => self::statSections(
                $totalStudents,
                $newStudentsThisMonth,
                $pendingRequests,
                $draftPages,
            ),
            'recentStudents' => StudentProfile::with(['user', 'programme', 'cohort'])
                ->latest()
                ->limit(5)
                ->get(),
            'recentCourses' => Course::with(['programme', 'faculty.user'])
                ->latest()
                ->limit(5)
                ->get(),
            'pendingChangeRequests' => ChangeRequest::with(['student.user', 'registration.course'])
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get(),
            'programmeBreakdown' => self::programmeBreakdown($totalStudents),
            'recentActivity' => self::recentActivity(),
            'portalInsights' => self::portalInsights($coursesWithoutFaculty),
            'websiteSnapshot' => self::websiteSnapshot($draftPages),
            'siteSettings' => SiteSettings::all(),
            'notificationCount' => $pendingRequests,
        ];
    }

    private static function greeting(): array
    {
        $hour = now()->hour;

        $timeGreeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };

        return [
            'time' => $timeGreeting,
            'name' => auth()->user()->name,
            'date' => now()->format('l, j F Y'),
            'institution' => SiteSettings::get('site_name'),
        ];
    }

    private static function attentionItems(
        int $pendingRequests,
        int $draftPages,
        int $newStudentsThisWeek,
        int $coursesWithoutFaculty,
    ): array {
        $items = [];

        if ($pendingRequests > 0) {
            $items[] = [
                'label' => "{$pendingRequests} change ".Str::plural('request', $pendingRequests).' need review',
                'route' => route('admin.change-requests.index'),
                'variant' => 'warning',
            ];
        }

        if ($draftPages > 0) {
            $items[] = [
                'label' => "{$draftPages} website ".Str::plural('page', $draftPages).' still in draft',
                'route' => route('admin.website.index'),
                'variant' => 'warning',
            ];
        }

        if ($coursesWithoutFaculty > 0) {
            $items[] = [
                'label' => "{$coursesWithoutFaculty} active ".Str::plural('course', $coursesWithoutFaculty).' without assigned faculty',
                'route' => route('admin.courses.index'),
                'variant' => 'warning',
            ];
        }

        if ($newStudentsThisWeek > 0) {
            $items[] = [
                'label' => "{$newStudentsThisWeek} new ".Str::plural('student', $newStudentsThisWeek).' registered this week',
                'route' => route('admin.students.index'),
                'variant' => 'info',
            ];
        }

        return $items;
    }

    private static function statSections(
        int $totalStudents,
        int $newStudentsThisMonth,
        int $pendingRequests,
        int $draftPages,
    ): array {
        $studentTrend = $newStudentsThisMonth > 0
            ? "+{$newStudentsThisMonth} this month"
            : null;

        return [
            [
                'label' => 'Operations',
                'stats' => [
                    ['label' => 'Total Students', 'value' => $totalStudents, 'icon' => 'users', 'color' => 'blue', 'href' => route('admin.students.index'), 'trend' => $studentTrend],
                    ['label' => 'Faculty Members', 'value' => FacultyProfile::count(), 'icon' => 'academic', 'color' => 'violet', 'href' => route('admin.faculty.index')],
                    ['label' => 'Pending Requests', 'value' => $pendingRequests, 'icon' => 'swap', 'color' => 'amber', 'href' => route('admin.change-requests.index'), 'hint' => $pendingRequests > 0 ? 'Action needed' : null],
                    ['label' => 'Active Courses', 'value' => Course::where('is_active', true)->count(), 'icon' => 'book', 'color' => 'emerald', 'href' => route('admin.courses.index')],
                ],
            ],
            [
                'label' => 'Academics',
                'stats' => [
                    ['label' => 'Programmes', 'value' => Programme::where('is_active', true)->count(), 'icon' => 'chart', 'color' => 'brand'],
                    ['label' => 'Registrations', 'value' => CourseRegistration::count(), 'icon' => 'clipboard', 'color' => 'blue'],
                    ['label' => 'Active Cohorts', 'value' => Cohort::where('is_active', true)->count(), 'icon' => 'users', 'color' => 'violet'],
                ],
            ],
            [
                'label' => 'Website',
                'stats' => [
                    ['label' => 'Published Pages', 'value' => SitePage::where('is_published', true)->count(), 'icon' => 'globe', 'color' => 'emerald', 'href' => route('admin.website.index')],
                    ['label' => 'Draft Pages', 'value' => $draftPages, 'icon' => 'folder', 'color' => 'amber', 'href' => route('admin.website.index'), 'hint' => $draftPages > 0 ? 'Needs review' : null],
                    ['label' => 'Library Books', 'value' => LibraryBook::where('is_published', true)->count(), 'icon' => 'library', 'color' => 'rose'],
                ],
            ],
        ];
    }

    private static function programmeBreakdown(int $totalStudents): array
    {
        return Programme::query()
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(6)
            ->get()
            ->map(fn (Programme $programme) => [
                'name' => $programme->name,
                'count' => $programme->students_count,
                'percentage' => $totalStudents > 0
                    ? (int) round(($programme->students_count / $totalStudents) * 100)
                    : 0,
            ])
            ->all();
    }

    private static function recentActivity(): array
    {
        $events = collect();

        foreach (StudentProfile::latest()->limit(4)->get() as $student) {
            $events->push([
                'icon' => 'users',
                'message' => "Student registered: {$student->fullName()}",
                'time' => $student->created_at,
                'route' => route('admin.students.edit', $student),
            ]);
        }

        foreach (SitePage::query()->orderByDesc('updated_at')->limit(4)->get() as $page) {
            $events->push([
                'icon' => 'globe',
                'message' => "Page updated: {$page->title}",
                'time' => $page->updated_at,
                'route' => route('admin.website.pages.edit', $page),
            ]);
        }

        foreach (ChangeRequest::with('student')->latest()->limit(4)->get() as $request) {
            $events->push([
                'icon' => 'swap',
                'message' => "Change request: {$request->student->fullName()}",
                'time' => $request->created_at,
                'route' => route('admin.change-requests.index'),
            ]);
        }

        return $events
            ->sortByDesc('time')
            ->take(8)
            ->values()
            ->map(fn (array $event) => [
                ...$event,
                'timeLabel' => $event['time']->diffForHumans(),
            ])
            ->all();
    }

    private static function portalInsights(int $coursesWithoutFaculty): array
    {
        $lastStudent = StudentProfile::latest()->first();
        $lastPage = SitePage::query()->orderByDesc('updated_at')->first();

        return [
            'lastStudent' => $lastStudent ? [
                'name' => $lastStudent->fullName(),
                'ago' => $lastStudent->created_at->diffForHumans(),
                'route' => route('admin.students.edit', $lastStudent),
            ] : null,
            'lastPageUpdate' => $lastPage ? [
                'title' => $lastPage->title,
                'ago' => $lastPage->updated_at->diffForHumans(),
                'route' => route('admin.website.pages.edit', $lastPage),
            ] : null,
            'activeCohorts' => Cohort::where('is_active', true)->count(),
            'coursesWithoutFaculty' => $coursesWithoutFaculty,
        ];
    }

    private static function websiteSnapshot(int $draftPages): array
    {
        $hero = HomeHeroSettings::settings();
        $publishedPages = SitePage::where('is_published', true)->count();
        $lastUpdatedPage = SitePage::query()->orderByDesc('updated_at')->first();

        return [
            'heroTitle' => $hero['heroTitle'],
            'heroImage' => $hero['slides'][0]['image'] ?? null,
            'siteName' => $hero['siteName'],
            'publishedPages' => $publishedPages,
            'draftPages' => $draftPages,
            'lastUpdatedPage' => $lastUpdatedPage ? [
                'title' => $lastUpdatedPage->title,
                'ago' => $lastUpdatedPage->updated_at->diffForHumans(),
                'route' => route('admin.website.pages.edit', $lastUpdatedPage),
            ] : null,
        ];
    }
}
