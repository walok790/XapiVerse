@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-jakarta">Settings</h1>
        <p class="mt-1 text-gray-500">Configure platform-wide settings and preferences.</p>
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

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="space-y-6">
            @foreach($settings->groupBy('group') as $group => $groupSettings)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 font-jakarta capitalize">{{ str_replace('_', ' ', $group) }}</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($groupSettings as $setting)
                            <div>
                                <label for="settings_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                </label>

                                @if($setting->type === 'boolean')
                                    <select name="settings[{{ $setting->key }}]" id="settings_{{ $setting->key }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border max-w-md">
                                        <option value="1" {{ old("settings.{$setting->key}", $setting->value) == '1' ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ old("settings.{$setting->key}", $setting->value) == '0' ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                @elseif($setting->type === 'textarea')
                                    <textarea name="settings[{{ $setting->key }}]" id="settings_{{ $setting->key }}" rows="3"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border max-w-2xl">{{ old("settings.{$setting->key}", $setting->value) }}</textarea>
                                @elseif($setting->type === 'number')
                                    <input type="number" name="settings[{{ $setting->key }}]" id="settings_{{ $setting->key }}" value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border max-w-md">
                                @else
                                    <input type="text" name="settings[{{ $setting->key }}]" id="settings_{{ $setting->key }}" value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm px-4 py-2.5 border max-w-2xl">
                                @endif

                                @if($setting->description)
                                    <p class="mt-1 text-xs text-gray-500">{{ $setting->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-end">
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
