@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Dashboard</h1>
        <p class="mt-1 text-gray-500">Overview of your API management platform.</p>
    </div>

    {{-- Stats Cards with Animated Counters --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-blue-500 to-blue-700 shadow-xl shadow-blue-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['total_users'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-blue-100 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-white" x-text="count.toLocaleString()">0</p>
            </div>
        </div>


        {{-- Active Source Keys --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-emerald-500 to-green-700 shadow-xl shadow-green-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['active_source_keys'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-green-100 mb-1">Active Source Keys</p>
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

        {{-- Active Services --}}
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl shadow-orange-500/20 transform hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300"
             x-data="{ count: 0, target: {{ $stats['active_services'] ?? 0 }} }"
             x-init="let interval = setInterval(() => { if(count < target) { count += Math.ceil(target / 40); if(count > target) count = target; } else { clearInterval(interval); } }, 30)">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-amber-100 mb-1">Active Services</p>
                <p class="text-3xl font-bold text-white" x-text="count.toLocaleString()">0</p>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Request Logs --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 font-jakarta">Recent Request Logs</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Latest API activity across all services</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700 transition-colors">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Response</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-brand-50/30 transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center">
                                        <span class="text-white text-[10px] font-bold">{{ substr($log->user->name ?? 'N', 0, 1) }}</span>
                                    </div>
                                    <span class="text-gray-900 font-medium text-xs">{{ $log->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-xs font-medium">{{ $log->apiService->name ?? 'N/A' }}</td>
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
                                    {{ $log->status }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs font-mono">{{ $log->response_time_ms ?? '-' }}ms</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-sm text-gray-500">No recent request logs.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 font-jakarta">Quick Actions</h2>
                <p class="text-xs text-gray-500 mt-0.5">Common management tasks</p>
            </div>
            <div class="p-5 grid grid-cols-2 gap-3">
                <a href="{{ route('admin.services.create') }}" class="flex flex-col items-center p-4 rounded-2xl bg-gray-50 hover:bg-gradient-to-br hover:from-brand-50 hover:to-purple-50 border border-transparent hover:border-brand-100 transition-all duration-200 transform hover:-translate-y-0.5 group">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 group-hover:bg-brand-200 flex items-center justify-center mb-2 transition-colors">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-brand-700 text-center">Add Service</span>
                </a>
                <a href="{{ route('admin.source-keys.create') }}" class="flex flex-col items-center p-4 rounded-2xl bg-gray-50 hover:bg-gradient-to-br hover:from-green-50 hover:to-emerald-50 border border-transparent hover:border-green-100 transition-all duration-200 transform hover:-translate-y-0.5 group">
                    <div class="w-10 h-10 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center mb-2 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-green-700 text-center">Source Key</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 rounded-2xl bg-gray-50 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 border border-transparent hover:border-blue-100 transition-all duration-200 transform hover:-translate-y-0.5 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center mb-2 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-blue-700 text-center">Users</span>
                </a>
                <a href="{{ route('admin.logs.index') }}" class="flex flex-col items-center p-4 rounded-2xl bg-gray-50 hover:bg-gradient-to-br hover:from-amber-50 hover:to-orange-50 border border-transparent hover:border-amber-100 transition-all duration-200 transform hover:-translate-y-0.5 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center mb-2 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-amber-700 text-center">View Logs</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center p-4 rounded-2xl bg-gray-50 hover:bg-gradient-to-br hover:from-gray-50 hover:to-slate-100 border border-transparent hover:border-gray-200 transition-all duration-200 transform hover:-translate-y-0.5 group col-span-2">
                    <div class="w-10 h-10 rounded-xl bg-gray-200 group-hover:bg-gray-300 flex items-center justify-center mb-2 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-gray-900 text-center">Settings</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
