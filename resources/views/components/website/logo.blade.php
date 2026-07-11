@props(['size' => 'md', 'variant' => 'light', 'markOnly' => false, 'crest' => false])

@php
    $sizes = [
        'sm' => ['mark' => 'h-10', 'name' => 'text-xs', 'tagline' => 'text-[10px]'],
        'md' => ['mark' => 'h-12 sm:h-14', 'name' => 'text-[0.8rem] leading-tight sm:text-sm', 'tagline' => 'text-[10px] sm:text-xs'],
        'lg' => ['mark' => 'h-16 sm:h-20', 'name' => 'text-base sm:text-lg', 'tagline' => 'text-xs sm:text-sm'],
        'crest' => ['mark' => 'h-[5.5rem] w-auto sm:h-24', 'name' => '', 'tagline' => ''],
    ];
    $sizeKey = $crest ? 'crest' : $size;
    $s = $sizes[$sizeKey] ?? $sizes['md'];
    $showText = ! $markOnly && ! $crest;
    $nameClass = $variant === 'dark' ? 'text-white' : 'text-brand-900';
    $taglineClass = $variant === 'dark' ? 'text-brand-200' : 'text-slate-500';
    $siteLabel = $siteName ?? \App\Models\SiteSetting::get('site_name', config('app.name'));
    $siteTagline = $siteTagline ?? \App\Models\SiteSetting::get('tagline', 'Knowledge and Excellence');
    $logoSrc = asset('images/idtm-logo.png');
@endphp

<a {{ $attributes->merge(['href' => route('home'), 'class' => 'website-logo group' . ($variant === 'dark' ? ' website-logo--dark' : '')]) }}>
    <img
        src="{{ $logoSrc }}"
        alt="{{ $siteLabel }}"
        class="website-logo__mark {{ $s['mark'] }} w-auto shrink-0 object-contain"
        width="512"
        height="512"
        decoding="async"
    />
    @if ($showText)
        <div class="website-logo__text min-w-0 max-w-[11rem] sm:max-w-xs">
            <span class="website-logo__name {{ $s['name'] }} {{ $nameClass }}">{{ $siteLabel }}</span>
            <span class="website-logo__tagline {{ $s['tagline'] }} {{ $taglineClass }}">{{ $siteTagline }}</span>
        </div>
    @endif
</a>
