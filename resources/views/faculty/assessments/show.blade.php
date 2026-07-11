@extends('layouts.portal')

@section('title', 'Assessment')
@section('breadcrumb', 'Faculty Portal / Assessments')

@section('content')
<x-portal.page-header :title="$assessment->title" :description="$assessment->course->code.' — '.$assessment->course->title">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('faculty.assessments.index') }}">Back to Assessments</x-portal.button>
        @if ($assessment->attachment_path)
            <x-portal.button variant="secondary" href="{{ route('faculty.assessments.brief', $assessment) }}">
                <x-portal.icon name="external" class="h-4 w-4" /> Brief
            </x-portal.button>
        @endif
    </x-slot:actions>
</x-portal.page-header>

@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">{{ session('status') }}</div>
@endif

<div class="mb-6 grid gap-4 sm:grid-cols-4">
    <x-portal.stat-card label="Type" :value="ucfirst($assessment->type)" icon="clipboard" color="violet" />
    <x-portal.stat-card label="Max Score" :value="rtrim(rtrim(number_format($assessment->max_score, 2), '0'), '.')" icon="grade" color="brand" />
    <x-portal.stat-card label="Submissions" :value="$submissions->count().' / '.$registeredCount" icon="users" color="blue" />
    <x-portal.stat-card label="Graded" :value="$submissions->whereNotNull('score')->count()" icon="grade" color="emerald" />
</div>

@if ($assessment->instructions)
    <x-portal.card title="Instructions" class="mb-6">
        <p class="whitespace-pre-line text-sm leading-6 text-slate-600">{{ $assessment->instructions }}</p>
    </x-portal.card>
@endif

<x-portal.card title="Submissions" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Student</th>
                    <th class="px-6 py-3.5">Submitted</th>
                    <th class="px-6 py-3.5">File</th>
                    <th class="px-6 py-3.5">Grade</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($submissions as $submission)
                    <tr class="align-top hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $submission->student->fullName() }}</p>
                            <p class="font-mono text-xs text-slate-500">{{ $submission->student->index_number }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $submission->submitted_at->format('M j, Y · g:i A') }}
                            @if ($submission->is_late)<div class="mt-1"><x-portal.badge variant="warning">Late</x-portal.badge></div>@endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('faculty.submissions.download', $submission) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700">
                                <x-portal.icon name="folder" class="h-4 w-4" /> Download
                            </a>
                            @if ($submission->note)<p class="mt-1 max-w-xs text-xs text-slate-500">“{{ $submission->note }}”</p>@endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($submission->isGraded())
                                <span class="font-semibold text-slate-900">{{ rtrim(rtrim(number_format($submission->score, 2), '0'), '.') }}</span><span class="text-slate-400">/{{ rtrim(rtrim(number_format($assessment->max_score, 2), '0'), '.') }}</span>
                                @if ($submission->feedback)<p class="mt-1 max-w-xs text-xs text-slate-500">{{ $submission->feedback }}</p>@endif
                            @else
                                <x-portal.badge variant="muted">Not graded</x-portal.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('faculty.submissions.grade', $submission) }}" class="flex flex-wrap items-start justify-end gap-2">
                                @csrf
                                <input type="number" name="score" step="0.01" min="0" max="{{ $assessment->max_score }}" value="{{ $submission->score }}" placeholder="Score" class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                <input type="text" name="feedback" value="{{ $submission->feedback }}" placeholder="Feedback (optional)" class="w-44 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <x-portal.button type="submit" size="sm">{{ $submission->isGraded() ? 'Update' : 'Grade' }}</x-portal.button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12"><x-portal.empty-state title="No submissions yet" description="Students who have submitted will appear here for grading." icon="clipboard" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-portal.card>
@endsection
