@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8" x-data="{ loading: false }">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Database Setup</h2>
    <p class="text-gray-500 text-sm mb-4">Enter your MySQL credentials below.</p>

    @if($mode === 'demo')
        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg text-sm text-orange-800">
            <strong>Demo Mode:</strong> Tables + demo data will be imported. You'll go straight to login.
        </div>
    @else
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
            <strong>Business Mode:</strong> Clean tables created. Next step: create your admin account.
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.save-database') }}" @submit="setTimeout(() => { loading = true }, 50)" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                <input type="text" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required :readonly="loading"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" :class="loading && 'opacity-50 bg-gray-100'">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <input type="number" name="db_port" value="{{ old('db_port', '3306') }}" required :readonly="loading"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" :class="loading && 'opacity-50 bg-gray-100'">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
            <input type="text" name="db_name" value="{{ old('db_name', 'xapiverse_db') }}" required :readonly="loading"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" :class="loading && 'opacity-50 bg-gray-100'">
            <p class="mt-1 text-xs text-gray-400">Auto-created if it doesn't exist.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="db_user" value="{{ old('db_user', 'root') }}" required :readonly="loading"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" :class="loading && 'opacity-50 bg-gray-100'">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="db_password" value="{{ old('db_password') }}" :readonly="loading"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" :class="loading && 'opacity-50 bg-gray-100'" placeholder="Empty if none">
            </div>
        </div>

        <div class="p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500"><strong>XAMPP default:</strong> Host <code>127.0.0.1</code> · Port <code>3306</code> · User <code>root</code> · Password <em>(empty)</em></p>
        </div>

        <!-- Loading indicator -->
        <div x-show="loading" x-cloak class="p-4 bg-brand-50 border border-brand-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-brand-600 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-brand-800">Installing database...</p>
                    <p class="text-xs text-brand-600">Creating tables. Please wait, don't close this page.</p>
                </div>
            </div>
            <div class="mt-3 w-full h-1.5 bg-brand-100 rounded-full overflow-hidden">
                <div class="h-full bg-brand-600 rounded-full animate-pulse w-full"></div>
            </div>
        </div>

        <div class="flex justify-between pt-2">
            <a href="{{ route('install.mode') }}" x-show="!loading" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
            </a>
            <button type="submit" :disabled="loading" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <span x-show="!loading">{{ $mode === 'demo' ? 'Install & Finish' : 'Import & Next' }}</span>
                <span x-show="loading" x-cloak>Installing...</span>
                <svg x-show="!loading" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
