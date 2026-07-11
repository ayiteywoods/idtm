<?php

namespace App\Support;

class PortalNavigation
{
    public static function resolve(): array
    {
        if (request()->routeIs('admin.*')) {
            return self::admin();
        }

        if (request()->routeIs('faculty.*')) {
            return self::faculty();
        }

        return self::student();
    }

    public static function admin(): array
    {
        return [
            'type' => 'admin',
            'name' => 'Administration',
            'subtitle' => 'Control Center',
            'sections' => [
                [
                    'label' => 'Overview',
                    'items' => [
                        ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
                    ],
                ],
                [
                    'label' => 'Management',
                    'items' => [
                        ['label' => 'Students', 'route' => route('admin.students.index'), 'active' => 'admin.students.*', 'icon' => 'users'],
                        ['label' => 'Faculty', 'route' => route('admin.faculty.index'), 'active' => 'admin.faculty.*', 'icon' => 'academic'],
                        ['label' => 'Courses', 'route' => route('admin.courses.index'), 'active' => 'admin.courses.*', 'icon' => 'book'],
                    ],
                ],
                [
                    'label' => 'Operations',
                    'items' => [
                        ['label' => 'Exam Reports', 'route' => route('admin.exam-reports.index'), 'active' => 'admin.exam-reports.*', 'icon' => 'grade'],
                        ['label' => 'Change Requests', 'route' => route('admin.change-requests.index'), 'active' => 'admin.change-requests.*', 'icon' => 'swap'],
                    ],
                ],
                [
                    'label' => 'Content',
                    'items' => [
                        ['label' => 'Site Settings', 'route' => route('admin.settings.index'), 'active' => 'admin.settings.*', 'icon' => 'settings'],
                        ['label' => 'Website CMS', 'route' => route('admin.website.index'), 'active' => 'admin.website.*', 'icon' => 'globe'],
                    ],
                ],
            ],
        ];
    }

    public static function student(): array
    {
        return [
            'type' => 'student',
            'name' => 'Student Portal',
            'subtitle' => 'Academic Hub',
            'sections' => [
                [
                    'label' => 'Main',
                    'items' => [
                        ['label' => 'Dashboard', 'route' => route('student.dashboard'), 'active' => 'student.dashboard', 'icon' => 'dashboard'],
                        ['label' => 'My Profile', 'route' => route('student.profile'), 'active' => 'student.profile', 'icon' => 'user'],
                    ],
                ],
                [
                    'label' => 'Academics',
                    'items' => [
                        ['label' => 'Course Registration', 'route' => route('student.registration'), 'active' => 'student.registration*', 'icon' => 'clipboard'],
                        ['label' => 'Assessments', 'route' => route('student.assessments'), 'active' => 'student.assessments*', 'icon' => 'clipboard'],
                        ['label' => 'My Grades', 'route' => route('student.grades'), 'active' => 'student.grades', 'icon' => 'grade'],
                        ['label' => 'Learning Materials', 'route' => route('student.learning-materials'), 'active' => 'student.learning-materials', 'icon' => 'folder'],
                        ['label' => 'Online Library', 'route' => route('student.library'), 'active' => 'student.library', 'icon' => 'library'],
                    ],
                ],
                [
                    'label' => 'Finance',
                    'items' => [
                        ['label' => 'My Wallet', 'route' => route('student.wallet'), 'active' => 'student.wallet', 'icon' => 'wallet'],
                    ],
                ],
                [
                    'label' => 'Support',
                    'items' => [
                        ['label' => 'Help Desk', 'route' => route('student.help-desk'), 'active' => 'student.help-desk', 'icon' => 'help'],
                        ['label' => 'Change Requests', 'route' => route('student.change-requests'), 'active' => 'student.change-requests', 'icon' => 'swap'],
                    ],
                ],
            ],
        ];
    }

    public static function faculty(): array
    {
        return [
            'type' => 'faculty',
            'name' => 'Faculty Portal',
            'subtitle' => 'Teaching Hub',
            'sections' => [
                [
                    'label' => 'Overview',
                    'items' => [
                        ['label' => 'Dashboard', 'route' => route('faculty.dashboard'), 'active' => 'faculty.dashboard', 'icon' => 'dashboard'],
                    ],
                ],
                [
                    'label' => 'Teaching',
                    'items' => [
                        ['label' => 'My Courses', 'route' => route('faculty.courses.index'), 'active' => 'faculty.courses.*', 'icon' => 'book'],
                        ['label' => 'Assessments', 'route' => route('faculty.assessments.index'), 'active' => 'faculty.assessments.*', 'icon' => 'clipboard'],
                        ['label' => 'Learning Materials', 'route' => route('faculty.materials.index'), 'active' => 'faculty.materials.*', 'icon' => 'folder'],
                        ['label' => 'Grades & Marks', 'route' => route('faculty.grades.index'), 'active' => 'faculty.grades.*', 'icon' => 'grade'],
                    ],
                ],
                [
                    'label' => 'Resources',
                    'items' => [
                        ['label' => 'Online Library', 'route' => route('faculty.library.index'), 'active' => 'faculty.library.*', 'icon' => 'library'],
                    ],
                ],
            ],
        ];
    }
}
