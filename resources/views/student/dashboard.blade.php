@extends('layouts.portal')

@section('title', 'Dashboard')
@section('breadcrumb', 'Student Portal')

@section('content')
<div class="mb-6 overflow-hidden rounded-lg bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 p-6 text-white shadow-lg shadow-brand-900/20 sm:p-8">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-white/15 text-2xl font-bold backdrop-blur">
                {{ strtoupper(substr($profile->first_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gold-300">Welcome back</p>
                <h1 class="text-2xl font-bold">{{ $profile->fullName() }}</h1>
                <p class="mt-1 text-sm text-brand-100">{{ $profile->index_number }} · {{ $profile->cohort?->name }}</p>
            </div>
        </div>
        @if ($profile->firstSpecialization)
            <div class="rounded-lg border border-gold-400/30 bg-gold-500/10 px-4 py-3 backdrop-blur sm:max-w-xs">
                <p class="text-xs font-medium uppercase tracking-wide text-gold-300">{{ $profile->programme?->name }}</p>
                <p class="mt-1 text-sm">{{ $profile->firstSpecialization->name }}</p>
                @if ($profile->secondSpecialization)
                    <p class="text-sm text-brand-100">{{ $profile->secondSpecialization->name }}</p>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-portal.stat-card label="Registered Courses" :value="$stats['registered_courses']" icon="clipboard" color="blue" :href="route('student.registration')" />
    <x-portal.stat-card label="Paid Courses" :value="$stats['paid_courses']" icon="book" color="emerald" :href="route('student.learning-materials')" />
    <x-portal.stat-card label="Outstanding (GHS)" :value="$stats['outstanding']" icon="wallet" color="amber" :href="route('student.wallet')" />
    <x-portal.stat-card label="Pending Requests" :value="$stats['pending_requests']" icon="swap" color="rose" :href="route('student.change-requests')" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Quick Access</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                ['title' => 'Course Registration', 'desc' => 'Register for outstanding modules in your programme.', 'route' => route('student.registration'), 'icon' => 'clipboard', 'iconClass' => 'bg-brand-50 text-brand-600 ring-brand-100'],
                ['title' => 'Learning Materials', 'desc' => 'Access resources for your registered courses.', 'route' => route('student.learning-materials'), 'icon' => 'folder', 'iconClass' => 'bg-emerald-50 text-emerald-600 ring-emerald-100'],
                ['title' => 'My Wallet', 'desc' => 'View fees, payment plan, and transactions.', 'route' => route('student.wallet'), 'icon' => 'wallet', 'iconClass' => 'bg-amber-50 text-amber-600 ring-amber-100'],
                ['title' => 'Online Library', 'desc' => 'Browse academic books and research resources.', 'route' => route('student.library'), 'icon' => 'library', 'iconClass' => 'bg-blue-50 text-blue-600 ring-blue-100'],
                ['title' => 'Help Desk', 'desc' => 'FAQs, policies, and support contacts.', 'route' => route('student.help-desk'), 'icon' => 'help', 'iconClass' => 'bg-violet-50 text-violet-600 ring-violet-100'],
                ['title' => 'Change Requests', 'desc' => 'Submit course registration corrections.', 'route' => route('student.change-requests'), 'icon' => 'swap', 'iconClass' => 'bg-rose-50 text-rose-600 ring-rose-100'],
            ] as $card)
                <a href="{{ $card['route'] }}" class="group flex gap-4 rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-brand-200">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg ring-1 {{ $card['iconClass'] }}">
                        <x-portal.icon :name="$card['icon']" class="h-5 w-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-gold-700">{{ $card['title'] }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $card['desc'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-6">
        @if ($upcomingInstallment)
            <x-portal.card title="Upcoming Payment">
                <div class="rounded-lg bg-amber-50 p-4 ring-1 ring-amber-100">
                    <p class="text-sm font-medium text-amber-900">{{ $upcomingInstallment->period_label }}</p>
                    <p class="mt-2 text-2xl font-bold text-amber-800">GHS {{ number_format($upcomingInstallment->amount, 2) }}</p>
                    <p class="mt-1 text-sm text-amber-700">Due {{ $upcomingInstallment->due_date->format('d M Y') }}</p>
                </div>
                <x-portal.button href="{{ route('student.wallet') }}" class="mt-4 w-full">View Payment Plan</x-portal.button>
            </x-portal.card>
        @endif

        <x-portal.card title="Academic Progress" :padding="true">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Programme</dt><dd class="font-medium text-slate-900 text-right">{{ $profile->programme?->name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Cohort</dt><dd class="font-medium text-slate-900">{{ $profile->cohort?->name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Courses</dt><dd class="font-medium text-slate-900">{{ $stats['registered_courses'] }} registered</dd></div>
            </dl>
            <x-portal.button variant="secondary" href="{{ route('student.profile') }}" class="mt-4 w-full">View Full Profile</x-portal.button>
        </x-portal.card>
    </div>
</div>
@endsection
