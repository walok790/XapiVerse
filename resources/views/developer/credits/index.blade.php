@extends('layouts.app')

@section('title', 'Credits & Billing')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Credits & Billing</h1>
        <p class="mt-1 text-gray-500">Manage your credit balance and purchase additional credits.</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-center space-x-2 mb-2">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-semibold text-red-800">Please fix the following errors:</p>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Current Balance Card --}}
    <div class="relative overflow-hidden rounded-2xl p-8 bg-gradient-to-br from-brand-600 via-purple-600 to-indigo-700 shadow-2xl shadow-brand-500/30 mb-8 animate-fade-in-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-purple-200">Total Credit Balance</p>
            </div>
            <p class="text-4xl lg:text-5xl font-bold text-white font-jakarta"
               x-data="{ count: 0, target: {{ $totalBalance ?? 0 }} }"
               x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)"
               x-text="count.toLocaleString() + ' credits'">0 credits</p>
            <p class="text-sm text-purple-200 mt-2">Available across all your API keys</p>
        </div>
    </div>

    {{-- Credit Packages --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 font-jakarta mb-4">Purchase Credits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($packages as $package)
            <div class="relative bg-white rounded-2xl border {{ $package->is_popular ? 'border-brand-300 shadow-xl shadow-brand-100/50' : 'border-gray-100 shadow-lg shadow-gray-100/50' }} p-6 transform hover:-translate-y-1 transition-all duration-200 group">
                {{-- Popular Badge --}}
                @if($package->is_popular)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-brand-600 to-purple-600 text-white shadow-lg shadow-brand-500/30">
                        Most Popular
                    </span>
                </div>
                @endif

                <div class="text-center mb-4 {{ $package->is_popular ? 'pt-2' : '' }}">
                    <h3 class="text-lg font-bold text-gray-900 font-jakarta">{{ $package->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $package->description }}</p>
                </div>

                <div class="text-center mb-4">
                    <div class="flex items-baseline justify-center">
                        <span class="text-3xl font-bold text-gray-900">${{ number_format($package->price, 2) }}</span>
                    </div>
                    <p class="text-sm font-medium text-brand-600 mt-1">{{ number_format($package->credits) }} credits</p>
                </div>

                <form action="{{ route('developer.credits.purchase') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <button type="submit" class="w-full px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 transform hover:-translate-y-0.5 {{ $package->is_popular ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gradient-to-r hover:from-brand-600 hover:to-purple-600 hover:text-white hover:shadow-lg hover:shadow-brand-500/30' }}">
                        Purchase
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-jakarta">Transaction History</h2>
            <p class="text-xs text-gray-500 mt-0.5">Your credit purchase and usage history</p>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Credits</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-brand-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-xs font-mono text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ Str::limit($transaction->transaction_id, 12) }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->type === 'purchase')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Purchase
                            </span>
                            @elseif($transaction->type === 'usage')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Usage
                            </span>
                            @elseif($transaction->type === 'refund')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                Refund
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-50 text-gray-700 border border-gray-200">
                                {{ ucfirst($transaction->type) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold {{ $transaction->type === 'purchase' || $transaction->type === 'refund' ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $transaction->type === 'purchase' || $transaction->type === 'refund' ? '+' : '-' }}{{ number_format($transaction->credits) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($transaction->amount)
                            ${{ number_format($transaction->amount, 2) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Completed
                            </span>
                            @elseif($transaction->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                Pending
                            </span>
                            @elseif($transaction->status === 'failed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Failed
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-50 text-gray-700 border border-gray-200">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 max-w-[150px] truncate">
                            {{ $transaction->notes ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $transaction->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
        @else
        {{-- Empty State --}}
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 font-jakarta mb-2">No Transactions Yet</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">Purchase your first credit package to get started with our API services.</p>
        </div>
        @endif
    </div>
</div>
@endsection
