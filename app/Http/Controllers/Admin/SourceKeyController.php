<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKeyImportBatch;
use App\Models\ApiService;
use App\Models\ApiSourceKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SourceKeyController extends Controller
{
    public function index(Request $request)
    {
        $services = ApiService::orderBy('name')->get();
        $selectedService = $request->input('service_id');

        $query = ApiSourceKey::with('service');

        if ($selectedService) {
            $query->where('api_service_id', $selectedService);
        }

        if ($request->input('key_type')) {
            $query->where('key_type', $request->input('key_type'));
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true)->where('is_exhausted', false);
        } elseif ($request->input('status') === 'exhausted') {
            $query->where('is_exhausted', true);
        } elseif ($request->input('status') === 'disabled') {
            $query->where('is_active', false);
        }

        $keys = $query->orderBy('priority')->orderBy('created_at', 'desc')->paginate(50);

        // Stats
        $stats = [
            'total' => ApiSourceKey::count(),
            'active' => ApiSourceKey::where('is_active', true)->where('is_exhausted', false)->count(),
            'exhausted' => ApiSourceKey::where('is_exhausted', true)->count(),
            'disabled' => ApiSourceKey::where('is_active', false)->count(),
        ];

        return view('admin.source-keys.index', compact('keys', 'services', 'selectedService', 'stats'));
    }

    public function create()
    {
        $services = ApiService::where('is_active', true)->orderBy('name')->get();
        return view('admin.source-keys.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'api_service_id' => 'required|exists:api_services,id',
            'key_type' => 'required|in:master,free,custom',
            'api_key' => 'required|string',
            'base_url_override' => 'nullable|url',
            'label' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1',
            'monthly_limit' => 'nullable|integer|min:1',
            'total_limit' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:1|max:10',
            'weight' => 'required|integer|min:1|max:100',
        ]);

        $validated['is_active'] = true;

        ApiSourceKey::create($validated);

        return redirect()->route('admin.source-keys.index')
            ->with('success', 'Source key added successfully.');
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'api_service_id' => 'required|exists:api_services,id',
            'key_type' => 'required|in:master,free,custom',
            'keys_file' => 'required|file|mimes:txt,csv|max:10240',
            'daily_limit' => 'nullable|integer|min:1',
            'monthly_limit' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        $file = $request->file('keys_file');
        $content = file_get_contents($file->getRealPath());
        $keys = collect(explode("\n", $content))
            ->map(fn($k) => trim($k))
            ->filter()
            ->unique();

        if ($keys->isEmpty()) {
            return back()->withErrors(['keys_file' => 'No valid keys found in file.']);
        }

        $batchId = Str::uuid()->toString();

        // Create batch record
        $batch = ApiKeyImportBatch::create([
            'batch_id' => $batchId,
            'api_service_id' => $request->input('api_service_id'),
            'imported_by' => auth()->id(),
            'key_type' => $request->input('key_type'),
            'total_imported' => 0,
            'total_failed' => 0,
            'daily_limit_per_key' => $request->input('daily_limit'),
            'monthly_limit_per_key' => $request->input('monthly_limit'),
            'priority' => $request->input('priority'),
            'status' => 'processing',
        ]);

        $imported = 0;
        $failed = 0;

        // Chunk insert
        $keys->chunk(500)->each(function ($chunk) use ($request, $batchId, &$imported, &$failed) {
            foreach ($chunk as $key) {
                try {
                    ApiSourceKey::create([
                        'api_service_id' => $request->input('api_service_id'),
                        'key_type' => $request->input('key_type'),
                        'api_key' => $key,
                        'daily_limit' => $request->input('daily_limit'),
                        'monthly_limit' => $request->input('monthly_limit'),
                        'priority' => $request->input('priority'),
                        'weight' => 50,
                        'is_active' => true,
                        'import_batch_id' => $batchId,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
        });

        // Update batch
        $batch->update([
            'total_imported' => $imported,
            'total_failed' => $failed,
            'status' => 'completed',
        ]);

        return redirect()->route('admin.source-keys.index')
            ->with('success', "Bulk import complete! Imported: {$imported}, Failed: {$failed}");
    }

    public function toggle(ApiSourceKey $sourceKey)
    {
        $sourceKey->update(['is_active' => !$sourceKey->is_active]);
        $status = $sourceKey->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "Source key {$status}.");
    }

    public function destroy(ApiSourceKey $sourceKey)
    {
        $sourceKey->delete();
        return back()->with('success', 'Source key deleted.');
    }
}
