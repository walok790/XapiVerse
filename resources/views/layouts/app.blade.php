<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'XapiVerse') - {{ config('app.name', 'XapiVerse') }}</title>

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
        :root {
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(124, 58, 237, 0.3); }
            50% { box-shadow: 0 0 20px rgba(124, 58, 237, 0.6); }
        }
        @keyframes counter-animate {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-slide-in-left { animation: slideInLeft 0.4s ease-out forwards; }
        .animate-slide-in-right { animation: slideInRight 0.4s ease-out forwards; }

        .nav-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-item:hover {
            transform: translateX(4px);
        }
        .nav-item.active {
            animation: pulse-glow 2s infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }

        .toast-slide-in {
            animation: slideInRight 0.4s ease-out forwards;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: false, showToast: false, toastMessage: '', toastType: 'success' }">
    @auth
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:z-30"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           @keydown.escape.window="sidebarOpen = false">

        <!-- Brand Logo Area -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-purple-500/30 group-hover:shadow-purple-500/50 transition-shadow duration-300">
                    <span class="text-white font-bold text-sm">X</span>
                </div>
                <span class="font-jakarta font-bold text-xl text-white tracking-tight">XapiVerse</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>


        <!-- Navigation Links -->
        <nav class="px-4 py-6 space-y-1 overflow-y-auto h-[calc(100vh-10rem)] custom-scrollbar">
            @if(auth()->user()->isAdmin())
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Administration</p>

            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                Dashboard
            </a>

            <a href="{{ route('admin.services.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.services.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.services.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                API Services
            </a>

            <a href="{{ route('admin.source-keys.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.source-keys.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.source-keys.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                Source Keys
            </a>


            <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.users.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                Users
            </a>

            <a href="{{ route('admin.logs.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.logs.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.logs.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                Request Logs
            </a>

            <a href="{{ route('admin.settings.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.settings.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                Settings
            </a>
            @endif


            @if(auth()->user()->isDeveloper())
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 mt-6">Developer</p>

            <a href="{{ route('developer.dashboard') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('developer.dashboard') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('developer.dashboard') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                Dashboard
            </a>

            <a href="{{ route('developer.api-keys.index') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('developer.api-keys.*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('developer.api-keys.*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                API Keys
            </a>

            <a href="{{ route('developer.docs') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('developer.docs*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('developer.docs*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                Documentation
            </a>

            <a href="{{ route('developer.credits') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('developer.credits*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('developer.credits*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                Credits
            </a>
            @endif


            @if(auth()->user()->isUser())
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 mt-6">Platform</p>

            <a href="{{ route('user.dashboard') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('user.dashboard') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('user.dashboard') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                Dashboard
            </a>

            <a href="{{ route('user.player') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('user.player*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('user.player*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                TeraBox Player
            </a>

            <a href="{{ route('user.profile') }}" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('user.profile*') ? 'active bg-gradient-to-r from-brand-600/20 to-purple-600/10 text-white border border-brand-500/30' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('user.profile*') ? 'bg-brand-600/30' : 'bg-gray-800 group-hover:bg-gray-700' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                Profile
            </a>
            @endif
        </nav>

        <!-- User Info at Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800 bg-gray-900/95 backdrop-blur-sm"
             x-data="{ userMenu: false }">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 cursor-pointer" @click="userMenu = !userMenu">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                        <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-gray-800 transition-all duration-200" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    @endauth


    <!-- Main Content Area -->
    <main class="@auth lg:ml-72 @endauth min-h-screen">
        @auth
        <!-- Top Bar -->
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200/50">
            <div class="flex items-center justify-between px-6 h-16">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <!-- Breadcrumb -->
                    <nav class="hidden sm:flex items-center space-x-2 text-sm">
                        <span class="text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-600 font-medium">@yield('title', 'Dashboard')</span>
                    </nav>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </header>
        @endauth


        <!-- Toast Notifications -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="fixed top-20 right-6 z-50 toast-slide-in">
            <div class="flex items-center space-x-3 px-5 py-4 bg-white border border-green-200 rounded-2xl shadow-xl shadow-green-500/10">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="fixed top-20 right-6 z-50 toast-slide-in">
            <div class="flex items-center space-x-3 px-5 py-4 bg-white border border-red-200 rounded-2xl shadow-xl shadow-red-500/10">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">{{ session('error') }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Page Content with fade-in animation -->
        <div class="animate-fade-in-up">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
