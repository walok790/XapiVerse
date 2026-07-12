@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8" x-data="{ loading: false }">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Database Setup</h2>
    <p class="text-gray-500 text-sm mb-2">Enter your MySQL database credentials.</p>

    @if($mode === 'demo')
        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
            <p class="text-sm text-orange-800"><strong>Demo Mode:</strong> After connecting, tables + demo data will be imported automatically. You'll be redirected to login.</p>
        </div>
    @else
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800"><strong>Business Mode:</strong> After connecting, clean tables will be created. Then you'll set up your super admin account.</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.save-database') }}" class="space-y-5" @submit="loading = true">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="db_host" class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                <input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required :disabled="loading"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
            </div>
            <div>
                <label for="db_port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <input type="number" id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" required :disabled="loading"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
            </div>
        </div>

        <div>
            <label for="db_name" class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
            <input type="text" id="db_name" name="db_name" value="{{ old('db_name', 'xapiverse_db') }}" required :disabled="loading"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
            <p class="mt-1 text-xs text-gray-500">Will be created automatically if it doesn't exist.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="db_user" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="db_user" name="db_user" value="{{ old('db_user', 'root') }}" required :disabled="loading"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
            </div>
            <div>
                <label for="db_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="db_password" name="db_password" value="{{ old('db_password') }}" :disabled="loading"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none disabled:bg-gray-100 disabled:text-gray-400"
                       placeholder="Empty if none">
            </div>
        </div>

        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
            <p class="text-xs text-gray-600"><strong>XAMPP:</strong> Host=<code>127.0.0.1</code> Port=<code>3306</code> User=<code>root</code> Pass=<em>(empty)</em></p>
        </div>

        <!-- Bottom Loading Bar (shows when installing) -->
        <div x-show="loading" x-cloak class="mt-4 p-4 bg-brand-50 border border-brand-200 rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-brand-800">Installing database...</span>
                <span class="text-xs text-brand-600">Please wait</span>
            </div>
            <!-- Animated progress bar -->
            <div class="w-full h-2 bg-brand-100 rounded-full overflow-hidden">
                <div class="h-full bg-brand-600 rounded-full animate-pulse" style="width: 100%; animation: progressMove 2s ease-in-out infinite;"></div>
            </div>
            <p class="text-xs text-brand-600 mt-2">Creating tables and importing data. Do not close this page.</p>
        </div>

        <div class="flex justify-between pt-2">
            <a href="{{ route('install.mode') }}" x-show="!loading" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" :disabled="loading" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="!loading">
                    <span class="inline-flex items-center">
                        @if($mode === 'demo')
                            Install & Finish
                        @else
                            Import & Next
                        @endif
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </template>
                <template x-if="loading">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Installing...
                    </span>
                </template>
            </button>
        </div>
    </form>
</div>

<style>
    @keyframes progressMove {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(0%); }
        100% { transform: translateX(100%); }
    }
</style>
@endsection
