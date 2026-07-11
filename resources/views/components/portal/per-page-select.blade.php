@props([
    'perPage' => 20,
    'options' => [10, 20, 50, 100],
    'id' => 'list',
])

<form method="GET" class="flex items-center gap-2 text-sm text-slate-500">
    @foreach (request()->except(['per_page', 'page']) as $key => $value)
        @if (is_array($value))
            @foreach ($value as $item)
                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <label for="per-page-{{ $id }}" class="whitespace-nowrap">Show</label>
    <select
        id="per-page-{{ $id }}"
        name="per_page"
        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200"
        onchange="this.form.submit()"
    >
        @foreach ($options as $option)
            <option value="{{ $option }}" @selected((int) $perPage === $option)>{{ $option }}</option>
        @endforeach
    </select>
    <span class="whitespace-nowrap">per page</span>
</form>
