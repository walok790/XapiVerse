@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Folder Permissions</h2>
    <p class="text-gray-500 text-sm mb-6">The following folders must be writable by the web server.</p>

    <div class="space-y-0 divide-y divide-gray-100">
        @foreach($permissions as $folder => $writable)
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-sm text-gray-700 font-mono">{{ $folder }}</span>
                </div>
                @if($writable)
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-green-600">Writable</span>
                        <span class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-red-600">Not Writable</span>
                        <span class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if(!$allPassed)
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Fix:</strong> Run this command on your server:<br>
                <code class="mt-1 block bg-yellow-100 px-3 py-1 rounded text-xs">chmod -R 775 storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache</code>
            </p>
        </div>
    @endif

    <div class="mt-6 flex justify-between">
        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        @if($allPassed)
            <a href="{{ route('install.mode') }}" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                Next Step
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Re-check
            </a>
        @endif
    </div>
</div>
@endsection
