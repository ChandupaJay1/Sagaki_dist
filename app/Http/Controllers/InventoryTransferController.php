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
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        return view('inventory_transfers.create', compact('products', 'units', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transfer_no' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['status'] = 'Pending';

        InventoryTransfer::create($data);
        return redirect()->route('inventory-transfers.index')->with('success', 'Inventory Transfer created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected,Pending'
        ]);

        $transfer = InventoryTransfer::findOrFail($id);
        $transfer->status = $request->status;
        $transfer->save();

        return redirect()->back()->with('success', 'Inventory Transfer status updated to ' . $request->status);
    }
}
