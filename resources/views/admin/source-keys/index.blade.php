@extends('layouts.app')

@section('title', 'Source Keys')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Source Keys</h1>
            <p class="mt-1 text-gray-500">Manage API source keys across all services.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.source-keys.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Key
            </a>
            <a href="{{ route('admin.source-keys.create') }}#bulk-import" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Bulk Import
            </a>
        </div>
    </div>


    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-sm text-gray-500">Exhausted</p>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['exhausted'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-sm text-gray-500">Disabled</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['disabled'] ?? 0) }}</p>
        </div>
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

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.source-keys.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label for="service" class="block text-xs font-medium text-gray-500 mb-1">Service</label>
                <select name="service_id" id="service" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="key_type" class="block text-xs font-medium text-gray-500 mb-1">Key Type</label>
                <select name="key_type" id="key_type" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
                    <option value="">All Types</option>
                    <option value="master" {{ request('key_type') == 'master' ? 'selected' : '' }}>Master</option>
                    <option value="free" {{ request('key_type') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="custom" {{ request('key_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="exhausted" {{ request('status') == 'exhausted' ? 'selected' : '' }}>Exhausted</option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.source-keys.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
        </form>
    </div>


    {{-- Keys Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Limit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used Today</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($keys as $key)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $key->service->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ Str::mask($key->api_key, '*', 4, -4) }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">{{ $key->key_type }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $key->daily_limit ? number_format($key->daily_limit) : 'Unlimited' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ number_format($key->used_today ?? 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $key->priority }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($key->is_active && !$key->is_exhausted)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @elseif($key->is_exhausted)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Exhausted</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form action="{{ route('admin.source-keys.toggle', $key) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-medium {{ $key->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800' }}">
                                            {{ $key->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.source-keys.destroy', $key) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No source keys found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($keys->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $keys->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
