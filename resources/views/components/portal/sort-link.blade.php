@props(['field', 'label'])

@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'asc');
    $isActive = $currentSort === $field;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
    $query = array_merge(request()->except('page'), ['sort' => $field, 'direction' => $nextDirection]);
    $url = url()->current().'?'.http_build_query($query);
@endphp

<a href="{{ $url }}" class="inline-flex items-center gap-1 hover:text-brand-700">
    <span>{{ $label }}</span>
    @if ($isActive)
        <span aria-hidden="true">{{ $currentDirection === 'asc' ? '^' : 'v' }}</span>
    @else
        <span class="text-slate-300" aria-hidden="true">&lt;&gt;</span>
    @endif
</a>
