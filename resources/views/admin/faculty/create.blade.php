@extends('layouts.portal')

@section('title', 'Add Faculty')
@section('breadcrumb', 'Administration / Faculty / Create')

@section('content')
<x-portal.page-header title="Add Faculty" description="Create a new faculty account and profile.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.faculty.index') }}">Back to Faculty</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.faculty.store') }}" class="space-y-6">
        @csrf

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
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
                <label for="employee_id" class="block text-sm font-semibold text-slate-700">Employee ID</label>
                <input id="employee_id" name="employee_id" type="text" value="{{ old('employee_id') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('employee_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label for="department" class="block text-sm font-semibold text-slate-700">Department</label>
                <input id="department" name="department" type="text" value="{{ old('department') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            </div>
        </div>

        @if ($courses->isNotEmpty())
            <div>
                <p class="block text-sm font-semibold text-slate-700">Assigned Courses</p>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    @foreach ($courses as $course)
                        <label class="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3">
                            <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(in_array($course->id, old('course_ids', [])))>
                            <span class="text-sm font-semibold text-slate-900">{{ $course->code }} — {{ $course->title }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.faculty.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Create Faculty</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
