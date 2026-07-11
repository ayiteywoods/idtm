<?php

namespace App\Support;

use App\Models\SiteSetting;

class RectorProfileSettings
{
    public static function defaults(): array
    {
        return [
            'image' => '/images/hero/slide-2.jpg',
            'alt' => 'Professor John Andoh Micah, Rector of IDTM',
            'name' => 'Professor John Andoh Micah',
            'role' => 'Rector',
            'intro' => 'Welcome to the Institute of Development & Technology Management. Whether you are a prospective student, faculty colleague, partner institution, or member of our alumni community, I am delighted that you are exploring what IDTM has to offer.',
            'message' => [
                'IDTM exists to educate Ghana\'s next generation of development and technology leaders.',
                'Our programmes combine academic rigour with practical insight drawn from Ghanaian and African contexts.',
                'We invest in faculty excellence, student support, and partnerships that open doors for our graduates.',
                'I invite you to learn more about our programmes, visit our campus in Accra, and join a community committed to knowledge and excellence.',
            ],
        ];
    }

    public static function profile(): array
    {
        $defaults = self::defaults();
        $messageRaw = SiteSetting::get('rector_message');

        $message = $messageRaw !== null
            ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $messageRaw))))
            : $defaults['message'];

        return [
            'image' => SiteSetting::get('rector_image', $defaults['image']),
            'alt' => SiteSetting::get('rector_image_alt', $defaults['alt']),
            'name' => SiteSetting::get('rector_name', $defaults['name']),
            'role' => SiteSetting::get('rector_role', $defaults['role']),
            'intro' => SiteSetting::get('rector_intro', $defaults['intro']),
            'message' => $message !== [] ? $message : $defaults['message'],
        ];
    }

    public static function messageText(?array $message = null): string
    {
        return implode("\n\n", $message ?? self::profile()['message']);
    }
}
