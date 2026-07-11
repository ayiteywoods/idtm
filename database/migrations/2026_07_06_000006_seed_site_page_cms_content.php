<?php

use App\Support\RectorProfileSettings;
use App\Support\WebsitePageContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (WebsitePageContent::pages() as $slug => $content) {
            $blocks = $content['blocks'] ?? [];

            if ($slug === 'office-of-the-rector') {
                $profile = RectorProfileSettings::profile();

                foreach ($blocks as $index => $block) {
                    if (($block['type'] ?? null) === 'rector-profile') {
                        $blocks[$index] = array_merge($block, $profile);
                        break;
                    }
                }
            }

            DB::table('site_pages')
                ->where('slug', $slug)
                ->update([
                    'eyebrow' => $content['eyebrow'] ?? null,
                    'subtitle' => $content['subtitle'] ?? null,
                    'blocks' => json_encode($blocks),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        DB::table('site_pages')->update([
            'eyebrow' => null,
            'subtitle' => null,
            'blocks' => null,
        ]);
    }
};
