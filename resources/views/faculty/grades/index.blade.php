@extends('layouts.portal')

@section('title', 'Grades & Marks')
@section('breadcrumb', 'Faculty Portal / Assessment')

@section('content')
<x-portal.page-header title="Grades & Marks" description="Record assignment marks, exam scores, and resit results." />

@if ($courses->isNotEmpty())
    <x-portal.card title="Add Grade" class="mb-6">
        <form method="POST" action="{{ route('faculty.grades.store') }}" class="grid gap-4 md:grid-cols-2">
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
                <label for="student_profile_id" class="block text-sm font-semibold text-slate-700">Student</label>
                <select id="student_profile_id" name="student_profile_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    <option value="">Select student</option>
                    @foreach ($courses as $course)
                        @foreach ($course->registrations as $registration)
                            <option value="{{ $registration->student_profile_id }}" data-course="{{ $course->id }}" @selected((int) old('student_profile_id') === $registration->student_profile_id)>
                                {{ $registration->student->fullName() }} ({{ $course->code }})
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700">Type</label>
                <select id="type" name="type" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    @foreach (['assignment', 'exam', 'project', 'quiz'] as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Assessment Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div>
                <label for="score" class="block text-sm font-semibold text-slate-700">Score</label>
                <input id="score" name="score" type="number" step="0.01" min="0" value="{{ old('score') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div>
                <label for="max_score" class="block text-sm font-semibold text-slate-700">Max Score</label>
                <input id="max_score" name="max_score" type="number" step="0.01" min="1" value="{{ old('max_score', 100) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <input type="hidden" name="is_resit" value="0">
                    <input type="checkbox" name="is_resit" value="1" class="rounded border-slate-300 text-gold-600 accent-gold-500" @checked(old('is_resit'))>
                    Resit exam
                </label>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <x-portal.button type="submit"><x-portal.icon name="plus" class="h-4 w-4" /> Add Grade</x-portal.button>
            </div>
        </form>
    </x-portal.card>
@endif

<x-portal.card :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Student</th>
                    <th class="px-6 py-3.5">Course</th>
                    <th class="px-6 py-3.5">Assessment</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Score</th>
                    <th class="px-6 py-3.5">Resit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($grades as $grade)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-medium">{{ $grade->student->fullName() }}</td>
                        <td class="px-6 py-4"><x-portal.badge variant="muted">{{ $grade->course->code }}</x-portal.badge></td>
                        <td class="px-6 py-4 text-slate-600">{{ $grade->title }}</td>
                        <td class="px-6 py-4"><x-portal.badge variant="info">{{ ucfirst($grade->type) }}</x-portal.badge></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">{{ $grade->score }}</span><span class="text-slate-400">/{{ $grade->max_score }}</span></td>
                        <td class="px-6 py-4"><x-portal.badge :variant="$grade->is_resit ? 'warning' : 'muted'">{{ $grade->is_resit ? 'Yes' : 'No' }}</x-portal.badge></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12"><x-portal.empty-state title="No grades recorded" description="Start recording assignment and exam scores for your students." icon="grade" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($grades->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $grades->links() }}</div>@endif
</x-portal.card>
@endsection
