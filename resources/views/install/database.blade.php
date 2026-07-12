@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Database Configuration</h2>
    <p class="text-gray-500 text-sm mb-6">Enter your MySQL/MariaDB database credentials.</p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

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
            <input type="text" id="db_name" name="db_name" value="{{ old('db_name') }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                   placeholder="xapiverse_db">
            <p class="mt-1 text-xs text-gray-500">The database must already exist. Create it manually first if needed.</p>
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
                <strong>XAMPP Users:</strong> Default settings are Host: <code>127.0.0.1</code>, Port: <code>3306</code>, Username: <code>root</code>, Password: <em>(empty)</em>
            </p>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Connect & Migrate
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
