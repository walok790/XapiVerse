@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8 text-center">
    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h2 class="font-jakarta text-2xl font-bold text-gray-900 mb-2">Installation Complete!</h2>
    <p class="text-gray-500 mb-6">XapiVerse has been successfully installed in <strong>{{ $mode }}</strong> mode.</p>

    @if($mode === 'demo' && !empty($demoCredentials))
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 text-left mb-6 max-w-lg mx-auto">
        <h3 class="text-sm font-bold text-orange-800 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            Demo Login Credentials
        </h3>
        <div class="space-y-2">
            @foreach($demoCredentials as $role => $creds)
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-orange-100">
                <span class="text-xs font-semibold uppercase text-orange-700">{{ $role }}</span>
                <span class="text-xs text-gray-600 font-mono">{{ $creds['email'] }} / {{ $creds['password'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($mode === 'business' && !empty($accounts))
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-left mb-6 max-w-lg mx-auto">
        <h3 class="text-sm font-bold text-blue-800 mb-3">Accounts Created</h3>
        <div class="space-y-2">
            @if(!empty($accounts['admin']))
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-blue-100">
                <span class="text-xs font-semibold uppercase text-blue-700">Admin</span>
                <span class="text-xs text-gray-600">{{ $accounts['admin']['email'] }}</span>
            </div>
            @endif
            @if(!empty($accounts['developer']))
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-blue-100">
                <span class="text-xs font-semibold uppercase text-blue-700">Developer</span>
                <span class="text-xs text-gray-600">{{ $accounts['developer']['email'] }}</span>
            </div>
            @endif
            @if(!empty($accounts['user']))
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-blue-100">
                <span class="text-xs font-semibold uppercase text-blue-700">User</span>
                <span class="text-xs text-gray-600">{{ $accounts['user']['email'] }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Login Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-6">
        <a href="/admin/login" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors w-full sm:w-auto justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Login as Admin
        </a>
        <a href="/login" class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors w-full sm:w-auto justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            Login as Developer
        </a>
        <a href="/login" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors w-full sm:w-auto justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Login as User
        </a>
    </div>

    <div class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-left max-w-lg mx-auto">
        <p class="text-xs text-yellow-800">
            <strong>Security:</strong> The installer is now locked. Delete <code>storage/installed/installed.lock</code> to reinstall.
        </p>
    </div>
</div>
@endsection
