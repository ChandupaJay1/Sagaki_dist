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
        // Filter out empty items before validation
        if ($request->has('items')) {
            $filteredItems = array_values(array_filter($request->items, function ($item) {
                return !empty($item['product_id']);
            }));
            $request->merge(['items' => $filteredItems]);
        }

        $validated = $request->validate([
            'site_from' => 'required|string',
            'site_to' => 'required|string',
            'date' => 'required|date',
            'memo' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        // Generate Transfer No
        $lastTransfer = InventoryTransfer::orderBy('id', 'desc')->first();
        $nextId = $lastTransfer ? $lastTransfer->id + 1 : 1;
        $transferNo = 'TN-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $transfer = InventoryTransfer::create([
            'site_from' => $validated['site_from'],
            'site_to' => $validated['site_to'],
            'transfer_no' => $transferNo,
            'date' => $validated['date'],
            'memo' => $validated['memo'],
            'status' => 'Pending',
        ]);

        foreach ($request->items as $item) {
            if (!empty($item['product_id'])) {
                // Ensure onhand is numeric to avoid SQL errors
                $onhand = $item['onhand'] ?? 0;
                if (!is_numeric($onhand)) {
                    $onhand = 0;
                }

                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? '',
                    'onhand' => $onhand,
                    'qty' => $item['qty'],
                    'unit' => $item['unit'] ?? '',
                ]);
            }
        }

        return redirect()->route('inventory-transfers.index')->with('success', 'Inventory Transfer created successfully. Number: ' . $transferNo);
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
