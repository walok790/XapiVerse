@extends('layouts.user')

@section('title', 'TeraBox Player')

@section('content')
<div x-data="playerApp()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="font-jakarta text-2xl sm:text-3xl font-bold text-white">Terabox Search</h1>
            <p class="text-gray-400 mt-1">Paste a link to stream or download</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="px-4 py-2 bg-[#141419] border border-white/10 text-white text-sm rounded-lg">
                Daily Credits <span class="font-bold">5 / 5</span>
            </span>
        </div>
    </div>

    <!-- Search Input -->
    <div class="mb-8">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <input type="text" x-model="url"
                   class="w-full pl-12 pr-4 py-4 bg-[#141419] border border-white/10 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                   placeholder="Paste Terabox URL here...">
        </div>
        <button @click="processLink()" :disabled="loading || !url"
                class="mt-4 px-6 py-3 bg-[#141419] border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            <span x-text="loading ? 'Processing...' : 'Watch Now'"></span>
        </button>
    </div>

    <!-- Result Area -->
    <div x-show="result" x-cloak class="mb-8">
        <div class="bg-[#141419] border border-white/5 rounded-xl p-6">
            <h3 class="font-jakarta font-semibold text-white mb-3">Result</h3>
            <div class="text-sm text-gray-400">
                <p x-text="result?.message"></p>
                <p class="mt-2 text-gray-500" x-text="result?.note"></p>
            </div>
        </div>
    </div>

    <!-- Error -->
    <div x-show="error" x-cloak class="mb-8">
        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
            <p class="text-sm text-red-400" x-text="error"></p>
        </div>
    </div>


    <!-- Your Stats -->
    <div>
        <h3 class="font-jakarta font-semibold text-white mb-4">Your Stats</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-[#141419] border border-white/5 rounded-xl p-5">
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
            <div class="bg-[#141419] border border-white/5 rounded-xl p-5">
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
            <div class="bg-[#141419] border border-white/5 rounded-xl p-5">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">0</p>
                        <p class="text-xs text-gray-500">Today's Searches</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function playerApp() {
    return {
        url: '',
        loading: false,
        result: null,
        error: null,

        async processLink() {
            this.loading = true;
            this.result = null;
            this.error = null;

            try {
                const response = await fetch('{{ route("user.player.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ link: this.url })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data.data;
                } else {
                    this.error = data.message || 'Something went wrong. Please try again.';
                }
            } catch (err) {
                this.error = 'Failed to process link. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
