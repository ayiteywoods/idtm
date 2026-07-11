@extends('layouts.portal')

@section('title', 'Dashboard')
@section('breadcrumb', 'Administration')

@section('content')

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-medium text-gold-700">{{ $greeting['time'] }}, {{ $greeting['name'] }}</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Admin Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $greeting['date'] }} &middot; {{ $greeting['institution'] }}</p>
    </div>
    <div class="flex shrink-0 flex-wrap items-center gap-2">
        <x-portal.button size="sm" variant="secondary" href="{{ route('admin.change-requests.index') }}">Change Requests</x-portal.button>
        <x-portal.button size="sm" variant="secondary" href="{{ route('admin.settings.index') }}">Site Settings</x-portal.button>
        <x-portal.button size="sm" href="{{ route('admin.website.index') }}">Website CMS</x-portal.button>
    </div>
</div>

<x-portal.attention-banner :items="$attention" />

@foreach ($statSections as $section)
    <div class="{{ $loop->first ? '' : 'mt-6' }}">
        <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $section['label'] }}</h2>
        <div class="grid gap-4 sm:grid-cols-2 {{ count($section['stats']) === 4 ? 'xl:grid-cols-4' : 'lg:grid-cols-3' }}">
            @foreach ($section['stats'] as $stat)
                <x-portal.stat-card
                    :label="$stat['label']"
                    :value="$stat['value']"
                    :icon="$stat['icon']"
                    :color="$stat['color']"
                    :href="$stat['href'] ?? null"
                    :hint="$stat['hint'] ?? null"
                    :trend="$stat['trend'] ?? null"
                />
            @endforeach
        </div>
    </div>
