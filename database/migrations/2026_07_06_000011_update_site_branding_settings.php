<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            'site_name' => 'Institute of Development & Technology Management',
            'tagline' => 'Knowledge and Excellence',
            'hero_title' => 'Shape Your Future in Development & Technology',
            'hero_subtitle' => 'Join a community of leaders, innovators, and change-makers at the Institute of Development & Technology Management.',
            'footer_intro' => 'Educating Ghana\'s next generation of development and technology leaders through rigorous postgraduate programmes in Cape Coast.',
            'contact_email' => 'info@idtm.edu.gh',
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
        //
    }
};
