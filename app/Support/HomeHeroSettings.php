<?php

namespace App\Support;

use App\Models\SiteSetting;

class HomeHeroSettings
{
    private const SLIDES = [
        1 => [
            'image' => '/images/hero/slide-1.jpg?v=2',
            'alt' => 'IDTM campus in Ghana',
        ],
        2 => [
            'image' => '/images/hero/slide-2.jpg?v=2',
            'alt' => 'Ghanaian graduates celebrating at IDTM',
        ],
        3 => [
            'image' => '/images/hero/slide-3.jpg?v=2',
            'alt' => 'Students learning at a modern business school in Accra, Ghana',
        ],
    ];

    public static function settings(): array
    {
        return [
            'siteName' => SiteSetting::get('site_name', config('app.name')),
            'tagline' => SiteSetting::get('tagline', 'Knowledge and Excellence'),
            'heroTitle' => SiteSetting::get('hero_title', 'Shape Your Future in Development & Technology'),
            'heroSubtitle' => SiteSetting::get('hero_subtitle', 'Join a community of leaders, innovators, and change-makers at the Institute of Development & Technology Management.'),
            'years' => SiteSetting::get('years_of_excellence', '15+'),
            'slides' => self::slides(),
        ];
    }

    public static function slides(): array
    {
        return collect(self::SLIDES)
            ->map(fn (array $slide, int $index) => [
                'image' => SiteSetting::get("hero_slide_{$index}_image", $slide['image']),
                'alt' => SiteSetting::get("hero_slide_{$index}_alt", $slide['alt']),
            ])
            ->values()
            ->all();
    }
}
