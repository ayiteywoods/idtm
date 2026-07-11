@props(['variant' => 'default'])

@php
    $classes = match ($variant) {
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'danger' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'muted' => 'bg-slate-100 text-slate-600 ring-slate-200',
        default => 'bg-gold-50 text-gold-800 ring-gold-200',
        'gold' => 'bg-gold-100 text-gold-800 ring-gold-300',
        'navy' => 'bg-brand-50 text-brand-700 ring-brand-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {$classes}"]) }}>
    {{ $slot }}
</span>
