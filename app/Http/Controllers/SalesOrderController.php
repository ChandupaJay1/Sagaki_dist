<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->latest()->paginate(10);
        return view('sales_orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get(); // Assuming reps are users
        $terms = PaymentTerm::orderBy('days')->get();
        $products = Product::where('is_main_product', false)->orderBy('name')->get();
        return view('sales_orders.create', compact('customers', 'locations', 'reps', 'terms', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'rep' => ['nullable', 'exists:users,id'],
            'location' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'order_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
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
            $salesOrder = SalesOrder::create([
                'customer_id' => $validated['customer_id'],
                'rep_id' => $request->rep,
                'location' => $validated['location'],
                'address' => $validated['address'],
                'delivery_destination' => $validated['delivery_destination'],
                'reference_no' => $validated['reference_no'],
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'],
                'terms' => $validated['terms'],
                'due_date' => $validated['due_date'],
                'attent' => $validated['attent'],
                'memo' => $validated['memo'],
                'header_discount_percent' => $validated['header_discount_percent'] ?? 0,
                'header_discount_amount' => $validated['header_discount_amount'] ?? 0,
                'total_amount' => $validated['total_amount'] ?? 0,
            ]);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $salesOrder->items()->create($item);
                }
            }
        });

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order created successfully.');
    }

    public function show($id)
    {
        $order = SalesOrder::with(['customer', 'rep', 'items.product'])->findOrFail($id);
        return view('sales_orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = SalesOrder::with('items')->findOrFail($id);
        $customers = Customer::orderBy('company_name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $products = Product::where('is_main_product', false)->orderBy('name')->get();
        
        return view('sales_orders.edit', compact('order', 'customers', 'locations', 'reps', 'terms', 'products'));
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'rep' => ['nullable', 'exists:users,id'],
            'location' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'order_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated, $salesOrder) {
            $salesOrder->update([
                'customer_id' => $validated['customer_id'],
                'rep_id' => $request->rep,
                'location' => $validated['location'],
                'address' => $validated['address'],
                'delivery_destination' => $validated['delivery_destination'],
                'reference_no' => $validated['reference_no'],
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'],
                'terms' => $validated['terms'],
                'due_date' => $validated['due_date'],
                'attent' => $validated['attent'],
                'memo' => $validated['memo'],
                'header_discount_percent' => $validated['header_discount_percent'] ?? 0,
                'header_discount_amount' => $validated['header_discount_amount'] ?? 0,
                'total_amount' => $validated['total_amount'] ?? 0,
            ]);

            // Sync items: delete existing and recreate
            $salesOrder->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $salesOrder->items()->create($item);
                }
            }
        });

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order updated successfully.');
    }

    public function destroy($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->items()->delete();
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')->with('success', 'Sales Order deleted successfully.');
    }
}

