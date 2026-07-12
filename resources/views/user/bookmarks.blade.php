@extends('layouts.user')

@section('title', 'My Bookmarks')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-jakarta text-2xl md:text-3xl font-bold text-white">My Bookmarks</h1>
            <p class="text-sm text-gray-500 mt-1">Your saved videos for later</p>
        </div>

        @if($bookmarks->count() > 0)
            <form method="POST" action="{{ route('user.bookmarks.clear') }}" onsubmit="return confirm('Are you sure you want to clear all bookmarks? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium rounded-xl hover:bg-red-500/20 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear All
                </button>
            </form>
        @endif
    </div>

    <!-- Bookmarks Grid -->
    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($bookmarks as $item)
                <div class="bg-[#141419] border border-white/5 rounded-xl p-5 hover:border-white/10 transition-colors group flex flex-col">
                    <!-- Bookmark Icon -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-brand-500/10 border border-brand-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </div>
                        <form method="POST" action="{{ route('user.bookmarks.remove', $item->id) }}" onsubmit="return confirm('Remove this bookmark?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-600 hover:text-red-400 transition-colors rounded-lg hover:bg-red-500/10 opacity-0 group-hover:opacity-100" title="Remove">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Content -->
                    <h4 class="text-sm font-medium text-white truncate mb-1">{{ $item->title ?? 'Untitled Video' }}</h4>
                    <p class="text-xs text-gray-500 truncate mb-3">{{ Str::limit($item->link, 50) }}</p>

                    <!-- Footer -->
                    <div class="mt-auto pt-3 border-t border-white/5">
                        <p class="text-xs text-gray-600">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookmarks->links() }}
        </div>
    @else
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-dark-950 border border-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            </div>
            <h3 class="font-jakarta font-semibold text-white text-lg mb-2">No bookmarks yet</h3>
            <p class="text-gray-500 text-sm">Bookmark videos from search results to find them easily later!</p>
            <a href="{{ route('user.home') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium rounded-xl hover:bg-brand-500/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Start Searching
            </a>
        </div>
    @endif
</div>
@endsection
