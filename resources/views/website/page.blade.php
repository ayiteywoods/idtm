@extends('layouts.website')

@section('title', $page->title.' — '.$siteName)

@section('content')
<x-website.page-hero
    :eyebrow="$content['eyebrow'] ?? $page->title"
    :title="$page->title"
    :subtitle="$content['subtitle'] ?? null"
    :breadcrumbs="$breadcrumbs"
/>

<section class="website-page-body">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="website-page-layout {{ $section ? 'has-sidebar' : '' }}">
            @if ($section && count($sidebar))
                <x-website.page-sidebar :items="$sidebar" :current-slug="$page->slug" />
            @endif

            <div class="website-page-main">
                @if ($content)
                    <x-website.page-blocks :blocks="$content['blocks'] ?? []" :programmes="$programmes" />
                @else
                    <div class="website-content-block">
                        <p class="website-content-intro">{{ $page->content }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
