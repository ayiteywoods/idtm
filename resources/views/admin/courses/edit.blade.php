@extends('layouts.portal')

@section('title', 'Edit Course')
@section('breadcrumb', 'Administration / Courses / Edit')

@section('content')
<x-portal.page-header :title="'Edit '.$course->code" :description="$course->title">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.courses.index') }}">Back to Courses</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700">Course Code</label>
                <input id="code" name="code" type="text" value="{{ old('code', $course->code) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('code')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="credits" class="block text-sm font-semibold text-slate-700">Credits</label>
                <input id="credits" name="credits" type="number" min="1" max="30" value="{{ old('credits', $course->credits) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('credits')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="title" class="block text-sm font-semibold text-slate-700">Course Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
            @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="programme_id" class="block text-sm font-semibold text-slate-700">Programme</label>
                <select id="programme_id" name="programme_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                    @foreach ($programmes as $programme)
                        <option value="{{ $programme->id }}" @selected((int) old('programme_id', $course->programme_id) === $programme->id)>{{ $programme->name }}</option>
                    @endforeach
                </select>
                @error('programme_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="specialization_id" class="block text-sm font-semibold text-slate-700">Specialization</label>
                <select id="specialization_id" name="specialization_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200">
                    <option value="">Core course</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected((int) old('specialization_id', $course->specialization_id) === $specialization->id)>{{ $specialization->name }}</option>
                    @endforeach
                </select>
                @error('specialization_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <input type="hidden" name="is_core" value="0">
                <input type="checkbox" name="is_core" value="1" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(old('is_core', $course->is_core))>
                <span>
                    <span class="block text-sm font-semibold text-slate-700">Core course</span>
                    <span class="block text-sm text-slate-500">Mark this course as part of the core curriculum.</span>
                </span>
            </label>

            <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(old('is_active', $course->is_active))>
                <span>
                    <span class="block text-sm font-semibold text-slate-700">Active course</span>
                    <span class="block text-sm text-slate-500">Inactive courses stay hidden from active course workflows.</span>
                </span>
            </label>
        </div>

        <div>
            <p class="block text-sm font-semibold text-slate-700">Assigned Faculty</p>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                @foreach ($faculty as $member)
                    <label class="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3">
                        <input type="checkbox" name="faculty_ids[]" value="{{ $member->id }}" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500" @checked(in_array($member->id, old('faculty_ids', $course->faculty->pluck('id')->all())))>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">{{ $member->user->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $member->title ?: 'Faculty' }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('faculty_ids')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.courses.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Save Course</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
