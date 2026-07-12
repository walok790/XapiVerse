@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8" x-data="{ tab: 'auto' }">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Database Setup</h2>
    <p class="text-gray-500 text-sm mb-6">Connect to your MySQL/MariaDB database. Tables will be created automatically.</p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Tabs: Auto Import / Manual Import -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-6">
            <button @click="tab = 'auto'" :class="tab === 'auto' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-3 px-1 border-b-2 font-medium text-sm transition-colors">
                Auto Import
            </button>
            <button @click="tab = 'manual'" :class="tab === 'manual' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-3 px-1 border-b-2 font-medium text-sm transition-colors">
                Manual Import
            </button>
        </nav>
    </div>

    <!-- Auto Import Tab -->
    <div x-show="tab === 'auto'">
        <p class="text-sm text-gray-600 mb-4">Enter your database credentials. The installer will automatically create the database (if needed) and import all tables.</p>

        <form method="POST" action="{{ route('install.save-database') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="db_host" class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                    <input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="127.0.0.1 or localhost">
                </div>
                <div>
                    <label for="db_port" class="block text-sm font-medium text-gray-700 mb-1">Database Port</label>
                    <input type="number" id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="3306">
                </div>
            </div>

            <div>
                <label for="db_name" class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                <input type="text" id="db_name" name="db_name" value="{{ old('db_name', 'xapiverse_db') }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                       placeholder="xapiverse_db">
                <p class="mt-1 text-xs text-gray-500">Database will be created automatically if it doesn't exist.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="db_user" class="block text-sm font-medium text-gray-700 mb-1">Database Username</label>
                    <input type="text" id="db_user" name="db_user" value="{{ old('db_user', 'root') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="root">
                </div>
                <div>
                    <label for="db_password" class="block text-sm font-medium text-gray-700 mb-1">Database Password</label>
                    <input type="password" id="db_password" name="db_password" value="{{ old('db_password') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="Leave empty if no password">
                </div>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>XAMPP Users:</strong> Default — Host: <code>127.0.0.1</code>, Port: <code>3306</code>, Username: <code>root</code>, Password: <em>(empty)</em>
                </p>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                    Connect & Import Tables
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Manual Import Tab -->
    <div x-show="tab === 'manual'" x-cloak>
        <p class="text-sm text-gray-600 mb-6">If auto import doesn't work, follow these steps to manually import the database.</p>

        <!-- Step 1: Download SQL -->
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">1 - Download the SQL file</h3>
            <a href="{{ route('install.download-sql') }}" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download SQL file
            </a>
        </div>

        <!-- Step 2: Instructions -->
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">2 - Follow these steps</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-brand-700 font-bold text-sm">1</span>
                    </div>
                    <p class="text-xs text-gray-600">Go to your <strong>phpMyAdmin</strong></p>
                    <p class="text-xs text-gray-400 mt-1">http://localhost/phpmyadmin</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-brand-700 font-bold text-sm">2</span>
                    </div>
                    <p class="text-xs text-gray-600">Create database <strong>xapiverse_db</strong></p>
                    <p class="text-xs text-gray-400 mt-1">Collation: utf8mb4_unicode_ci</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-brand-700 font-bold text-sm">3</span>
                    </div>
                    <p class="text-xs text-gray-600">Click <strong>Import</strong> tab</p>
                    <p class="text-xs text-gray-400 mt-1">Select the downloaded SQL file</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-brand-700 font-bold text-sm">4</span>
                    </div>
                    <p class="text-xs text-gray-600">Click <strong>Go</strong> to import</p>
                    <p class="text-xs text-gray-400 mt-1">Then come back here</p>
                </div>
            </div>
        </div>

        <!-- Step 3: After manual import, still need to save DB config -->
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
            <p class="text-sm text-yellow-800">
                <strong>After importing:</strong> You still need to enter your database details below so the application can connect.
            </p>
        </div>

        <form method="POST" action="{{ route('install.save-database') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="manual_import" value="1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                    <input type="text" name="db_host" value="127.0.0.1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                    <input type="number" name="db_port" value="3306" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                <input type="text" name="db_name" value="xapiverse_db" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="db_user" value="root" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="db_password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none" placeholder="Empty if none">
                </div>
            </div>

            <div class="flex justify-between pt-2">
                <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                    Verify Connection & Continue
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
