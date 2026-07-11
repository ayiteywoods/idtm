@extends('layouts.portal')

@section('title', 'Faculty')
@section('breadcrumb', 'Administration / Faculty')

@section('content')
<x-portal.page-header title="Faculty Management" :description="$total.' faculty members in the system.'">
    <x-slot:actions>
        <x-portal.button href="{{ route('admin.faculty.create') }}">
            <x-portal.icon name="plus" class="h-4 w-4" /> Add Faculty
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card :padding="false">
    <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
        <p class="text-sm text-slate-500">Showing {{ $faculty->firstItem() ?? 0 }}-{{ $faculty->lastItem() ?? 0 }} of {{ $faculty->total() }} faculty members</p>
        <x-portal.per-page-select id="faculty" :per-page="$perPage" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="name" label="Faculty" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="employee" label="Employee ID" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="department" label="Department" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="title" label="Title" /></th>
                    <th class="px-6 py-3.5">Assigned Courses</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($faculty as $member)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-50 text-sm font-bold text-violet-600">{{ strtoupper(substr($member->user->name, 0, 1)) }}</div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $member->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $member->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $member->employee_id }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $member->department ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $member->title ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if ($member->courses->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($member->courses->take(3) as $course)
                                        <x-portal.badge variant="muted">{{ $course->code }}</x-portal.badge>
                                    @endforeach
                                    @if ($member->courses->count() > 3)
                                        <x-portal.badge variant="muted">+{{ $member->courses->count() - 3 }}</x-portal.badge>
                                    @endif
                                </div>
                            @else
                                <x-portal.badge variant="warning">Unassigned</x-portal.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700" data-faculty-drawer-open="faculty-drawer-{{ $member->id }}" aria-label="View {{ $member->user->name }}">
                                    <x-portal.icon name="eye" class="h-4 w-4" />
                                </button>
                                <a href="{{ route('admin.faculty.edit', $member) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-gold-50 hover:text-gold-700" aria-label="Edit {{ $member->user->name }}">
                                    <x-portal.icon name="pencil" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.faculty.destroy', $member) }}" onsubmit="return confirm(@js('Delete '.$member->user->name.'? This cannot be undone.'));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-rose-50 hover:text-rose-700" aria-label="Delete {{ $member->user->name }}">
                                        <x-portal.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12"><x-portal.empty-state title="No faculty yet" description="Add faculty members and assign courses to them." icon="academic" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($faculty->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">{{ $faculty->links() }}</div>
    @endif
</x-portal.card>

@foreach ($faculty as $member)
    <div id="faculty-drawer-{{ $member->id }}" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <button type="button" class="absolute inset-0 bg-slate-900/50" data-faculty-drawer-close aria-label="Close faculty drawer"></button>

        <aside class="absolute right-0 top-0 flex h-full w-full max-w-xl flex-col bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $member->user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $member->employee_id }} · {{ $member->user->email }}</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-faculty-drawer-close aria-label="Close drawer">
                    <x-portal.icon name="close" class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        'Department' => $member->department,
                        'Title' => $member->title,
                        'Phone' => $member->phone,
                        'Account Status' => $member->user->is_active ? 'Active' : 'Inactive',
                    ] as $label => $value)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $value ?: '—' }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-900">Assigned Courses</h3>
                    <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                        @forelse ($member->courses as $course)
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $course->code }} — {{ $course->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $course->registrations->count() }} registered students</p>
                                </div>
                                <x-portal.badge :variant="$course->is_active ? 'success' : 'muted'">{{ $course->is_active ? 'Active' : 'Inactive' }}</x-portal.badge>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-slate-500">No courses assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <x-portal.button variant="ghost" href="{{ route('admin.faculty.edit', $member) }}">Edit Faculty</x-portal.button>
                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gold-500 px-4 py-2.5 text-sm font-semibold text-brand-900 transition hover:bg-gold-400" data-faculty-drawer-close>Close</button>
            </div>
        </aside>
    </div>
@endforeach

<script>
    document.querySelectorAll('[data-faculty-drawer-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = document.getElementById(trigger.dataset.facultyDrawerOpen);
            drawer?.classList.remove('hidden');
            drawer?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('[data-faculty-drawer-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = trigger.closest('[id^="faculty-drawer-"]');
            drawer?.classList.add('hidden');
            drawer?.setAttribute('aria-hidden', 'true');
        });
    });
</script>
@endsection
