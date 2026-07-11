<?php

namespace App\Support;

use App\Models\BlogPost;

class HomeNewsMosaic
{
    public static function cells(): array
    {
        $posts = BlogPost::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $congregation = $posts->get(0);
        $featured = $posts->get(1);
        $rector = $posts->get(2);

        return [
            [
                'type' => 'image',
                'area' => 'a',
                'image' => '/images/hero/slide-2.jpg?v=2',
                'alt' => 'IDTM graduation congregation',
                'url' => $congregation ? route('blog.show', $congregation) : route('blog.index'),
            ],
            [
                'type' => 'card',
                'area' => 'b',
                'variant' => 'light',
                'pointer' => 'left',
                'date' => $congregation?->published_at?->format('F j, Y') ?? 'June 7, 2026',
                'title' => $congregation?->title ?? 'IDTM Holds 7th Congregation',
                'excerpt' => $congregation?->excerpt ?? 'Professor John Micah delivering an address at the 7th congregation ceremony of the Institute of Development & Technology Management.',
                'url' => $congregation ? route('blog.show', $congregation) : route('blog.index'),
            ],
            [
                'type' => 'image',
                'area' => 'c',
                'image' => '/images/hero/slide-3.jpg?v=2',
                'alt' => 'IDTM leadership',
                'url' => route('pages.show', 'leadership'),
            ],
            [
                'type' => 'featured',
                'area' => 'd',
                'image' => $featured?->coverImageUrl() ?? '/images/hero/slide-1.jpg?v=2',
                'alt' => $featured?->title ?? 'Campus news',
                'date' => $featured?->published_at?->format('F j, Y') ?? 'May 25, 2025',
                'title' => $featured?->title ?? 'High school exam the most testing of times',
                'excerpt' => $featured?->excerpt ?? 'Insights and perspectives from IDTM faculty on education, development policy, and technology management across Ghana.',
                'url' => $featured ? route('blog.show', $featured) : route('blog.index'),
            ],
            [
                'type' => 'card',
                'area' => 'e',
                'variant' => 'dark',
                'date' => 'June 7, 2026',
                'title' => 'Enroll at IDTM Now',
                'excerpt' => 'The available post-graduate programmes for the upcoming academic year are now open for applications. Secure your place today.',
                'url' => route('pages.show', 'how-to-apply'),
            ],
            [
                'type' => 'image',
                'area' => 'f',
                'image' => '/images/hero/slide-1.jpg?v=2',
                'alt' => 'IDTM campus',
                'url' => route('pages.show', 'campus-life'),
            ],
            [
                'type' => 'card',
                'area' => 'g',
                'variant' => 'light',
                'pointer' => 'top',
                'date' => $rector?->published_at?->format('F j, Y') ?? 'October 20, 2025',
                'title' => $rector?->title ?? 'The Rector',
                'excerpt' => $rector?->excerpt ?? 'Professor John Andoh Micah — Rector. Welcome to the Institute of Development & Technology Management.',
                'url' => $rector ? route('blog.show', $rector) : route('pages.show', 'leadership'),
            ],
        ];
    }
}
