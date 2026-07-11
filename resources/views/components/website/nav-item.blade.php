@props(['item', 'theme' => 'main'])

@if (($item['type'] ?? 'link') === 'dropdown')
    <div class="website-nav-dropdown {{ $theme === 'utility' ? 'website-nav-dropdown--utility' : '' }}" data-nav-dropdown>
        <button type="button"
                class="website-nav-dropdown__trigger {{ ($item['active'] ?? false) ? 'is-active' : '' }}"
                aria-expanded="false">
            <span>{{ $item['label'] }}</span>
            <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
        <div class="website-nav-dropdown__menu">
            @foreach ($item['items'] as $subItem)
                <a href="{{ $subItem['route'] }}"
                   class="website-nav-dropdown__link {{ ($subItem['highlight'] ?? false) ? 'is-highlight' : '' }}">
                    {{ $subItem['label'] }}
                </a>
            @endforeach
        </div>
    </div>
@else
    <a href="{{ $item['route'] }}"
       class="website-nav-link {{ ($item['active'] ?? false) ? 'is-active' : '' }}">
        {{ $item['label'] }}
    </a>
@endif
