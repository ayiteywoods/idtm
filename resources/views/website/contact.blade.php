@extends('layouts.website')

@section('title', 'Contact Us — '.$siteName)

@section('content')
<x-website.page-hero
    eyebrow="Get in Touch"
    title="Contact IDTM"
    subtitle="Reach the Office of the Registrar in Cape Coast for admissions and general enquiries."
    :breadcrumbs="[
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Contact'],
    ]"
/>

<section class="website-page-body">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-2">
                <div class="website-info-card">
                    <h2 class="website-info-card__title">Visit Us</h2>
                    <p class="website-info-card__text mt-3 flex items-start gap-3">
                        <x-website.icon name="contact" class="mt-0.5 shrink-0 text-gold-600" />
                        <span>{{ $contactAddress }}</span>
                    </p>
                </div>
                <div class="website-info-card">
                    <h2 class="website-info-card__title">Contact Details</h2>
                    <div class="mt-3 space-y-3 text-sm text-slate-600">
                        <p class="flex items-center gap-3">
                            <x-website.icon name="mail" class="shrink-0 text-gold-600" />
                            <a href="mailto:{{ $contactEmail }}" class="hover:text-brand-700">{{ $contactEmail }}</a>
                        </p>
                        @foreach (array_filter(array_map('trim', explode(';', $contactPhone))) as $phone)
                            <p class="flex items-center gap-3">
                                <x-website.icon name="contact" class="shrink-0 text-gold-600" />
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="hover:text-brand-700">{{ $phone }}</a>
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="website-split-card">
                    <h3 class="website-split-card__title">Office Hours</h3>
                    <p class="website-split-card__text">Monday – Friday: 8:00 AM – 5:00 PM (GMT)</p>
                    <p class="website-split-card__text mt-1">Saturday: 9:00 AM – 1:00 PM</p>
                </div>
            </div>

            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('contact.store') }}" class="website-info-card !p-6 sm:!p-8">
                    @csrf
                    <h2 class="website-content-heading !mb-1">Send us a message</h2>
                    <p class="text-sm text-slate-500">Fill out the form and our team will get back to you.</p>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">Phone (optional)</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="subject" class="mb-1.5 block text-sm font-medium text-slate-700">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                            @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="mb-1.5 block text-sm font-medium text-slate-700">Message</label>
                            <textarea name="message" id="message" rows="5" required
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <button type="submit" class="website-btn website-btn--primary mt-6">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
