@extends('layouts.portal')

@section('title', 'Course Registration')
@section('breadcrumb', 'Student Portal / Academics')

@section('content')
@php
    $firstSpecCount = $profile->registrations->where('specialization_id', $profile->first_specialization_id)->count();
    $firstRequired = $profile->firstSpecialization?->required_courses ?? 12;
    $firstComplete = $firstSpecCount >= $firstRequired;
    $firstProgress = min(100, ($firstSpecCount / max(1, $firstRequired)) * 100);
@endphp

<x-portal.page-header title="Course Registration" :description="$profile->programme?->name" />

<div class="mb-6 flex items-start gap-3 rounded-lg bg-blue-50 px-4 py-3.5 text-sm text-blue-800 ring-1 ring-blue-200">
    <x-portal.icon name="help" class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" />
    <p>Complete all courses in your first specialization before registering for the second.</p>
</div>

<div class="mb-6 rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200/80">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-lg font-bold text-slate-600">{{ strtoupper(substr($profile->first_name, 0, 1)) }}</div>
        <div>
            <p class="font-semibold text-slate-900">{{ $profile->fullName() }}</p>
            <p class="text-sm text-slate-500">{{ $profile->index_number }} · {{ $profile->cohort?->name }}</p>
        </div>
    </div>
</div>

<div class="grid gap-6 md:grid-cols-2">
    @if ($profile->firstSpecialization)
        <x-portal.card>
            <div class="flex items-start justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-50 text-brand-600 ring-1 ring-brand-100">
                    <x-portal.icon name="book" />
                </div>
                <x-portal.badge variant="success">Active</x-portal.badge>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $profile->firstSpecialization->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">First Specialization</p>
            <div class="mt-5">
                <div class="flex justify-between text-sm"><span class="text-slate-500">Progress</span><span class="font-medium text-slate-900">{{ $firstSpecCount }}/{{ $firstRequired }} courses</span></div>
                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-gold-500" style="width: {{ $firstProgress }}%"></div>
                </div>
            </div>
            <x-portal.button class="mt-5 w-full" href="{{ route('student.registration.catalog', ['specialization_id' => $profile->first_specialization_id]) }}">Register for Courses</x-portal.button>
        </x-portal.card>
    @endif

    @if ($profile->secondSpecialization)
        <x-portal.card @class(['opacity-75' => ! $firstComplete])>
            <div class="flex items-start justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-500 ring-1 ring-slate-200">
                    <x-portal.icon name="chart" />
                </div>
                <x-portal.badge :variant="$firstComplete ? 'success' : 'muted'">{{ $firstComplete ? 'Unlocked' : 'Locked' }}</x-portal.badge>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $profile->secondSpecialization->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">Second Specialization</p>
            @unless ($firstComplete)
                <p class="mt-5 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">Complete first specialization first to unlock registration.</p>
            @else
                <x-portal.button class="mt-5 w-full" href="{{ route('student.registration.catalog', ['specialization_id' => $profile->second_specialization_id]) }}">Register for Courses</x-portal.button>
            @endunless
        </x-portal.card>
    @endif
</div>
@if ($profile->registrations->isNotEmpty())
    <x-portal.card title="Registered Courses" class="mt-8">
        <div class="divide-y divide-slate-100">
            @foreach ($profile->registrations as $registration)
                <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                    <div>
                        <p class="font-medium text-slate-900">{{ $registration->course->code }} — {{ $registration->course->title }}</p>
                        <p class="text-sm text-slate-500">{{ ucfirst($registration->status) }} · {{ $registration->is_paid ? 'Paid' : 'Unpaid' }}</p>
                    </div>
                    <x-portal.badge :variant="$registration->is_paid ? 'success' : 'warning'">{{ $registration->is_paid ? 'Paid' : 'Pending Payment' }}</x-portal.badge>
                </div>
            @endforeach
        </div>
    </x-portal.card>
@endif
@endsection
