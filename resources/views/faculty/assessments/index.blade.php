@extends('layouts.portal')

@section('title', 'Assessments')
@section('breadcrumb', 'Faculty Portal / Assessments')

@section('content')
<x-portal.page-header title="Assessments" description="Create assignments, quizzes, projects, and exams for your courses, then collect and grade student submissions." />

@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">{{ session('status') }}</div>
@endif

@if ($courses->isNotEmpty())
    <x-portal.card title="Create Assessment" class="mb-6">
        <form method="POST" action="{{ route('faculty.assessments.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label for="course_id" class="block text-sm font-semibold text-slate-700">Course</label>
                <select id="course_id" name="course_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((int) old('course_id') === $course->id)>{{ $course->code }} — {{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700">Type</label>
                <select id="type" name="type" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    @foreach (\App\Models\Assessment::TYPES as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div class="md:col-span-2">
                <label for="instructions" class="block text-sm font-semibold text-slate-700">Instructions <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="instructions" name="instructions" rows="3" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">{{ old('instructions') }}</textarea>
            </div>
            <div>
                <label for="max_score" class="block text-sm font-semibold text-slate-700">Max Score</label>
                <input id="max_score" name="max_score" type="number" step="0.01" min="1" value="{{ old('max_score', 100) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div>
                <label for="due_at" class="block text-sm font-semibold text-slate-700">Due Date <span class="font-normal text-slate-400">(optional)</span></label>
                <input id="due_at" name="due_at" type="datetime-local" value="{{ old('due_at') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            </div>
            <div class="md:col-span-2">
                <label for="attachment" class="block text-sm font-semibold text-slate-700">Brief / Attachment <span class="font-normal text-slate-400">(optional)</span></label>
                <input id="attachment" name="attachment" type="file" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900 hover:file:bg-gold-200">
                <p class="mt-1 text-xs text-slate-500">PDF, DOC, PPT, XLS, or ZIP · up to 20 MB.</p>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <x-portal.button type="submit"><x-portal.icon name="plus" class="h-4 w-4" /> Publish Assessment</x-portal.button>
            </div>
        </form>
    </x-portal.card>
@else
    <x-portal.card class="mb-6"><p class="text-sm text-slate-500">You have no assigned courses yet. Assessments can be created once courses are assigned to you.</p></x-portal.card>
@endif

<x-portal.card :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Assessment</th>
                    <th class="px-6 py-3.5">Course</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Due</th>
                    <th class="px-6 py-3.5 text-center">Submissions</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($assessments as $assessment)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <a href="{{ route('faculty.assessments.show', $assessment) }}" class="font-medium text-slate-900 hover:text-brand-700">{{ $assessment->title }}</a>
                        </td>
                        <td class="px-6 py-4"><x-portal.badge variant="muted">{{ $assessment->course->code }}</x-portal.badge></td>
                        <td class="px-6 py-4"><x-portal.badge variant="info">{{ ucfirst($assessment->type) }}</x-portal.badge></td>
                        <td class="px-6 py-4 text-slate-600">
                            @if ($assessment->due_at)
                                {{ $assessment->due_at->format('M j, Y · g:i A') }}
                                @if ($assessment->isPastDue())<span class="ml-1 text-xs font-semibold text-rose-600">(closed)</span>@endif
                            @else
                                <span class="text-slate-400">No deadline</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-slate-900">{{ $assessment->submissions_count }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('faculty.assessments.show', $assessment) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700" aria-label="View"><x-portal.icon name="eye" class="h-4 w-4" /></a>
                                <form method="POST" action="{{ route('faculty.assessments.destroy', $assessment) }}" onsubmit="return confirm('Delete this assessment and all its submissions?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-rose-50 hover:text-rose-700" aria-label="Delete"><x-portal.icon name="trash" class="h-4 w-4" /></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12"><x-portal.empty-state title="No assessments yet" description="Create your first assignment, quiz, project, or exam using the form above." icon="clipboard" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($assessments->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $assessments->links() }}</div>@endif
</x-portal.card>
@endsection
