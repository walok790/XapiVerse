@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Installation Mode</h2>
    <p class="text-gray-500 text-sm mb-6">Choose how you want to set up XapiVerse.</p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.save-mode') }}" x-data="{ mode: 'business' }">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Business Mode -->
            <label class="cursor-pointer">
                <input type="radio" name="install_mode" value="business" x-model="mode" class="sr-only peer">
                <div class="p-6 border-2 rounded-xl peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-all h-full">
                    <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-jakarta font-bold text-gray-900 mb-1">Business Mode</h3>
                    <p class="text-sm text-gray-500">Production setup. You create your own admin, developer, and user accounts. No demo data imported.</p>
                    <ul class="mt-3 space-y-1 text-xs text-gray-500">
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Create your own accounts</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> No demo data</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Ready for production</li>
                    </ul>
                </div>
            </label>

            <!-- Demo Mode -->
            <label class="cursor-pointer">
                <input type="radio" name="install_mode" value="demo" x-model="mode" class="sr-only peer">
                <div class="p-6 border-2 rounded-xl peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-all h-full">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-jakarta font-bold text-gray-900 mb-1">Demo Mode</h3>
                    <p class="text-sm text-gray-500">Quick setup for testing. Pre-configured accounts and sample API services are auto-imported.</p>
                    <ul class="mt-3 space-y-1 text-xs text-gray-500">
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Pre-made accounts (admin/dev/user)</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Sample API services & keys</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> One-click install</li>
                    </ul>
                </div>
            </label>
        </div>

        <!-- Info box based on mode -->
        <div x-show="mode === 'demo'" x-cloak class="p-4 bg-orange-50 border border-orange-200 rounded-lg mb-6">
            <p class="text-sm text-orange-800">
                <strong>Demo Mode:</strong> Will create default accounts with password <code class="bg-orange-100 px-1 rounded">password</code>. Login credentials will be shown on the login page. No additional setup needed.
            </p>
        </div>

        <div x-show="mode === 'business'" class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-6">
            <p class="text-sm text-blue-800">
                <strong>Business Mode:</strong> You'll create your own admin, developer, and user accounts in the next step. No sample data will be imported.
            </p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('install.database') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                <span x-show="mode === 'business'">Next: Create Accounts</span>
                <span x-show="mode === 'demo'" x-cloak>Install Demo</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
