<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $packages = CreditPackage::where('is_active', true)->orderBy('sort_order')->get();
        $transactions = auth()->user()->transactions()->latest()->limit(20)->get();
        $totalBalance = auth()->user()->apiKeys()->sum('credits_balance');
        return view('developer.credits.index', compact('packages', 'transactions', 'totalBalance'));
    }

    public function purchase(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:credit_packages,id']);

        $package = CreditPackage::findOrFail($request->input('package_id'));

        // For now, directly add credits (payment integration in future)
        $apiKey = auth()->user()->apiKeys()->where('is_active', true)->first();

        if (!$apiKey) {
            return back()->withErrors(['error' => 'Create an API key first.']);
        }

        // Create transaction
        Transaction::create([
            'user_id' => auth()->id(),
            'transaction_id' => 'txn_' . \Illuminate\Support\Str::random(20),
            'type' => 'purchase',
            'credits' => $package->credits,
            'amount' => $package->price,
            'status' => 'completed',
            'notes' => 'Purchased ' . $package->name . ' package',
        ]);

        // Add credits to first active key
        $apiKey->increment('credits_balance', $package->credits);
        auth()->user()->increment('total_credits_purchased', $package->credits);

        return back()->with('success', number_format($package->credits) . ' credits added successfully!');
    }
}
