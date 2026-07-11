@extends('layouts.portal')

@section('title', 'My Courses')
@section('breadcrumb', 'Faculty Portal / Teaching')

@section('content')
<x-portal.page-header title="My Courses" description="Courses assigned to you by administration.">
    <x-slot:actions>
        <x-portal.badge variant="info">{{ $courses->count() }} courses</x-portal.badge>
    </x-slot:actions>
</x-portal.page-header>

<div class="grid gap-4">
    @forelse ($courses as $course)
        <x-portal.card :padding="true">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-brand-50 text-brand-600 ring-1 ring-brand-100">
                        <x-portal.icon name="book" class="h-6 w-6" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-slate-900">{{ $course->code }}</p>
                        <p class="text-sm text-slate-600">{{ $course->title }}</p>
                        <div class="mt-2 flex gap-2">
                            <x-portal.badge variant="muted">{{ $course->registrations_count }} registered</x-portal.badge>
                            @if ($course->is_core)<x-portal.badge variant="info">Core Module</x-portal.badge>@endif
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <x-portal.button variant="secondary" :href="route('faculty.materials.index')">Materials</x-portal.button>
                    <x-portal.button :href="route('faculty.courses.students', $course)">View Students</x-portal.button>
                </div>
            </div>
        </x-portal.card>
    @empty
        <x-portal.empty-state title="No courses assigned" description="Contact administration to get courses assigned to you." icon="book" />
    @endforelse
</div>
@endsection
