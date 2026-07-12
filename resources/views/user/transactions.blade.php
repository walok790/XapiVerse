@extends('layouts.user')

@section('title', 'Transaction History')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="font-jakarta text-2xl md:text-3xl font-bold text-white">Transaction History</h1>
        <p class="text-sm text-gray-500 mt-1">View all your payment and credit transactions</p>
    </div>

    <!-- Transactions Table -->
    @if($transactions->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block bg-[#141419] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-white font-mono">{{ $transaction->transaction_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->type === 'purchase' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                        {{ $transaction->type === 'refund' ? 'bg-orange-500/10 text-orange-400' : '' }}
                                        {{ $transaction->type === 'subscription' ? 'bg-purple-500/10 text-purple-400' : '' }}
                                        {{ !in_array($transaction->type, ['purchase', 'refund', 'subscription']) ? 'bg-gray-500/10 text-gray-400' : '' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-white font-medium">
                                    {{ $transaction->amount ? '$' . number_format($transaction->amount, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-brand-400 font-medium">{{ $transaction->credits ? '+' . $transaction->credits : '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-400' : '' }}
                                        {{ $transaction->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                        {{ $transaction->status === 'failed' ? 'bg-red-500/10 text-red-400' : '' }}
                                        {{ $transaction->status === 'refunded' ? 'bg-orange-500/10 text-orange-400' : '' }}
                                        {{ !in_array($transaction->status, ['completed', 'pending', 'failed', 'refunded']) ? 'bg-gray-500/10 text-gray-400' : '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5
                                            {{ $transaction->status === 'completed' ? 'bg-green-400' : '' }}
                                            {{ $transaction->status === 'pending' ? 'bg-yellow-400' : '' }}
                                            {{ $transaction->status === 'failed' ? 'bg-red-400' : '' }}
                                            {{ $transaction->status === 'refunded' ? 'bg-orange-400' : '' }}"></span>
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @foreach($transactions as $transaction)
                <div class="bg-[#141419] border border-white/5 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-400' : '' }}
                            {{ $transaction->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                            {{ $transaction->status === 'failed' ? 'bg-red-500/10 text-red-400' : '' }}
                            {{ $transaction->status === 'refunded' ? 'bg-orange-500/10 text-orange-400' : '' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-mono">{{ $transaction->transaction_id }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-white/5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $transaction->type === 'purchase' ? 'bg-blue-500/10 text-blue-400' : '' }}
                            {{ $transaction->type === 'refund' ? 'bg-orange-500/10 text-orange-400' : '' }}
                            {{ $transaction->type === 'subscription' ? 'bg-purple-500/10 text-purple-400' : '' }}
                            {{ !in_array($transaction->type, ['purchase', 'refund', 'subscription']) ? 'bg-gray-500/10 text-gray-400' : '' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                        <div class="text-right">
                            <p class="text-sm text-white font-medium">{{ $transaction->amount ? '$' . number_format($transaction->amount, 2) : '-' }}</p>
                            <p class="text-xs text-brand-400">{{ $transaction->credits ? '+' . $transaction->credits . ' credits' : '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-dark-950 border border-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <h3 class="font-jakarta font-semibold text-white text-lg mb-2">No transactions yet</h3>
            <p class="text-gray-500 text-sm">Your transaction history will appear here once you make a purchase.</p>
        </div>
    @endif
</div>
@endsection
