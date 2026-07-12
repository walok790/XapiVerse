@extends('layouts.user')

@section('title', 'Support')

@section('content')
<div x-data="{ showModal: false }" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-jakarta text-2xl md:text-3xl font-bold text-white">Support</h1>
            <p class="text-sm text-gray-500 mt-1">Get help from our support team</p>
        </div>

        <button @click="showModal = true" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 hover:from-brand-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-brand-500/20">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Ticket
        </button>
    </div>

    <!-- New Ticket Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg bg-[#141419] border border-white/5 rounded-2xl shadow-2xl shadow-black/40 p-6">

                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-jakarta text-lg font-semibold text-white">Create Support Ticket</h3>
                    <button @click="showModal = false" class="p-1.5 text-gray-500 hover:text-white transition-colors rounded-lg hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form method="POST" action="{{ route('user.support.create') }}" class="space-y-4">
                    @csrf

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-400 mb-1.5">Subject</label>
                        <input type="text" id="subject" name="subject" required placeholder="Brief description of your issue" value="{{ old('subject') }}"
                               class="w-full px-4 py-3 bg-dark-950 border border-white/10 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/50 transition-colors">
                        @error('subject')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-400 mb-1.5">Priority</label>
                        <select id="priority" name="priority" required
                                class="w-full px-4 py-3 bg-dark-950 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/50 transition-colors">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-400 mb-1.5">Message</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Describe your issue in detail..."
                                  class="w-full px-4 py-3 bg-dark-950 border border-white/10 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/50 transition-colors resize-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 hover:from-brand-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl transition-all">
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    @if($tickets->count() > 0)
        <div class="space-y-3">
            @foreach($tickets as $ticket)
                <div class="bg-[#141419] border border-white/5 rounded-xl p-5 hover:border-white/10 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-xs text-gray-500 font-mono">#{{ $ticket->ticket_id }}</span>
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->status === 'open' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                {{ $ticket->status === 'in_progress' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                {{ $ticket->status === 'resolved' ? 'bg-green-500/10 text-green-400' : '' }}
                                {{ $ticket->status === 'closed' ? 'bg-gray-500/10 text-gray-400' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5
                                    {{ $ticket->status === 'open' ? 'bg-yellow-400' : '' }}
                                    {{ $ticket->status === 'in_progress' ? 'bg-blue-400' : '' }}
                                    {{ $ticket->status === 'resolved' ? 'bg-green-400' : '' }}
                                    {{ $ticket->status === 'closed' ? 'bg-gray-400' : '' }}"></span>
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                            <!-- Priority Badge -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->priority === 'high' ? 'bg-red-500/10 text-red-400' : '' }}
                                {{ $ticket->priority === 'medium' ? 'bg-orange-500/10 text-orange-400' : '' }}
                                {{ $ticket->priority === 'low' ? 'bg-gray-500/10 text-gray-400' : '' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-600">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>

                    <h4 class="text-sm font-medium text-white mb-2">{{ $ticket->subject }}</h4>

                    <!-- Admin Reply -->
                    @if($ticket->admin_reply)
                        <div class="mt-3 p-3 bg-dark-950 border border-white/5 rounded-lg">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-5 h-5 bg-brand-500/20 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-xs font-medium text-brand-400">Admin Reply</span>
                            </div>
                            <p class="text-sm text-gray-300">{{ $ticket->admin_reply }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="bg-[#141419] border border-white/5 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-dark-950 border border-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="font-jakarta font-semibold text-white text-lg mb-2">No tickets yet</h3>
            <p class="text-gray-500 text-sm">Create a support ticket if you need help with anything.</p>
            <button @click="showModal = true" class="inline-flex items-center mt-4 px-4 py-2 bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium rounded-xl hover:bg-brand-500/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create First Ticket
            </button>
        </div>
    @endif
</div>
@endsection
