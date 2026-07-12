@extends('layouts.user')

@section('title', 'Subscription')

@section('content')
<!-- Header -->
<div class="text-center mb-10">
    <h1 class="font-jakarta text-2xl sm:text-3xl font-bold text-white">Choose Your Plan</h1>
    <p class="text-gray-400 mt-2 max-w-lg mx-auto">Unlock premium features with a plan that fits your needs. Cancel anytime.</p>
</div>

<!-- Current Plan Banner -->
<div class="bg-[#141419] border border-white/5 rounded-xl p-4 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-yellow-500/10 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
        </div>
        <div>
            <p class="text-sm text-gray-400">Current Plan: <span class="text-green-400 font-semibold">Free</span></p>
            <p class="text-xs text-gray-500">Free forever — upgrade for more features</p>
        </div>
    </div>
</div>


<!-- Pricing Cards -->
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- Free Plan -->
    <div class="bg-[#141419] border border-white/5 rounded-xl p-6 flex flex-col">
        <div class="mb-6">
            <h3 class="font-jakarta font-bold text-white text-lg mb-1">Free</h3>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-jakarta font-extrabold text-white">$0</span>
                <span class="text-sm text-gray-500">/forever</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">Get started basics</p>
        </div>
        <ul class="space-y-3 mb-6 flex-1">
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">5 videos per day</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">720p quality</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Basic watch history</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="text-gray-500">No bookmarks</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="text-gray-500">No priority speed</span>
            </li>
        </ul>
        <button disabled class="w-full py-3 text-sm font-medium text-gray-500 bg-dark-950 border border-white/5 rounded-lg cursor-not-allowed">
            Current Plan
        </button>
    </div>


    <!-- Pro Plan -->
    <div class="bg-[#141419] border-2 border-brand-500/50 rounded-xl p-6 flex flex-col relative shadow-lg shadow-brand-500/10">
        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="px-3 py-1 bg-brand-600 text-white text-xs font-bold rounded-full uppercase">Most Popular</span>
        </div>
        <div class="mb-6">
            <h3 class="font-jakarta font-bold text-white text-lg mb-1">Pro</h3>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-jakarta font-extrabold text-white">$9</span>
                <span class="text-sm text-gray-500">/month</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">For power users</p>
        </div>
        <ul class="space-y-3 mb-6 flex-1">
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Unlimited videos</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">1080p HD quality</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Unlimited bookmarks</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Priority speed</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">No ads</span>
            </li>
        </ul>
        <div class="space-y-2">
            <button class="w-full py-3 text-sm font-semibold text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors">
                Auto Pay (Instant)
            </button>
            <button class="w-full py-2.5 text-sm font-medium text-gray-400 border border-white/10 rounded-lg hover:bg-white/5 transition-colors">
                Manual Pay
            </button>
        </div>
    </div>


    <!-- Enterprise Plan -->
    <div class="bg-[#141419] border border-white/5 rounded-xl p-6 flex flex-col">
        <div class="mb-6">
            <h3 class="font-jakarta font-bold text-white text-lg mb-1">Enterprise</h3>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-jakarta font-extrabold text-white">$29</span>
                <span class="text-sm text-gray-500">/month</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">For teams</p>
        </div>
        <ul class="space-y-3 mb-6 flex-1">
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Everything in Pro</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">4K quality</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">API access</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">5 team seats</span>
            </li>
            <li class="flex items-center text-sm">
                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-300">Priority support</span>
            </li>
        </ul>
        <div class="space-y-2">
            <button class="w-full py-3 text-sm font-semibold text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors">
                Auto Pay (Instant)
            </button>
            <button class="w-full py-2.5 text-sm font-medium text-gray-400 border border-white/10 rounded-lg hover:bg-white/5 transition-colors">
                Manual Pay
            </button>
        </div>
    </div>
</div>


<!-- Coupon Code Section -->
<div class="bg-[#141419] border border-white/5 rounded-xl p-6" x-data="{ coupon: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h3 class="font-jakarta font-semibold text-white">Have a coupon code?</h3>
            <p class="text-sm text-gray-500 mt-1">Enter your code to get a discount on any plan</p>
        </div>
        <div class="flex items-center space-x-3">
            <input type="text" x-model="coupon"
                   class="px-4 py-2.5 bg-dark-950 border border-white/10 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors w-48"
                   placeholder="Enter code...">
            <button :disabled="!coupon"
                    class="px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Apply
            </button>
        </div>
    </div>
</div>
@endsection
