@props(['items' => []])

@if (count($items) > 0)
    <div {{ $attributes->merge(['class' => 'mb-6 space-y-2']) }}>
        @foreach ($items as $item)
            <a href="{{ $item['route'] }}"
               class="flex items-center justify-between gap-4 rounded-lg px-4 py-3 text-sm font-medium ring-1 transition hover:shadow-sm
               {{ match ($item['variant'] ?? 'info') {
                   'warning' => 'bg-amber-50 text-amber-950 ring-amber-200 hover:bg-amber-100',
                   'info' => 'bg-blue-50 text-blue-950 ring-blue-200 hover:bg-blue-100',
                   default => 'bg-slate-50 text-slate-900 ring-slate-200 hover:bg-slate-100',
               } }}">
                <span>{{ $item['label'] }}</span>
                <span class="shrink-0 text-xs font-semibold opacity-70">Review &rarr;</span>
            </a>
        @endforeach
    </div>
@else
    <div {{ $attributes->merge(['class' => 'mb-6 flex items-center gap-3 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 ring-1 ring-emerald-200']) }}>
        <x-portal.icon name="chart" class="h-5 w-5 shrink-0 text-emerald-600" />
        <span>All clear — no items need your attention right now.</span>
    </div>
@endif
