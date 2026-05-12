<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use App\Models\Unit;
use App\Models\PaymentTerm;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;
use App\Services\InventoryService;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        return view('invoices.create', compact('customers', 'products', 'locations', 'units', 'terms', 'reps', 'accounts'));
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
            'rep_id' => ['nullable', 'exists:users,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'villa_type' => ['nullable', 'string', 'max:255'],
            'meal_plan' => ['nullable', 'string', 'max:255'],
            'no_of_pax' => ['nullable', 'integer'],
            'check_in_date' => ['nullable', 'date'],
            'room_type' => ['nullable', 'string', 'max:255'],
            'check_out_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
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
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $invoice = Invoice::create($data);

            // Update Customer Balance
            $customer = Customer::find($invoice->customer_id);
            if ($customer) {
                $customer->balance += (float)($invoice->total_amount ?? 0);
                $customer->save();
            }

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $invoice->items()->create([
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

                    // Update Inventory
                    InventoryService::updateStock(
                        $item['product_id'],
                        $item['location'] ?? $invoice->location_id,
                        -(float)$item['qty'],
                        'Out',
                        'Invoice',
                        $invoice->id,
                        "Sold via Invoice: " . ($invoice->invoice_no ?? $invoice->id)
                    );
                }
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'payments.cheques', 'payBillItems.payBill'])->findOrFail($id);
        
        // Calculate Customer Outstanding
        $totalInvoices = Invoice::where('customer_id', $invoice->customer_id)->sum('total_amount');
        $totalPaid = \App\Models\PayBillItem::whereHas('payBill', function($q) use ($invoice) {
            $q->where('customer_id', $invoice->customer_id);
        })->sum('amount_to_pay');
        $totalReturns = \App\Models\SalesReturn::where('customer_id', $invoice->customer_id)->sum('total_amount');
        
        $outstanding = ($totalInvoices - $totalPaid) - $totalReturns;

        return view('invoices.show', compact('invoice', 'outstanding'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        
        return view('invoices.edit', compact('invoice', 'customers', 'products', 'locations', 'units', 'terms', 'reps', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($request->has('items')) {
            $items = collect($request->items)->filter(function($item) {
                return !empty($item['product_id']);
            })->toArray();
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'rep_id' => ['nullable', 'exists:users,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'villa_type' => ['nullable', 'string', 'max:255'],
            'meal_plan' => ['nullable', 'string', 'max:255'],
            'no_of_pax' => ['nullable', 'integer'],
            'check_in_date' => ['nullable', 'date'],
            'room_type' => ['nullable', 'string', 'max:255'],
            'check_out_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
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

        \DB::transaction(function () use ($request, $validated, $invoice) {
            $oldCustomerId = $invoice->customer_id;
            $oldTotalAmount = (float)($invoice->total_amount ?? 0);

            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $invoice->update($data);
            $newTotalAmount = (float)($invoice->total_amount ?? 0);
            $newCustomerId = $invoice->customer_id;

            // Update Customer Balance
            if ($oldCustomerId == $newCustomerId) {
                $customer = Customer::find($newCustomerId);
                if ($customer) {
                    $customer->balance += ($newTotalAmount - $oldTotalAmount);
                    $customer->save();
                }
            } else {
                // Customer changed
                $oldCustomer = Customer::find($oldCustomerId);
                if ($oldCustomer) {
                    $oldCustomer->balance -= $oldTotalAmount; // Reverse old invoice
                    $oldCustomer->save();
                }
                $newCustomer = Customer::find($newCustomerId);
                if ($newCustomer) {
                    $newCustomer->balance += $newTotalAmount; // Apply new invoice
                    $newCustomer->save();
                }
            }

            // Reverse old stock
            foreach ($invoice->items as $oldItem) {
                InventoryService::updateStock(
                    $oldItem->product_id,
                    $oldItem->location ?? $invoice->location_id,
                    (float)$oldItem->qty, // Add back
                    'Out Reverse',
                    'Invoice',
                    $invoice->id,
                    "Reversed old Invoice item for update: " . ($invoice->invoice_no ?? $invoice->id)
                );
            }

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $invoice->items()->create([
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

                    // Update New Stock
                    InventoryService::updateStock(
                        $item['product_id'],
                        $item['location'] ?? $invoice->location_id,
                        -(float)$item['qty'],
                        'Out',
                        'Invoice',
                        $invoice->id,
                        "Updated via Invoice: " . ($invoice->invoice_no ?? $invoice->id)
                    );
                }
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        \DB::transaction(function () use ($invoice) {
            // Update Customer Balance
            $customer = Customer::find($invoice->customer_id);
            if ($customer) {
                $customer->balance -= (float)($invoice->total_amount ?? 0);
                $customer->save();
            }

            $invoice->items()->delete();
            $invoice->delete();
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Prior Invoices for the customer (Load dropdown on Sales Return create).
     */
    public function ajaxCustomerInvoices(string $customer)
    {
        $rows = Invoice::query()
            ->where('customer_id', $customer)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get(['id', 'invoice_no', 'date', 'total_amount']);

        return response()->json($rows->map(function (Invoice $i) {
            return [
                'id' => $i->id,
                'invoice_no' => $i->invoice_no,
                'date' => $i->date,
                'total_amount' => (float) ($i->total_amount ?? 0),
            ];
        }));
    }

    /**
     * Full Invoice header + line items for copying into a new Sales Return form.
     */
    public function ajaxInvoiceDetails(string $invoice)
    {
        $model = Invoice::with(['items.product'])->findOrFail($invoice);

        $items = $model->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => (float) $item->qty,
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
            'invoice_no' => $model->invoice_no,
            'address' => $model->address,
            'delivery_destination' => $model->delivery_destination,
            'date' => $model->date,
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
            'invoice' => $header,
            'items' => $items,
        ]);
    }
}
