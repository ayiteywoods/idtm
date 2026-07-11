<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            ['slug' => 'the-rector', 'title' => 'The Rector', 'content' => "Professor John Andoh Micah serves as Rector of the Institute of Development & Technology Management, providing strategic leadership for academic excellence and institutional growth."],
            ['slug' => 'rectors-welcome', 'title' => "Rector's Welcome", 'content' => "Welcome to the Institute of Development & Technology Management. We educate Ghana's next generation of development and technology leaders."],
            ['slug' => 'rector-office', 'title' => 'Office Administration', 'content' => 'The Office of the Rector coordinates institutional strategy, executive communications, and liaison with governing bodies and stakeholders.'],
            ['slug' => 'faculty', 'title' => 'Faculty', 'content' => 'IDTM faculty combine scholarly expertise with professional practice across development policy, technology management, and business leadership.'],
            ['slug' => 'administration', 'title' => 'Administration', 'content' => 'Professional staff supporting admissions, student services, finance, and campus operations at IDTM.'],
        ];

        $now = now();

        foreach ($pages as $page) {
            $exists = DB::table('site_pages')->where('slug', $page['slug'])->exists();

            if ($exists) {
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

        DB::table('site_pages')
            ->where('slug', 'leadership')
            ->update(['title' => 'Leadership Team', 'updated_at' => $now]);
    }

    public function down(): void
    {
        DB::table('site_pages')->whereIn('slug', [
            'the-rector',
            'rectors-welcome',
            'rector-office',
            'faculty',
            'administration',
        ])->delete();

        DB::table('site_pages')
            ->where('slug', 'leadership')
            ->update(['title' => 'Leadership', 'updated_at' => now()]);
    }
};
