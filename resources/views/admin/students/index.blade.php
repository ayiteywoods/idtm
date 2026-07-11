@extends('layouts.portal')

@section('title', 'Students')
@section('breadcrumb', 'Administration / Students')

@section('content')
<x-portal.page-header title="Student Management" :description="$total.' students registered in the system.'">
    <x-slot:actions>
        <x-portal.button variant="secondary" href="{{ route('portal.student-search') }}">
            <x-portal.icon name="search" class="h-4 w-4" /> Search
        </x-portal.button>
        <x-portal.button href="{{ route('admin.students.create') }}">
            <x-portal.icon name="plus" class="h-4 w-4" /> Add Student
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card :padding="false">
    <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
        <p class="text-sm text-slate-500">Showing {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</p>
        <x-portal.per-page-select id="students" :per-page="$perPage" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="name" label="Student" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="index" label="Index Number" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="programme" label="Programme" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="cohort" label="Cohort" /></th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($students as $student)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600">{{ strtoupper(substr($student->first_name, 0, 1)) }}</div>
                                <span class="font-medium text-slate-900">{{ $student->fullName() }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $student->index_number }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $student->programme?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $student->cohort?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $student->user->email }}</td>
                        <td class="px-6 py-4">
                            <x-portal.badge variant="{{ $student->user->is_active ? 'success' : 'muted' }}">
                                {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                            </x-portal.badge>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700"
                                    data-student-drawer-open="student-drawer-{{ $student->id }}"
                                    aria-label="View {{ $student->fullName() }}"
                                >
                                    <x-portal.icon name="eye" class="h-4 w-4" />
                                </button>
                                <a
                                    href="{{ route('admin.students.edit', $student) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-gold-50 hover:text-gold-700"
                                    aria-label="Edit {{ $student->fullName() }}"
                                >
                                    <x-portal.icon name="pencil" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm(@js('Delete '.$student->fullName().'? This cannot be undone.'));">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-rose-50 hover:text-rose-700"
                                        aria-label="Delete {{ $student->fullName() }}"
                                    >
                                        <x-portal.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12"><x-portal.empty-state title="No students yet" description="Add your first student to get started." icon="users" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($students->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">{{ $students->links() }}</div>
    @endif
</x-portal.card>

@foreach ($students as $student)
    <div id="student-drawer-{{ $student->id }}" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <button type="button" class="absolute inset-0 bg-slate-900/50" data-student-drawer-close aria-label="Close student drawer"></button>

        <aside class="absolute right-0 top-0 flex h-full w-full max-w-2xl flex-col bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div class="flex items-center gap-4">
                    <x-portal.avatar
                        :photo="$student->profilePhotoUrl()"
                        :initial="strtoupper(substr($student->first_name, 0, 1))"
                        :alt="$student->fullName()"
                    />
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $student->fullName() }}</h2>
                        <p class="text-sm text-slate-500">{{ $student->index_number }} · {{ $student->user->email }}</p>
                    </div>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-student-drawer-close aria-label="Close drawer">
                    <x-portal.icon name="close" class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        'Programme' => $student->programme?->name,
                        'Cohort' => $student->cohort?->name,
                        'First Specialization' => $student->firstSpecialization?->name,
                        'Second Specialization' => $student->secondSpecialization?->name,
                        'Phone' => $student->phone,
                        'Location' => $student->location,
                        'Region' => $student->region,
                        'Gender' => $student->gender,
                    ] as $label => $value)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $value ?: '—' }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-brand-50 px-4 py-3 ring-1 ring-brand-100">
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-600">Courses</p>
                        <p class="mt-1 text-2xl font-bold text-brand-900">{{ $student->registrations->count() }}</p>
                    </div>
                    <div class="rounded-lg bg-gold-50 px-4 py-3 ring-1 ring-gold-100">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Results</p>
                        <p class="mt-1 text-2xl font-bold text-brand-900">{{ $student->grades->count() }}</p>
                    </div>
                    <div class="rounded-lg bg-emerald-50 px-4 py-3 ring-1 ring-emerald-100">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Fees Paid</p>
                        <p class="mt-1 text-2xl font-bold text-brand-900">
                            {{ $student->paymentPlan ? $student->paymentPlan->currency.' '.number_format($student->paymentPlan->total_deposited, 2) : '—' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-900">Registered Courses</h3>
                    <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                        @forelse ($student->registrations as $registration)
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $registration->course?->code }} — {{ $registration->course?->title }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst($registration->status) }}</p>
                                </div>
                                <x-portal.badge :variant="$registration->is_paid ? 'success' : 'warning'">{{ $registration->is_paid ? 'Paid' : 'Unpaid' }}</x-portal.badge>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-slate-500">No registered courses yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-900">Results</h3>
                    <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                        @forelse ($student->grades as $grade)
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $grade->course?->code }} — {{ $grade->title }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst($grade->type) }}</p>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">{{ $grade->score ?? '—' }}/{{ $grade->max_score }}</span>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-slate-500">No results recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <x-portal.button variant="secondary" href="{{ route('admin.students.print', ['student' => $student, 'mode' => 'print']) }}" target="_blank">Print</x-portal.button>
                <x-portal.button href="{{ route('admin.students.print', ['student' => $student, 'mode' => 'pdf']) }}" target="_blank">Export PDF</x-portal.button>
                <x-portal.button variant="ghost" href="{{ route('admin.students.edit', $student) }}">Edit Student</x-portal.button>
                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100" data-student-drawer-close>Close</button>
            </div>
        </aside>
    </div>
@endforeach

<script>
    document.querySelectorAll('[data-student-drawer-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = document.getElementById(trigger.dataset.studentDrawerOpen);
            drawer?.classList.remove('hidden');
            drawer?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('[data-student-drawer-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = trigger.closest('[id^="student-drawer-"]');
            drawer?.classList.add('hidden');
            drawer?.setAttribute('aria-hidden', 'true');
        });
    });
</script>
@endsection
