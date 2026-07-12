<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - XapiVerse</title>
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
<nav class="fixed top-0 left-0 right-0 z-50 bg-dark-950/80 backdrop-blur-xl border-b border-white/5" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                <span class="font-jakarta font-bold text-lg"><span class="text-white">Xapi</span><span class="text-white font-extrabold">Verse</span></span>
            </a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Home</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Services</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Features</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Pricing</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">API</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">XapiPlay</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Contact</a>
            </div>
            <div class="hidden md:flex items-center space-x-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-colors">Get Started</a>
            </div>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-400 hover:text-white">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileOpen" x-cloak x-transition class="md:hidden bg-dark-900 border-t border-white/5">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Home</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Services</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Features</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Pricing</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">API</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">XapiPlay</a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5">Contact</a>
            <div class="pt-2 border-t border-white/5 mt-2">
                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white rounded-lg hover:bg-white/5">Sign In</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-sm text-brand-400 font-medium rounded-lg hover:bg-white/5">Get Started</a>
            </div>
        </div>
    </div>
</nav>


<!-- Main Content -->
<main class="flex-1 flex items-center justify-center pt-24 pb-16 px-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-[#141419] border border-white/5 rounded-xl p-8">
            <h2 class="font-jakarta text-2xl font-bold text-white mb-1">Welcome back</h2>
            <p class="text-gray-400 text-sm mb-6">Sign in to your account</p>

            @if($errors->any())
            <div class="mb-5 p-3 bg-red-500/10 border border-red-500/20 rounded-lg flex items-start space-x-2">
                <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-red-400">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 bg-dark-950 border border-white/10 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                               placeholder="you@example.com">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 bg-dark-950 border border-white/10 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 bg-dark-950 border-white/20 rounded text-brand-600 focus:ring-brand-500 focus:ring-offset-0">
                        <span class="ml-2 text-sm text-gray-400">Remember</span>
                    </label>
                    <a href="#" class="text-sm text-teal-400 hover:text-teal-300 transition-colors">Forgot?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-white text-dark-950 font-semibold text-sm rounded-lg hover:bg-gray-100 transition-colors">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                No account? <a href="{{ route('register') }}" class="text-white hover:text-brand-400 font-medium transition-colors">Create one</a>
            </p>
        </div>


        <!-- Demo Credentials -->
        @if($isDemo && !empty($demoCredentials))
        <div class="mt-5 bg-[#141419] border border-white/5 rounded-xl p-4">
            <div class="flex items-center space-x-2 mb-3">
                <span class="px-2 py-0.5 bg-brand-600/20 text-brand-400 text-xs font-bold uppercase rounded">DEMO</span>
                <span class="text-xs text-gray-500">Use these credentials to explore</span>
            </div>
            @foreach($demoCredentials as $role => $creds)
            <div class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-white/5' : '' }}">
                <span class="text-xs font-semibold text-gray-400 uppercase">{{ $role }}</span>
                <span class="text-xs text-gray-500 font-mono">{{ $creds['email'] }} / {{ $creds['password'] }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <p class="text-center text-sm text-gray-600 mt-4">
            Admin? <a href="{{ route('admin.login') }}" class="text-red-400 hover:text-red-300 font-medium transition-colors">Login here</a>
        </p>
    </div>
</main>


<!-- Footer -->
<footer class="border-t border-white/5 py-12 px-4 mt-auto">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8 mb-10">
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
            <div>
                <h4 class="font-jakarta font-semibold text-white text-sm mb-4">Product</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">API Marketplace</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-white transition-colors">Contact Us</a></li>
                </ul>
            </div>
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
