<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use App\Models\ApiService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiRequestLog::with(['user', 'apiService', 'sourceKey']);

        if ($request->input('service_id')) {
            $query->where('api_service_id', $request->input('service_id'));
        }

        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->input('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->input('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->latest('created_at')->paginate(50);
        $services = ApiService::orderBy('name')->get();

        return view('admin.logs.index', compact('logs', 'services'));
    }
}
