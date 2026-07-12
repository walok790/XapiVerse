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
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }


        @keyframes float-logo {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        @keyframes checkmark-pop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .animate-float-logo { animation: float-logo 3s ease-in-out infinite; }
        .animate-pulse-ring { animation: pulse-ring 2s ease-out infinite; }
        .animate-checkmark { animation: checkmark-pop 0.3s ease-out forwards; }
        .dot-bg {
            background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 relative">
    <!-- Dot Pattern Background -->
    <div class="absolute inset-0 dot-bg opacity-50"></div>

    <div class="w-full max-w-3xl relative z-10">
        <!-- Logo with float animation -->
        <div class="text-center mb-10">
            <div class="inline-flex flex-col items-center">
                <div class="animate-float-logo mb-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-500/30 border border-purple-400/20">
                        <span class="text-white font-bold text-2xl">X</span>
                    </div>
                </div>
                <span class="font-jakarta font-bold text-2xl text-gray-900">XapiVerse</span>
                <p class="text-gray-500 mt-1 text-sm">Installation Wizard</p>
            </div>
        </div>

        <!-- Steps Indicator -->
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
            $progressPercent = ($currentStep / (count($steps) - 1)) * 100;
        @endphp


        <div class="mb-10 px-4">
            <div class="flex items-center justify-between relative">
                {{-- Progress line background --}}
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 rounded-full"></div>
                {{-- Progress line filled --}}
                <div class="absolute top-4 left-0 h-0.5 bg-gradient-to-r from-green-500 to-brand-500 rounded-full transition-all duration-700 ease-out" style="width: {{ $progressPercent }}%"></div>

                @foreach($steps as $index => $step)
                <div class="relative flex flex-col items-center z-10">
                    @if($index < $currentStep)
                    {{-- Completed --}}
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center shadow-lg shadow-green-500/30 animate-checkmark">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    @elseif($index === $currentStep)
                    {{-- Current step with glow ring --}}
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-brand-400 animate-pulse-ring opacity-30"></div>
                        <div class="relative w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-lg shadow-brand-500/40 ring-4 ring-brand-100">
                            <span class="text-white font-bold text-xs">{{ $index + 1 }}</span>
                        </div>
                    </div>
                    @else
                    {{-- Upcoming --}}
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-100">
                        <span class="text-gray-400 font-semibold text-xs">{{ $index + 1 }}</span>
                    </div>
                    @endif
                    <span class="text-[11px] mt-2 whitespace-nowrap font-medium {{ $index === $currentStep ? 'text-brand-600' : ($index < $currentStep ? 'text-green-600' : 'text-gray-400') }}">{{ $step['name'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Progress text --}}
            <div class="text-center mt-4">
                <span class="text-xs text-gray-500">Step {{ $currentStep + 1 }} of {{ count($steps) }}</span>
                <span class="text-xs text-gray-400 mx-1">&middot;</span>
                <span class="text-xs font-medium text-brand-600">{{ round($progressPercent) }}% complete</span>
            </div>
        </div>


        <!-- Content Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden transition-shadow duration-300 hover:shadow-2xl hover:shadow-gray-200/70"
             x-data x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-400">XapiVerse v1.0.0</p>
        </div>
    </div>
</body>
</html>
