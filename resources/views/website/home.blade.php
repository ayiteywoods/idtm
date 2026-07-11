@extends('layouts.website')

@section('title', $siteName)

@section('content')
<x-website.hero-slider
    :hero-slides="$heroSlides"
    :tagline="$tagline"
    :hero-title="$heroTitle"
    :hero-subtitle="$heroSubtitle"
/>

{{-- Welcome --}}
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-gold-600">Welcome to IDTM</p>
            <h2 class="website-section-heading mt-2">Ghana's hub for development & technology excellence</h2>
            <p class="mt-4 text-slate-600 leading-relaxed">
                The Institute of Development & Technology Management prepares professionals across Ghana and West Africa
                for leadership in development policy, technology management, and innovation through rigorous academic programmes
                and a vibrant learning community.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('pages.show', 'about') }}" class="rounded-lg bg-brand-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Learn About Us</a>
                <a href="{{ route('contact') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Contact Admissions</a>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                ['title' => 'Accredited Programmes', 'text' => 'Industry-aligned MBA and executive programmes designed for working professionals in Ghana.'],
                ['title' => 'Expert Faculty', 'text' => 'Learn from experienced lecturers and practitioners with deep knowledge of African markets.'],
                ['title' => 'Flexible Learning', 'text' => 'Blended learning options that fit the schedules of busy professionals across Ghana.'],
                ['title' => 'Career Growth', 'text' => 'Graduates lead organisations, launch ventures, and drive change across the region.'],
            ] as $feature)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                    <h3 class="website-subheading">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<x-website.news-mosaic :cells="$newsMosaic" />

<x-website.campus-programmes :programmes="$featuredProgrammes" />

<x-website.upcoming-events :events="$upcomingEvents" />

<x-website.join-cta />

<x-website.feature-gallery :panels="$featureGallery" />

{{-- Stats --}}
<section class="bg-brand-900 py-12 text-white">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 sm:px-6 md:grid-cols-4 lg:px-8">
        @foreach ([
            ['label' => 'Active Students', 'value' => $stats['students']],
            ['label' => 'Programmes', 'value' => $stats['programmes']],
            ['label' => 'Faculty Members', 'value' => $stats['faculty']],
            ['label' => 'Years of Excellence', 'value' => $stats['years']],
        ] as $stat)
            <div class="text-center">
                <p class="text-3xl font-bold text-gold-400">{{ $stat['value'] }}</p>
                <p class="mt-1 text-sm text-brand-100">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Admissions steps --}}
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-gold-600">Admissions</p>
            <h2 class="website-section-heading mt-2">Start your application journey</h2>
            <p class="mx-auto mt-3 max-w-2xl text-slate-600">Join students from across Ghana and beyond. Our admissions team guides you through every step.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-4">
            @foreach ([
                ['step' => '01', 'title' => 'Choose a Programme', 'text' => 'Explore our MBA and specializations to find your fit.', 'route' => route('pages.show', 'programmes')],
                ['step' => '02', 'title' => 'Check Requirements', 'text' => 'Review entry requirements and required documents.', 'route' => route('pages.show', 'admission-requirements')],
                ['step' => '03', 'title' => 'Submit Application', 'text' => 'Complete the online application and upload documents.', 'route' => route('pages.show', 'how-to-apply')],
                ['step' => '04', 'title' => 'Enrol & Begin', 'text' => 'Receive your offer, pay fees, and access the student portal.', 'route' => route('login')],
            ] as $step)
                <a href="{{ $step['route'] }}" class="group rounded-lg border border-slate-200 p-6 transition hover:border-gold-300 hover:shadow-md">
                    <p class="text-2xl font-bold text-gold-500">{{ $step['step'] }}</p>
                    <h3 class="website-subheading mt-3 group-hover:text-brand-900">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $step['text'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-gold-50 py-16">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="website-section-heading">Ready to take the next step?</h2>
        <p class="mt-3 text-slate-600">Speak with our admissions team or apply today to join the next cohort at IDTM.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('pages.show', 'how-to-apply') }}" class="rounded-lg bg-gold-500 px-6 py-3 text-sm font-semibold text-brand-900 hover:bg-gold-400">Apply Now</a>
            <a href="{{ route('contact') }}" class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-brand-800 ring-1 ring-slate-200 hover:bg-slate-50">Contact Us</a>
        </div>
    </div>
</section>
@endsection
