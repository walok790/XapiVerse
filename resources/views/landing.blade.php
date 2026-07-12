<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XapiVerse - Fast & Affordable APIs for Developers</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd',
                            400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9',
                            800: '#5b21b6', 900: '#4c1d95',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-5deg); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 8s ease-in-out infinite; }
        .animate-gradient { 
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite; 
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fade-in-up-delay-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
        .animate-fade-in-up-delay-2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(124, 58, 237, 0.25);
        }
        .pricing-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>


<body class="bg-white min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-purple-500/30">
                        <span class="text-white font-bold text-sm">X</span>
                    </div>
                    <span class="font-jakarta font-bold text-xl text-gray-900 tracking-tight">XapiVerse</span>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Features</a>
                    <a href="#pricing" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Pricing</a>
                    <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Docs</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-purple-500/25 transition-all duration-200 hover:shadow-purple-500/40">Register</a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-3">
            <a href="#features" class="block text-sm font-medium text-gray-600 py-2">Features</a>
            <a href="#pricing" class="block text-sm font-medium text-gray-600 py-2">Pricing</a>
            <a href="#" class="block text-sm font-medium text-gray-600 py-2">Docs</a>
            <div class="pt-3 border-t border-gray-100 flex space-x-3">
                <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-200 rounded-xl">Login</a>
                <a href="{{ route('register') }}" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl">Register</a>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-44 lg:pb-32 overflow-hidden">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 animate-gradient"></div>
        
        <!-- Floating Shapes -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-300/20 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="absolute top-40 right-20 w-20 h-20 bg-gradient-to-br from-brand-400 to-purple-500 rounded-2xl opacity-20 animate-float rotate-12"></div>
        <div class="absolute bottom-32 left-20 w-16 h-16 bg-gradient-to-br from-indigo-400 to-brand-500 rounded-xl opacity-20 animate-float-delayed -rotate-12"></div>
        <div class="absolute top-60 left-1/3 w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-lg opacity-15 animate-float"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in-up">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-brand-100 text-brand-700 mb-6">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
                    Usage-Based API Platform
                </span>
            </div>

            <h1 class="animate-fade-in-up text-4xl sm:text-5xl lg:text-7xl font-jakarta font-extrabold text-gray-900 leading-tight mb-6">
                Fast & Affordable<br>
                <span class="bg-gradient-to-r from-indigo-600 via-brand-600 to-purple-600 bg-clip-text text-transparent">APIs for Developers</span>
            </h1>

            <p class="animate-fade-in-up-delay-1 max-w-2xl mx-auto text-lg sm:text-xl text-gray-600 mb-10 leading-relaxed">
                Pay only for what you use. Access powerful APIs with transparent pricing, automatic key rotation, and multi-service support — all in one platform.
            </p>

            <div class="animate-fade-in-up-delay-2 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl hover:from-indigo-700 hover:to-purple-700 shadow-xl shadow-purple-500/25 transition-all duration-300 hover:shadow-purple-500/40 hover:-translate-y-0.5">
                    Get Started Free
                </a>
                <a href="#" class="w-full sm:w-auto px-8 py-4 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-2xl hover:border-brand-300 hover:text-brand-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5">
                    View Documentation
                </a>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-jakarta font-bold text-gray-900 mb-4">Everything You Need</h2>
                <p class="max-w-2xl mx-auto text-lg text-gray-600">Powerful features designed to make API integration seamless and cost-effective.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1: Key Rotation -->
                <div class="group p-8 rounded-2xl border border-gray-100 hover:border-brand-200 bg-white hover:bg-gradient-to-br hover:from-brand-50 hover:to-purple-50 transition-all duration-300 hover:shadow-xl hover:shadow-brand-500/10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-jakarta font-bold text-gray-900 mb-3">Key Rotation</h3>
                    <p class="text-gray-600 leading-relaxed">Automatic API key rotation ensures maximum uptime and distributes load evenly across multiple source keys.</p>
                </div>

                <!-- Feature 2: Usage-Based Pricing -->
                <div class="group p-8 rounded-2xl border border-gray-100 hover:border-brand-200 bg-white hover:bg-gradient-to-br hover:from-brand-50 hover:to-purple-50 transition-all duration-300 hover:shadow-xl hover:shadow-brand-500/10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-pink-500 flex items-center justify-center mb-6 shadow-lg shadow-pink-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-jakarta font-bold text-gray-900 mb-3">Usage-Based Pricing</h3>
                    <p class="text-gray-600 leading-relaxed">Pay only for the API calls you make. No monthly fees, no hidden charges — just transparent per-request pricing.</p>
                </div>

                <!-- Feature 3: Multi-Service Support -->
                <div class="group p-8 rounded-2xl border border-gray-100 hover:border-brand-200 bg-white hover:bg-gradient-to-br hover:from-brand-50 hover:to-purple-50 transition-all duration-300 hover:shadow-xl hover:shadow-brand-500/10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-jakarta font-bold text-gray-900 mb-3">Multi-Service Support</h3>
                    <p class="text-gray-600 leading-relaxed">Access multiple API services through a single platform. One account, one API key, unlimited possibilities.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Pricing Section -->
    <section id="pricing" class="py-20 lg:py-32 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-jakarta font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="max-w-2xl mx-auto text-lg text-gray-600">Buy credits and use them across all services. No subscriptions, no surprises.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Starter -->
                <div class="pricing-card relative p-8 rounded-2xl border border-gray-200 bg-white">
                    <h3 class="text-lg font-jakarta font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="flex items-baseline mb-1">
                        <span class="text-4xl font-jakarta font-extrabold text-gray-900">$1</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">25,000 credits</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            25K API requests
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            All services included
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Never expires
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 text-sm font-semibold text-brand-600 border border-brand-200 rounded-xl hover:bg-brand-50 transition-colors">Get Started</a>
                </div>

                <!-- Developer -->
                <div class="pricing-card relative p-8 rounded-2xl border-2 border-brand-500 bg-white shadow-xl shadow-brand-500/10">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="px-3 py-1 text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full">Popular</span>
                    </div>
                    <h3 class="text-lg font-jakarta font-bold text-gray-900 mb-2">Developer</h3>
                    <div class="flex items-baseline mb-1">
                        <span class="text-4xl font-jakarta font-extrabold text-gray-900">$5</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">150,000 credits</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            150K API requests
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            All services included
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Priority support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-purple-500/25 transition-all">Get Started</a>
                </div>


                <!-- Business -->
                <div class="pricing-card relative p-8 rounded-2xl border border-gray-200 bg-white">
                    <h3 class="text-lg font-jakarta font-bold text-gray-900 mb-2">Business</h3>
                    <div class="flex items-baseline mb-1">
                        <span class="text-4xl font-jakarta font-extrabold text-gray-900">$20</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">750,000 credits</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            750K API requests
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            All services included
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Best value
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 text-sm font-semibold text-brand-600 border border-brand-200 rounded-xl hover:bg-brand-50 transition-colors">Get Started</a>
                </div>

                <!-- Enterprise -->
                <div class="pricing-card relative p-8 rounded-2xl border border-gray-200 bg-white">
                    <h3 class="text-lg font-jakarta font-bold text-gray-900 mb-2">Enterprise</h3>
                    <div class="flex items-baseline mb-1">
                        <span class="text-4xl font-jakarta font-extrabold text-gray-900">$100</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">5,000,000 credits</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            5M API requests
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            All services included
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Dedicated support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 text-sm font-semibold text-brand-600 border border-brand-200 rounded-xl hover:bg-brand-50 transition-colors">Get Started</a>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-brand-600 to-purple-700 animate-gradient"></div>
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-10 left-10 w-40 h-40 bg-white rounded-full blur-2xl animate-float"></div>
                    <div class="absolute bottom-10 right-10 w-60 h-60 bg-white rounded-full blur-3xl animate-float-delayed"></div>
                </div>
                <div class="relative px-8 py-16 sm:px-16 sm:py-20 text-center">
                    <h2 class="text-3xl sm:text-4xl font-jakarta font-bold text-white mb-4">Ready to Get Started?</h2>
                    <p class="max-w-xl mx-auto text-lg text-white/80 mb-8">Join thousands of developers already using XapiVerse to power their applications.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 text-base font-semibold text-brand-700 bg-white rounded-2xl hover:bg-gray-50 shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                        Create Free Account
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-3 mb-6 md:mb-0">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                        <span class="text-white font-bold text-xs">X</span>
                    </div>
                    <span class="font-jakarta font-bold text-lg text-white">XapiVerse</span>
                </div>
                <div class="flex items-center space-x-6 mb-6 md:mb-0">
                    <a href="#features" class="text-sm text-gray-400 hover:text-white transition-colors">Features</a>
                    <a href="#pricing" class="text-sm text-gray-400 hover:text-white transition-colors">Pricing</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Documentation</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Support</a>
                </div>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} XapiVerse. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
