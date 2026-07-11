<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            ['slug' => 'admission-form', 'title' => 'Admission Form', 'content' => 'Apply to the Institute of Development & Technology Management by completing the admission form and submitting required documents.'],
            ['slug' => 'brochure', 'title' => 'Brochure', 'content' => 'Download the IDTM programme brochure for an overview of programmes, admission requirements, and campus life.'],
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

        DB::table('site_pages')
            ->where('slug', 'admission-requirements')
            ->update(['title' => 'Entry Requirement', 'updated_at' => $now]);
    }

    public function down(): void
    {
        DB::table('site_pages')->whereIn('slug', [
            'admission-form',
            'brochure',
        ])->delete();

        DB::table('site_pages')
            ->where('slug', 'admission-requirements')
            ->update(['title' => 'Entry Requirements', 'updated_at' => now()]);
    }
};
