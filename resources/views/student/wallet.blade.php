@extends('layouts.portal')

@section('title', 'My Wallet')
@section('breadcrumb', 'Student Portal / Finance')

@section('content')
<x-portal.page-header title="My Wallet" description="Fees, deposits, and payment schedule." />

@if ($paymentPlan)
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <x-portal.stat-card label="Total Fees" :value="'GHS '.number_format($paymentPlan->total_fees, 2)" icon="wallet" color="blue" />
        <x-portal.stat-card label="Total Deposited" :value="'GHS '.number_format($paymentPlan->total_deposited, 2)" icon="chart" color="emerald" />
        <x-portal.stat-card label="Outstanding" :value="'GHS '.number_format($paymentPlan->outstanding(), 2)" icon="bell" color="amber" />
    </div>

    <x-portal.card title="Make a Deposit" class="mb-6">
        <form method="POST" action="{{ route('student.wallet.deposit') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="min-w-[12rem] flex-1">
                <label for="amount" class="block text-sm font-semibold text-slate-700">Amount (GHS)</label>
                <input id="amount" name="amount" type="number" step="0.01" min="1" max="{{ $paymentPlan->outstanding() }}" value="{{ old('amount') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                @error('amount')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <x-portal.button type="submit">Record Deposit</x-portal.button>
        </form>
    </x-portal.card>

    <div class="mb-6 rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200/80">
        <div class="mb-2 flex justify-between text-sm"><span class="text-slate-500">Payment progress</span><span class="font-medium text-slate-900">{{ round(($paymentPlan->total_deposited / $paymentPlan->total_fees) * 100) }}%</span></div>
        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-lg bg-gradient-to-r from-gold-400 to-gold-500" style="width: {{ min(100, ($paymentPlan->total_deposited / $paymentPlan->total_fees) * 100) }}%"></div>
        </div>
    </div>

    <x-portal.card title="Monthly Payment Plan ({{ $profile->programme?->name }} — {{ $profile->cohort?->name }})" :padding="false">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Period</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Due Date</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($paymentPlan->installments as $installment)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $installment->period_label }}</td>
                            <td class="px-6 py-4 text-slate-600">GHS {{ number_format($installment->amount, 2) }}</td>
                            <td class="px-6 py-4 {{ $installment->due_date->isPast() && $installment->status !== 'passed' ? 'text-rose-600 font-medium' : 'text-slate-600' }}">
                                {{ $installment->due_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <x-portal.badge :variant="$installment->status === 'passed' ? 'success' : 'warning'">{{ ucfirst($installment->status) }}</x-portal.badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-portal.card>
@else
    <x-portal.empty-state title="No payment plan assigned" description="Contact the finance office to set up your payment plan." icon="wallet">
        <x-slot:action><x-portal.button href="{{ route('student.help-desk') }}">Contact Support</x-portal.button></x-slot:action>
    </x-portal.empty-state>
@endif
@endsection
