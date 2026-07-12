<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'XapiVerse') }}</title>
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
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-lg">X</span>
                </div>
                <span class="font-jakarta font-bold text-2xl text-gray-900">XapiVerse</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Welcome back</h2>
            <p class="text-gray-500 text-sm mb-6">Sign in as a Developer or User.</p>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            @if($isDemo && !empty($demoCredentials))
                <div class="mb-4 p-3 bg-brand-50 border border-brand-200 rounded-lg">
                    <p class="text-xs text-brand-700 font-semibold mb-2">Demo Credentials:</p>
                    @foreach($demoCredentials as $role => $creds)
                        <div class="flex items-center justify-between mb-1 last:mb-0">
                            <span class="text-xs font-medium text-brand-600 uppercase">{{ $role }}</span>
                            <span class="text-xs text-gray-600 font-mono">{{ $creds['email'] }} / {{ $creds['password'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"
                           placeholder="you@example.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"
                           placeholder="Enter your password">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>
                <button type="submit" class="w-full px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700 transition-colors">
                    Sign In
                </button>
            </form>
        </div>

        <div class="text-center mt-6 space-y-2">
            <p class="text-sm text-gray-600">
                Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 font-medium hover:text-brand-700">Create one</a>
            </p>
            <p class="text-sm text-gray-500">
                Admin? <a href="{{ route('admin.login') }}" class="text-red-600 font-medium hover:text-red-700">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
