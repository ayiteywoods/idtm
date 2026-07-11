@extends('layouts.portal')

@section('title', 'Register Courses')
@section('breadcrumb', 'Student Portal / Course Registration')

@section('content')
<x-portal.page-header :title="$specialization->name" description="Select courses to register for this specialization.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('student.registration') }}">Back</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

@if ($courses->isEmpty())
    <x-portal.empty-state title="No courses available" description="You have already registered for all available courses in this specialization, or no courses are open." icon="clipboard" />
@else
    <x-portal.card>
        <form method="POST" action="{{ route('student.registration.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="specialization_id" value="{{ $specialization->id }}">

            <div class="space-y-3">
                @foreach ($courses as $course)
                    <label class="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50">
                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">{{ $course->code }} — {{ $course->title }}</span>
                            <span class="block text-xs text-slate-500">{{ $course->credits }} credits · {{ $course->is_core ? 'Core' : 'Specialization' }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('course_ids')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror

            <div class="flex justify-end border-t border-slate-100 pt-6">
                <x-portal.button type="submit">Register Selected Courses</x-portal.button>
            </div>
        </form>
    </x-portal.card>
@endif
@endsection
