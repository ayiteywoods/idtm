@props(['title', 'description' => null, 'icon' => 'folder'])

<div class="flex flex-col items-center justify-center rounded-lg bg-white px-6 py-16 text-center shadow-sm ring-1 ring-slate-200/80">
    <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
        <x-portal.icon :name="$icon" class="h-7 w-7" />
    </div>
    <h3 class="mt-4 text-base font-semibold text-slate-900">{{ $title }}</h3>
    @if ($description)
        <p class="mt-2 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-6">{{ $action }}</div>
    @endisset
</div>
