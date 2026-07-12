@extends('layouts.user')

@section('title', 'Home')

@section('content')
<div x-data="searchApp()" class="space-y-8">

    <!-- Header Section -->
    <div class="text-center space-y-3">
        <h1 class="font-jakarta text-3xl md:text-4xl font-bold text-white">Terabox Search</h1>
        <p class="text-gray-400 text-sm md:text-base">Paste a link to stream or download</p>
        <div class="inline-flex items-center px-3 py-1.5 bg-brand-500/10 border border-brand-500/20 rounded-full">
            <svg class="w-4 h-4 text-brand-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="text-xs font-medium text-brand-300">Daily Credits: <span class="text-white font-semibold">{{ $user->credits ?? 0 }}</span></span>
        </div>
    </div>

    <!-- Search Input -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-2">
            <div class="flex items-center space-x-2">
                <div class="flex-1 flex items-center bg-dark-950 rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 text-gray-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <input type="text" x-model="url" @keydown.enter="processUrl()" placeholder="Paste Terabox URL here..." class="flex-1 bg-transparent text-white placeholder-gray-500 text-sm focus:outline-none">
                </div>
                <button @click="processUrl()" :disabled="loading" class="px-6 py-3 bg-gradient-to-r from-brand-500 to-purple-600 hover:from-brand-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="loading ? 'Processing...' : 'Watch Now'"></span>
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <div x-show="error" x-cloak x-transition class="mt-4 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3">
            <p class="text-sm text-red-400" x-text="error"></p>
        </div>
    </div>

    <!-- Result Area -->
    <div x-show="result" x-cloak x-transition class="max-w-2xl mx-auto">
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-6 space-y-4">
            <h3 class="font-jakarta font-semibold text-white text-lg" x-text="result?.title || 'Result'"></h3>
            <div x-html="result?.html || ''"></div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto">
        <div class="bg-[#141419] border border-white/5 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $todaySearches }}</p>
            <p class="text-xs text-gray-500 mt-1">Today's Searches</p>
        </div>
        <div class="bg-[#141419] border border-white/5 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $downloadCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Downloads</p>
        </div>
        <div class="bg-[#141419] border border-white/5 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $bookmarkCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Bookmarks</p>
        </div>
    </div>

    <!-- Recent Watches Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-jakarta text-xl font-bold text-white">Recent Watches</h2>
        </div>

        @if($recentWatches->count() > 0)
            <div class="grid gap-3">
                @foreach($recentWatches as $item)
                    <div class="bg-[#141419] border border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-white/10 transition-colors">
                        <div class="flex-1 min-w-0 mr-4">
                            <h4 class="text-sm font-medium text-white truncate">{{ $item->title ?? 'Untitled' }}</h4>
                            <p class="text-xs text-gray-500 truncate mt-1">{{ Str::limit($item->link, 60) }}</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="text-xs text-gray-600">{{ $item->created_at->diffForHumans() }}</span>
                                @if($item->status)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $item->status === 'completed' ? 'bg-green-500/10 text-green-400' : 'bg-yellow-500/10 text-yellow-400' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <button @click="addBookmark('{{ $item->id }}')" class="flex-shrink-0 p-2 text-gray-500 hover:text-brand-400 transition-colors rounded-lg hover:bg-white/5" title="Bookmark">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#141419] border border-white/5 rounded-xl p-8 text-center">
                <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-gray-500 text-sm">No recent watches. Start searching!</p>
            </div>
        @endif
    </div>
</div>

<script>
function searchApp() {
    return {
        url: '',
        loading: false,
        error: null,
        result: null,

        async processUrl() {
            if (!this.url.trim()) {
                this.error = 'Please paste a Terabox URL';
                return;
            }
            this.loading = true;
            this.error = null;
            this.result = null;

            try {
                const response = await fetch('{{ route("user.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: this.url })
                });

                const data = await response.json();

                if (!response.ok) {
                    this.error = data.message || 'Something went wrong. Please try again.';
                    return;
                }

                this.result = data;
            } catch (e) {
                this.error = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        },

        async addBookmark(id) {
            try {
                const response = await fetch('{{ route("user.bookmarks.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message || 'Bookmarked successfully!');
                } else {
                    alert(data.message || 'Failed to bookmark.');
                }
            } catch (e) {
                alert('Network error. Please try again.');
            }
        }
    }
}
</script>
@endsection
