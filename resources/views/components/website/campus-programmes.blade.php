@props(['programmes'])

@php
    $programmeList = collect($programmes);
    $visibleProgrammes = $programmeList->take(3);
    $showSeeAll = $programmeList->count() > 3;
@endphp

<section class="campus-programmes">
    <div class="campus-programmes__grid">
        <div class="campus-programmes__campus">
            <img src="/images/hero/slide-1.jpg?v=2" alt="IDTM campus" class="campus-programmes__campus-image" loading="lazy">
            <div class="campus-programmes__campus-overlay"></div>
            <div class="campus-programmes__campus-content">
                <h2 class="campus-programmes__campus-title">On Campus</h2>
                <p class="campus-programmes__campus-text">
                    IDTM's campus creates a stunning backdrop for all that happens within the University.
                </p>
                <a href="{{ route('pages.show', 'campus-life') }}" class="campus-programmes__explore">
                    Explore
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="campus-programmes__list-panel">
            <div class="campus-programmes__list-inner mx-auto max-w-xl px-6 py-10 sm:px-8 sm:py-12 lg:px-10 lg:py-14">
                <h2 class="campus-programmes__list-heading website-section-heading">Our programs</h2>

                <ul class="campus-programmes__list">
                    @foreach ($visibleProgrammes as $programme)
                        <li class="campus-programmes__item">
                            <span class="campus-programmes__icon" aria-hidden="true">
                                @if ($programme['icon'] === 'ma')
                                    <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <circle cx="32" cy="22" r="10" />
                                        <path d="M32 32v8M26 40h12M20 48h24M24 52h16" />
                                        <path d="M44 18l4-4M44 26l6 2M20 18l-4-4M20 26l-6 2" />
                                    </svg>
                                @elseif ($programme['icon'] === 'mphil')
                                    <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <circle cx="22" cy="16" r="6" />
                                        <path d="M14 52V34c0-4 3.5-7 8-7s8 3 8 7v18M30 40h18v12H30zM34 44h10M34 48h10" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <circle cx="18" cy="18" r="6" />
                                        <path d="M10 52V36c0-4 3.5-7 8-7h2M38 16h16v28H38zM42 22h8M42 28h8M42 34h5" />
                                        <path d="M14 52h36" />
                                    </svg>
                                @endif
                            </span>
                            <div class="campus-programmes__item-body">
                                <h3 class="campus-programmes__item-title">
                                    <a href="{{ $programme['url'] }}">{{ $programme['name'] }}</a>
                                </h3>
                                <p class="campus-programmes__item-text">{{ $programme['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if ($showSeeAll)
                    <a href="{{ route('pages.show', 'programmes') }}" class="campus-programmes__see-all">See all</a>
                @endif
            </div>
        </div>
    </div>
</section>
