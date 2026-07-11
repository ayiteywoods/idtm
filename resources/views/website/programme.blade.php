@extends('layouts.website')

@section('title', $programme->name.' — '.$siteName)

@section('content')
<x-website.page-hero
    eyebrow="Programme"
    :title="$programme->name"
    :subtitle="$programme->description"
    :breadcrumbs="$breadcrumbs"
/>

<section class="website-page-body">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="website-page-layout has-sidebar">
            <x-website.page-sidebar :items="$programmeSidebar" />

            <div class="website-page-main">
                @if ($programme->specializations->isNotEmpty())
                    <div class="website-content-block">
                        <h2 class="website-content-heading">Specializations</h2>
                        <div class="website-card-grid">
                            @foreach ($programme->specializations as $spec)
                                <div class="website-info-card">
                                    <h3 class="website-info-card__title">{{ $spec->name }}</h3>
                                    <p class="website-info-card__text">Specialized MBA pathway within the {{ $programme->name }} programme.</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($programme->courses->isNotEmpty())
                    <div class="website-content-block">
                        <h2 class="website-content-heading">Core Courses</h2>
                        <div class="website-table-wrap">
                            <table class="website-table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Course Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programme->courses as $course)
                                        <tr>
                                            <td class="font-medium text-brand-800">{{ $course->code }}</td>
                                            <td>{{ $course->title }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="website-content-block">
                    <div class="website-cta-card">
                        <h2 class="website-cta-card__title">Programme Fees</h2>
                        <p class="website-cta-card__text">
                            Total tuition: <strong class="text-gold-600">GHS {{ number_format($programme->total_fees, 2) }}</strong>.
                            Flexible installment plans available through the student portal.
                        </p>
                        <div class="website-cta-card__actions">
                            <a href="{{ route('pages.show', 'how-to-apply') }}" class="website-btn website-btn--primary">Apply Now</a>
                            <a href="{{ route('contact') }}" class="website-btn website-btn--secondary">Ask Admissions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
