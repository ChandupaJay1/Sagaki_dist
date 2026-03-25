<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransfer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Location;
use Illuminate\Http\Request;

class InventoryTransferController extends Controller
{
    public function index()
    {
        $transfers = InventoryTransfer::latest()->paginate(10);
        return view('inventory_transfers.index', compact('transfers'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('name', 'not like', '%Transit%')->orderBy('name')->get();
        return view('inventory_transfers.create', compact('products', 'units', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transfer_no' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        InventoryTransfer::create($request->all());
        return redirect()->route('inventory-transfers.index')->with('success', 'Inventory Transfer created successfully.');
    }
}
