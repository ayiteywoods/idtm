@extends('layouts.portal')

@section('title', 'Student Search')
@section('breadcrumb', 'Portal / Student Search')

@section('content')
<x-portal.page-header title="Student Search" description="Search by student name, index number, or email to review profile, courses, and results." />

<x-portal.card class="mb-6">
    <form method="GET" action="{{ route('portal.student-search') }}" class="flex flex-col gap-3 sm:flex-row">
        <label for="student-search-page" class="sr-only">Search students</label>
        <input
            id="student-search-page"
            name="q"
            type="search"
            value="{{ $query }}"
            placeholder="Enter student name or index number"
            class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200"
            autofocus
        >
        <x-portal.button type="submit">
            <x-portal.icon name="search" class="h-4 w-4" /> Search
        </x-portal.button>
    </form>
</x-portal.card>

@if ($query === '')
    <x-portal.empty-state title="Search for a student" description="Enter a student name, index number, or email to pull up their profile, registered courses, and results." icon="search" />
@elseif ($students->isEmpty())
    <x-portal.empty-state title="No student found" :description="'No student matched &quot;'.$query.'&quot;.'" icon="users" />
@else
    <div class="space-y-6">
        @foreach ($students as $student)
            <x-portal.card>
                <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                    <div class="min-w-0">
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

                        <dl class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-lg bg-slate-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Programme</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $student->programme?->name ?? '—' }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Cohort</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $student->cohort?->name ?? '—' }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Phone</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $student->phone ?: '—' }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</dt>
                                <dd class="mt-1">
                                    <x-portal.badge :variant="$student->user->is_active ? 'success' : 'muted'">{{ $student->user->is_active ? 'Active' : 'Inactive' }}</x-portal.badge>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    @if (auth()->user()->isAdmin())
                        <x-portal.button variant="secondary" href="{{ route('admin.students.edit', $student) }}">Edit Student</x-portal.button>
                    @endif
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Registered Courses</h3>
                        <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                            @forelse ($student->registrations as $registration)
                                <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $registration->course?->code }} — {{ $registration->course?->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $registration->specialization?->name ?? 'Core course' }}</p>
                                    </div>
                                    <x-portal.badge :variant="$registration->is_paid ? 'success' : 'warning'">{{ $registration->is_paid ? 'Paid' : 'Unpaid' }}</x-portal.badge>
                                </div>
                            @empty
                                <p class="px-4 py-6 text-sm text-slate-500">No registered courses yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Results</h3>
                        <div class="mt-3 overflow-hidden rounded-lg border border-slate-100">
                            @forelse ($student->grades as $grade)
                                <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $grade->course?->code }} — {{ $grade->title }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($grade->type) }}{{ $grade->remarks ? ' · '.$grade->remarks : '' }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900">{{ $grade->score ?? '—' }}/{{ $grade->max_score }}</span>
                                </div>
                            @empty
                                <p class="px-4 py-6 text-sm text-slate-500">No results recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </x-portal.card>
        @endforeach
    </div>
@endif
@endsection