@endforeach

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-portal.card title="Recent Students" :padding="false">
            <x-slot:header>
                <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all</a>
            </x-slot:header>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Student</th>
                            <th class="px-6 py-3">Index</th>
                            <th class="px-6 py-3">Programme</th>
                            <th class="px-6 py-3">Cohort</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentStudents as $student)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-3.5">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">{{ strtoupper(substr($student->first_name, 0, 1)) }}</div>
                                        <div>
                                            <p class="font-medium text-slate-900 hover:text-brand-700">{{ $student->fullName() }}</p>
                                            <p class="text-xs text-slate-500">{{ $student->user->email }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-6 py-3.5 font-mono text-xs text-slate-600">{{ $student->index_number }}</td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $student->programme?->name ?? '—' }}</td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $student->cohort?->name ?? '—' }}</td>
                                <td class="px-6 py-3.5 text-right">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No students registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-portal.card>

        <x-portal.card title="Enrollment by Programme">
            <x-slot:header>
                <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">All students</a>
            </x-slot:header>
            <div class="space-y-4">
                @forelse ($programmeBreakdown as $programme)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                            <span class="font-medium text-slate-900">{{ $programme['name'] }}</span>
                            <span class="shrink-0 text-slate-500">{{ $programme['count'] }} students ({{ $programme['percentage'] }}%)</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-brand-600 transition-all" style="width: {{ max($programme['percentage'], $programme['count'] > 0 ? 4 : 0) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No programme enrollment data yet.</p>
                @endforelse
            </div>
        </x-portal.card>

        <x-portal.card title="Recent Activity">
            <div class="divide-y divide-slate-100">
                @forelse ($recentActivity as $activity)
                    <a href="{{ $activity['route'] }}" class="flex items-start gap-3 py-3 transition first:pt-0 last:pb-0 hover:opacity-80">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                            <x-portal.icon :name="$activity['icon']" class="h-4 w-4" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900">{{ $activity['message'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $activity['timeLabel'] }}</p>
                        </div>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500">No recent activity to show.</p>
                @endforelse
            </div>
        </x-portal.card>
    </div>

    <div class="space-y-6">
        <x-portal.card title="Pending Change Requests">
            <x-slot:header>
                <a href="{{ route('admin.change-requests.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all</a>
            </x-slot:header>
            <div class="space-y-3">
                @forelse ($pendingChangeRequests as $request)
                    <a href="{{ route('admin.change-requests.index') }}" class="block rounded-lg border border-amber-100 bg-amber-50/50 p-3 transition hover:border-amber-200 hover:bg-amber-50">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-slate-900">{{ $request->student->fullName() }}</p>
                            <x-portal.badge variant="warning">Pending</x-portal.badge>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-600">{{ $request->registration->course->code }}</p>
                        <p class="mt-2 line-clamp-2 text-xs text-slate-500">{{ $request->description }}</p>
                        <p class="mt-2 text-xs font-medium text-brand-600">Review request &rarr;</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">No pending requests. All clear!</p>
                @endforelse
            </div>
        </x-portal.card>

        <x-portal.card title="Website Snapshot">
            @if ($websiteSnapshot['heroImage'])
                <img src="{{ $websiteSnapshot['heroImage'] }}" alt="Homepage hero preview" class="mb-4 aspect-[16/9] w-full rounded-lg object-cover ring-1 ring-slate-200">
            @endif

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Homepage hero</p>
                <p class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-slate-900">{{ $websiteSnapshot['heroTitle'] }}</p>
                <p class="mt-2 text-xs text-slate-500">{{ $websiteSnapshot['publishedPages'] }} site pages live &middot; {{ $websiteSnapshot['draftPages'] }} drafts</p>
            </div>

            @if ($websiteSnapshot['lastUpdatedPage'])
                <div class="mt-4 border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-500">Last page update</p>
                    <a href="{{ $websiteSnapshot['lastUpdatedPage']['route'] }}" class="mt-1 block line-clamp-2 text-sm font-medium text-slate-900 hover:text-brand-700">
                        {{ $websiteSnapshot['lastUpdatedPage']['title'] }}
                    </a>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $websiteSnapshot['lastUpdatedPage']['ago'] }}</p>
                </div>
            @endif

            <div class="mt-4 grid gap-2 border-t border-slate-100 pt-4">
                <x-portal.button variant="secondary" href="{{ route('admin.settings.index') }}" class="w-full justify-start">
                    <x-portal.icon name="settings" class="h-4 w-4" /> Site Settings
                </x-portal.button>
                <x-portal.button variant="secondary" href="{{ route('admin.website.homepage.edit') }}" class="w-full justify-start">
                    <x-portal.icon name="home" class="h-4 w-4" /> Edit Homepage Hero
                </x-portal.button>
            </div>
        </x-portal.card>

        <x-portal.card title="Portal Insights" :padding="true">
            <dl class="space-y-4 text-sm">
                @if ($portalInsights['lastStudent'])
                    <div>
                        <dt class="text-slate-500">Last student added</dt>
                        <dd class="mt-1">
                            <a href="{{ $portalInsights['lastStudent']['route'] }}" class="font-medium text-slate-900 hover:text-brand-700">{{ $portalInsights['lastStudent']['name'] }}</a>
                            <p class="text-xs text-slate-500">{{ $portalInsights['lastStudent']['ago'] }}</p>
                        </dd>
                    </div>
                @endif
                @if ($portalInsights['lastPageUpdate'])
                    <div>
                        <dt class="text-slate-500">Last content update</dt>
                        <dd class="mt-1">
                            <a href="{{ $portalInsights['lastPageUpdate']['route'] }}" class="font-medium text-slate-900 hover:text-brand-700">{{ $portalInsights['lastPageUpdate']['title'] }}</a>
                            <p class="text-xs text-slate-500">{{ $portalInsights['lastPageUpdate']['ago'] }}</p>
                        </dd>
                    </div>
                @endif
                <div class="flex justify-between border-t border-slate-100 pt-4">
                    <dt class="text-slate-500">Active cohorts</dt>
                    <dd class="font-medium text-slate-900">{{ $portalInsights['activeCohorts'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Courses without faculty</dt>
                    <dd>
                        @if ($portalInsights['coursesWithoutFaculty'] > 0)
                            <a href="{{ route('admin.courses.index') }}" class="font-medium text-amber-700 hover:text-amber-800">{{ $portalInsights['coursesWithoutFaculty'] }}</a>
                        @else
                            <span class="font-medium text-slate-900">0</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </x-portal.card>
    </div>
</div>
@endsection
