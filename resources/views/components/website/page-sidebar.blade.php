@props(['items' => [], 'currentSlug' => null])

<aside class="website-page-sidebar">
    <p class="website-page-sidebar__title">In this section</p>
    <nav class="website-page-sidebar__nav">
        @foreach ($items as $item)
            @if (($item['type'] ?? 'link') === 'dropdown')
                @php
                    $childSlugs = collect($item['items'] ?? [])
                        ->pluck('slug')
                        ->filter()
                        ->all();

                    if (isset($item['slug'])) {
                        $childSlugs[] = $item['slug'];
                    }

                    $isGroupActive = in_array($currentSlug, $childSlugs, true);
                @endphp
                <div class="website-page-sidebar__group {{ $isGroupActive ? 'is-open' : '' }}" data-sidebar-dropdown>
                    <button type="button"
                            class="website-page-sidebar__group-trigger {{ $isGroupActive ? 'is-active' : '' }}"
                            aria-expanded="{{ $isGroupActive ? 'true' : 'false' }}">
                        <span>{{ $item['label'] }}</span>
                        <svg class="website-page-sidebar__group-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="website-page-sidebar__group-menu">
                        @foreach ($item['items'] as $subItem)
                            @php
                                $subHref = $subItem['route'] ?? ($subItem['slug'] ? route('pages.show', $subItem['slug']) : '#');
                                $isSubActive = isset($subItem['slug']) && $currentSlug === $subItem['slug'];
                            @endphp
                            <a href="{{ $subHref }}"
                               class="website-page-sidebar__link website-page-sidebar__link--child {{ $isSubActive ? 'is-active' : '' }}">
                                {{ $subItem['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                @php
                    $href = $item['route'] ?? ($item['slug'] ? route('pages.show', $item['slug']) : '#');
                    $isActive = isset($item['slug']) && $currentSlug === $item['slug'];
                @endphp
                <a href="{{ $href }}"
                   class="website-page-sidebar__link {{ $isActive ? 'is-active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>
