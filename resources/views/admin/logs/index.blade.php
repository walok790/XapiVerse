@extends('layouts.app')

@section('title', 'Request Logs')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Request Logs</h1>
        <p class="mt-1 text-gray-500">Monitor API requests and response details.</p>
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
        <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label for="service" class="block text-xs font-medium text-gray-500 mb-1">Service</label>
                <select name="service" id="service" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[130px]">
                <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (2xx)</option>
                    <option value="client_error" {{ request('status') == 'client_error' ? 'selected' : '' }}>Client Error (4xx)</option>
                    <option value="server_error" {{ request('status') == 'server_error' ? 'selected' : '' }}>Server Error (5xx)</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="date_from" class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
            </div>
            <div class="min-w-[150px]">
                <label for="date_to" class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 border">
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
        </form>
    </div>


    {{-- Logs Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Endpoint</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $log->created_at->format('M d, H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $log->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $log->apiService->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ Str::limit($log->endpoint, 30) }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Success</span>
                                @elseif($log->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                @elseif($log->status === 'rate_limited')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Rate Limited</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $log->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $log->response_time_ms ?? '-' }}ms</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $log->credits_charged ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-medium">No logs found</p>
                                <p class="mt-1">Request logs will appear here once API requests are made.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
