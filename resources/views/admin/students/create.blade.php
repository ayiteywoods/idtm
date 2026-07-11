@extends('layouts.portal')

@section('title', 'Add Student')
@section('breadcrumb', 'Administration / Students / Create')

@section('content')
<x-portal.page-header title="Add Student" description="Create a new student account and profile.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.students.index') }}">Back to Students</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
        @csrf

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label for="first_name" class="block text-sm font-semibold text-slate-700">First Name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('first_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="other_names" class="block text-sm font-semibold text-slate-700">Other Names</label>
                <input id="other_names" name="other_names" type="text" value="{{ old('other_names') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-semibold text-slate-700">Last Name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('last_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="index_number" class="block text-sm font-semibold text-slate-700">Index Number</label>
                <input id="index_number" name="index_number" type="text" value="{{ old('index_number') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('index_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('username')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="programme_id" class="block text-sm font-semibold text-slate-700">Programme</label>
                <select id="programme_id" name="programme_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    <option value="">None</option>
                    @foreach ($programmes as $programme)
                        <option value="{{ $programme->id }}" @selected((int) old('programme_id') === $programme->id)>{{ $programme->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="cohort_id" class="block text-sm font-semibold text-slate-700">Cohort</label>
                <select id="cohort_id" name="cohort_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    <option value="">None</option>
                    @foreach ($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" @selected((int) old('cohort_id') === $cohort->id)>{{ $cohort->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="first_specialization_id" class="block text-sm font-semibold text-slate-700">First Specialization</label>
                <select id="first_specialization_id" name="first_specialization_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    <option value="">None</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected((int) old('first_specialization_id') === $specialization->id)>{{ $specialization->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="second_specialization_id" class="block text-sm font-semibold text-slate-700">Second Specialization</label>
                <select id="second_specialization_id" name="second_specialization_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    <option value="">None</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected((int) old('second_specialization_id') === $specialization->id)>{{ $specialization->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.students.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Create Student</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
