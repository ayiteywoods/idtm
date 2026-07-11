<?php

namespace App\Support;

class HomeCampusProgrammes
{
    public static function programmes(): array
    {
        return [
            [
                'name' => 'MA Development Studies',
                'description' => 'The Master of Arts (Development Studies) programme is a professional development programme designed for practitioners and leaders in Ghana and West Africa.',
                'icon' => 'ma',
                'url' => route('pages.show', 'ma-development-studies'),
            ],
            [
                'name' => 'MPhil Development Studies',
                'description' => 'The Master of Philosophy (Development Studies) programme is run as a research-focused pathway for advanced scholars and policy professionals.',
                'icon' => 'mphil',
                'url' => route('pages.show', 'mphil-development-studies'),
            ],
            [
                'name' => 'PhD Development Studies',
                'description' => 'The PhD (Development Studies) programme is run as a regular programme, preparing researchers for leadership in academia, policy, and technology management.',
                'icon' => 'phd',
                'url' => route('pages.show', 'phd-development-studies'),
            ],
        ];
    }
}
