@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Admin Account & Site Setup</h2>
    <p class="text-gray-500 text-sm mb-6">Create your administrator account and configure basic site settings.</p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.save-admin') }}" class="space-y-5">
        @csrf

        <div class="pb-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Site Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="{{ old('site_name', 'XapiVerse') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="XapiVerse">
                </div>
                <div>
                    <label for="site_url" class="block text-sm font-medium text-gray-700 mb-1">Site URL</label>
                    <input type="url" id="site_url" name="site_url" value="{{ old('site_url', 'http://localhost') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors"
                           placeholder="https://yourdomain.com">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Admin Account</h3>
            <div class="space-y-4">
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
                           placeholder="admin@yourdomain.com">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('install.database') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Complete Installation
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
