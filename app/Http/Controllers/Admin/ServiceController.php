<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = ApiService::withCount('sourceKeys')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:api_services,slug',
            'description' => 'nullable|string',
            'base_url' => 'required|url',
            'rotation_strategy' => 'required|in:round_robin,priority,least_used,weighted,fill_rotate',
            'credits_per_request' => 'required|integer|min:1',
            'rate_limit_per_minute' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_public'] = $request->boolean('is_public');

        ApiService::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'API Service created successfully.');
    }

    public function edit(ApiService $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, ApiService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:api_services,slug,' . $service->id,
            'description' => 'nullable|string',
            'base_url' => 'required|url',
            'rotation_strategy' => 'required|in:round_robin,priority,least_used,weighted,fill_rotate',
            'credits_per_request' => 'required|integer|min:1',
            'rate_limit_per_minute' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_public'] = $request->boolean('is_public');

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'API Service updated successfully.');
    }

    public function destroy(ApiService $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'API Service deleted successfully.');
    }
}
