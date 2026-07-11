@extends('layouts.portal')

@section('title', 'Edit Faculty')
@section('breadcrumb', 'Administration / Faculty / Edit')

@section('content')
<x-portal.page-header :title="'Edit '.$member->user->name" :description="$member->employee_id.' · '.$member->user->email">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.faculty.index') }}">Back to Faculty</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.faculty.update', $member) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $member->user->name) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $member->user->email) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label for="employee_id" class="block text-sm font-semibold text-slate-700">Employee ID</label>
                <input id="employee_id" name="employee_id" type="text" value="{{ old('employee_id', $member->employee_id) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('employee_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $member->title) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $member->phone) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="department" class="block text-sm font-semibold text-slate-700">Department</label>
            <input id="department" name="department" type="text" value="{{ old('department', $member->department) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
            @error('department')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <p class="block text-sm font-semibold text-slate-700">Assigned Courses</p>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                @foreach ($courses as $course)
                    <label class="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3">
                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(in_array($course->id, old('course_ids', $member->courses->pluck('id')->all())))>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">{{ $course->code }} — {{ $course->title }}</span>
                            <span class="block text-xs text-slate-500">{{ $course->is_core ? 'Core' : 'Specialization' }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('course_ids')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(old('is_active', $member->user->is_active))>
            <span>
                <span class="block text-sm font-semibold text-slate-700">Active account</span>
                <span class="block text-sm text-slate-500">Inactive faculty cannot sign in to the portal.</span>
            </span>
        </label>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.faculty.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Save Faculty</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
