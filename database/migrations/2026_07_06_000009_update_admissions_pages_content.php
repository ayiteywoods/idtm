<?php

use App\Support\AdmissionsPagesContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['admission-form', 'admission-requirements', 'programmes', 'brochure'] as $slug) {
            $content = AdmissionsPagesContent::for($slug);

            if (! $content) {
                continue;
            }

            $summary = collect($content['blocks'] ?? [])
                ->first(fn (array $block) => in_array($block['type'] ?? null, ['intro', 'heading'], true));

            DB::table('site_pages')
                ->where('slug', $slug)
                ->update([
                    'eyebrow' => $content['eyebrow'] ?? null,
                    'subtitle' => $content['subtitle'] ?? null,
                    'content' => $summary['text'] ?? '',
                    'blocks' => json_encode($content['blocks'] ?? []),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
