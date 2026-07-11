<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PageBlockUploader
{
    public function process(Request $request, array $blocks, string $slug): array
    {
        foreach ($blocks as $index => &$block) {
            $type = $block['type'] ?? null;

            if (in_array($type, ['image', 'image-text', 'rector-profile'], true)) {
                $file = $request->file("block_images.{$index}");

                if ($file instanceof UploadedFile) {
                    $block['image'] = $this->storeImage($file, $slug);
                }
            }

            if ($type === 'people' && ! empty($block['items']) && is_array($block['items'])) {
                foreach ($block['items'] as $personIndex => &$person) {
                    $file = $request->file("block_images.{$index}.{$personIndex}");

                    if ($file instanceof UploadedFile) {
                        $person['image'] = $this->storeImage($file, $slug);
                    }
                }
            }
        }

        return $blocks;
    }

    public function storeImage(UploadedFile $file, string $slug): string
    {
        $directory = public_path('images/pages/'.str_replace('/', '-', $slug));

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = time().'-'.bin2hex(random_bytes(4)).'.'.$file->extension();
        $file->move($directory, $filename);

        return '/images/pages/'.str_replace('/', '-', $slug).'/'.$filename;
    }
}
