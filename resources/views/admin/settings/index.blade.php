@extends('layouts.portal')

@section('title', 'Site Settings')
@section('breadcrumb', 'Administration / Site Settings')

@section('content')
<x-portal.page-header title="Site Settings" description="Manage branding, contact details, and quick links to homepage and page content.">
    <x-slot:actions>
        <x-portal.button variant="secondary" href="{{ route('home') }}" target="_blank">
            <x-portal.icon name="external" class="h-4 w-4" /> Preview Site
        </x-portal.button>
        <x-portal.button href="{{ route('admin.website.index') }}">Website CMS</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">
        {{ session('status') }}
    </div>
@endif

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <a href="{{ route('admin.website.homepage.edit') }}" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-gold-300 hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold-100 text-brand-900">
            <x-portal.icon name="home" class="h-5 w-5" />
        </div>
        <h3 class="mt-4 font-semibold text-slate-900 group-hover:text-brand-700">Homepage Hero</h3>
        <p class="mt-1 text-sm text-slate-500">Headline, subtitle, stats label, and slider images.</p>
    </a>

    <a href="{{ route('admin.website.index') }}" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-gold-300 hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
            <x-portal.icon name="globe" class="h-5 w-5" />
        </div>
        <h3 class="mt-4 font-semibold text-slate-900 group-hover:text-brand-700">Website Pages</h3>
        <p class="mt-1 text-sm text-slate-500">Edit page content, images, and blocks across the public site.</p>
    </a>

    <a href="{{ route('home') }}" target="_blank" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-gold-300 hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
            <x-portal.icon name="eye" class="h-5 w-5" />
        </div>
        <h3 class="mt-4 font-semibold text-slate-900 group-hover:text-brand-700">Live Preview</h3>
        <p class="mt-1 text-sm text-slate-500">Open the public website in a new tab to review changes.</p>
    </a>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <x-portal.card title="Branding">
        <p class="mb-6 text-sm text-slate-500">Shown in the header logo, footer, and browser title across the public website.</p>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="site_name" class="block text-sm font-semibold text-slate-700">Site Name</label>
                <input id="site_name" name="site_name" type="text" value="{{ old('site_name', $settings['site_name']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('site_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="tagline" class="block text-sm font-semibold text-slate-700">Tagline</label>
                <input id="tagline" name="tagline" type="text" value="{{ old('tagline', $settings['tagline']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('tagline')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 rounded-lg border border-slate-100 bg-slate-50/80 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Preview</p>
            <p class="mt-2 text-lg font-bold text-brand-900">{{ old('site_name', $settings['site_name']) }}</p>
            <p class="text-sm text-slate-600">{{ old('tagline', $settings['tagline']) }}</p>
        </div>
    </x-portal.card>

    <x-portal.card title="Footer & Contact" id="contact">
        <p class="mb-6 text-sm text-slate-500">Displayed in the website footer and on the contact page. Separate multiple phone numbers with a semicolon.</p>

        <div>
            <label for="footer_intro" class="block text-sm font-semibold text-slate-700">Footer Intro Text</label>
            <textarea id="footer_intro" name="footer_intro" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm leading-6 focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>{{ old('footer_intro', $settings['footer_intro']) }}</textarea>
            @error('footer_intro')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div>
                <label for="contact_email" class="block text-sm font-semibold text-slate-700">Contact Email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('contact_email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_phone" class="block text-sm font-semibold text-slate-700">Contact Phone</label>
                <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                <p class="mt-1 text-xs text-slate-500">Example: +233 208 824 029; +233 555 371 028</p>
                @error('contact_phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="contact_address" class="block text-sm font-semibold text-slate-700">Contact Address</label>
            <input id="contact_address" name="contact_address" type="text" value="{{ old('contact_address', $settings['contact_address']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
            @error('contact_address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </x-portal.card>

    <div class="flex flex-wrap justify-end gap-3">
        <x-portal.button variant="ghost" href="{{ route('admin.dashboard') }}">Cancel</x-portal.button>
        <x-portal.button type="submit">Save Site Settings</x-portal.button>
    </div>
</form>
@endsection
