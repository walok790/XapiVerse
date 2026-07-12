@extends('layouts.app')

@section('title', 'API Keys')

@section('content')
<div class="p-6 lg:p-8" x-data="{ showCreateModal: false, showKey: null, copiedKey: null }">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-jakarta">API Keys</h1>
            <p class="mt-1 text-gray-500">Manage your API keys and access credentials.</p>
        </div>
        <button @click="showCreateModal = true" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:from-brand-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Generate New Key
        </button>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-center space-x-2 mb-2">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-semibold text-red-800">Please fix the following errors:</p>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Create Key Modal --}}
    <div x-show="showCreateModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCreateModal = false"></div>

        {{-- Modal Content --}}
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-jakarta">Generate New API Key</h3>
                <button @click="showCreateModal = false" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('developer.api-keys.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Key Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g., Production App, Testing"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all duration-200"
                               value="{{ old('name') }}">
                    </div>
                    <div>
                        <label for="rate_limit_per_minute" class="block text-sm font-medium text-gray-700 mb-1">Rate Limit (requests/minute)</label>
                        <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute" min="1" max="1000" value="{{ old('rate_limit_per_minute', 60) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all duration-200">
                        <p class="mt-1 text-xs text-gray-500">Maximum requests allowed per minute (1-1000)</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 mt-6">
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all duration-200">
                        Generate Key
                    </button>
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- API Keys List --}}
    @if($keys->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">API Key</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Credits</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Used</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Last Used</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($keys as $key)
                    <tr class="hover:bg-brand-50/30 transition-colors duration-150" x-data="{ revealed: false }">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-900">{{ $key->name }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg font-mono text-gray-700" x-text="revealed ? '{{ $key->api_key }}' : '{{ $key->getMaskedKey() }}'"></code>
                                <button @click="revealed = !revealed" class="p-1 rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-colors" :title="revealed ? 'Hide' : 'Reveal'">
                                    <svg x-show="!revealed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="revealed" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                                <button @click="navigator.clipboard.writeText('{{ $key->api_key }}'); copiedKey = {{ $key->id }}; setTimeout(() => copiedKey = null, 2000)" class="p-1 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Copy to clipboard">
                                    <svg x-show="copiedKey !== {{ $key->id }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <svg x-show="copiedKey === {{ $key->id }}" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($key->credits_balance) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ number_format($key->request_logs_count) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($key->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Toggle Active/Inactive --}}
                                <form action="{{ route('developer.api-keys.toggle', $key) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 rounded-lg transition-colors {{ $key->is_active ? 'text-amber-500 hover:text-amber-700 hover:bg-amber-50' : 'text-green-500 hover:text-green-700 hover:bg-green-50' }}" title="{{ $key->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($key->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Delete/Revoke --}}
                                <form action="{{ route('developer.api-keys.destroy', $key) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to revoke this API key? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Revoke Key">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-100 to-purple-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 font-jakarta mb-2">No API Keys Yet</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Generate your first API key to start making requests to our services.</p>
        <button @click="showCreateModal = true" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Generate Your First Key
        </button>
    </div>
    @endif
</div>
@endsection
