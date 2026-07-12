@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="p-6 lg:p-8 space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-jakarta font-bold text-gray-900">Profile Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account information and security settings.</p>
    </div>

    <!-- Profile Information -->
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-brand-50 to-purple-50">
            <h2 class="text-lg font-jakarta font-semibold text-gray-900">Profile Information</h2>
            <p class="text-sm text-gray-500 mt-0.5">Update your name and email address.</p>
        </div>
        <div class="p-6 sm:p-8">
            @if(session('profile_success'))
            <div class="mb-6 flex items-center p-4 rounded-xl bg-green-50 border border-green-200">
                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('profile_success') }}</p>
            </div>
            @endif

            @if($errors->has('name') || $errors->has('email'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul class="text-sm text-red-700 space-y-1">
                        @if($errors->has('name'))<li>{{ $errors->first('name') }}</li>@endif
                        @if($errors->has('email'))<li>{{ $errors->first('email') }}</li>@endif
                    </ul>
                </div>
            </div>
            @endif


            <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                        placeholder="Enter your full name">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                        placeholder="Enter your email address">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-purple-500/25 transition-all duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Change Password -->
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-brand-50 to-purple-50">
            <h2 class="text-lg font-jakarta font-semibold text-gray-900">Change Password</h2>
            <p class="text-sm text-gray-500 mt-0.5">Ensure your account is using a strong, unique password.</p>
        </div>
        <div class="p-6 sm:p-8">
            @if(session('password_success'))
            <div class="mb-6 flex items-center p-4 rounded-xl bg-green-50 border border-green-200">
                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('password_success') }}</p>
            </div>
            @endif

            @if($errors->has('current_password') || $errors->has('password'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul class="text-sm text-red-700 space-y-1">
                        @if($errors->has('current_password'))<li>{{ $errors->first('current_password') }}</li>@endif
                        @if($errors->has('password'))<li>{{ $errors->first('password') }}</li>@endif
                    </ul>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('user.profile.password') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                        placeholder="Enter your current password">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                        placeholder="Enter your new password">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder-gray-400"
                        placeholder="Confirm your new password">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-purple-500/25 transition-all duration-200">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
