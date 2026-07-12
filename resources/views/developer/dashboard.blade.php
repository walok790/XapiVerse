@extends('layouts.app')

@section('title', 'Developer Dashboard')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-gray-500">Monitor your API usage and manage your integrations.</p>
    </div>

    {{-- Stats Cards with Animated Counters --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Credits Balance --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-emerald-500 to-green-700 shadow-xl shadow-green-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['total_credits'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-green-100 mb-1">Credits Balance</p>
                <p class="text-3xl font-bold text-white" x-text="count.toLocaleString()">0</p>
            </div>
        </div>

        {{-- Active API Keys --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-blue-500 to-blue-700 shadow-xl shadow-blue-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['active_keys'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-blue-100 mb-1">Active API Keys</p>
                <p class="text-3xl font-bold text-white" x-text="count.toLocaleString()">0</p>
            </div>
        </div>

        {{-- Requests Today --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-purple-500 to-brand-700 shadow-xl shadow-purple-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['requests_today'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-purple-100 mb-1">Requests Today</p>
                <p class="text-3xl font-bold text-white" x-text="count.toLocaleString()">0</p>
            </div>
        </div>

        {{-- Success Rate --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl shadow-orange-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['success_rate'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-amber-100 mb-1">Success Rate</p>
                <p class="text-3xl font-bold text-white"><span x-text="count">0</span>%</p>
            </div>
        </div>
    </div>

    {{-- Daily Usage Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 font-jakarta">Daily API Usage</h2>
                <p class="text-xs text-gray-500 mt-0.5">Requests over the last 7 days</p>
            </div>
        </div>
        @php
            $maxCount = collect($dailyUsage)->max('count') ?: 1;
        @endphp
        <div class="flex items-end justify-between space-x-2 h-48">
            @foreach($dailyUsage as $day)
            <div class="flex-1 flex flex-col items-center justify-end h-full group">
                <span class="text-xs font-semibold text-gray-700 mb-2 opacity-0 group-hover:opacity-100 transition-opacity">{{ number_format($day['count']) }}</span>
                <div class="w-full rounded-t-lg bg-gradient-to-t from-brand-600 to-purple-400 transition-all duration-300 group-hover:from-brand-700 group-hover:to-purple-500 relative"
                     style="height: {{ $maxCount > 0 ? ($day['count'] / $maxCount) * 100 : 0 }}%; min-height: 4px;">
                </div>
                <span class="text-[10px] text-gray-500 mt-2 font-medium">{{ \Carbon\Carbon::parse($day['date'])->format('D') }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent API Logs --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 font-jakarta">Recent API Logs</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Your latest API activity</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Endpoint</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Response</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-brand-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-900">{{ $log->apiService->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-mono">{{ Str::limit($log->endpoint, 30) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->status === 'success')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                    Success
                                </span>
                                @elseif($log->status === 'failed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                    Failed
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                                    {{ ucfirst($log->status) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">{{ $log->response_time_ms ?? '-' }}ms</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-sm text-gray-500">No API requests yet. Start making calls!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Available Services --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 font-jakarta">Available Services</h2>
                <p class="text-xs text-gray-500 mt-0.5">APIs you can integrate</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse($services as $service)
                <a href="{{ route('developer.docs.show', $service->slug) }}" class="block p-4 rounded-xl bg-gray-50 hover:bg-gradient-to-br hover:from-brand-50 hover:to-purple-50 border border-transparent hover:border-brand-100 transition-all duration-200 transform hover:-translate-y-0.5 group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-100 group-hover:bg-brand-200 flex items-center justify-center transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-brand-700 truncate">{{ $service->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $service->description }}</p>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">No services available yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
