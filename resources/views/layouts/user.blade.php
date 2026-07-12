<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - XapiVerse</title>
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
                        dark: { 50:'#f8fafc',100:'#f1f5f9',200:'#e2e8f0',300:'#cbd5e1',400:'#94a3b8',500:'#64748b',600:'#475569',700:'#334155',800:'#1e293b',900:'#141419',950:'#0d0d12' }
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
<body class="bg-dark-950 text-white min-h-screen flex flex-col">


<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-dark-950/80 backdrop-blur-xl border-b border-white/5" x-data="{ mobileOpen: false, profileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                <span class="font-jakarta font-bold text-lg"><span class="text-white">Xapi</span><span class="text-white font-extrabold">Verse</span></span>
            </a>

            <!-- Center Nav Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Home</a>
                <a href="{{ route('user.home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Dashboard</a>
                <a href="{{ route('user.bookmarks') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Bookmarks</a>
                <a href="{{ route('user.history') }}" class="text-sm text-gray-400 hover:text-white transition-colors">History</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">API</a>
                <a href="{{ route('user.home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">XapiPlay</a>
                <a href="{{ route('user.subscription') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Subscription</a>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <button class="relative text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" class="w-9 h-9 rounded-full bg-brand-600 flex items-center justify-center text-white font-semibold text-sm hover:ring-2 hover:ring-brand-400/50 transition-all">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>


                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-dark-900 border border-white/10 rounded-xl shadow-xl shadow-black/20 py-2 z-50">
                        <div class="px-4 py-2 border-b border-white/5">
                            <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <a href="{{ route('user.home') }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            My Bookmarks
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            My History
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Transactions
                        </a>
                        <a href="{{ route('user.subscription') }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Upgrade Premium
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Support
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            Messages
                        </a>
                        <div class="border-t border-white/5 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-white/5 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Mobile Hamburger -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-400 hover:text-white">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-cloak x-transition class="md:hidden bg-dark-900 border-t border-white/5">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Home</a>
            <a href="{{ route('user.home') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Dashboard</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Bookmarks</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">History</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">API</a>
            <a href="{{ route('user.home') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">XapiPlay</a>
            <a href="{{ route('user.subscription') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Subscription</a>
        </div>
    </div>
</nav>

<!-- Toast Notifications -->
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
     class="fixed top-20 right-4 z-50 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm flex items-center space-x-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
     class="fixed top-20 right-4 z-50 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm flex items-center space-x-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- Main Content -->
<main class="flex-1 pt-20 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</main>


<!-- Footer -->
<footer class="border-t border-white/5 py-12 px-4 mt-auto">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8 mb-10">
            <!-- Brand -->
            <div class="md:col-span-1">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="font-jakarta font-bold text-lg"><span class="text-white">Xapi</span><span class="text-white font-extrabold">Verse</span></span>
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
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">API Marketplace</a></li>
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
            <!-- Support -->
            <div>
                <h4 class="font-jakarta font-semibold text-white text-sm mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Documentation</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Status Page</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Community</a></li>
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
