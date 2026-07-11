@props(['title' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-lg bg-white shadow-sm ring-1 ring-slate-200/80']) }}>
    @if ($title || isset($header))
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            @if ($title)
                <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
            @endif
            @isset($header)
                {{ $header }}
            @endisset
        </div>
    @endif
    <div @class(['px-6 py-5' => $padding])>
        {{ $slot }}
    </div>
</div>
