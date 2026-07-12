<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XapiVerse - TeraBox Video Player & Downloader</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'], 'inter': ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' },
                        dark: { 50:'#f8fafc',100:'#f1f5f9',200:'#e2e8f0',300:'#cbd5e1',400:'#94a3b8',500:'#64748b',600:'#475569',700:'#334155',800:'#1e293b',900:'#0f172a',950:'#020617' }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-dark-950 text-white min-h-screen">

<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-dark-950/80 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white font-bold text-sm">X</span>
                </div>
                <span class="font-jakarta font-bold text-lg text-white">XapiVerse</span>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Home</a>
                <a href="#services" class="text-sm text-gray-400 hover:text-white transition-colors">Services</a>
                <a href="#features" class="text-sm text-gray-400 hover:text-white transition-colors">Features</a>
                <a href="#pricing" class="text-sm text-gray-400 hover:text-white transition-colors">Pricing</a>
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors">API</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">XapiPlay</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Contact</a>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-colors">Get Started</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero: Terabox Search -->
<section class="pt-28 pb-16 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="font-jakarta text-3xl sm:text-4xl font-bold text-white">Terabox Search</h1>
                <p class="text-gray-400 text-sm mt-1">Paste a link to stream or download — no signup required</p>
            </div>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="px-3 py-1.5 bg-dark-800 border border-white/10 rounded-lg text-xs text-gray-300">
                    <span class="text-white font-semibold">5</span> free credits
                </span>
                <span class="px-3 py-1.5 bg-green-600/20 border border-green-500/30 rounded-lg text-xs text-green-400 font-medium">5 free/day</span>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mt-6 flex items-center bg-dark-800 border border-white/10 rounded-xl p-2" x-data="{ link: '' }">
            <div class="flex items-center pl-3 pr-2 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <input type="url" x-model="link" placeholder="Paste Terabox URL here..." class="flex-1 bg-transparent text-white text-sm placeholder-gray-500 outline-none py-2.5 px-2">
            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-white text-dark-900 text-sm font-semibold rounded-lg hover:bg-gray-100 transition-colors flex items-center space-x-2">
                <span>Watch Now</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
            </a>
        </div>

        <!-- Signup Prompt -->
        <div class="mt-4 flex items-center justify-between bg-dark-800/50 border border-white/5 rounded-xl px-5 py-3">
            <div class="flex items-center space-x-2">
                <span class="text-brand-400">✦</span>
                <div>
                    <p class="text-sm font-medium text-white">Want more daily searches?</p>
                    <p class="text-xs text-gray-500">Create a free account for more credits, or upgrade for unlimited.</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('register') }}" class="px-4 py-1.5 bg-brand-600 text-white text-xs font-medium rounded-lg hover:bg-brand-700 transition-colors">Sign Up Free</a>
                <a href="{{ route('login') }}" class="px-4 py-1.5 bg-dark-700 text-gray-300 text-xs font-medium rounded-lg hover:bg-dark-600 transition-colors">Login</a>
            </div>
        </div>

        <!-- Quick Features -->
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-dark-800 border border-white/5 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-brand-600/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <p class="text-xs font-medium text-white">Instant</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Fast processing</p>
            </div>
            <div class="bg-dark-800 border border-white/5 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-green-600/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p class="text-xs font-medium text-white">Secure</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Private & safe</p>
            </div>
            <div class="bg-dark-800 border border-white/5 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/></svg>
                </div>
                <p class="text-xs font-medium text-white">HD Quality</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Up to 1080p</p>
            </div>
            <div class="bg-dark-800 border border-white/5 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-purple-600/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xs font-medium text-white">Mobile Ready</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Works everywhere</p>
            </div>
        </div>
    </div>
</section>


<!-- Our Services -->
<section id="services" class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <span class="inline-flex items-center px-3 py-1 bg-dark-800 border border-white/10 rounded-full text-xs text-gray-400 mb-4">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Our Services
            </span>
            <h2 class="font-jakarta text-3xl sm:text-4xl font-bold text-white">Everything You Need</h2>
            <p class="text-gray-400 mt-3 max-w-lg mx-auto">Stream, download, and manage Terabox content with ease.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-5">
            <!-- Video Downloads -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-blue-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Video Downloads</h3>
                <p class="text-sm text-gray-500">Download Terabox videos in 480p, 720p, 1080p. Save to your device instantly.</p>
            </div>
            <!-- Online Streaming -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-orange-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Online Streaming</h3>
                <p class="text-sm text-gray-500">Stream directly in your browser without downloading. Adaptive quality playback.</p>
            </div>
            <!-- Link Converter -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-green-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Link Converter</h3>
                <p class="text-sm text-gray-500">Convert any Terabox sharing link to a direct download link. All domains supported.</p>
            </div>
            <!-- Batch Processing -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-emerald-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Batch Processing</h3>
                <p class="text-sm text-gray-500">Process multiple links at once. Queue downloads and stream playlists.</p>
            </div>
            <!-- Developer API -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-purple-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Developer API</h3>
                <p class="text-sm text-gray-500">Integrate XapiVerse into your apps with our REST API. Full documentation available.</p>
            </div>
            <!-- Privacy First -->
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-colors group">
                <div class="w-11 h-11 bg-pink-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-2">Privacy First</h3>
                <p class="text-sm text-gray-500">We never store your videos or personal information. Zero-log policy.</p>
            </div>
        </div>
    </div>
