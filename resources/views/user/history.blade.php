@extends('layouts.user')

@section('title', 'Watch History')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-jakarta text-2xl md:text-3xl font-bold text-white">Watch History</h1>
            <p class="text-sm text-gray-500 mt-1">Your recently watched videos</p>
        </div>

        @if($history->count() > 0)
            <form method="POST" action="{{ route('user.history.clear') }}" onsubmit="return confirm('Are you sure you want to clear all history? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium rounded-xl hover:bg-red-500/20 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear All
                </button>
            </form>
        @endif
    </div>

    <!-- History List -->
    @if($history->count() > 0)
        <div class="space-y-3">
            @foreach($history as $item)
                <div class="bg-[#141419] border border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-white/10 transition-colors group">
                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                        <!-- Play Icon -->
                        <div class="flex-shrink-0 w-10 h-10 bg-brand-500/10 border border-brand-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-white truncate">{{ $item->title ?? 'Untitled Video' }}</h4>
                            <p class="text-xs text-gray-500 truncate mt-1">{{ Str::limit($item->link, 80) }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.history.delete', $item->id) }}" onsubmit="return confirm('Delete this item from history?')" class="flex-shrink-0 ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-600 hover:text-red-400 transition-colors rounded-lg hover:bg-red-500/10 opacity-0 group-hover:opacity-100" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $history->links() }}
        </div>
    @else
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-dark-950 border border-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-jakarta font-semibold text-white text-lg mb-2">No history yet</h3>
            <p class="text-gray-500 text-sm">Start watching videos and they'll appear here!</p>
            <a href="{{ route('user.home') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium rounded-xl hover:bg-brand-500/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Start Searching
            </a>
        </div>
    @endif
</div>
@endsection
