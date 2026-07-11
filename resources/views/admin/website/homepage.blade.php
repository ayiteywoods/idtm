@extends('layouts.portal')

@section('title', 'Edit Homepage Hero')
@section('breadcrumb', 'Administration / Website / Homepage Hero')

@section('content')
<x-portal.page-header title="Edit Homepage Hero" description="Update the homepage headline, supporting copy, stats label, and hero slider images. Site name and tagline are managed in Site Settings.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.settings.index') }}">Site Settings</x-portal.button>
        <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Back to CMS</x-portal.button>
        <x-portal.button variant="secondary" href="{{ route('home') }}" target="_blank">
            <x-portal.icon name="external" class="h-4 w-4" /> Preview Site
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<form method="POST" action="{{ route('admin.website.homepage.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <x-portal.card title="Hero Content">
        <div class="mb-6 rounded-lg border border-slate-100 bg-slate-50/80 px-4 py-3 text-sm text-slate-600">
            <span class="font-semibold text-slate-700">{{ $settings['siteName'] }}</span>
            <span class="text-slate-400">·</span>
            {{ $settings['tagline'] }}
            <a href="{{ route('admin.settings.index') }}" class="ml-2 font-medium text-brand-600 hover:text-brand-700">Edit branding</a>
        </div>

        <div>
            <label for="hero_title" class="block text-sm font-semibold text-slate-700">Hero Heading</label>
            <input id="hero_title" name="hero_title" type="text" value="{{ old('hero_title', $settings['heroTitle']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
            @error('hero_title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6">
            <label for="hero_subtitle" class="block text-sm font-semibold text-slate-700">Hero Subtitle</label>
            <textarea id="hero_subtitle" name="hero_subtitle" rows="3" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm leading-6 focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>{{ old('hero_subtitle', $settings['heroSubtitle']) }}</textarea>
            @error('hero_subtitle')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6">
            <label for="years_of_excellence" class="block text-sm font-semibold text-slate-700">Years of Excellence Stat</label>
            <input id="years_of_excellence" name="years_of_excellence" type="text" value="{{ old('years_of_excellence', $settings['years']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
            @error('years_of_excellence')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </x-portal.card>

    <x-portal.card title="Hero Slider Images">
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ([1, 2, 3] as $index)
                @php $slide = $settings['slides'][$index - 1]; @endphp
                <div class="rounded-lg border border-slate-200 p-4">
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" class="h-40 w-full rounded-lg object-cover">

                    <div class="mt-4">
                        <label for="hero_slide_{{ $index }}_alt" class="block text-sm font-semibold text-slate-700">Slide {{ $index }} Alt Text</label>
                        <input id="hero_slide_{{ $index }}_alt" name="hero_slide_{{ $index }}_alt" type="text" value="{{ old("hero_slide_{$index}_alt", $slide['alt']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                        @error("hero_slide_{$index}_alt")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mt-4">
                        <label for="hero_slide_{{ $index }}_image" class="block text-sm font-semibold text-slate-700">Replace Slide {{ $index }} Image</label>
                        <input id="hero_slide_{{ $index }}_image" name="hero_slide_{{ $index }}_image" type="file" accept="image/*" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900 hover:file:bg-gold-200">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to keep the current image.</p>
                        @error("hero_slide_{$index}_image")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            @endforeach
        </div>
    </x-portal.card>

    <div class="flex flex-wrap justify-end gap-3">
        <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Cancel</x-portal.button>
        <x-portal.button type="submit">Save Homepage Changes</x-portal.button>
    </div>
</form>
@endsection
