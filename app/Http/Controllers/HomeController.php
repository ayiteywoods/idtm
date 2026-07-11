<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Programme;
use App\Models\SitePage;
use App\Models\StudentProfile;
use App\Models\User;
use App\Support\HomeCampusProgrammes;
use App\Support\HomeFeatureGallery;
use App\Support\HomeHeroSettings;
use App\Support\HomeNewsMosaic;
use App\Support\HomeUpcomingEvents;
use App\Support\SitePageContent;
use App\Support\WebsitePageContent;
use App\UserRole;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $programmes = Programme::query()->orderBy('name')->get();
        $hero = HomeHeroSettings::settings();

        return view('website.home', [
            'siteName' => $hero['siteName'],
            'tagline' => $hero['tagline'],
            'heroTitle' => $hero['heroTitle'],
            'heroSubtitle' => $hero['heroSubtitle'],
            'heroSlides' => $hero['slides'],
            'stats' => [
                'students' => StudentProfile::count(),
                'programmes' => $programmes->count(),
                'faculty' => User::where('role', UserRole::Faculty)->count(),
                'years' => $hero['years'],
            ],
            'blogPosts' => BlogPost::query()
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
            'newsMosaic' => HomeNewsMosaic::cells(),
            'upcomingEvents' => HomeUpcomingEvents::all(),
            'featuredProgrammes' => HomeCampusProgrammes::programmes(),
            'featureGallery' => HomeFeatureGallery::panels(),
        ]);
    }

    public function page(string $slug): View
    {
        $page = SitePage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $content = SitePageContent::resolve($page);
        $section = WebsitePageContent::sectionFor($slug);
        $sidebar = $section ? WebsitePageContent::sidebar($section) : [];

        if ($content && filled($page->content) && empty($page->blocks)) {
            $blocks = $content['blocks'] ?? [];
            $introIndex = collect($blocks)->search(fn (array $block) => ($block['type'] ?? null) === 'intro');

            if ($introIndex === false) {
                array_unshift($blocks, ['type' => 'intro', 'text' => $page->content]);
            } else {
                $blocks[$introIndex]['text'] = $page->content;
            }

            $content['blocks'] = $blocks;
        }

        if ($section === 'programmes') {
            $sidebar = array_merge(
                $sidebar,
                Programme::query()
                    ->orderBy('name')
                    ->get()
                    ->map(fn (Programme $p) => [
                        'label' => $p->name,
                        'route' => route('programmes.show', $p),
                    ])
                    ->all()
            );
        }

        $breadcrumbs = [
            ['label' => 'Home', 'route' => route('home')],
        ];

        if ($section) {
            $sectionLabel = match ($section) {
                'about' => 'About',
                'admissions' => 'Admissions',
                'academics' => 'Academics',
                'programmes' => 'Programmes',
                default => ucfirst($section),
            };
            $breadcrumbs[] = [
                'label' => $sectionLabel,
                'route' => route('pages.show', match ($section) {
                    'about' => 'about',
                    'admissions' => 'admission-form',
                    'academics' => 'academic-calendar',
                    'programmes' => 'programmes',
                    default => $slug,
                }),
            ];
        }

        $breadcrumbs[] = ['label' => $page->title];

        return view('website.page', [
            'page' => $page,
            'content' => $content,
            'sidebar' => $sidebar,
            'section' => $section,
            'breadcrumbs' => $breadcrumbs,
            'programmes' => $slug === 'programmes'
                ? Programme::query()->with('specializations')->orderBy('name')->get()
                : null,
        ]);
    }

    public function programme(Programme $programme): View
    {
        $programme->load('specializations', 'courses');

        return view('website.programme', [
            'programme' => $programme,
            'breadcrumbs' => [
                ['label' => 'Home', 'route' => route('home')],
                ['label' => 'Programmes', 'route' => route('pages.show', 'programmes')],
                ['label' => $programme->name],
            ],
            'programmeSidebar' => array_merge(
                [['label' => 'All Programmes', 'slug' => 'programmes']],
                Programme::query()->orderBy('name')->get()->map(fn (Programme $p) => [
                    'label' => $p->name,
                    'route' => route('programmes.show', $p),
                    'slug' => null,
                ])->all()
            ),
        ]);
    }
}
