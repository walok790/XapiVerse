@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Select Installation Mode</h2>
    <p class="text-gray-500 text-sm mb-6">Choose how you want to set up XapiVerse.</p>

    <form method="POST" action="{{ route('install.save-mode') }}" x-data="{ mode: 'demo' }">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Demo Mode -->
            <label class="cursor-pointer">
                <input type="radio" name="install_mode" value="demo" x-model="mode" class="sr-only peer">
                <div class="p-6 border-2 rounded-xl peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-all h-full">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-jakarta font-bold text-gray-900 mb-1">Demo Mode</h3>
                    <p class="text-sm text-gray-500 mb-3">Quick setup for testing. Pre-made accounts and sample data imported automatically.</p>
                    <ul class="space-y-1 text-xs text-gray-500">
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Auto-creates admin/developer/user</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Sample API services + source keys</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-orange-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> One-click: just enter database → done!</li>
                    </ul>
                </div>
            </label>

            <!-- Business Mode -->
            <label class="cursor-pointer">
                <input type="radio" name="install_mode" value="business" x-model="mode" class="sr-only peer">
                <div class="p-6 border-2 rounded-xl peer-checked:border-brand-600 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300 transition-all h-full">
                    <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-jakarta font-bold text-gray-900 mb-1">Business Mode</h3>
                    <p class="text-sm text-gray-500 mb-3">Production setup. Clean database, no demo data. You create your own super admin.</p>
                    <ul class="space-y-1 text-xs text-gray-500">
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Clean empty database</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Create your own super admin</li>
                        <li class="flex items-center"><svg class="w-3.5 h-3.5 text-brand-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Ready for production use</li>
                    </ul>
                </div>
            </label>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Next: Database Setup
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
