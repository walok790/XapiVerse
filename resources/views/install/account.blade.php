@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Create Super Admin</h2>
    <p class="text-gray-500 text-sm mb-6">Set up your admin account to manage the platform.</p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.save-account') }}" class="space-y-5">
        @csrf

        <div>
            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', 'XapiVerse') }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
        </div>

        <hr class="border-gray-200">

        <div>
            <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-1">Admin Full Name</label>
            <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"
                   placeholder="John Doe">
        </div>

        <div>
            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
            <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"
                   placeholder="admin@yourdomain.com">
        </div>

        <div>
            <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-1">Admin Password</label>
            <input type="password" id="admin_password" name="admin_password" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"
                   placeholder="Minimum 8 characters">
        </div>

        <div class="flex justify-between pt-2">
            <a href="{{ route('install.database') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                Create Admin & Finish
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
