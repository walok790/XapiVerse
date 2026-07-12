@extends('layouts.app')

@section('title', 'API Documentation')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-900 via-brand-900 to-purple-900 p-8 lg:p-12 mb-8 animate-fade-in-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/10 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl lg:text-4xl font-bold text-white font-jakarta mb-3">API Documentation</h1>
            <p class="text-gray-300 text-lg mb-6 max-w-2xl">Integrate powerful APIs into your applications with our simple RESTful endpoints.</p>
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-5 py-3">
                <span class="text-xs font-medium text-gray-300 uppercase tracking-wider">Base URL</span>
                <code class="text-sm font-mono text-white font-semibold">{{ url('/api/v1') }}</code>
                <button onclick="navigator.clipboard.writeText('{{ url('/api/v1') }}')" class="p-1 rounded-lg text-gray-400 hover:text-white transition-colors" title="Copy Base URL">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Authentication Section --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8 mb-8">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 font-jakarta">Authentication</h2>
        </div>
        <p class="text-sm text-gray-600 mb-4">All API requests require authentication using a Bearer token. Include your API key in the <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded font-mono text-brand-600">Authorization</code> header of every request.</p>
        <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto">
            <pre class="text-sm font-mono text-gray-300"><span class="text-green-400">Authorization:</span> Bearer <span class="text-amber-300">your-api-key-here</span></pre>
        </div>
        <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
            <div class="flex items-start space-x-2">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-blue-700">You can generate API keys from your <a href="{{ route('developer.api-keys.index') }}" class="font-semibold underline hover:text-blue-900">API Keys page</a>. Keep your keys secure and never expose them in client-side code.</p>
            </div>
        </div>
    </div>

    {{-- Available Services --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 font-jakarta mb-4">Available Services</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($services as $service)
            <a href="{{ route('developer.docs.show', $service->slug) }}" class="group block bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 hover:border-brand-200 hover:shadow-brand-100/50 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-100 to-purple-100 group-hover:from-brand-200 group-hover:to-purple-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 group-hover:text-brand-700 transition-colors">{{ $service->name }}</h3>
                </div>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $service->description }}</p>
                <div class="flex items-center text-xs font-medium text-brand-600 group-hover:text-brand-700">
                    View Documentation
                    <svg class="w-3.5 h-3.5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <p class="text-sm text-gray-500">No services available yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Start --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 font-jakarta">Quick Start</h2>
        </div>
        <p class="text-sm text-gray-600 mb-4">Make your first API request using cURL:</p>
        <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
            <pre class="text-sm font-mono text-gray-300"><span class="text-gray-500"># Make a request to the API</span>
<span class="text-green-400">curl</span> -X GET <span class="text-amber-300">{{ url('/api/v1') }}/{service}/{endpoint}</span> \
  -H <span class="text-cyan-300">"Authorization: Bearer YOUR_API_KEY"</span> \
  -H <span class="text-cyan-300">"Content-Type: application/json"</span></pre>
        </div>
        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Example Response</p>
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm font-mono text-gray-300">{
  <span class="text-cyan-300">"success"</span>: <span class="text-green-400">true</span>,
  <span class="text-cyan-300">"data"</span>: {
    <span class="text-cyan-300">"result"</span>: <span class="text-amber-300">"..."</span>
  },
  <span class="text-cyan-300">"credits_used"</span>: <span class="text-purple-300">1</span>,
  <span class="text-cyan-300">"credits_remaining"</span>: <span class="text-purple-300">999</span>
}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
