<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ApiService;

class DocsController extends Controller
{
    public function index()
    {
        $services = ApiService::where('is_active', true)->where('is_public', true)->orderBy('sort_order')->get();
        return view('developer.docs.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = ApiService::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $services = ApiService::where('is_active', true)->where('is_public', true)->orderBy('sort_order')->get();
        return view('developer.docs.show', compact('service', 'services'));
    }
}
