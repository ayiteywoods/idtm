@props(['label', 'value', 'icon' => 'chart', 'color' => 'brand', 'href' => null, 'hint' => null, 'trend' => null])

@php
    $colors = [
        'brand' => 'bg-brand-50 text-brand-600 ring-brand-100',
        'blue' => 'bg-blue-50 text-blue-600 ring-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
        'violet' => 'bg-violet-50 text-violet-600 ring-violet-100',
        'rose' => 'bg-rose-50 text-rose-600 ring-rose-100',
    ];
    $iconColor = $colors[$color] ?? $colors['brand'];
@endphp

@if ($href)
    <a href="{{ $href }}" class="group block rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition hover:shadow-md hover:ring-gold-300">
@else
    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
@endif
    <div class="flex items-center justify-between gap-4">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg ring-1 {{ $iconColor }}">
            <x-portal.icon :name="$icon" class="h-5 w-5" />
        </div>

        <div class="min-w-0 text-right">
            <div class="flex items-start justify-end gap-2">
                <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                @if ($hint)
                    <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">{{ $hint }}</span>
                @endif
            </div>
            <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
            @if ($trend)
                <p class="mt-1 text-xs font-medium text-emerald-600">{{ $trend }}</p>
            @endif
        </div>
    </div>
@if ($href)
    </a>
@else
    </div>
@endif
