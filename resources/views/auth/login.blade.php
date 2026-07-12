<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'XapiVerse') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { 'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'], 'inter': ['Inter', 'sans-serif'] }, colors: { brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' } } } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }


        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(5px) rotate(-1deg); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.4); opacity: 0; }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 8s ease-in-out 2s infinite; }
        .animate-float-slow { animation: float 10s ease-in-out 1s infinite; }
        .gradient-animate {
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }
        .input-float-label {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Left Side - Gradient with animated shapes -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-brand-700 gradient-animate">
        <!-- Floating shapes -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-32 h-32 bg-white/10 rounded-full blur-xl animate-float"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-white/10 rounded-full blur-lg animate-float-delayed"></div>
            <div class="absolute bottom-32 left-32 w-40 h-40 bg-white/5 rounded-full blur-2xl animate-float-slow"></div>
            <div class="absolute bottom-20 right-40 w-20 h-20 bg-white/10 rounded-2xl rotate-45 blur-sm animate-float"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        </div>
        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center px-12 text-white">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                    <span class="text-white font-bold text-xl">X</span>
                </div>
                <span class="font-jakarta font-bold text-3xl">XapiVerse</span>
            </div>
            <h1 class="font-jakarta text-4xl font-bold mb-4 leading-tight">Manage your APIs<br/>with confidence.</h1>
            <p class="text-white/70 text-lg max-w-md">A powerful API management platform that lets you proxy, monitor, and control access to your services.</p>
            <div class="mt-12 flex items-center space-x-6">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm text-white/80">Key Rotation</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm text-white/80">Usage Analytics</span>
                </div>
            </div>
        </div>
    </div>


    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md" x-data="{ focused: '' }">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="/" class="inline-flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <span class="text-white font-bold text-lg">X</span>
                    </div>
                    <span class="font-jakarta font-bold text-2xl text-gray-900">XapiVerse</span>
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
                <h2 class="font-jakarta text-2xl font-bold text-gray-900 mb-1">Welcome back</h2>
                <p class="text-gray-500 text-sm mb-8">Sign in as a Developer or User to continue.</p>

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start space-x-3">
                    <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
                @endif

                @if($isDemo && !empty($demoCredentials))
                <div class="mb-6 p-4 bg-gradient-to-r from-brand-50 to-purple-50 border border-brand-100 rounded-2xl">
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="w-5 h-5 rounded-full bg-brand-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-brand-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-700 uppercase tracking-wide">Demo Credentials</span>
                    </div>
                    @foreach($demoCredentials as $role => $creds)
                    <div class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-brand-100' : '' }}">
                        <span class="text-xs font-semibold text-brand-600 uppercase">{{ $role }}</span>
                        <span class="text-xs text-gray-600 font-mono bg-white px-2 py-0.5 rounded-md">{{ $creds['email'] }} / {{ $creds['password'] }}</span>
                    </div>
                    @endforeach
                </div>
                @endif


                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <!-- Email -->
                    <div class="relative">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   @focus="focused = 'email'" @blur="focused = ''"
                                   class="w-full pl-11 pr-4 py-3 border-2 rounded-xl text-sm transition-all duration-200 outline-none"
                                   :class="focused === 'email' ? 'border-brand-500 ring-4 ring-brand-500/10 bg-white' : 'border-gray-200 bg-gray-50 hover:border-gray-300'"
                                   placeholder="you@example.com">
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                   @focus="focused = 'password'" @blur="focused = ''"
                                   class="w-full pl-11 pr-4 py-3 border-2 rounded-xl text-sm transition-all duration-200 outline-none"
                                   :class="focused === 'password' ? 'border-brand-500 ring-4 ring-brand-500/10 bg-white' : 'border-gray-200 bg-gray-50 hover:border-gray-300'"
                                   placeholder="Enter your password">
                        </div>
                    </div>
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 transition">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>
                    <!-- Submit -->
                    <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40">
                        Sign In
                    </button>
                </form>
            </div>

            <div class="text-center mt-8 space-y-3">
                <p class="text-sm text-gray-600">
                    Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:text-brand-700 transition-colors">Create one</a>
                </p>
                <p class="text-sm text-gray-500">
                    Admin? <a href="{{ route('admin.login') }}" class="text-red-600 font-medium hover:text-red-700 transition-colors">Login here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
