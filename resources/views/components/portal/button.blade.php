@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-9 px-3 text-xs',
        'md' => 'h-10 px-4 text-sm',
    ];
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 '.($sizes[$size] ?? $sizes['md']);
    $variants = [
        'primary' => 'bg-gold-500 text-brand-900 hover:bg-gold-400 focus:ring-gold-400',
        'secondary' => 'bg-white text-brand-800 ring-1 ring-brand-200 hover:bg-brand-50 focus:ring-brand-400',
        'dark' => 'bg-brand-800 text-white hover:bg-brand-900 focus:ring-brand-600',
        'ghost' => 'text-slate-600 hover:bg-slate-100 focus:ring-slate-400',
        'navy' => 'bg-brand-600 text-white hover:bg-brand-700 focus:ring-brand-500',
    ];
    $class = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
