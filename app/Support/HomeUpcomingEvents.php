<?php

namespace App\Support;

class HomeUpcomingEvents
{
    public static function all(): array
    {
        return [
            [
                'title' => 'Positive Psychology',
                'excerpt' => 'Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit.',
                'image' => '/images/hero/slide-3.jpg?v=2',
                'day' => '16',
                'month' => 'DEC',
                'url' => route('pages.show', 'campus-life'),
            ],
            [
                'title' => 'MBA Open Day',
                'excerpt' => 'Meet faculty, explore specializations, and learn about admissions for the upcoming cohort at the Institute of Development & Technology Management.',
                'image' => '/images/hero/slide-1.jpg?v=2',
                'day' => '08',
                'month' => 'APR',
                'url' => route('pages.show', 'how-to-apply'),
            ],
            [
                'title' => 'Leadership Seminar Series',
                'excerpt' => 'Join industry leaders and IDTM faculty for a full-day seminar on development policy, technology management, and innovation in Ghana.',
                'image' => '/images/hero/slide-2.jpg?v=2',
                'day' => '22',
                'month' => 'JAN',
                'url' => route('contact'),
            ],
        ];
    }
}
