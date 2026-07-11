@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
])

<section class="website-page-hero">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if (count($breadcrumbs))
            <nav class="website-breadcrumb" aria-label="Breadcrumb">
                @foreach ($breadcrumbs as $index => $crumb)
                    @if ($index > 0)
                        <span class="website-breadcrumb__sep">/</span>
                    @endif
                    @if (! empty($crumb['route']))
                        <a href="{{ $crumb['route'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span class="website-breadcrumb__current">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if ($eyebrow)
            <p class="website-page-hero__eyebrow">{{ $eyebrow }}</p>
        @endif
        <h1 class="website-page-hero__title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="website-page-hero__subtitle">{{ $subtitle }}</p>
        @endif
    </div>
</section>
