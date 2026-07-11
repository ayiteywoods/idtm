<section id="hero-slider" class="hero-slider" aria-label="Featured highlights">
    <div class="hero-slider__slides">
        @foreach ($heroSlides as $index => $slide)
            <div class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-slide="{{ $index }}">
                <img
                    src="{{ $slide['image'] }}"
                    alt="{{ $slide['alt'] }}"
                    class="hero-slide__image"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                >
                <div class="hero-slide__overlay"></div>
            </div>
        @endforeach
    </div>

    <div class="hero-slider__content">
        <div class="hero-slider__text">
            <p class="hero-slider__tagline">{{ $tagline }}</p>
            <h1 class="hero-slider__title website-section-heading">{{ $heroTitle }}</h1>
            <p class="hero-slider__subtitle">{{ $heroSubtitle }}</p>
            <div class="hero-slider__actions">
                <a href="{{ route('login') }}" class="hero-slider__btn hero-slider__btn--primary">
                    Portal Access
                </a>
                <a href="{{ route('pages.show', 'admissions') }}" class="hero-slider__btn hero-slider__btn--secondary">
                    View Admissions
                </a>
            </div>
        </div>
    </div>

    <div class="hero-slider__controls" aria-hidden="true">
        @foreach ($heroSlides as $index => $slide)
            <button
                type="button"
                class="hero-slider__dot {{ $index === 0 ? 'is-active' : '' }}"
                data-slide-to="{{ $index }}"
                aria-label="Go to slide {{ $index + 1 }}"
            ></button>
        @endforeach
    </div>
</section>
