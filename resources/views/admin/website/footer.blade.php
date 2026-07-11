@extends('layouts.portal')

@section('title', 'Edit Footer')
@section('breadcrumb', 'Administration / Website / Footer')

@section('content')
<x-portal.page-header title="Edit Footer" description="Update the public website footer intro and contact information.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Back to CMS</x-portal.button>
        <x-portal.button variant="secondary" href="{{ route('home') }}" target="_blank">
            <x-portal.icon name="external" class="h-4 w-4" /> Preview Site
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

<x-portal.card>
    <form method="POST" action="{{ route('admin.website.footer.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="footer_intro" class="block text-sm font-semibold text-slate-700">Footer Intro Text</label>
            <textarea id="footer_intro" name="footer_intro" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm leading-6 focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>{{ old('footer_intro', $settings['footer_intro']) }}</textarea>
            @error('footer_intro')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="contact_email" class="block text-sm font-semibold text-slate-700">Contact Email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('contact_email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_phone" class="block text-sm font-semibold text-slate-700">Contact Phone</label>
                <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
                @error('contact_phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="contact_address" class="block text-sm font-semibold text-slate-700">Contact Address</label>
            <input id="contact_address" name="contact_address" type="text" value="{{ old('contact_address', $settings['contact_address']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200" required>
            @error('contact_address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
            <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Cancel</x-portal.button>
            <x-portal.button type="submit">Save Footer</x-portal.button>
        </div>
    </form>
</x-portal.card>
@endsection
