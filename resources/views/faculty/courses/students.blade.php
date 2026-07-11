@extends('layouts.portal')

@section('title', 'Registered Students')
@section('breadcrumb', 'Faculty Portal / '.$course->code)

@section('content')
<x-portal.page-header :title="$course->code.' — '.$course->title" description="Students registered for this course.">
    <x-slot:actions>
        <x-portal.button variant="secondary" :href="route('faculty.courses.index')">← Back to Courses</x-portal.button>
        <x-portal.button :href="route('faculty.grades.index')">Record Grades</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<div class="mb-6">
    <x-portal.stat-card label="Registered Students" :value="$course->registrations->count()" icon="users" color="blue" />
</div>

<x-portal.card :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Student</th>
                    <th class="px-6 py-3.5">Index Number</th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Payment</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($course->registrations as $registration)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-xs font-bold text-blue-600">{{ strtoupper(substr($registration->student->first_name, 0, 1)) }}</div>
                                <span class="font-medium">{{ $registration->student->fullName() }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $registration->student->index_number }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $registration->student->user->email }}</td>
                        <td class="px-6 py-4">
                            <x-portal.badge :variant="$registration->is_paid ? 'success' : 'warning'">{{ $registration->is_paid ? 'Paid' : 'Unpaid' }}</x-portal.badge>
                        </td>
                        <td class="px-6 py-4"><x-portal.badge variant="muted">{{ ucfirst($registration->status) }}</x-portal.badge></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12"><x-portal.empty-state title="No students registered" description="Students will appear here once they register for this course." icon="users" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-portal.card>
@endsection
