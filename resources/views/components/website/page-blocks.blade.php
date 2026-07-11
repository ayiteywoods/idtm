@props(['blocks' => [], 'programmes' => null])

@foreach ($blocks as $block)
    @switch($block['type'])
        @case('intro')
            <div class="website-content-block">
                <p class="website-content-intro">{{ $block['text'] }}</p>
            </div>
            @break

        @case('heading')
            <div class="website-content-block">
                <h2 class="website-content-heading">{{ $block['text'] }}</h2>
            </div>
            @break

        @case('image')
            <div class="website-content-block">
                <figure class="website-figure">
                    <img
                        src="{{ $block['image'] }}"
                        alt="{{ $block['alt'] ?? '' }}"
                        class="website-figure__image"
                        loading="lazy"
                    >
                    @if (! empty($block['caption']))
                        <figcaption class="website-figure__caption">{{ $block['caption'] }}</figcaption>
                    @endif
                </figure>
            </div>
            @break

        @case('image-text')
            <div class="website-content-block">
                <div class="website-image-text {{ ($block['position'] ?? 'left') === 'right' ? 'website-image-text--reverse' : '' }}">
                    <div class="website-image-text__media">
                        <img
                            src="{{ $block['image'] }}"
                            alt="{{ $block['alt'] ?? $block['title'] ?? '' }}"
                            class="website-image-text__image"
                            loading="lazy"
                        >
                    </div>
                    <div class="website-image-text__content">
                        @if (! empty($block['title']))
                            <h2 class="website-content-heading">{{ $block['title'] }}</h2>
                        @endif
                        @if (! empty($block['text']))
                            <p class="website-content-intro">{{ $block['text'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @break

        @case('cards')
            <div class="website-content-block">
                @if (! empty($block['title']))
                    <h2 class="website-content-heading">{{ $block['title'] }}</h2>
                @endif
                <div class="website-card-grid">
                    @foreach ($block['items'] as $item)
                        <div class="website-info-card">
                            <h3 class="website-info-card__title">{{ $item['title'] }}</h3>
                            <p class="website-info-card__text">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('split')
            <div class="website-content-block">
                <div class="website-split-grid">
                    @foreach ($block['items'] as $item)
                        <div class="website-split-card">
                            <h2 class="website-split-card__title">{{ $item['title'] }}</h2>
                            <p class="website-split-card__text">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('stats')
            <div class="website-content-block">
                <div class="website-stats-grid">
                    @foreach ($block['items'] as $item)
                        <div class="website-stat-card">
                            <p class="website-stat-card__value">{{ $item['value'] }}</p>
                            <p class="website-stat-card__label">{{ $item['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('list')
            <div class="website-content-block">
                @if (! empty($block['title']))
                    <h2 class="website-content-heading">{{ $block['title'] }}</h2>
                @endif
                <ul class="website-check-list">
                    @foreach ($block['items'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @break

        @case('steps')
            <div class="website-content-block">
                @if (! empty($block['title']))
                    <h2 class="website-content-heading">{{ $block['title'] }}</h2>
                @endif
                <div class="website-steps">
                    @foreach ($block['items'] as $index => $item)
                        <div class="website-step">
                            <div class="website-step__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                            <div>
                                <h3 class="website-step__title">{{ $item['title'] }}</h3>
                                <p class="website-step__text">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('rector-profile')
            <div class="website-content-block">
                <div class="website-rector-profile">
                    <div class="website-rector-profile__media">
                        <img
                            src="{{ $block['image'] }}"
                            alt="{{ $block['alt'] ?? $block['name'] }}"
                            class="website-rector-profile__image"
                            width="480"
                            height="600"
                            loading="lazy"
                        >
                        <div class="website-rector-profile__caption">
                            <h2 class="website-rector-profile__name">{{ $block['name'] }}</h2>
                            <p class="website-rector-profile__role">{{ $block['role'] }}</p>
                        </div>
                    </div>
                    <div class="website-rector-profile__message">
                        <h2 class="website-content-heading">Welcome from the Rector</h2>
                        @if (! empty($block['intro']))
                            <p class="website-content-intro">{{ $block['intro'] }}</p>
                        @endif
                        @if (! empty($block['message']))
                            <ul class="website-check-list">
                                @foreach ($block['message'] as $paragraph)
                                    <li>{{ $paragraph }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            @break

        @case('people')
            <div class="website-content-block">
                <div class="website-people-grid">
                    @foreach ($block['items'] as $person)
                        <div class="website-person-card">
                            @if (! empty($person['image']))
                                <img src="{{ $person['image'] }}" alt="{{ $person['name'] }}" class="website-person-card__photo">
                            @else
                                <div class="website-person-card__avatar">{{ strtoupper(substr($person['name'], 0, 1)) }}</div>
                            @endif
                            <h3 class="website-person-card__name">{{ $person['name'] }}</h3>
                            <p class="website-person-card__role">{{ $person['role'] }}</p>
                            <p class="website-person-card__bio">{{ $person['bio'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('table')
            <div class="website-content-block">
                @if (! empty($block['title']))
                    <h2 class="website-content-heading">{{ $block['title'] }}</h2>
                @endif
                <div class="website-table-wrap">
                    <table class="website-table">
                        <thead>
                            <tr>
                                @foreach ($block['headers'] as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($block['rows'] as $row)
                                <tr>
                                    @foreach ($row as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @break

        @case('cta')
            <div class="website-content-block">
                <div class="website-cta-card">
                    @if (! empty($block['title']))
                        <h2 class="website-cta-card__title">{{ $block['title'] }}</h2>
                    @endif
                    @if (! empty($block['text']))
                        <p class="website-cta-card__text">{{ $block['text'] }}</p>
                    @endif
                    <div class="website-cta-card__actions">
                        @if (! empty($block['primary']))
                            <a href="{{ route($block['primary']['route'], $block['primary']['params'] ?? []) }}"
                               class="website-btn website-btn--primary">{{ $block['primary']['label'] }}</a>
                        @endif
                        @if (! empty($block['secondary']))
                            <a href="{{ route($block['secondary']['route'], $block['secondary']['params'] ?? []) }}"
                               class="website-btn website-btn--secondary">{{ $block['secondary']['label'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
            @break

        @case('download')
            <div class="website-content-block">
                <a href="{{ $block['url'] }}" class="website-download-card" download>
                    <span class="website-download-card__icon" aria-hidden="true">↓</span>
                    <span class="website-download-card__label">{{ $block['label'] }}</span>
                    <span class="website-download-card__action">Download</span>
                </a>
            </div>
            @break
    @endswitch
@endforeach

@if ($programmes && $programmes->isNotEmpty())
    <div class="website-content-block">
        <h2 class="website-content-heading">Available Programmes</h2>
        <div class="website-programme-grid">
            @foreach ($programmes as $programme)
                <article class="website-programme-card">
                    <h3 class="website-programme-card__title">{{ $programme->name }}</h3>
                    <p class="website-programme-card__text">{{ $programme->description }}</p>
                    @if ($programme->specializations->isNotEmpty())
                        <ul class="website-programme-card__specs">
                            @foreach ($programme->specializations as $spec)
                                <li>{{ $spec->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="website-programme-card__footer">
                        <span class="website-programme-card__fee">GHS {{ number_format($programme->total_fees, 0) }}</span>
                        <a href="{{ route('programmes.show', $programme) }}" class="website-programme-card__link">View programme →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
