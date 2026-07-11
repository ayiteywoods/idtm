@extends('layouts.portal')

@section('title', 'Edit Student')
@section('breadcrumb', 'Administration / Students / Edit')

@section('content')
<x-portal.page-header :title="'Edit '.$student->fullName()" :description="$student->index_number.' · '.$student->user->email">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.students.index') }}">Back to Students</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label for="first_name" class="block text-sm font-semibold text-slate-700">First Name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $student->first_name) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('first_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="other_names" class="block text-sm font-semibold text-slate-700">Other Names</label>
                <input id="other_names" name="other_names" type="text" value="{{ old('other_names', $student->other_names) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('other_names')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-semibold text-slate-700">Last Name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $student->last_name) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('last_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="index_number" class="block text-sm font-semibold text-slate-700">Index Number</label>
                <input id="index_number" name="index_number" type="text" value="{{ old('index_number', $student->index_number) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('index_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $student->user->email) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="programme_id" class="block text-sm font-semibold text-slate-700">Programme</label>
                <select id="programme_id" name="programme_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    <option value="">None</option>
                    @foreach ($programmes as $programme)
                        <option value="{{ $programme->id }}" @selected((int) old('programme_id', $student->programme_id) === $programme->id)>{{ $programme->name }}</option>
                    @endforeach
                </select>
                @error('programme_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="cohort_id" class="block text-sm font-semibold text-slate-700">Cohort</label>
                <select id="cohort_id" name="cohort_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    <option value="">None</option>
                    @foreach ($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" @selected((int) old('cohort_id', $student->cohort_id) === $cohort->id)>{{ $cohort->name }}</option>
                    @endforeach
                </select>
                @error('cohort_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="first_specialization_id" class="block text-sm font-semibold text-slate-700">First Specialization</label>
                <select id="first_specialization_id" name="first_specialization_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    <option value="">None</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected((int) old('first_specialization_id', $student->first_specialization_id) === $specialization->id)>{{ $specialization->name }}</option>
                    @endforeach
                </select>
                @error('first_specialization_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="second_specialization_id" class="block text-sm font-semibold text-slate-700">Second Specialization</label>
                <select id="second_specialization_id" name="second_specialization_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    <option value="">None</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected((int) old('second_specialization_id', $student->second_specialization_id) === $specialization->id)>{{ $specialization->name }}</option>
                    @endforeach
                </select>
                @error('second_specialization_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label for="gender" class="block text-sm font-semibold text-slate-700">Gender</label>
                <input id="gender" name="gender" type="text" value="{{ old('gender', $student->gender) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('gender')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="date_of_birth" class="block text-sm font-semibold text-slate-700">Date of Birth</label>
                <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('date_of_birth')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $student->phone) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-4">
            <div>
                <label for="country" class="block text-sm font-semibold text-slate-700">Country Code</label>
                <input id="country" name="country" type="text" maxlength="2" value="{{ old('country', $student->country) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('country')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-semibold text-slate-700">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location', $student->location) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('location')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="region" class="block text-sm font-semibold text-slate-700">Region</label>
                <input id="region" name="region" type="text" value="{{ old('region', $student->region) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('region')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="religion" class="block text-sm font-semibold text-slate-700">Religion</label>
                <input id="religion" name="religion" type="text" value="{{ old('religion', $student->religion) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('religion')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(old('is_active', $student->user->is_active))>
            <span>
                <span class="block text-sm font-semibold text-slate-700">Active account</span>
                <span class="block text-sm text-slate-500">Inactive students cannot sign in to the portal.</span>
            </span>
        </label>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.students.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Save Student</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
