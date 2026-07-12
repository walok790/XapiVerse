@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8 text-center">
    <!-- Success Icon -->
    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h2 class="font-jakarta text-2xl font-bold text-gray-900 mb-2">Installation Complete!</h2>
    <p class="text-gray-500 mb-8">XapiVerse has been successfully installed and configured.</p>

    <!-- Quick Info -->
    <div class="bg-gray-50 rounded-xl p-6 text-left mb-8 max-w-md mx-auto">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">What's Next?</h3>
        <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Login to Admin Panel and add API Services</span>
            </li>
            <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Import your API source keys (bulk or single)</span>
            </li>
            <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Configure rotation strategies</span>
            </li>
            <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Set up credit packages and pricing</span>
            </li>
        </ul>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="/login" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Go to Login
        </a>
        <a href="/" class="inline-flex items-center px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Visit Homepage
        </a>
    </div>

    <!-- Security Warning -->
    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-left max-w-md mx-auto">
        <div class="flex items-start space-x-2">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-yellow-800">
                <strong>Security:</strong> The installer is now locked. Delete <code>storage/installed/installed.lock</code> only if you need to reinstall.
            </p>
        </div>
    </div>
</div>
@endsection
