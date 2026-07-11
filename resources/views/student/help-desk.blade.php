@extends('layouts.portal')

@section('title', 'Help Desk')
@section('breadcrumb', 'Student Portal / Support')

@section('content')
<x-portal.page-header title="Help Desk" description="FAQs, fees, policies & support contacts." />

<div class="mb-6 flex flex-wrap gap-2">
    <x-portal.badge variant="default">All</x-portal.badge>
    @foreach ($categories as $category)
        <x-portal.badge variant="muted">{{ $category }}</x-portal.badge>
    @endforeach
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-3">
        @forelse ($faqs as $faq)
            <details class="group rounded-lg bg-white shadow-sm ring-1 ring-slate-200/80">
                <summary class="cursor-pointer px-5 py-4 font-medium text-slate-900 transition group-open:text-brand-700">{{ $faq->question }}</summary>
                <div class="border-t border-slate-100 px-5 py-4 text-sm leading-relaxed text-slate-600">{{ $faq->answer }}</div>
            </details>
        @empty
            <x-portal.empty-state title="No FAQs available" description="Check back later for help articles." icon="help" />
        @endforelse
    </div>

    <x-portal.card title="Your Admission Advisors">
        <x-portal.empty-state title="No advisors assigned yet" description="Your admission advisor contacts will appear here once assigned." icon="user" />
    </x-portal.card>
</div>
@endsection
