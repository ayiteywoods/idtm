@extends('layouts.portal')

@section('title', 'Courses')
@section('breadcrumb', 'Administration / Courses')

@section('content')
<x-portal.page-header title="Course Management" :description="$total.' courses across all programmes.'">
    <x-slot:actions>
        <x-portal.button href="{{ route('admin.courses.create') }}">
            <x-portal.icon name="plus" class="h-4 w-4" /> Add Course
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card :padding="false">
    <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
        <p class="text-sm text-slate-500">Showing {{ $courses->firstItem() ?? 0 }}-{{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} courses</p>
        <x-portal.per-page-select id="courses" :per-page="$perPage" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="code" label="Code" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="title" label="Course Title" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="programme" label="Programme" /></th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="students" label="Students" /></th>
                    <th class="px-6 py-3.5">Faculty</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($courses as $course)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-brand-700">{{ $course->code }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $course->title }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $course->programme?->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <x-portal.badge :variant="$course->is_core ? 'info' : 'muted'">{{ $course->is_core ? 'Core' : 'Specialization' }}</x-portal.badge>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $course->registrations_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $course->faculty->map(fn ($f) => $f->user->name)->join(', ') ?: '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700" data-course-drawer-open="course-drawer-{{ $course->id }}" aria-label="View {{ $course->code }}">
                                    <x-portal.icon name="eye" class="h-4 w-4" />
                                </button>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-gold-50 hover:text-gold-700" aria-label="Edit {{ $course->code }}">
                                    <x-portal.icon name="pencil" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm(@js('Delete '.$course->code.'? This will remove related registrations and grades.'));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-rose-50 hover:text-rose-700" aria-label="Delete {{ $course->code }}">
                                        <x-portal.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12"><x-portal.empty-state title="No courses yet" description="Create courses and assign them to faculty." icon="book" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($courses->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">{{ $courses->links() }}</div>
    @endif
</x-portal.card>

@foreach ($courses as $course)
    <div id="course-drawer-{{ $course->id }}" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <button type="button" class="absolute inset-0 bg-slate-900/50" data-course-drawer-close aria-label="Close course drawer"></button>

        <aside class="absolute right-0 top-0 flex h-full w-full max-w-xl flex-col bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $course->code }} — {{ $course->title }}</h2>
                    <p class="text-sm text-slate-500">{{ $course->programme?->name ?? 'No programme' }}</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-course-drawer-close aria-label="Close drawer">
                    <x-portal.icon name="close" class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        'Programme' => $course->programme?->name,
                        'Specialization' => $course->specialization?->name,
                        'Credits' => $course->credits,
                        'Type' => $course->is_core ? 'Core' : 'Specialization',
                        'Status' => $course->is_active ? 'Active' : 'Inactive',
                        'Registered Students' => $course->registrations_count,
                    ] as $label => $value)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $value ?: '—' }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-900">Assigned Faculty</h3>
                    <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                        @forelse ($course->faculty as $member)
                            <div class="border-b border-slate-100 px-4 py-3 last:border-b-0">
                                <p class="text-sm font-medium text-slate-900">{{ $member->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $member->title ?: 'Faculty' }} · {{ $member->user->email }}</p>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-slate-500">No faculty assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <x-portal.button variant="ghost" href="{{ route('admin.courses.edit', $course) }}">Edit Course</x-portal.button>
                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gold-500 px-4 py-2.5 text-sm font-semibold text-brand-900 transition hover:bg-gold-400" data-course-drawer-close>Close</button>
            </div>
        </aside>
    </div>
@endforeach

<script>
    document.querySelectorAll('[data-course-drawer-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = document.getElementById(trigger.dataset.courseDrawerOpen);
            drawer?.classList.remove('hidden');
            drawer?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('[data-course-drawer-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = trigger.closest('[id^="course-drawer-"]');
            drawer?.classList.add('hidden');
            drawer?.setAttribute('aria-hidden', 'true');
        });
    });
</script>
@endsection
