<?php

namespace App\Support;

use App\Models\SiteSetting;

class SiteSettings
{
    public static function defaults(): array
    {
        return [
            'site_name' => config('app.name'),
            'tagline' => 'Knowledge and Excellence',
            'footer_intro' => 'Shaping leaders in development policy, technology management, and innovation across Ghana and West Africa.',
            'contact_email' => 'info@idtm.edu.gh',
            'contact_phone' => '+233 208 824 029; +233 555 371 028',
            'contact_address' => 'Office of the Registrar, P. O. Box DL 494, Adisadel, Cape Coast',
        ];
    }

    public static function branding(): array
    {
        return [
            'site_name' => self::get('site_name'),
            'tagline' => self::get('tagline'),
        ];
    }

    public static function contact(): array
    {
        return [
            'footer_intro' => self::get('footer_intro'),
            'contact_email' => self::get('contact_email'),
            'contact_phone' => self::get('contact_phone'),
            'contact_address' => self::get('contact_address'),
        ];
    }

    public static function all(): array
    {
        return array_merge(self::branding(), self::contact());
    }

    public static function get(string $key): string
    {
        return SiteSetting::get($key, self::defaults()[$key] ?? '');
    }

    public static function save(array $values): void
    {
        foreach ($values as $key => $value) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
