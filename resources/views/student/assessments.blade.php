@extends('layouts.portal')

@section('title', 'Assessments')
@section('breadcrumb', 'Student Portal / Academics')

@section('content')
<x-portal.page-header title="Assessments" description="View your assignments, quizzes, projects, and exams — then upload your submissions." />

@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 ring-1 ring-rose-200">{{ $errors->first() }}</div>
@endif

<div class="space-y-4">
    @forelse ($assessments as $assessment)
        @php $submission = $assessment->submissions->first(); @endphp
        <x-portal.card>
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-portal.badge variant="info">{{ ucfirst($assessment->type) }}</x-portal.badge>
                        <x-portal.badge variant="muted">{{ $assessment->course->code }}</x-portal.badge>
                        @if ($submission)
                            @if ($submission->isGraded())
                                <x-portal.badge variant="success">Graded</x-portal.badge>
                            @else
                                <x-portal.badge variant="navy">Submitted</x-portal.badge>
                            @endif
                            @if ($submission->is_late)<x-portal.badge variant="warning">Late</x-portal.badge>@endif
                        @endif
                    </div>
                    <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $assessment->title }}</h3>
                    <p class="text-sm text-slate-500">{{ $assessment->course->title }}</p>
                    @if ($assessment->due_at)
                        <p class="mt-1 text-sm {{ $assessment->isPastDue() ? 'text-rose-600' : 'text-slate-600' }}">
                            Due {{ $assessment->due_at->format('M j, Y · g:i A') }}
                            @if ($assessment->isPastDue()) · deadline passed @else · {{ $assessment->due_at->diffForHumans() }} @endif
                        </p>
                    @else
                        <p class="mt-1 text-sm text-slate-500">No deadline</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Max Score</p>
                    <p class="text-xl font-bold text-brand-900">{{ rtrim(rtrim(number_format($assessment->max_score, 2), '0'), '.') }}</p>
                </div>
            </div>

            @if ($assessment->instructions)
                <p class="mt-4 whitespace-pre-line rounded-lg bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-600">{{ $assessment->instructions }}</p>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-3">
                @if ($assessment->attachment_path)
                    <x-portal.button variant="secondary" size="sm" href="{{ route('student.assessments.brief', $assessment) }}">
                        <x-portal.icon name="folder" class="h-4 w-4" /> Download brief
                    </x-portal.button>
                @endif
                @if ($submission)
                    <x-portal.button variant="ghost" size="sm" href="{{ route('student.submissions.download', $submission) }}">
                        <x-portal.icon name="eye" class="h-4 w-4" /> My submission ({{ $submission->original_name }})
                    </x-portal.button>
                @endif
            </div>

            @if ($submission && $submission->isGraded())
                <div class="mt-4 rounded-lg border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                    <p class="text-sm font-semibold text-emerald-800">Score: {{ rtrim(rtrim(number_format($submission->score, 2), '0'), '.') }} / {{ rtrim(rtrim(number_format($assessment->max_score, 2), '0'), '.') }}</p>
                    @if ($submission->feedback)<p class="mt-1 text-sm text-emerald-700">Feedback: {{ $submission->feedback }}</p>@endif
                </div>
            @else
                <form method="POST" action="{{ route('student.assessments.submit', $assessment) }}" enctype="multipart/form-data" class="mt-4 flex flex-wrap items-end gap-3 border-t border-slate-100 pt-4">
                    @csrf
                    <div class="min-w-0 flex-1">
                        <label for="file-{{ $assessment->id }}" class="block text-sm font-semibold text-slate-700">{{ $submission ? 'Replace submission' : 'Upload submission' }}</label>
                        <input id="file-{{ $assessment->id }}" name="file" type="file" required class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900 hover:file:bg-gold-200">
                        <p class="mt-1 text-xs text-slate-500">PDF, DOC, PPT, XLS, or ZIP · up to 20 MB.@if ($assessment->isPastDue()) <span class="font-semibold text-amber-600">Deadline passed — your upload will be flagged late.</span>@endif</p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label for="note-{{ $assessment->id }}" class="block text-sm font-semibold text-slate-700">Note <span class="font-normal text-slate-400">(optional)</span></label>
                        <input id="note-{{ $assessment->id }}" name="note" type="text" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Message for your lecturer">
                    </div>
                    <x-portal.button type="submit" size="sm"><x-portal.icon name="plus" class="h-4 w-4" /> {{ $submission ? 'Resubmit' : 'Submit' }}</x-portal.button>
                </form>
            @endif
        </x-portal.card>
    @empty
        <x-portal.empty-state title="No assessments available" description="Assessments for your registered and paid courses will appear here." icon="clipboard">
            <x-slot:action><x-portal.button href="{{ route('student.registration') }}">Go to Registration</x-portal.button></x-slot:action>
        </x-portal.empty-state>
    @endforelse
</div>
@endsection
