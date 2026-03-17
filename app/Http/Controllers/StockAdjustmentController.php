<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\Account;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with('account')->latest()->paginate(10);
        return view('stock_adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $accounts = Account::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        return view('stock_adjustments.create', compact('accounts', 'products', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'nullable|exists:accounts,id',
            'date' => 'nullable|date',
        ]);

        StockAdjustment::create($request->all());
        return redirect()->route('stock-adjustments.index')->with('success', 'Stock Adjustment created successfully.');
    }
}
