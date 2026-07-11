@props(['initial', 'photo' => null, 'alt' => '', 'size' => 'md'])

@php
    $sizes = [
        'sm' => ['box' => 'h-8 w-8', 'text' => 'text-xs'],
        'md' => ['box' => 'h-12 w-12', 'text' => 'text-lg'],
        'lg' => ['box' => 'h-44 w-44', 'text' => 'text-5xl'],
    ];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $fallbackClass = "mx-auto flex {$sizeClasses['box']} items-center justify-center rounded-lg bg-gold-100 {$sizeClasses['text']} font-bold text-brand-800 ring-2 ring-gold-300";
    $imageClass = "mx-auto {$sizeClasses['box']} rounded-lg object-cover object-center shadow-md ring-2 ring-gold-300";
@endphp

@if ($photo)
    <div class="relative mx-auto {{ $sizeClasses['box'] }}">
        <img
            src="{{ $photo }}"
            alt="{{ $alt }}"
            class="{{ $imageClass }}"
            onerror="this.hidden = true; this.nextElementSibling.hidden = false;"
        >
        <div hidden class="{{ $fallbackClass }}">
            {{ $initial }}
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => $fallbackClass]) }}>
        {{ $initial }}
    </div>
@endif
