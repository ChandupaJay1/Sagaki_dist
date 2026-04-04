<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('vendor')->latest()->paginate(10);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('purchase_orders.create', compact('vendors', 'reps', 'terms', 'locations', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated) {
            $purchaseOrder = PurchaseOrder::create($validated);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $purchaseOrder->items()->create($item);
                }
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['vendor', 'items.product'])->findOrFail($id);
        return view('purchase_orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = PurchaseOrder::with('items')->findOrFail($id);
        $vendors = Vendor::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchase_orders.edit', compact('order', 'vendors', 'reps', 'terms', 'locations', 'products'));
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated, $purchaseOrder) {
            $purchaseOrder->update($validated);

            $purchaseOrder->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $purchaseOrder->items()->create($item);
                }
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
    }
}
