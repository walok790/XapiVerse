<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ config('app.name', 'XapiVerse') }}</title>
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


        @keyframes border-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(220, 38, 38, 0.2), 0 0 30px rgba(220, 38, 38, 0.1); }
            50% { box-shadow: 0 0 25px rgba(220, 38, 38, 0.4), 0 0 50px rgba(220, 38, 38, 0.2); }
        }
        @keyframes subtle-pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.5; }
        }
        .card-glow {
            animation: border-glow 3s ease-in-out infinite;
        }
        .dot-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .shield-glow {
            animation: subtle-pulse 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="absolute inset-0 dot-pattern opacity-50"></div>

    <!-- Subtle gradient orbs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-900/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-900/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md relative z-10" x-data="{ focused: '' }">
        <!-- Logo & Shield -->
        <div class="text-center mb-8">
            <div class="inline-flex flex-col items-center">
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-red-500/20 rounded-2xl blur-xl shield-glow"></div>
                    <div class="relative w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-2xl flex items-center justify-center shadow-2xl shadow-red-500/30 border border-red-400/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <span class="font-jakarta font-bold text-2xl text-white">XapiVerse</span>
                <span class="text-red-400 text-xs font-semibold uppercase tracking-widest mt-1">Administrator Access</span>
            </div>
        </div>


        <!-- Login Card -->
        <div class="card-glow bg-gray-900 rounded-3xl border border-red-500/20 p-8 relative overflow-hidden">
            <!-- Subtle inner glow -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-red-500/50 to-transparent"></div>

            <h2 class="font-jakarta text-xl font-bold text-white mb-1">Admin Login</h2>
            <p class="text-gray-500 text-sm mb-6">Access the administrative control panel.</p>

            @if($errors->any())
            <div class="mb-5 p-4 bg-red-950/50 border border-red-800/50 rounded-2xl flex items-start space-x-3">
                <div class="w-5 h-5 rounded-full bg-red-900/50 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <p class="text-sm text-red-300">{{ $errors->first() }}</p>
            </div>
            @endif

            @if($isDemo && !empty($demoCredentials))
            <div class="mb-5 p-4 bg-amber-950/30 border border-amber-700/30 rounded-2xl">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span class="text-xs font-bold text-amber-300 uppercase tracking-wide">Demo Credentials</span>
                </div>
                @foreach($demoCredentials as $role => $creds)
                <div class="flex items-center justify-between py-1">
                    <span class="text-xs font-medium text-amber-400 uppercase">{{ $role }}</span>
                    <span class="text-xs text-gray-400 font-mono bg-gray-800 px-2 py-0.5 rounded">{{ $creds['email'] }} / {{ $creds['password'] }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           @focus="focused = 'email'" @blur="focused = ''"
                           class="w-full px-4 py-3 bg-gray-800/50 border-2 rounded-xl text-sm text-white placeholder-gray-500 transition-all duration-200 outline-none"
                           :class="focused === 'email' ? 'border-red-500/50 ring-4 ring-red-500/10' : 'border-gray-700 hover:border-gray-600'"
                           placeholder="admin@yourdomain.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           @focus="focused = 'password'" @blur="focused = ''"
                           class="w-full px-4 py-3 bg-gray-800/50 border-2 rounded-xl text-sm text-white placeholder-gray-500 transition-all duration-200 outline-none"
                           :class="focused === 'password' ? 'border-red-500/50 ring-4 ring-red-500/10' : 'border-gray-700 hover:border-gray-600'"
                           placeholder="Enter admin password">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-red-600 bg-gray-800 border-gray-600 rounded focus:ring-red-500 focus:ring-offset-gray-900">
                    <span class="ml-2 text-sm text-gray-400">Remember me</span>
                </div>
                <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold rounded-xl hover:from-red-700 hover:to-red-800 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-red-500/20 hover:shadow-red-500/30 border border-red-500/20">
                    Sign In to Admin Panel
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-sm text-gray-500">
            Not an admin? <a href="{{ route('login') }}" class="text-brand-400 font-medium hover:text-brand-300 transition-colors">User/Developer Login</a>
        </p>
    </div>
</body>
</html>
