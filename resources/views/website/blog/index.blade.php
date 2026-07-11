@extends('layouts.website')

@section('title', 'Blog — '.$siteName)

@section('content')
<x-website.page-hero
    eyebrow="News & Insights"
    title="University Blog"
    subtitle="Stories, updates, and insights from the Institute of Development & Technology Management community in Ghana."
    :breadcrumbs="[
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Blog'],
    ]"
/>

<section class="website-page-body">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($posts->isEmpty())
            <div class="website-info-card text-center">
                <p class="text-slate-600">No blog posts published yet. Check back soon.</p>
            </div>
        @else
            <div class="website-blog-grid">
                @foreach ($posts as $post)
                    <x-website.blog-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