</section>


<!-- Why XapiVerse -->
<section id="features" class="py-20 px-4 bg-dark-900/50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="font-jakarta text-3xl sm:text-4xl font-bold text-white">Why XapiVerse?</h2>
            <p class="text-gray-400 mt-3">Powerful features for the best experience.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-yellow-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">Lightning Fast</h3>
                <p class="text-sm text-gray-500">Stream instantly with optimized CDN delivery globally.</p>
            </div>
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-cyan-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">Private & Secure</h3>
                <p class="text-sm text-gray-500">End-to-end protection. Your privacy is our top priority.</p>
            </div>
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-indigo-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">HD Quality</h3>
                <p class="text-sm text-gray-500">Download in 480p to 1080p. Multiple quality options.</p>
            </div>
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-rose-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">Mobile Friendly</h3>
                <p class="text-sm text-gray-500">Works perfectly on phones, tablets, and desktops.</p>
            </div>
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-amber-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">Bookmarks</h3>
                <p class="text-sm text-gray-500">Save videos to your library. Build playlists.</p>
            </div>
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6">
                <div class="w-11 h-11 bg-violet-600/20 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-jakarta font-semibold text-white mb-1">Watch History</h3>
                <p class="text-sm text-gray-500">Never lose a video. Full history with search.</p>
            </div>
        </div>
    </div>
</section>


<!-- Simple Pricing -->
<section id="pricing" class="py-20 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="font-jakarta text-3xl sm:text-4xl font-bold text-white">Simple Pricing</h2>
            <p class="text-gray-400 mt-3">Start free. Upgrade when needed.</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-5">
            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 text-center hover:border-white/10 transition-colors">
                <h3 class="font-jakarta font-bold text-white text-lg mb-1">Free</h3>
                <p class="text-3xl font-jakarta font-extrabold text-white mb-1">$0</p>
                <p class="text-xs text-gray-500 mb-4">5 requests/day</p>
                <ul class="space-y-2 text-sm text-gray-400 mb-6 text-left">
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Stream & Download</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>720p quality</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>No signup needed</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-2.5 text-sm font-medium text-white border border-white/10 rounded-lg hover:bg-white/5 transition-colors">Get Started</a>
            </div>

            <div class="bg-dark-900 border-2 border-brand-500/50 rounded-2xl p-6 text-center relative shadow-lg shadow-brand-500/10">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="px-3 py-0.5 bg-brand-600 text-white text-xs font-bold rounded-full">Popular</span>
                </div>
                <h3 class="font-jakarta font-bold text-white text-lg mb-1">Pro</h3>
                <p class="text-3xl font-jakarta font-extrabold text-white mb-1">$5</p>
                <p class="text-xs text-gray-500 mb-4">150,000 credits</p>
                <ul class="space-y-2 text-sm text-gray-400 mb-6 text-left">
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Unlimited streaming</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>1080p + 4K quality</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Batch downloads</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>API access</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-2.5 text-sm font-semibold text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-colors">Get Pro</a>
            </div>

            <div class="bg-dark-900 border border-white/5 rounded-2xl p-6 text-center hover:border-white/10 transition-colors">
                <h3 class="font-jakarta font-bold text-white text-lg mb-1">Enterprise</h3>
                <p class="text-3xl font-jakarta font-extrabold text-white mb-1">$100</p>
                <p class="text-xs text-gray-500 mb-4">5,000,000 credits</p>
                <ul class="space-y-2 text-sm text-gray-400 mb-6 text-left">
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Everything in Pro</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Priority support</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Custom integrations</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-2.5 text-sm font-medium text-white border border-white/10 rounded-lg hover:bg-white/5 transition-colors">Contact Us</a>
            </div>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="border-t border-white/5 py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8 mb-10">
            <!-- Brand -->
            <div class="md:col-span-1">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white font-bold text-sm">X</span>
                    </div>
                    <span class="font-jakarta font-bold text-lg text-white">XapiVerse</span>
                </div>
                <p class="text-sm text-gray-500 mb-4">Your all-in-one API marketplace and digital services platform. Stream, download, and build with powerful APIs.</p>
                <div class="flex items-center space-x-3">
                    <a href="#" class="w-8 h-8 bg-dark-800 border border-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-dark-800 border border-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-dark-800 border border-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03z"/></svg>
                    </a>
                </div>
            </div>
            <!-- Product -->
            <div>
                <h4 class="font-jakarta font-semibold text-white text-sm mb-4">Product</h4>
                <ul class="space-y-2">
                    <li><a href="#features" class="text-sm text-gray-500 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#pricing" class="text-sm text-gray-500 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-white transition-colors">API Marketplace</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Contact Us</a></li>
                </ul>
            </div>
            <!-- Legal -->
            <div>
                <h4 class="font-jakarta font-semibold text-white text-sm mb-4">Legal</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Refund Policy</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">DMCA Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between">
            <p class="text-xs text-gray-600">&copy; {{ date('Y') }} XapiVerse. All rights reserved.</p>
            <p class="text-xs text-gray-600 mt-2 sm:mt-0">Powered by XapiVerse Platform</p>
        </div>
    </div>
</footer>

</body>
</html>
