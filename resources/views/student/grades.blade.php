@extends('layouts.portal')

@section('title', 'My Grades')
@section('breadcrumb', 'Student Portal / Academics')

@section('content')
<x-portal.page-header title="My Grades" description="View your assessment scores and exam results." />

<x-portal.card :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Course</th>
                    <th class="px-6 py-3.5">Assessment</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Score</th>
                    <th class="px-6 py-3.5">Resit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($grades as $grade)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4"><x-portal.badge variant="muted">{{ $grade->course->code }}</x-portal.badge></td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $grade->title }}</td>
                        <td class="px-6 py-4"><x-portal.badge variant="info">{{ ucfirst($grade->type) }}</x-portal.badge></td>
                        <td class="px-6 py-4"><span class="font-semibold">{{ $grade->score }}</span><span class="text-slate-400">/{{ $grade->max_score }}</span></td>
                        <td class="px-6 py-4"><x-portal.badge :variant="$grade->is_resit ? 'warning' : 'muted'">{{ $grade->is_resit ? 'Yes' : 'No' }}</x-portal.badge></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12"><x-portal.empty-state title="No grades yet" description="Your assessment scores will appear here once faculty record them." icon="grade" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($grades->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $grades->links() }}</div>@endif
</x-portal.card>
@endsection
