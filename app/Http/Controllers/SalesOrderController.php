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
use App\Models\Invoice;
use App\Models\InvoiceItem;

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

        // Generate next Order Number for display
        $lastOrder = SalesOrder::latest()->first();
        if (!$lastOrder || !$lastOrder->order_no) {
            $nextOrderNo = 'SO00001';
        } else {
            $lastNo = (int)str_replace('SO', '', $lastOrder->order_no);
            $nextOrderNo = 'SO' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('sales_orders.create', compact('customers', 'locations', 'reps', 'terms', 'products', 'accounts', 'nextOrderNo'));
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

            // Generate Order Number
            $lastOrder = SalesOrder::latest()->first();
            if (!$lastOrder || !$lastOrder->order_no) {
                $data['order_no'] = 'SO00001';
            } else {
                $lastNo = (int)str_replace('SO', '', $lastOrder->order_no);
                $data['order_no'] = 'SO' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
            }

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

    /**
     * All Sales Orders (no customer filter) — used by Invoice create Load dropdown.
     */
    public function ajaxAllSalesOrders()
    {
        $rows = SalesOrder::query()
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get(['id', 'order_no', 'order_date', 'total_amount', 'customer_id']);

        return response()->json($rows->map(function (SalesOrder $s) {
            return [
                'id'           => $s->id,
                'order_no'     => $s->order_no,
                'date'         => $s->order_date,
                'total_amount' => (float) ($s->total_amount ?? 0),
                'customer_id'  => $s->customer_id,
            ];
        }));
    }

    /**
     * Prior Sales Orders for the customer (Load dropdown on Invoice create).
     */
    public function ajaxCustomerSalesOrders(string $customer)
    {
        $rows = SalesOrder::query()
            ->where('customer_id', $customer)
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get(['id', 'order_no', 'order_date', 'total_amount']);

        return response()->json($rows->map(function (SalesOrder $s) {
            return [
                'id' => $s->id,
                'order_no' => $s->order_no,
                'date' => $s->order_date,
                'total_amount' => (float) ($s->total_amount ?? 0),
            ];
        }));
    }

    /**
     * Full Sales Order header + line items for copying into a new Invoice form.
     */
    public function ajaxSalesOrderDetails(string $so)
    {
        $model = SalesOrder::with(['items.product'])->findOrFail($so);

        // Pre-calculate how much has already been invoiced against this Sales Order
        $orderNo = $model->order_no;
        $invoicedQtyByProduct = \App\Models\InvoiceItem::whereHas('invoice', function ($q) use ($orderNo) {
                $q->where('load', $orderNo);
            })
            ->select('product_id', \DB::raw('SUM(qty) as total_invoiced'))
            ->groupBy('product_id')
            ->pluck('total_invoiced', 'product_id');

        $items = $model->items->map(function ($item) use ($invoicedQtyByProduct) {
            return [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => (float) max(0, $item->qty - ($invoicedQtyByProduct[$item->product_id] ?? 0)),
                'original_qty' => (float) $item->qty,
                'invoiced_qty' => (float) ($invoicedQtyByProduct[$item->product_id] ?? 0),
                'rate' => (float) $item->rate,
                'amount' => (float) $item->amount,
                'disc_percent' => (float) ($item->disc_percent ?? 0),
                'discount' => (float) ($item->discount ?? 0),
                'total' => (float) $item->total,
                'location' => $item->location,
                'unit' => $item->unit,
                'product' => $item->relationLoaded('product') && $item->product ? [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'cost' => $item->product->cost,
                ] : null,
            ];
        })->values();

        $header = [
            'customer_id' => $model->customer_id,
            'order_no' => $model->order_no,
            'address' => $model->address,
            'delivery_destination' => $model->delivery_destination,
            'date' => $model->order_date,
            'expected_date' => $model->expected_date,
            'due_date' => $model->due_date,
            'rep_id' => $model->rep_id,
            'attent' => $model->attent,
            'memo' => $model->memo,
            'location_id' => $model->location_id,
            'payment_term_id' => $model->payment_term_id,
            'account_id' => $model->account_id,
            'subtotal' => $model->subtotal,
            'header_discount_percent' => $model->header_discount_percent,
            'header_discount_amount' => $model->header_discount_amount,
            'tax_amount' => $model->tax_amount,
            'sscl_percent' => $model->sscl_percent,
            'sscl_amount' => $model->sscl_amount,
            'vat_percent' => $model->vat_percent,
            'vat_amount' => $model->vat_amount,
            'total_amount' => $model->total_amount,
        ];

        return response()->json([
            'so' => $header,
            'items' => $items,
        ]);
    }

    public function print()
    {
        $order = SalesOrder::with(['customer', 'rep', 'items.product'])->findOrFail($id);
        return view('sales_orders.print', compact('order'));
    }
}
