<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            ['slug' => 'office-of-the-rector', 'title' => 'Office of the Rector', 'content' => 'Strategic leadership and executive direction for the Institute of Development & Technology Management.'],
            ['slug' => 'leadership-and-staff', 'title' => 'Leadership and Staff', 'content' => 'The people who lead, teach, and support the IDTM community.'],
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
            'office-of-the-rector',
            'leadership-and-staff',
        ])->delete();
    }
};
