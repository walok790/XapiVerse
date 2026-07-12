<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ rtrim(url('/'), '/') }}">
    <title>Install - XapiVerse</title>
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
    <style>body{font-family:'Inter',sans-serif}h1,h2,h3,h4,h5,h6{font-family:'Plus Jakarta Sans',sans-serif}[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-lg">X</span>
                </div>
                <span class="font-jakarta font-bold text-2xl text-gray-900">XapiVerse</span>
            </div>
            <p class="text-gray-500 mt-2">Installation Wizard</p>
        </div>

        <!-- Steps -->
        @php
            $steps = [
                ['name' => 'Requirements', 'route' => 'install.requirements'],
                ['name' => 'Permissions', 'route' => 'install.permissions'],
                ['name' => 'Mode', 'route' => 'install.mode'],
                ['name' => 'Database', 'route' => 'install.database'],
                ['name' => 'Account', 'route' => 'install.account'],
            ];
            $currentStep = collect($steps)->search(fn($s) => request()->routeIs($s['route']));
            if ($currentStep === false) $currentStep = 0;
        @endphp
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach($steps as $index => $step)
                    <div class="flex items-center {{ $index < count($steps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $index < $currentStep ? 'bg-green-500 text-white' : ($index === $currentStep ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                                @if($index < $currentStep)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <span class="text-xs mt-1 whitespace-nowrap {{ $index === $currentStep ? 'text-brand-600 font-medium' : 'text-gray-500' }}">{{ $step['name'] }}</span>
                        </div>
                        @if($index < count($steps) - 1)
                            <div class="flex-1 h-0.5 mx-1 sm:mx-2 mt-[-12px] {{ $index < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @yield('content')
        </div>

        <div class="text-center mt-6 text-sm text-gray-400">XapiVerse v1.0.0</div>
    </div>
</body>
</html>
