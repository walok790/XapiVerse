@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-brand-600 mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Users
        </a>
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Edit User: {{ $user->name }}</h1>
        <p class="mt-1 text-gray-500">Update user details and manage credits.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <ul class="text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit User Details --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 font-jakarta">User Details</h2>
        </div>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="developer" {{ old('role', $user->role) == 'developer' ? 'selected' : '' }}>Developer</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 mr-3">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                    Update User
                </button>
            </div>
        </form>
    </div>

    {{-- Add Credits Section --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 font-jakarta">Add Credits</h2>
            <p class="text-sm text-gray-500 mt-1">Current balance: <span class="font-semibold text-gray-900">{{ number_format($user->credits_balance ?? 0, 2) }}</span></p>
        </div>
        <form action="{{ route('admin.users.add-credits', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="credits" class="block text-sm font-medium text-gray-700 mb-1">Credits Amount</label>
                    <input type="number" name="credits" id="credits" min="1" step="0.01" required placeholder="Enter credits amount"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <input type="text" name="notes" id="notes" placeholder="Reason for adding credits"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    Add Credits
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
