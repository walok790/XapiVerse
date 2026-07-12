<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name', 'XapiVerse') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { 'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'], 'inter': ['Inter', 'sans-serif'] }, colors: { brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' } } } }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; } h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', sans-serif; } [x-cloak]{display:none !important;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-lg">X</span>
                </div>
                <span class="font-jakarta font-bold text-2xl text-gray-900">XapiVerse</span>
            </a>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8" x-data="{ role: '{{ old('role', 'developer') }}' }">
            <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Create your account</h2>
            <p class="text-gray-500 text-sm mb-6">Join XapiVerse and start using our APIs.</p>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">I am a</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="developer" x-model="role" class="sr-only peer">
                            <div class="p-3 border-2 rounded-lg text-center peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-colors">
                                <svg class="w-6 h-6 mx-auto mb-1 text-gray-500 peer-checked:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                <span class="text-sm font-medium text-gray-700">Developer</span>
                                <p class="text-xs text-gray-500 mt-0.5">Use APIs in my apps</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="user" x-model="role" class="sr-only peer">
                            <div class="p-3 border-2 rounded-lg text-center peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-colors">
                                <svg class="w-6 h-6 mx-auto mb-1 text-gray-500 peer-checked:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-sm font-medium text-gray-700">User</span>
                                <p class="text-xs text-gray-500 mt-0.5">Use platform tools</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="John Doe">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="Min 8 characters">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="Repeat password">
                </div>

                <button type="submit" class="w-full px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700 transition-colors">
                    Create Account
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <p class="text-center mt-6 text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand-600 font-medium hover:text-brand-700">Sign in</a>
        </p>
    </div>
</body>
</html>
