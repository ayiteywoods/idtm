@props(['panels'])

<section class="feature-gallery" aria-label="Campus highlights">
    @foreach ($panels as $panel)
        @if ($panel['type'] === 'featured')
            <a href="{{ $panel['url'] }}"
               class="feature-gallery__panel feature-gallery__panel--featured"
               style="background-image: url('{{ $panel['image'] }}')">
                <div class="feature-gallery__overlay"></div>
                <div class="feature-gallery__featured-copy">
                    <h3 class="feature-gallery__featured-title">{{ $panel['title'] }}</h3>
                    <p class="feature-gallery__featured-subtitle">{{ $panel['subtitle'] }}</p>
                    <p class="feature-gallery__featured-tagline">{{ $panel['tagline'] }}</p>
                </div>
            </a>
        @else
            <a href="{{ $panel['url'] }}"
               class="feature-gallery__panel feature-gallery__panel--strip"
               style="background-image: url('{{ $panel['image'] }}'); background-position: {{ $panel['position'] ?? 'center' }};"
               aria-label="Campus highlight"></a>
        @endif
    @endforeach
</section>
