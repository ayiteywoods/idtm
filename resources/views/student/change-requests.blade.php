@extends('layouts.portal')

@section('title', 'Change Requests')
@section('breadcrumb', 'Student Portal / Support')

@section('content')
<x-portal.page-header title="Change Requests" description="Submit a request to correct a course registration error.">
    <x-slot:actions>
        @if ($eligibleRegistrations->isNotEmpty())
            <x-portal.button href="#new-request-form"><x-portal.icon name="plus" class="h-4 w-4" /> New Request</x-portal.button>
        @endif
    </x-slot:actions>
</x-portal.page-header>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <x-portal.stat-card label="Total Requests" :value="$stats['total']" icon="swap" color="brand" />
    <x-portal.stat-card label="Pending" :value="$stats['pending']" icon="bell" color="amber" />
    <x-portal.stat-card label="Resolved" :value="$stats['resolved']" icon="chart" color="emerald" />
</div>

@if ($eligibleRegistrations->isNotEmpty())
    <x-portal.card id="new-request-form" title="Submit a New Request" class="mb-6">
        <form method="POST" action="{{ route('student.change-requests.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="course_registration_id" class="block text-sm font-semibold text-slate-700">Course Registration</label>
                <select id="course_registration_id" name="course_registration_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    <option value="">Select a course</option>
                    @foreach ($eligibleRegistrations as $registration)
                        <option value="{{ $registration->id }}" @selected((int) old('course_registration_id') === $registration->id)>
                            {{ $registration->course->code }} — {{ $registration->course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_registration_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm" placeholder="Explain the registration error you need corrected..." required>{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end">
                <x-portal.button type="submit">Submit Request</x-portal.button>
            </div>
        </form>
    </x-portal.card>
@endif

@if ($requests->isEmpty())
    <x-portal.empty-state title="No Requests Yet" description="Made a mistake with a course registration? Submit a correction request and the admin team will review it." icon="swap" />
@else
    <div class="space-y-4">
        @foreach ($requests as $request)
            <x-portal.card :padding="true">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $request->registration->course->code }} — {{ $request->registration->course->title }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $request->description }}</p>
                        @if ($request->admin_notes)
                            <p class="mt-2 text-sm text-slate-500"><strong>Admin notes:</strong> {{ $request->admin_notes }}</p>
                        @endif
                        <p class="mt-2 text-xs text-slate-400">{{ $request->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <x-portal.badge :variant="match($request->status) { 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', default => 'muted' }">
                        {{ ucfirst($request->status) }}
                    </x-portal.badge>
                </div>
            </x-portal.card>
        @endforeach
    </div>
@endif
@endsection
