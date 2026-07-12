@extends('layouts.app')

@section('title', $service->name . ' - API Docs')

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left Sidebar - Service Navigation --}}
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-4 sticky top-24">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider px-3 mb-3">API Services</h3>
                <nav class="space-y-1">
                    @foreach($services as $s)
                    <a href="{{ route('developer.docs.show', $s->slug) }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ $s->id === $service->id ? 'bg-gradient-to-r from-brand-600/10 to-purple-600/5 text-brand-700 border border-brand-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 {{ $s->id === $service->id ? 'bg-brand-100' : 'bg-gray-100' }} transition-colors">
                            <svg class="w-3.5 h-3.5 {{ $s->id === $service->id ? 'text-brand-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <span class="truncate">{{ $s->name }}</span>
                    </a>
                    @endforeach
                </nav>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('developer.docs') }}" class="flex items-center px-3 py-2 text-xs font-medium text-gray-500 hover:text-brand-600 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Overview
                    </a>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            {{-- Service Header --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8 mb-6 animate-fade-in-up">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-100 to-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 font-jakarta">{{ $service->name }}</h1>
                        <p class="text-gray-500 mt-1">{{ $service->description }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <div class="inline-flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <span class="text-xs font-medium text-gray-500">Base URL</span>
                        <code class="text-xs font-mono text-brand-600 font-semibold">{{ url('/api/v1/' . $service->slug) }}</code>
                    </div>
                    @if($service->credits_per_request)
                    <div class="inline-flex items-center space-x-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-medium text-amber-700">{{ $service->credits_per_request }} credit(s) per request</span>
                    </div>
                    @endif
                    @if($service->rate_limit_per_minute)
                    <div class="inline-flex items-center space-x-2 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-medium text-blue-700">{{ $service->rate_limit_per_minute }} requests/min</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Endpoints --}}
            @if($service->endpoints && count($service->endpoints) > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8 mb-6">
                <h2 class="text-lg font-bold text-gray-900 font-jakarta mb-4">Endpoints</h2>
                <div class="space-y-3">
                    @foreach($service->endpoints as $endpoint)
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-brand-200 transition-colors">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                            {{ $endpoint['method'] === 'GET' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $endpoint['method'] === 'POST' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $endpoint['method'] === 'PUT' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $endpoint['method'] === 'PATCH' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $endpoint['method'] === 'DELETE' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $endpoint['method'] }}
                        </span>
                        <code class="text-sm font-mono text-gray-800 font-medium">{{ $endpoint['path'] }}</code>
                        <span class="text-sm text-gray-500 hidden sm:inline">{{ $endpoint['description'] ?? '' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Code Examples with Tabs --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8 mb-6" x-data="{ activeTab: 'curl' }">
                <h2 class="text-lg font-bold text-gray-900 font-jakarta mb-4">Code Examples</h2>

                {{-- Tab Navigation --}}
                <div class="flex space-x-1 bg-gray-100 rounded-xl p-1 mb-4">
                    <button @click="activeTab = 'curl'" :class="activeTab === 'curl' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="flex-1 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200">cURL</button>
                    <button @click="activeTab = 'php'" :class="activeTab === 'php' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="flex-1 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200">PHP</button>
                    <button @click="activeTab = 'python'" :class="activeTab === 'python' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="flex-1 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200">Python</button>
                    <button @click="activeTab = 'nodejs'" :class="activeTab === 'nodejs' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="flex-1 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200">Node.js</button>
                </div>

                {{-- cURL Example --}}
                <div x-show="activeTab === 'curl'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
                        <pre class="text-sm font-mono text-gray-300"><span class="text-green-400">curl</span> -X GET <span class="text-amber-300">{{ url('/api/v1/' . $service->slug) }}/endpoint</span> \
  -H <span class="text-cyan-300">"Authorization: Bearer YOUR_API_KEY"</span> \
  -H <span class="text-cyan-300">"Content-Type: application/json"</span> \
  -H <span class="text-cyan-300">"Accept: application/json"</span></pre>
                    </div>
                </div>

                {{-- PHP Example --}}
                <div x-show="activeTab === 'php'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
                        <pre class="text-sm font-mono text-gray-300"><span class="text-purple-400">&lt;?php</span>

<span class="text-gray-500">// Using Guzzle HTTP client</span>
<span class="text-blue-400">$client</span> = <span class="text-cyan-300">new</span> \GuzzleHttp\Client();

<span class="text-blue-400">$response</span> = <span class="text-blue-400">$client</span>-><span class="text-green-400">request</span>(<span class="text-amber-300">'GET'</span>, <span class="text-amber-300">'{{ url('/api/v1/' . $service->slug) }}/endpoint'</span>, [
    <span class="text-amber-300">'headers'</span> => [
        <span class="text-amber-300">'Authorization'</span> => <span class="text-amber-300">'Bearer YOUR_API_KEY'</span>,
        <span class="text-amber-300">'Accept'</span> => <span class="text-amber-300">'application/json'</span>,
    ]
]);

<span class="text-blue-400">$data</span> = <span class="text-green-400">json_decode</span>(<span class="text-blue-400">$response</span>-><span class="text-green-400">getBody</span>(), <span class="text-purple-400">true</span>);</pre>
                    </div>
                </div>

                {{-- Python Example --}}
                <div x-show="activeTab === 'python'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
                        <pre class="text-sm font-mono text-gray-300"><span class="text-purple-400">import</span> requests

url = <span class="text-amber-300">"{{ url('/api/v1/' . $service->slug) }}/endpoint"</span>
headers = {
    <span class="text-amber-300">"Authorization"</span>: <span class="text-amber-300">"Bearer YOUR_API_KEY"</span>,
    <span class="text-amber-300">"Content-Type"</span>: <span class="text-amber-300">"application/json"</span>,
    <span class="text-amber-300">"Accept"</span>: <span class="text-amber-300">"application/json"</span>
}

response = requests.<span class="text-green-400">get</span>(url, headers=headers)
data = response.<span class="text-green-400">json</span>()</pre>
                    </div>
                </div>

                {{-- Node.js Example --}}
                <div x-show="activeTab === 'nodejs'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
                        <pre class="text-sm font-mono text-gray-300"><span class="text-purple-400">const</span> response = <span class="text-purple-400">await</span> <span class="text-green-400">fetch</span>(<span class="text-amber-300">'{{ url('/api/v1/' . $service->slug) }}/endpoint'</span>, {
  method: <span class="text-amber-300">'GET'</span>,
  headers: {
    <span class="text-amber-300">'Authorization'</span>: <span class="text-amber-300">'Bearer YOUR_API_KEY'</span>,
    <span class="text-amber-300">'Content-Type'</span>: <span class="text-amber-300">'application/json'</span>,
    <span class="text-amber-300">'Accept'</span>: <span class="text-amber-300">'application/json'</span>
  }
});

<span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> response.<span class="text-green-400">json</span>();
<span class="text-green-400">console</span>.<span class="text-green-400">log</span>(data);</pre>
                    </div>
                </div>
            </div>

            {{-- Response Format --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6 lg:p-8 mb-6">
                <h2 class="text-lg font-bold text-gray-900 font-jakarta mb-4">Response Format</h2>
                <p class="text-sm text-gray-600 mb-4">All responses are returned in JSON format with the following structure:</p>
                <div class="bg-gray-900 rounded-xl p-5 overflow-x-auto">
                    <pre class="text-sm font-mono text-gray-300">{
  <span class="text-cyan-300">"success"</span>: <span class="text-green-400">true</span>,
  <span class="text-cyan-300">"data"</span>: {
    <span class="text-gray-500">// Response data specific to the endpoint</span>
  },
  <span class="text-cyan-300">"credits_used"</span>: <span class="text-purple-300">1</span>,
  <span class="text-cyan-300">"credits_remaining"</span>: <span class="text-purple-300">999</span>
}</pre>
                </div>
                <div class="mt-4 bg-gray-900 rounded-xl p-5 overflow-x-auto">
                    <p class="text-xs text-gray-400 mb-2 font-semibold">Error Response:</p>
                    <pre class="text-sm font-mono text-gray-300">{
  <span class="text-cyan-300">"success"</span>: <span class="text-red-400">false</span>,
  <span class="text-cyan-300">"error"</span>: {
    <span class="text-cyan-300">"code"</span>: <span class="text-amber-300">"RATE_LIMIT_EXCEEDED"</span>,
    <span class="text-cyan-300">"message"</span>: <span class="text-amber-300">"Too many requests. Please try again later."</span>
  }
}</pre>
                </div>
            </div>

            {{-- Rate Limits & Credits Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 font-jakarta">Rate Limits</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Rate limits are applied per API key. When exceeded, requests return HTTP 429.</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Per Minute</span>
                            <span class="font-semibold text-gray-900">{{ $service->rate_limit_per_minute ?? 60 }} requests</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-100/50 p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 font-jakarta">Credits</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Each request consumes credits from your API key balance.</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Cost per Request</span>
                            <span class="font-semibold text-gray-900">{{ $service->credits_per_request ?? 1 }} credit(s)</span>
                        </div>
                    </div>
                    <a href="{{ route('developer.credits') }}" class="inline-flex items-center mt-3 text-xs font-medium text-brand-600 hover:text-brand-700">
                        Purchase more credits
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
