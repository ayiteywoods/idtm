@extends('layouts.portal')

@section('title', 'Dashboard')
@section('breadcrumb', 'Faculty Portal')

@section('content')
<x-portal.page-header :title="'Welcome, '.$profile->user->name" :description="($profile->title ?? 'Faculty').' · '.($profile->department ?? 'Department')">
    <x-slot:actions>
        <x-portal.button href="{{ route('faculty.materials.index') }}">Upload Material</x-portal.button>
        <x-portal.button variant="secondary" href="{{ route('faculty.grades.index') }}">Record Grades</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-portal.stat-card label="Assigned Courses" :value="$stats['courses']" icon="book" color="brand" :href="route('faculty.courses.index')" />
    <x-portal.stat-card label="Total Students" :value="$stats['students']" icon="users" color="blue" />
    <x-portal.stat-card label="Learning Materials" :value="$stats['materials']" icon="folder" color="emerald" :href="route('faculty.materials.index')" />
    <x-portal.stat-card label="Grades Recorded" :value="$stats['grades']" icon="grade" color="amber" :href="route('faculty.grades.index')" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <x-portal.card title="My Courses">
            <x-slot:header>
                <a href="{{ route('faculty.courses.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all</a>
            </x-slot:header>
            <div class="space-y-3">
                @forelse ($courses as $course)
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3.5 transition hover:border-brand-200">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                                <x-portal.icon name="book" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $course->code }}</p>
                                <p class="text-sm text-slate-500">{{ Str::limit($course->title, 50) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-portal.badge variant="muted">{{ $course->registrations_count }} students</x-portal.badge>
                            <x-portal.button variant="ghost" class="!px-2 !py-1.5 text-xs" :href="route('faculty.courses.students', $course)">View</x-portal.button>
                        </div>
                    </div>
                @empty
                    <x-portal.empty-state title="No courses assigned" description="Contact administration to get courses assigned to you." icon="book" />
                @endforelse
            </div>
        </x-portal.card>

        <x-portal.card title="Recent Grades">
            <x-slot:header>
                <a href="{{ route('faculty.grades.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all</a>
            </x-slot:header>
            <div class="overflow-x-auto -mx-6 -my-5">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Student</th>
                            <th class="px-6 py-3">Course</th>
                            <th class="px-6 py-3">Assessment</th>
                            <th class="px-6 py-3">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentGrades as $grade)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-3 font-medium">{{ $grade->student->fullName() }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $grade->course->code }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $grade->title }}</td>
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $grade->score }}/{{ $grade->max_score }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No grades recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-portal.card>
    </div>

    <div class="space-y-6">
        <x-portal.card title="Quick Actions" :padding="true">
            <div class="grid gap-2">
                <x-portal.button variant="secondary" href="{{ route('faculty.materials.index') }}" class="w-full justify-start"><x-portal.icon name="folder" class="h-4 w-4" /> Upload Materials</x-portal.button>
                <x-portal.button variant="secondary" href="{{ route('faculty.grades.index') }}" class="w-full justify-start"><x-portal.icon name="grade" class="h-4 w-4" /> Add Grades / Resits</x-portal.button>
                <x-portal.button variant="secondary" href="{{ route('faculty.library.index') }}" class="w-full justify-start"><x-portal.icon name="library" class="h-4 w-4" /> Upload Books</x-portal.button>
            </div>
        </x-portal.card>

        <x-portal.card title="Faculty Profile" :padding="true">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Employee ID</dt><dd class="font-mono font-medium">{{ $profile->employee_id }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Department</dt><dd class="font-medium">{{ $profile->department ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Title</dt><dd class="font-medium">{{ $profile->title ?? '—' }}</dd></div>
            </dl>
        </x-portal.card>
    </div>
</div>
@endsection
