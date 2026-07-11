@props(['events'])

<section class="upcoming-events" id="events-slider">
    <div class="upcoming-events__wrap mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="upcoming-events__header">
            <h2 class="upcoming-events__heading website-section-heading">Upcoming Events</h2>
            <a href="{{ route('pages.show', 'campus-life') }}" class="upcoming-events__see-all">See All Events</a>
        </div>

        <div class="upcoming-events__stage">
            @foreach ($events as $index => $event)
                <article class="upcoming-events__slide {{ $index === 0 ? 'is-active' : '' }}" data-event-slide>
                    <div class="upcoming-events__visual">
                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" loading="lazy">
                        <div class="upcoming-events__overlay"></div>
                        <div class="upcoming-events__date">
                            <span class="upcoming-events__date-day">{{ $event['day'] }}</span>
                            <span class="upcoming-events__date-month">{{ $event['month'] }}</span>
                        </div>
                    </div>

                    <div class="upcoming-events__panel">
                        <div class="upcoming-events__card">
                            <span class="upcoming-events__decor" aria-hidden="true"></span>
                            <h3 class="upcoming-events__title">{{ $event['title'] }}</h3>
                            <p class="upcoming-events__excerpt">{{ $event['excerpt'] }}</p>
                            <a href="{{ $event['url'] }}" class="upcoming-events__link">Read More →</a>
                        </div>
                    </div>
                </article>
            @endforeach

            @if (count($events) > 1)
                <div class="upcoming-events__dots" role="tablist" aria-label="Event slides">
                    @foreach ($events as $index => $event)
                        <button type="button"
                                class="upcoming-events__dot {{ $index === 0 ? 'is-active' : '' }}"
                                data-event-dot="{{ $index }}"
                                aria-label="Show event {{ $index + 1 }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
