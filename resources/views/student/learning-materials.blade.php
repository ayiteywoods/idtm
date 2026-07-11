@extends('layouts.portal')

@section('title', 'Learning Materials')
@section('breadcrumb', 'Student Portal / Academics')

@section('content')
<x-portal.page-header title="Learning Materials" :description="$profile->programme?->name.' — My Learning Resources'" />

<div class="space-y-4">
    @forelse ($registrations as $registration)
        <details class="group overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-slate-200/80 open:ring-brand-200">
            <summary class="flex cursor-pointer items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-800 text-gold-400">
                        <x-portal.icon name="book" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $registration->course->title }}</p>
                        <p class="text-sm text-slate-500">{{ $registration->course->code }} · {{ $registration->course->learningMaterials->count() }} resources</p>
                    </div>
                </div>
                <svg class="h-5 w-5 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </summary>
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800 ring-1 ring-emerald-100">
                    Tip: Click any link below to access the learning material.
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @forelse ($registration->course->learningMaterials as $material)
                        <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-3">
                            <div class="flex items-center gap-3">
                                <x-portal.icon name="folder" class="h-5 w-5 text-brand-500" />
                                <div>
                                    <p class="font-medium text-slate-900">{{ $material->title }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst($material->type) }}</p>
                                </div>
                            </div>
                            @if ($material->file_path)
                                <a href="{{ route('student.materials.download', $material) }}" class="rounded-lg bg-gold-500 px-3 py-1.5 text-xs font-semibold text-brand-900 hover:bg-gold-400">Download</a>
                            @elseif ($material->url)
                                <a href="{{ $material->url }}" target="_blank" class="rounded-lg bg-gold-500 px-3 py-1.5 text-xs font-semibold text-brand-900 hover:bg-gold-400">Open</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 sm:col-span-2">No materials uploaded yet for this course.</p>
                    @endforelse
                </div>
            </div>
        </details>
    @empty
        <x-portal.empty-state title="No learning materials available" description="Register and pay for courses to access learning resources." icon="folder">
            <x-slot:action><x-portal.button href="{{ route('student.registration') }}">Go to Registration</x-portal.button></x-slot:action>
        </x-portal.empty-state>
    @endforelse
</div>
@endsection
