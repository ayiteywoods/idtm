<?php

namespace App\Support;

class HomeFeatureGallery
{
    public static function panels(): array
    {
        return [
            [
                'type' => 'featured',
                'image' => '/images/hero/slide-3.jpg?v=2',
                'title' => 'Tuition by experience professors',
                'subtitle' => 'Tuition by experience professors',
                'tagline' => 'IDTM — Empowering Minds',
                'url' => route('pages.show', 'about'),
            ],
            [
                'type' => 'strip',
                'image' => '/images/hero/slide-1.jpg?v=2',
                'position' => '20% center',
                'url' => route('pages.show', 'campus-life'),
            ],
            [
                'type' => 'strip',
                'image' => '/images/hero/slide-2.jpg?v=2',
                'position' => '60% center',
                'url' => route('pages.show', 'alumni'),
            ],
            [
                'type' => 'strip',
                'image' => '/images/hero/slide-1.jpg?v=2',
                'position' => '75% center',
                'url' => route('pages.show', 'programmes'),
            ],
            [
                'type' => 'strip',
                'image' => '/images/hero/slide-3.jpg?v=2',
                'position' => '40% center',
                'url' => route('pages.show', 'admissions'),
            ],
            [
                'type' => 'strip',
                'image' => '/images/hero/slide-2.jpg?v=2',
                'position' => '85% center',
                'url' => route('contact'),
            ],
        ];
    }
}
