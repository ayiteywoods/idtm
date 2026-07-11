<?php

namespace App\Support;

use App\Models\SitePage;

class SitePageContent
{
    public static function resolve(SitePage $page): ?array
    {
        $defaults = WebsitePageContent::for($page->slug);
        $blocks = self::normalizeBlocks($page->blocks);

        if ($blocks !== []) {
            return [
                'eyebrow' => filled($page->eyebrow) ? $page->eyebrow : ($defaults['eyebrow'] ?? $page->title),
                'subtitle' => filled($page->subtitle) ? $page->subtitle : ($defaults['subtitle'] ?? null),
                'blocks' => $blocks,
            ];
        }

        if ($defaults) {
            return $defaults;
        }

        if (filled($page->content)) {
            return [
                'eyebrow' => filled($page->eyebrow) ? $page->eyebrow : $page->title,
                'subtitle' => $page->subtitle,
                'blocks' => [['type' => 'intro', 'text' => $page->content]],
            ];
        }

        return null;
    }

    public static function editorPayload(SitePage $page): array
    {
        $resolved = self::resolve($page);

        return [
            'eyebrow' => $page->eyebrow ?? ($resolved['eyebrow'] ?? $page->title),
            'subtitle' => $page->subtitle ?? ($resolved['subtitle'] ?? ''),
            'blocks' => $resolved['blocks'] ?? [],
        ];
    }

    public static function blockTypes(): array
    {
        return [
            'intro' => 'Intro paragraph',
            'heading' => 'Section heading',
            'image' => 'Image',
            'image-text' => 'Image with text',
            'rector-profile' => 'Rector profile',
            'list' => 'Bullet list',
            'cards' => 'Info cards',
            'split' => 'Two columns',
            'people' => 'People profiles',
            'steps' => 'Numbered steps',
            'stats' => 'Statistics',
            'table' => 'Data table',
            'cta' => 'Call to action',
            'download' => 'File download',
        ];
    }

    public static function defaultBlock(string $type): array
    {
        return match ($type) {
            'intro' => ['type' => 'intro', 'text' => ''],
            'heading' => ['type' => 'heading', 'text' => ''],
            'image' => ['type' => 'image', 'image' => '', 'alt' => '', 'caption' => ''],
            'image-text' => ['type' => 'image-text', 'image' => '', 'alt' => '', 'title' => '', 'text' => '', 'position' => 'left'],
            'rector-profile' => ['type' => 'rector-profile', 'image' => '', 'alt' => '', 'name' => '', 'role' => 'Rector', 'intro' => '', 'message' => []],
            'list' => ['type' => 'list', 'title' => '', 'items' => []],
            'cards' => ['type' => 'cards', 'title' => '', 'items' => [['title' => '', 'text' => '']]],
            'split' => ['type' => 'split', 'items' => [
                ['title' => '', 'text' => ''],
                ['title' => '', 'text' => ''],
            ]],
            'people' => ['type' => 'people', 'items' => [['name' => '', 'role' => '', 'bio' => '', 'image' => '']]],
            'steps' => ['type' => 'steps', 'title' => '', 'items' => [['title' => '', 'text' => '']]],
            'stats' => ['type' => 'stats', 'items' => [['value' => '', 'label' => '']]],
            'table' => ['type' => 'table', 'title' => '', 'headers' => ['Column 1', 'Column 2'], 'rows' => [['', '']]],
            'cta' => ['type' => 'cta', 'title' => '', 'text' => '', 'primary' => ['label' => '', 'route' => 'contact']],
            'download' => ['type' => 'download', 'label' => '', 'url' => ''],
            default => ['type' => 'intro', 'text' => ''],
        };
    }

    private static function normalizeBlocks(mixed $blocks): array
    {
        if (! is_array($blocks)) {
            return [];
        }

        return array_values(array_filter($blocks, fn ($block) => is_array($block) && filled($block['type'] ?? null)));
    }
}
