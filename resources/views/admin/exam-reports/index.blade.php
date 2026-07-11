@extends('layouts.portal')

@section('title', 'Examination Reports')
@section('breadcrumb', 'Administration / Examination Reports')

@section('content')
<x-portal.page-header title="Examination Reports" description="Generate a results broadsheet for any course, with pass/fail analysis you can print or export.">
    @if ($course)
        <x-slot:actions>
            <x-portal.button variant="secondary" href="{{ route('admin.exam-reports.print', ['course' => $course->id, 'cohort' => request('cohort'), 'type' => $type]) }}" target="_blank">
                <x-portal.icon name="external" class="h-4 w-4" /> Print / Export
            </x-portal.button>
        </x-slot:actions>
    @endif
</x-portal.page-header>

<x-portal.card title="Report Filters" class="mb-6">
    <form method="GET" action="{{ route('admin.exam-reports.index') }}" class="grid gap-4 md:grid-cols-4">
        <div class="md:col-span-2">
            <label for="course" class="block text-sm font-semibold text-slate-700">Course</label>
            <select id="course" name="course" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                <option value="">Select a course…</option>
                @foreach ($courses as $option)
                    <option value="{{ $option->id }}" @selected($course && $course->id === $option->id)>
                        {{ $option->code }} — {{ $option->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="cohort" class="block text-sm font-semibold text-slate-700">Cohort</label>
            <select id="cohort" name="cohort" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                <option value="">All cohorts</option>
                @foreach ($cohorts as $option)
                    <option value="{{ $option->id }}" @selected($selectedCohort && $selectedCohort->id === $option->id)>{{ $option->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="type" class="block text-sm font-semibold text-slate-700">Assessment</label>
            <div class="mt-2 flex gap-2">
                <select id="type" name="type" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    @foreach (\App\Support\ExamReport::TYPES as $option)
                        <option value="{{ $option }}" @selected($type === $option)>{{ \App\Support\ExamReport::typeLabel($option) }}</option>
                    @endforeach
                </select>
                <x-portal.button type="submit">Run</x-portal.button>
            </div>
        </div>
    </form>
</x-portal.card>

@if (! $course)
    <x-portal.empty-state
        title="Select a course to begin"
        description="Choose a course above to generate its examination results broadsheet."
        icon="grade" />
@else
    @php $summary = $report['summary']; @endphp

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-portal.stat-card label="Students" :value="$summary['students']" icon="users" color="blue" />
        <x-portal.stat-card label="Class Average" :value="$summary['average'] !== null ? $summary['average'].'%' : '—'" icon="chart" color="violet" />
        <x-portal.stat-card label="Passed" :value="$summary['passed']" icon="grade" color="emerald" :hint="$summary['pass_rate'] !== null ? $summary['pass_rate'].'% rate' : null" />
        <x-portal.stat-card label="Failed" :value="$summary['failed']" icon="swap" color="rose" />
    </div>

    <x-portal.card :padding="false">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
            <div>
                <h2 class="text-base font-semibold text-slate-900">{{ $course->code }} — {{ $course->title }}</h2>
                <p class="text-sm text-slate-500">
                    {{ $typeLabel }}
                    @if ($selectedCohort) &middot; {{ $selectedCohort->name }} @endif
                    @if ($course->programme) &middot; {{ $course->programme->name }} @endif
                </p>
            </div>
            @if ($summary['ungraded'] > 0)
                <x-portal.badge variant="warning">{{ $summary['ungraded'] }} awaiting grades</x-portal.badge>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">#</th>
                        <th class="px-6 py-3.5">Student</th>
                        <th class="px-6 py-3.5">Index</th>
                        <th class="px-6 py-3.5">Cohort</th>
                        <th class="px-6 py-3.5 text-center">Assessments</th>
                        <th class="px-6 py-3.5 text-center">Score</th>
                        <th class="px-6 py-3.5 text-center">%</th>
                        <th class="px-6 py-3.5 text-center">Grade</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($report['rows'] as $index => $row)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $row['name'] }}
                                @if ($row['has_resit'])<x-portal.badge variant="warning" class="ml-2">Resit</x-portal.badge>@endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $row['index_number'] }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $row['cohort'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">{{ $row['assessments'] }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">
                                @if ($row['assessments'] > 0)
                                    {{ rtrim(rtrim(number_format($row['obtained'], 2), '0'), '.') }}/{{ rtrim(rtrim(number_format($row['max'], 2), '0'), '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-900">{{ $row['percentage'] !== null ? $row['percentage'].'%' : '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $row['grade'] === 'F' ? 'text-rose-600' : 'text-brand-700' }}">{{ $row['grade'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-portal.badge :variant="match($row['status']) { 'Pass' => 'success', 'Fail' => 'danger', default => 'muted' }">{{ $row['status'] }}</x-portal.badge>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-12"><x-portal.empty-state title="No registered students" description="No students are registered for this course{{ $selectedCohort ? ' in the selected cohort' : '' }}." icon="users" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-portal.card>

    <p class="mt-4 text-xs text-slate-400">Grading scale: A (80+), B (70–79), C (60–69), D (50–59), F (below 50). Pass mark {{ (int) \App\Support\ExamReport::PASS_MARK }}%.</p>
@endif
@endsection
