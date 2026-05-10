<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\Account;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with(['location', 'creator'])->latest()->paginate(10);
        return view('inventory.adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        // Generate next Adjustment Number
        $lastAdj = StockAdjustment::orderBy('id', 'desc')->first();
        $nextId = $lastAdj ? $lastAdj->id + 1 : 1;
        $adjustmentNo = 'ADJ-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return view('inventory.adjustments.create', compact('products', 'locations', 'accounts', 'adjustmentNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'memo' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.current_qty' => 'required|numeric',
            'items.*.new_qty' => 'required|numeric',
            'items.*.adjustment_qty' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Generate Adjustment Number again to be safe
            $lastAdj = StockAdjustment::orderBy('id', 'desc')->first();
            $nextId = $lastAdj ? $lastAdj->id + 1 : 1;
            $adjustmentNo = 'ADJ-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $adjustment = StockAdjustment::create([
                'adjustment_no' => $adjustmentNo,
                'location_id' => $validated['location_id'],
                'date' => $validated['date'],
                'memo' => $validated['memo'],
                'status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $item) {
                $adjustment->items()->create([
                    'product_id' => $item['product_id'],
                    'current_qty' => $item['current_qty'],
                    'new_qty' => $item['new_qty'],
                    'adjustment_qty' => $item['adjustment_qty'],
                ]);
            }

            return redirect()->route('stock-adjustments.index')->with('success', 'Stock Adjustment created successfully. Number: ' . $adjustmentNo);
        });
    }

    public function approve($id)
    {
        return DB::transaction(function () use ($id) {
            $adjustment = StockAdjustment::with('items.product')->findOrFail($id);

            if ($adjustment->status !== 'Pending') {
                return redirect()->back()->with('error', 'Only Pending adjustments can be approved.');
            }

            foreach ($adjustment->items as $item) {
                // Update stock using adjustment_qty
                // If Adjustment Qty is positive, it increases stock. If negative, it decreases stock.
                InventoryService::updateStock(
                    $item->product_id,
                    $adjustment->location_id,
                    $item->adjustment_qty,
                    'Adjustment',
                    'Stock Adjustment',
                    $adjustment->id,
                    "Stock Adjusted via " . $adjustment->adjustment_no
                );
            }

            $adjustment->status = 'Approved';
            $adjustment->save();

            return redirect()->route('stock-adjustments.index')->with('success', 'Stock Adjustment approved successfully.');
        });
    }

    public function show($id)
    {
        $adjustment = StockAdjustment::with(['location', 'creator', 'items.product'])->findOrFail($id);
        return view('inventory.adjustments.show', compact('adjustment'));
    }

    public function destroy($id)
    {
        $adjustment = StockAdjustment::findOrFail($id);

        if ($adjustment->status !== 'Pending') {
            return redirect()->back()->with('error', 'Only Pending adjustments can be deleted.');
        }

        $adjustment->items()->delete();
        $adjustment->delete();

        return redirect()->route('stock-adjustments.index')->with('success', 'Stock Adjustment deleted successfully.');
    }
}
