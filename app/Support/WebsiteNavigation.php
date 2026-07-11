<?php

namespace App\Support;

class WebsiteNavigation
{
    public static function utilityNav(): array
    {
        return [
            [
                'label' => 'Student',
                'type' => 'dropdown',
                'active' => request()->routeIs('login') || request()->routeIs('student.*'),
                'items' => [
                    ['label' => 'Portal Access', 'route' => route('login')],
                    ['label' => 'How to Apply', 'route' => route('pages.show', 'how-to-apply')],
                    ['label' => 'Entry Requirements', 'route' => route('pages.show', 'admission-requirements')],
                    ['label' => 'Fees & Funding', 'route' => route('pages.show', 'fees-funding')],
                ],
            ],
            [
                'label' => 'Online Libraries',
                'type' => 'dropdown',
                'active' => request()->routeIs('pages.show') && request()->route('slug') === 'library',
                'items' => [
                    ['label' => 'Library Overview', 'route' => route('pages.show', 'library')],
                    ['label' => 'Student Portal Library', 'route' => route('login')],
                ],
            ],
            [
                'label' => 'Policies and Regulations',
                'type' => 'dropdown',
                'active' => request()->routeIs('pages.show') && in_array(request()->route('slug'), [
                    'terms', 'privacy', 'admission-requirements', 'fees-funding',
                ], true),
                'items' => [
                    ['label' => 'Entry Requirements', 'route' => route('pages.show', 'admission-requirements')],
                    ['label' => 'Fees & Funding', 'route' => route('pages.show', 'fees-funding')],
                    ['label' => 'Terms of Service', 'route' => route('pages.show', 'terms')],
                    ['label' => 'Privacy Policy', 'route' => route('pages.show', 'privacy')],
                ],
            ],
            [
                'label' => 'Alumni',
                'type' => 'link',
                'route' => route('pages.show', 'alumni'),
                'active' => request()->routeIs('pages.show') && request()->route('slug') === 'alumni',
            ],
        ];
    }

    public static function mainNav(): array
    {
        $aboutSlugs = WebsitePageContent::sectionSlugs('about');
        $academicSlugs = WebsitePageContent::sectionSlugs('academics');
        $admissionSlugs = WebsitePageContent::admissionActiveSlugs();

        return [
            [
                'label' => 'Home',
                'type' => 'link',
                'route' => route('home'),
                'active' => request()->routeIs('home'),
            ],
            [
                'label' => 'About Us',
                'type' => 'dropdown',
                'active' => request()->routeIs('pages.show') && in_array(request()->route('slug'), $aboutSlugs, true),
                'items' => WebsitePageContent::mainNavAboutItems(),
            ],
            [
                'label' => 'Academics',
                'type' => 'dropdown',
                'active' => request()->routeIs('pages.show') && in_array(request()->route('slug'), $academicSlugs, true),
                'items' => WebsitePageContent::flatNavItems('academics'),
            ],
            [
                'label' => 'Admissions',
                'type' => 'dropdown',
                'active' => request()->routeIs('pages.show') && in_array(request()->route('slug'), $admissionSlugs, true),
                'items' => WebsitePageContent::mainNavAdmissionsItems(),
            ],
            [
                'label' => 'Contact',
                'type' => 'link',
                'route' => route('contact'),
                'active' => request()->routeIs('contact'),
            ],
        ];
    }

    public static function footerLinks(): array
    {
        return [
            'About' => WebsitePageContent::mainNavAboutItems(),
            'Admissions' => WebsitePageContent::mainNavAdmissionsItems(),
            'Resources' => [
                ['label' => 'Blog', 'route' => route('blog.index')],
                ['label' => 'Alumni', 'route' => route('pages.show', 'alumni')],
                ['label' => 'Library', 'route' => route('pages.show', 'library')],
                ['label' => 'Careers', 'route' => route('pages.show', 'careers')],
                ['label' => 'Portal Access', 'route' => route('login')],
            ],
        ];
    }
}
