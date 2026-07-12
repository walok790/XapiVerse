@extends('layouts.app')

@section('title', 'Edit API Service')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8">
        <a href="{{ route('admin.services.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-brand-600 mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Services
        </a>
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Edit Service: {{ $service->name }}</h1>
        <p class="mt-1 text-gray-500">Update the API service configuration.</p>
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


    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">{{ old('description', $service->description) }}</textarea>
            </div>

            <div>
                <label for="base_url" class="block text-sm font-medium text-gray-700 mb-1">Base URL</label>
                <input type="url" name="base_url" id="base_url" value="{{ old('base_url', $service->base_url) }}" placeholder="https://api.example.com/v1"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="rotation_strategy" class="block text-sm font-medium text-gray-700 mb-1">Rotation Strategy</label>
                    <select name="rotation_strategy" id="rotation_strategy"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="round_robin" {{ old('rotation_strategy', $service->rotation_strategy) == 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                        <option value="priority" {{ old('rotation_strategy', $service->rotation_strategy) == 'priority' ? 'selected' : '' }}>Priority</option>
                        <option value="least_used" {{ old('rotation_strategy', $service->rotation_strategy) == 'least_used' ? 'selected' : '' }}>Least Used</option>
                        <option value="weighted" {{ old('rotation_strategy', $service->rotation_strategy) == 'weighted' ? 'selected' : '' }}>Weighted</option>
                        <option value="fill_rotate" {{ old('rotation_strategy', $service->rotation_strategy) == 'fill_rotate' ? 'selected' : '' }}>Fill & Rotate</option>
                    </select>
                </div>

                <div>
                    <label for="credits_per_request" class="block text-sm font-medium text-gray-700 mb-1">Credits Per Request</label>
                    <input type="number" name="credits_per_request" id="credits_per_request" value="{{ old('credits_per_request', $service->credits_per_request) }}" min="0" step="0.01"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="rate_limit_per_minute" class="block text-sm font-medium text-gray-700 mb-1">Rate Limit / Minute</label>
                    <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute" value="{{ old('rate_limit_per_minute', $service->rate_limit_per_minute) }}" min="1"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="flex items-center space-x-6 pt-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public', $service->is_public) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Public</span>
                </label>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 mr-3">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                    Update Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
