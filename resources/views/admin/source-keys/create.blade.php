@extends('layouts.app')

@section('title', 'Add Source Key')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8">
        <a href="{{ route('admin.source-keys.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-brand-600 mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Source Keys
        </a>
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Add Source Key</h1>
        <p class="mt-1 text-gray-500">Add a single key or bulk import multiple keys.</p>
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

    {{-- Section 1: Add Single Key --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 font-jakarta">Add Single Key</h2>
        </div>
        <form action="{{ route('admin.source-keys.store') }}" method="POST" class="p-6 space-y-6">
            @csrf


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="api_service_id" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select name="api_service_id" id="api_service_id" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('api_service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="key_type" class="block text-sm font-medium text-gray-700 mb-1">Key Type</label>
                    <select name="key_type" id="key_type" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="master" {{ old('key_type') == 'master' ? 'selected' : '' }}>Master</option>
                        <option value="free" {{ old('key_type') == 'free' ? 'selected' : '' }}>Free</option>
                        <option value="custom" {{ old('key_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="api_key" class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                <input type="text" name="api_key" id="api_key" value="{{ old('api_key') }}" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="base_url_override" class="block text-sm font-medium text-gray-700 mb-1">Base URL Override</label>
                    <input type="url" name="base_url_override" id="base_url_override" value="{{ old('base_url_override') }}" placeholder="Leave empty to use service default"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                    <input type="text" name="label" id="label" value="{{ old('label') }}" placeholder="Optional label for identification"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="daily_limit" class="block text-sm font-medium text-gray-700 mb-1">Daily Limit</label>
                    <input type="number" name="daily_limit" id="daily_limit" value="{{ old('daily_limit') }}" min="0" placeholder="Unlimited"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="monthly_limit" class="block text-sm font-medium text-gray-700 mb-1">Monthly Limit</label>
                    <input type="number" name="monthly_limit" id="monthly_limit" value="{{ old('monthly_limit') }}" min="0" placeholder="Unlimited"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="total_limit" class="block text-sm font-medium text-gray-700 mb-1">Total Limit</label>
                    <input type="number" name="total_limit" id="total_limit" value="{{ old('total_limit') }}" min="0" placeholder="Unlimited"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (1-10)</label>
                    <input type="number" name="priority" id="priority" value="{{ old('priority', 5) }}" min="1" max="10"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (1-100)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight', 50) }}" min="1" max="100"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.source-keys.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 mr-3">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                    Add Key
                </button>
            </div>
        </form>
    </div>


    {{-- Section 2: Bulk Import --}}
    <div id="bulk-import" class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 font-jakarta">Bulk Import</h2>
        </div>
        <form action="{{ route('admin.source-keys.bulk-import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-blue-700">Upload a .txt or .csv file with one API key per line.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bulk_api_service_id" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select name="api_service_id" id="bulk_api_service_id" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bulk_key_type" class="block text-sm font-medium text-gray-700 mb-1">Key Type</label>
                    <select name="key_type" id="bulk_key_type" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                        <option value="master">Master</option>
                        <option value="free" selected>Free</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="keys_file" class="block text-sm font-medium text-gray-700 mb-1">Keys File (.txt or .csv)</label>
                <input type="file" name="keys_file" id="keys_file" accept=".txt,.csv" required
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="bulk_daily_limit" class="block text-sm font-medium text-gray-700 mb-1">Daily Limit</label>
                    <input type="number" name="daily_limit" id="bulk_daily_limit" min="0" placeholder="Unlimited"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="bulk_monthly_limit" class="block text-sm font-medium text-gray-700 mb-1">Monthly Limit</label>
                    <input type="number" name="monthly_limit" id="bulk_monthly_limit" min="0" placeholder="Unlimited"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>

                <div>
                    <label for="bulk_priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (1-10)</label>
                    <input type="number" name="priority" id="bulk_priority" value="5" min="1" max="10"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                    Import Keys
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
