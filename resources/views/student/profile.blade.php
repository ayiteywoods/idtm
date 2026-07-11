@extends('layouts.portal')

@section('title', 'My Profile')
@section('breadcrumb', 'Student Portal / Profile')

@section('content')
<x-portal.page-header title="Personal Details" :description="$profile->programme?->name.' · '.$profile->index_number" />

<div class="grid gap-6 lg:grid-cols-3">
    <x-portal.card class="lg:col-span-1">
        <div class="text-center">
            <x-portal.avatar
                size="lg"
                :photo="$profile->profilePhotoUrl()"
                :initial="strtoupper(substr($profile->first_name, 0, 1))"
                :alt="$profile->fullName()"
            />
            <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $profile->fullName() }}</h2>
            <p class="text-sm text-slate-500">{{ $profile->user->email }}</p>
            <div class="mt-4 flex justify-center gap-2">
                <x-portal.badge variant="info">{{ $profile->index_number }}</x-portal.badge>
                <x-portal.badge variant="muted">{{ $profile->cohort?->name }}</x-portal.badge>
            </div>
        </div>
        @if ($profile->firstSpecialization)
            <div class="mt-6 rounded-lg border border-gold-200 bg-gold-50 p-4 ring-1 ring-gold-100">
                <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Specializations</p>
                <p class="mt-2 text-sm font-medium text-brand-900">1. {{ $profile->firstSpecialization->name }}</p>
                @if ($profile->secondSpecialization)
                    <p class="text-sm text-brand-800">2. {{ $profile->secondSpecialization->name }}</p>
                @endif
            </div>
        @endif
    </x-portal.card>

    <x-portal.card title="Profile Information" class="lg:col-span-2">
        <dl class="grid gap-5 sm:grid-cols-2">
            @foreach ([
                'First Name' => $profile->first_name,
                'Other Name(s)' => $profile->other_names,
                'Last Name' => $profile->last_name,
                'Gender' => $profile->gender,
                'Date of Birth' => $profile->date_of_birth?->format('M j, Y'),
                'Phone' => $profile->phone,
                'Country' => $profile->country,
                'Location' => $profile->location,
                'Region' => $profile->region,
                'Religion' => $profile->religion,
            ] as $label => $value)
                <div class="rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">{{ $value ?: '—' }}</dd>
                </div>
            @endforeach
        </dl>
    </x-portal.card>
</div>
@endsection
