<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;

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
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        return view('sales_orders.create', compact('customers', 'locations', 'reps', 'terms', 'products', 'accounts'));
    }

    public function store(Request $request)
    {
        if ($request->has('items')) {
            $items = collect($request->items)->filter(function($item) {
                return !empty($item['product_id']);
            })->toArray();
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'rep' => ['nullable', 'exists:users,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'order_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'subtotal' => ['nullable', 'numeric'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'tax_amount' => ['nullable', 'numeric'],
            'sscl_percent' => ['nullable', 'numeric'],
            'sscl_amount' => ['nullable', 'numeric'],
            'vat_percent' => ['nullable', 'numeric'],
            'vat_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated) {
            $data = Arr::except($validated, ['items', 'rep']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $data['rep_id'] = $request->rep;
            
            $salesOrder = SalesOrder::create($data);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $salesOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'description' => $item['description'] ?? '',
                        'qty' => (float)($item['qty'] ?? 0),
                        'rate' => (float)($item['rate'] ?? 0),
                        'amount' => $amountVal,
                        'disc_percent' => $discPercent,
                        'discount' => $discountVal,
                        'total' => $totalVal,
                        'location' => $item['location'] ?? null,
                        'unit' => $item['unit'] ?? null,
                    ]);
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
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        
        return view('sales_orders.edit', compact('order', 'customers', 'locations', 'reps', 'terms', 'products', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);

        if ($request->has('items')) {
            $items = collect($request->items)->filter(function($item) {
                return !empty($item['product_id']);
            })->toArray();
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'rep' => ['nullable', 'exists:users,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'order_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'subtotal' => ['nullable', 'numeric'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'tax_amount' => ['nullable', 'numeric'],
            'sscl_percent' => ['nullable', 'numeric'],
            'sscl_amount' => ['nullable', 'numeric'],
            'vat_percent' => ['nullable', 'numeric'],
            'vat_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated, $salesOrder) {
            $data = Arr::except($validated, ['items', 'rep']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $data['rep_id'] = $request->rep;
            
            $salesOrder->update($data);

            // Sync items: delete existing and recreate
            $salesOrder->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $salesOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'description' => $item['description'] ?? '',
                        'qty' => (float)($item['qty'] ?? 0),
                        'rate' => (float)($item['rate'] ?? 0),
                        'amount' => $amountVal,
                        'disc_percent' => $discPercent,
                        'discount' => $discountVal,
                        'total' => $totalVal,
                        'location' => $item['location'] ?? null,
                        'unit' => $item['unit'] ?? null,
                    ]);
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
