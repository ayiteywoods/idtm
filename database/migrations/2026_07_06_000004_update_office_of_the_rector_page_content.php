<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_pages')
            ->where('slug', 'office-of-the-rector')
            ->update([
                'content' => '',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('site_pages')
            ->where('slug', 'office-of-the-rector')
            ->update([
                'content' => 'Strategic leadership and executive direction for the Institute of Development & Technology Management.',
                'updated_at' => now(),
            ]);
    }
};
