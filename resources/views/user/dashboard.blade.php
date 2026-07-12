@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="font-jakarta text-2xl sm:text-3xl font-bold text-white">Welcome back, {{ $user->name }}!</h1>
        <p class="text-gray-400 mt-1">Here's your activity overview.</p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <span class="px-3 py-1 bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-xs font-semibold rounded-full">Free Plan</span>
        <a href="{{ route('user.player') }}" class="px-4 py-2 border border-white/10 text-white text-sm rounded-lg hover:bg-white/5 transition-colors">Search Videos</a>
    </div>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-[#141419] border border-white/5 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">0</p>
                <p class="text-xs text-gray-500">Videos Watched</p>
            </div>
        </div>
    </div>
    <div class="bg-[#141419] border border-white/5 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">0</p>
                <p class="text-xs text-gray-500">Downloads</p>
            </div>
        </div>
    </div>
    <div class="bg-[#141419] border border-white/5 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">0</p>
                <p class="text-xs text-gray-500">Bookmarks</p>
            </div>
        </div>
    </div>
    <div class="bg-[#141419] border border-white/5 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">0</p>
                <p class="text-xs text-gray-500">History</p>
            </div>
        </div>
    </div>
    <div class="bg-[#141419] border border-white/5 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">5</p>
                <p class="text-xs text-gray-500">Credits Today</p>
            </div>
        </div>
    </div>
</div>


<!-- Main Grid -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left Column (2/3) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Today's Usage -->
        <div class="bg-[#141419] border border-white/5 rounded-xl p-6">
            <h3 class="font-jakarta font-semibold text-white mb-4">Today's Usage</h3>
            <div class="mb-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">0 / 5 searches used</span>
                    <span class="text-sm text-gray-500">0%</span>
                </div>
                <div class="w-full h-2 bg-dark-950 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-600 rounded-full" style="width: 0%"></div>
                </div>
            </div>
            <div class="flex items-center space-x-4 mt-4">
                <a href="{{ route('user.player') }}" class="text-sm text-teal-400 hover:text-teal-300 font-medium transition-colors">Search Now →</a>
                <a href="{{ route('user.subscription') }}" class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Upgrade for More</a>
            </div>
        </div>

        <!-- Recent History -->
        <div class="bg-[#141419] border border-white/5 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-jakarta font-semibold text-white">Recent History</h3>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">View All →</a>
            </div>
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-dark-950 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 text-sm">No history yet. Start watching!</p>
                <a href="{{ route('user.player') }}" class="inline-block mt-3 text-sm text-brand-400 hover:text-brand-300 font-medium transition-colors">Go to Player →</a>
            </div>
        </div>
    </div>

    <!-- Right Column (1/3) -->
    <div class="space-y-6">
        <!-- Your Plan -->
        <div class="bg-[#141419] border border-white/5 rounded-xl p-6">
            <h3 class="font-jakarta font-semibold text-white mb-4">Your Plan</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Plan</span>
                    <span class="text-sm font-semibold text-white">Free</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Daily Searches</span>
                    <span class="text-sm font-semibold text-white">5</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">API Access</span>
                    <span class="text-sm font-semibold text-red-400">No</span>
                </div>
            </div>
            <a href="{{ route('user.subscription') }}" class="block w-full mt-5 py-2.5 text-center text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-colors">Upgrade Plan</a>
        </div>

        <!-- Quick Links -->
        <div class="bg-[#141419] border border-white/5 rounded-xl p-6">
            <h3 class="font-jakarta font-semibold text-white mb-4">Quick Links</h3>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <span class="text-sm text-gray-400 group-hover:text-white">My Bookmarks</span>
                        <span class="text-xs text-gray-600 bg-dark-950 px-2 py-0.5 rounded">0</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <span class="text-sm text-gray-400 group-hover:text-white">Watch History</span>
                        <span class="text-xs text-gray-600 bg-dark-950 px-2 py-0.5 rounded">0</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <span class="text-sm text-gray-400 group-hover:text-white">Transactions</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <span class="text-sm text-gray-400 group-hover:text-white">Support</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.profile') }}" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <span class="text-sm text-gray-400 group-hover:text-white">Edit Profile</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
