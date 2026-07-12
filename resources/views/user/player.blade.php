@extends('layouts.app')

@section('title', 'TeraBox Player')

@section('content')
<div class="p-6 lg:p-8 space-y-8" x-data="{
    link: '',
    loading: false,
    result: null,
    error: null,
    async processLink() {
        if (!this.link.trim()) {
            this.error = 'Please enter a TeraBox link';
            return;
        }
        this.loading = true;
        this.error = null;
        this.result = null;
        try {
            const response = await fetch('{{ route("user.player.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ link: this.link })
            });
            const data = await response.json();
            if (!response.ok) {
                this.error = data.message || 'Something went wrong. Please try again.';
            } else {
                this.result = data;
            }
        } catch (e) {
            this.error = 'Network error. Please check your connection and try again.';
        } finally {
            this.loading = false;
        }
    }
}">


    <!-- Header Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-brand-600 to-purple-700 p-8 shadow-xl shadow-brand-500/20">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        </div>
        <div class="relative">
            <h1 class="text-2xl sm:text-3xl font-jakarta font-bold text-white mb-2">TeraBox Player</h1>
            <p class="text-white/70">Paste a TeraBox link below to stream or download the video.</p>
        </div>
    </div>

    <!-- Input Section -->
    <div class="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 shadow-sm">
        <div class="max-w-3xl mx-auto">
            <label for="terabox-link" class="block text-sm font-medium text-gray-700 mb-2">TeraBox Link</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <input
                        id="terabox-link"
                        type="url"
                        x-model="link"
                        @keydown.enter="processLink()"
                        placeholder="https://terabox.com/s/..."
                        class="w-full pl-12 pr-4 py-3.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                    >
                </div>
                <button
                    @click="processLink()"
                    :disabled="loading"
                    class="px-8 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-purple-500/25 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                >
                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Processing...' : 'Process Link'"></span>
                </button>
            </div>
        </div>
    </div>


    <!-- Error Display -->
    <div x-show="error" x-cloak x-transition class="rounded-2xl border border-red-200 bg-red-50 p-5">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-red-800">Error</h4>
                <p class="text-sm text-red-700 mt-1" x-text="error"></p>
            </div>
        </div>
    </div>

    <!-- Result Display -->
    <div x-show="result" x-cloak x-transition class="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-4">Result</h3>
        
        <!-- Video Player Area -->
        <div class="mb-6">
            <div class="aspect-video bg-gray-900 rounded-xl flex items-center justify-center overflow-hidden">
                <template x-if="result && result.video_url">
                    <video controls class="w-full h-full" :src="result.video_url">
                        Your browser does not support the video tag.
                    </video>
                </template>
                <template x-if="result && !result.video_url">
                    <div class="text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">Video preview not available</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Download Link -->
        <template x-if="result && result.download_url">
            <a :href="result.download_url" target="_blank" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 shadow-lg shadow-green-500/25 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download File
            </a>
        </template>
    </div>


    <!-- How It Works -->
    <div class="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg font-jakarta font-semibold text-gray-900 mb-6">How It Works</h3>
        <div class="grid sm:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-brand-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-brand-600">1</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Paste Link</h4>
                <p class="text-xs text-gray-500">Copy your TeraBox share link and paste it in the input field above.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-purple-600">2</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Process</h4>
                <p class="text-xs text-gray-500">Click the Process button and wait while we extract the media content.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-indigo-600">3</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Watch / Download</h4>
                <p class="text-xs text-gray-500">Stream the video directly in your browser or download it to your device.</p>
            </div>
        </div>
    </div>
</div>
@endsection
