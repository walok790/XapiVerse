@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8">
        <h1 class="font-jakarta text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-500 mt-1">Your platform dashboard.</p>
    </div>

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-brand-600 to-brand-800 rounded-2xl p-8 text-white mb-8">
        <h2 class="font-jakarta text-xl font-bold mb-2">Welcome to XapiVerse</h2>
        <p class="text-brand-100 mb-4">Access powerful tools and services on our platform.</p>
        <div class="flex space-x-3">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-white text-brand-700 text-sm font-medium rounded-lg hover:bg-brand-50 transition-colors">
                Explore Services
            </a>
        </div>
    </div>

    <!-- Available Services -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-jakarta text-lg font-semibold text-gray-900 mb-4">Available Services</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse(\App\Models\ApiService::where('is_active', true)->where('is_public', true)->get() as $service)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-brand-300 transition-colors">
                    <h4 class="font-semibold text-gray-900">{{ $service->name }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($service->description, 80) }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No services available yet. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
