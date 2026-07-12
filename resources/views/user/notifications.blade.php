@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-jakarta text-2xl md:text-3xl font-bold text-white">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Stay updated with your account activity</p>
        </div>

        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('user.notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium rounded-xl hover:bg-brand-500/20 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="bg-[#141419] border border-white/5 rounded-xl p-4 hover:border-white/10 transition-colors {{ !$notification->is_read ? 'border-l-2 border-l-brand-500' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- Type Icon -->
                        <div class="flex-shrink-0 mt-0.5">
                            @switch($notification->type)
                                @case('info')
                                    <div class="w-9 h-9 bg-blue-500/10 border border-blue-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @break
                                @case('success')
                                    <div class="w-9 h-9 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @break
                                @case('warning')
                                    <div class="w-9 h-9 bg-yellow-500/10 border border-yellow-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    @break
                                @case('error')
                                    <div class="w-9 h-9 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @break
                                @default
                                    <div class="w-9 h-9 bg-gray-500/10 border border-gray-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-sm {{ !$notification->is_read ? 'font-semibold text-white' : 'font-medium text-gray-300' }} truncate">
                                    {{ $notification->title }}
                                </h4>
                                <!-- Unread Dot -->
                                @if(!$notification->is_read)
                                    <span class="flex-shrink-0 ml-2 w-2 h-2 bg-brand-500 rounded-full"></span>
                                @endif
                            </div>
                            <p class="text-sm {{ !$notification->is_read ? 'text-gray-300' : 'text-gray-500' }}">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-600 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-dark-950 border border-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="font-jakarta font-semibold text-white text-lg mb-2">No notifications</h3>
            <p class="text-gray-500 text-sm">You're all caught up! New notifications will appear here.</p>
        </div>
    @endif
</div>
@endsection
