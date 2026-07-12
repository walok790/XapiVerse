@extends('layouts.app')

@section('title', 'Developer Dashboard')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8">
        <h1 class="font-jakarta text-2xl font-bold text-gray-900">Developer Dashboard</h1>
        <p class="text-gray-500 mt-1">Manage your API keys and monitor usage.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format(auth()->user()->getTotalCreditsBalance()) }}</p>
            <p class="text-sm text-gray-500">Credits Remaining</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->getActiveApiKeysCount() }}</p>
            <p class="text-sm text-gray-500">Active API Keys</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format(auth()->user()->total_credits_used) }}</p>
            <p class="text-sm text-gray-500">Total Requests Made</p>
        </div>
    </div>

    <!-- API Keys List -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-jakarta text-lg font-semibold text-gray-900">Your API Keys</h3>
            <a href="{{ route('developer.api-keys.index') }}" class="text-sm text-brand-600 font-medium hover:text-brand-700">Manage All</a>
        </div>

        @forelse(auth()->user()->apiKeys()->where('is_active', true)->limit(5)->get() as $key)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $key->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $key->getMaskedKey() }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">{{ number_format($key->credits_balance) }}</p>
                    <p class="text-xs text-gray-500">credits</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-4">No API keys yet. Create one to get started!</p>
        @endforelse
    </div>
</div>
@endsection
