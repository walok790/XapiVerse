@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 lg:p-8 space-y-8">
    <!-- Welcome Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-brand-600 to-purple-700 p-8 shadow-xl shadow-brand-500/20">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full blur-2xl translate-y-1/2 -translate-x-1/4"></div>
        </div>
        <div class="relative">
            <div class="flex items-center space-x-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
                    Platform User
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-jakarta font-bold text-white mb-2">
                Welcome back, {{ $user->name }}!
            </h1>
            <p class="text-white/70 text-sm sm:text-base">Access your tools and services from your personal dashboard.</p>
        </div>
    </div>


    <!-- Available Tools -->
    <div>
        <h2 class="text-xl font-jakarta font-bold text-gray-900 mb-4">Available Tools</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
            <a href="{{ url('/user/player') }}" class="group block p-6 rounded-2xl border border-gray-100 bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-4 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-1 group-hover:text-brand-600 transition-colors">{{ $service->name }}</h3>
                <p class="text-sm text-gray-500">{{ $service->description ?? 'Access this service tool' }}</p>
                <div class="mt-4 flex items-center text-sm font-medium text-brand-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Open Tool
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            @empty
            <a href="{{ url('/user/player') }}" class="group block p-6 rounded-2xl border border-gray-100 bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-4 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-1 group-hover:text-brand-600 transition-colors">TeraBox Player</h3>
                <p class="text-sm text-gray-500">Stream and download TeraBox videos directly</p>
                <div class="mt-4 flex items-center text-sm font-medium text-brand-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Open Tool
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            @endforelse
        </div>
    </div>


    <!-- Quick Actions & Stats -->
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="p-6 rounded-2xl border border-gray-100 bg-white">
            <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ url('/user/player') }}" class="flex items-center p-3 rounded-xl hover:bg-brand-50 transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center mr-3 group-hover:bg-brand-200 transition-colors">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">TeraBox Player</p>
                        <p class="text-xs text-gray-500">Stream or download videos</p>
                    </div>
                </a>
                <a href="{{ url('/user/profile') }}" class="flex items-center p-3 rounded-xl hover:bg-brand-50 transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Profile Settings</p>
                        <p class="text-xs text-gray-500">Update your account details</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Account Info -->
        <div class="p-6 rounded-2xl border border-gray-100 bg-white">
            <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-4">Account Info</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <span class="text-sm text-gray-500">Member since</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <span class="text-sm text-gray-500">Account type</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-700 capitalize">{{ $user->role }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
