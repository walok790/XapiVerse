<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ config('app.name', 'XapiVerse') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { 'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'], 'inter': ['Inter', 'sans-serif'] }, colors: { brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' } } } }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; } h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="font-jakarta font-bold text-2xl text-white">XapiVerse</span>
            </a>
            <p class="text-gray-400 mt-2 text-sm">Administrator Access</p>
        </div>

        <!-- Admin Login Card -->
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-8">
            <h2 class="font-jakarta text-xl font-bold text-white mb-1">Admin Login</h2>
            <p class="text-gray-400 text-sm mb-6">Access the admin control panel.</p>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-900/50 border border-red-700 rounded-lg">
                    <p class="text-sm text-red-300">{{ $errors->first() }}</p>
                </div>
            @endif

            @if($isDemo && !empty($demoCredentials))
                <div class="mb-4 p-3 bg-orange-900/30 border border-orange-700/50 rounded-lg">
                    <p class="text-xs text-orange-300 font-semibold mb-1">Demo Credentials:</p>
                    @foreach($demoCredentials as $role => $creds)
                        <p class="text-xs text-orange-200 font-mono">{{ $creds['email'] }} / {{ $creds['password'] }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-sm text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none"
                           placeholder="admin@yourdomain.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-sm text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none"
                           placeholder="Enter admin password">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-red-600 bg-gray-700 border-gray-600 rounded focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-400">Remember me</span>
                </div>
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Sign In to Admin Panel
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-500">
            Not an admin? <a href="{{ route('login') }}" class="text-brand-400 font-medium hover:text-brand-300">User/Developer Login</a>
        </p>
    </div>
</body>
</html>
