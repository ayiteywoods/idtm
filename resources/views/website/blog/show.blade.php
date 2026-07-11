@extends('layouts.website')

@section('title', $post->title.' — '.$siteName)

@section('content')
<x-website.page-hero
    :eyebrow="$post->category ?? 'Blog'"
    :title="$post->title"
    :subtitle="$post->excerpt"
    :breadcrumbs="[
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Blog', 'route' => route('blog.index')],
        ['label' => $post->title],
    ]"
/>

<section class="website-page-body">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="website-page-layout {{ $recentPosts->isNotEmpty() ? 'has-sidebar' : '' }}">
            <article class="website-page-main">
                @if ($post->coverImageUrl())
                    <div class="website-blog-cover mb-8 overflow-hidden rounded-lg">
                        <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="h-72 w-full object-cover">
                    </div>
                @endif

                <div class="mb-6 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span>{{ $post->author }}</span>
                    <span>·</span>
                    <span>{{ $post->published_at?->format('F j, Y') }}</span>
                </div>

                <div class="website-content-intro space-y-4">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </article>

            @if ($recentPosts->isNotEmpty())
                <aside class="website-page-sidebar">
                    <p class="website-page-sidebar__title">Recent Posts</p>
                    <nav class="website-page-sidebar__nav">
                        @foreach ($recentPosts as $recent)
                            <a href="{{ route('blog.show', $recent) }}" class="website-page-sidebar__link">
                                {{ $recent->title }}
                            </a>
                        @endforeach
                    </nav>
                    <a href="{{ route('blog.index') }}" class="website-blog-card__link mt-4 inline-block">View all posts →</a>
                </aside>
            @endif
        </div>
    </div>
</section>
@endsection
