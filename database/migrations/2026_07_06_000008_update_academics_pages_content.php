<?php

use App\Support\AcademicsPagesContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $slugs = [
            'academic-calendar',
            'courses-ma-mphil-phd',
            'library',
            'ma-mphil-admissions',
            'mphil-development-studies',
            'ma-development-studies',
            'phd-development-studies',
        ];

        foreach ($slugs as $slug) {
            $content = AcademicsPagesContent::for($slug);

            if (! $content) {
                continue;
            }

            $summary = collect($content['blocks'] ?? [])
                ->first(fn (array $block) => in_array($block['type'] ?? null, ['intro', 'heading'], true));

            $text = $summary['text'] ?? ($summary['items'][0] ?? '');

            DB::table('site_pages')
                ->where('slug', $slug)
                ->update([
                    'eyebrow' => $content['eyebrow'] ?? null,
                    'subtitle' => $content['subtitle'] ?? null,
                    'content' => $text,
                    'blocks' => json_encode($content['blocks'] ?? []),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Content can be restored from WebsitePageContent defaults if needed.
    }
};
