<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            'contact_address' => 'Office of the Registrar, P. O. Box DL 494, Adisadel, Cape Coast',
            'contact_phone' => '+233 208 824 029; +233 555 371 028',
        ];

        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'contact_address')->update([
            'value' => 'East Legon, Accra, Ghana',
            'updated_at' => now(),
        ]);

        DB::table('site_settings')->where('key', 'contact_phone')->update([
            'value' => '+233 30 278 0000',
            'updated_at' => now(),
        ]);
    }
};
