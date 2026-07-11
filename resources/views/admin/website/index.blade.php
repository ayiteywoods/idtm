@extends('layouts.portal')

@section('title', 'Website CMS')
@section('breadcrumb', 'Administration / Website')

@section('content')
<x-portal.page-header title="Website Content Management" description="Manage pages with the block editor and homepage hero images. Branding and contact details live in Site Settings.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.settings.index') }}">Site Settings</x-portal.button>
        <x-portal.button variant="secondary" href="{{ route('home') }}" target="_blank">
            <x-portal.icon name="external" class="h-4 w-4" /> Preview Site
        </x-portal.button>
        <x-portal.button href="{{ route('admin.website.pages.create') }}">
            <x-portal.icon name="plus" class="h-4 w-4" /> New Page
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">
        {{ session('status') }}
    </div>
@endif

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <x-portal.stat-card label="Published Pages" :value="$pages->where('is_published', true)->count()" icon="globe" color="emerald" />
    <x-portal.stat-card label="Draft Pages" :value="$pages->where('is_published', false)->count()" icon="folder" color="amber" />
    <x-portal.stat-card label="Published FAQs" :value="$faqCount" icon="help" color="blue" />
</div>

<x-portal.card class="mb-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Homepage Hero</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $homepageSettings['heroTitle'] }}</h2>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">{{ $homepageSettings['heroSubtitle'] }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex -space-x-3">
                @foreach ($homepageSettings['slides'] as $slide)
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" class="h-12 w-12 rounded-lg object-cover ring-2 ring-white">
                @endforeach
            </div>
            <x-portal.button href="{{ route('admin.website.homepage.edit') }}">
                Edit Hero & Images
            </x-portal.button>
        </div>
    </div>
</x-portal.card>

<x-portal.card class="mb-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Site Branding</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $brandingSettings['site_name'] }}</h2>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">{{ $brandingSettings['tagline'] }}</p>
            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">
                <span>{{ $footerSettings['contact_email'] }}</span>
                <span>{{ $footerSettings['contact_phone'] }}</span>
            </div>
        </div>
        <x-portal.button href="{{ route('admin.settings.index') }}">
            Edit Site Settings
        </x-portal.button>
    </div>
</x-portal.card>

<x-portal.card title="Website Pages" class="mb-6" :padding="false">
    <div class="grid md:grid-cols-2">
        @foreach ($pages as $page)
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-4 transition hover:bg-slate-50/80 odd:md:border-r">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                        <x-portal.icon name="globe" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">{{ $page->title }}</p>
                        <p class="text-sm text-slate-500">/pages/{{ $page->slug }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <x-portal.badge :variant="$page->is_published ? 'success' : 'muted'">{{ $page->is_published ? 'Published' : 'Draft' }}</x-portal.badge>
                    <x-portal.button variant="ghost" class="!px-3 !py-1.5 text-xs" href="{{ route('pages.show', $page->slug) }}" target="_blank">View</x-portal.button>
                    <x-portal.button variant="secondary" class="!px-3 !py-1.5 text-xs" href="{{ route('admin.website.pages.edit', $page) }}">Edit</x-portal.button>
                </div>
            </div>
        @endforeach
    </div>
</x-portal.card>
@endsection
