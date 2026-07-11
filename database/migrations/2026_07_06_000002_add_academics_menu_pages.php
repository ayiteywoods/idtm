<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            ['slug' => 'academic-calendar', 'title' => 'Academic Calendar', 'content' => 'Key academic dates, teaching periods, and institutional events for MA, MPhil, and PhD programmes at IDTM.'],
            ['slug' => 'courses-ma-mphil-phd', 'title' => 'Courses For MA, MPhil And PhD Programmes', 'content' => 'Course offerings across MA, MPhil, and PhD Development Studies programmes at the Institute of Development & Technology Management.'],
            ['slug' => 'ma-mphil-admissions', 'title' => 'MA & MPhil Admissions Details', 'content' => 'Admission requirements and application guidance for MA and MPhil Development Studies programmes.'],
            ['slug' => 'ma-development-studies', 'title' => 'MA Development Studies', 'content' => 'The Master of Arts (Development Studies) programme is a professional development programme designed for practitioners and leaders in Ghana and West Africa.'],
            ['slug' => 'mphil-development-studies', 'title' => 'MPhil Development Studies', 'content' => 'The Master of Philosophy (Development Studies) programme is a research-focused pathway for advanced scholars and policy professionals.'],
            ['slug' => 'phd-development-studies', 'title' => 'PhD Development Studies', 'content' => 'The PhD (Development Studies) programme prepares researchers for leadership in academia, policy, and technology management.'],
        ];

        $now = now();

        foreach ($pages as $page) {
            if (DB::table('site_pages')->where('slug', $page['slug'])->exists()) {
                continue;
            }

            DB::table('site_pages')->insert([
                'slug' => $page['slug'],
                'title' => $page['title'],
                'content' => $page['content'],
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('site_pages')->whereIn('slug', [
            'academic-calendar',
            'courses-ma-mphil-phd',
            'ma-mphil-admissions',
            'ma-development-studies',
            'mphil-development-studies',
            'phd-development-studies',
        ])->delete();
    }
};
