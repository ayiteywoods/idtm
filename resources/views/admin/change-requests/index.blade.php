@extends('layouts.portal')

@section('title', 'Change Requests')
@section('breadcrumb', 'Administration / Change Requests')

@section('content')
<x-portal.page-header title="Change Requests" description="Review and process student course registration corrections.">
    <x-slot:actions>
        <x-portal.badge variant="warning">{{ $stats['pending'] }} pending review</x-portal.badge>
    </x-slot:actions>
</x-portal.page-header>

<div class="mb-6 grid gap-4 sm:grid-cols-4">
    <x-portal.stat-card label="Total Requests" :value="$stats['total']" icon="swap" color="brand" />
    <x-portal.stat-card label="Pending" :value="$stats['pending']" icon="bell" color="amber" />
    <x-portal.stat-card label="Approved" :value="$stats['approved']" icon="chart" color="emerald" />
    <x-portal.stat-card label="Rejected" :value="$stats['rejected']" icon="swap" color="rose" />
</div>

<x-portal.card :padding="false">
    <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
        <p class="text-sm text-slate-500">Showing {{ $requests->firstItem() ?? 0 }}-{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} requests</p>
        <x-portal.per-page-select id="change-requests" :per-page="$perPage" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="student" label="Student" /></th>
                    <th class="px-6 py-3.5">Course</th>
                    <th class="px-6 py-3.5">Description</th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="status" label="Status" /></th>
                    <th class="px-6 py-3.5"><x-portal.sort-link field="created" label="Submitted" /></th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($requests as $request)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $request->student->fullName() }}</p>
                            <p class="font-mono text-xs text-slate-500">{{ $request->student->index_number }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $request->registration->course->code }} - {{ $request->registration->course->title }}</td>
                        <td class="max-w-sm px-6 py-4 text-slate-600">{{ str($request->description)->limit(90) }}</td>
                        <td class="px-6 py-4">
                            <x-portal.badge :variant="match($request->status) { 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', default => 'muted' }">
                                {{ ucfirst($request->status) }}
                            </x-portal.badge>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $request->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700" data-request-drawer-open="request-drawer-{{ $request->id }}" aria-label="View request">
                                    <x-portal.icon name="eye" class="h-4 w-4" />
                                </button>
                                @if ($request->status === 'pending')
                                    <form method="POST" action="{{ route('admin.change-requests.review', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.change-requests.review', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100">Reject</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.change-requests.destroy', $request) }}" onsubmit="return confirm('Delete this change request?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-rose-50 hover:text-rose-700" aria-label="Delete request">
                                        <x-portal.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12"><x-portal.empty-state title="No change requests" description="Student correction requests will appear here for review." icon="swap" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($requests->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">{{ $requests->links() }}</div>
    @endif
</x-portal.card>

@foreach ($requests as $request)
    <div id="request-drawer-{{ $request->id }}" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <button type="button" class="absolute inset-0 bg-slate-900/50" data-request-drawer-close aria-label="Close request drawer"></button>

        <aside class="absolute right-0 top-0 flex h-full w-full max-w-xl flex-col bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Change Request</h2>
                    <p class="text-sm text-slate-500">{{ $request->student->fullName() }} · {{ $request->student->index_number }}</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-request-drawer-close aria-label="Close drawer">
                    <x-portal.icon name="close" class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Course</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $request->registration->course->code }} - {{ $request->registration->course->title }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ ucfirst($request->status) }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Submitted</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $request->created_at->format('M j, Y g:ia') }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Reviewed By</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $request->reviewer?->name ?? '—' }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Student Description</p>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $request->description }}</p>
                </div>

                @if ($request->admin_notes)
                    <div class="mt-4 rounded-lg border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Admin Notes</p>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $request->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 px-6 py-4">
                @if ($request->status === 'pending')
                    <form method="POST" action="{{ route('admin.change-requests.review', $request) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <x-portal.button variant="secondary" type="submit">Reject</x-portal.button>
                    </form>
                    <form method="POST" action="{{ route('admin.change-requests.review', $request) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <x-portal.button type="submit">Approve</x-portal.button>
                    </form>
                @endif
                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100" data-request-drawer-close>Close</button>
            </div>
        </aside>
    </div>
@endforeach

<script>
    document.querySelectorAll('[data-request-drawer-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = document.getElementById(trigger.dataset.requestDrawerOpen);
            drawer?.classList.remove('hidden');
            drawer?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('[data-request-drawer-close]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const drawer = trigger.closest('[id^="request-drawer-"]');
            drawer?.classList.add('hidden');
            drawer?.setAttribute('aria-hidden', 'true');
        });
    });
</script>
@endsection
