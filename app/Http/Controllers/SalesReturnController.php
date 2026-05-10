<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\Customer;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;
use App\Services\InventoryService;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with('customer')->latest()->paginate(10);
        return view('sales_returns.index', compact('returns'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        return view('sales_returns.create', compact('customers', 'reps', 'terms', 'locations', 'products', 'accounts'));
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
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'order_by' => ['nullable', 'string', 'max:255'],
            'checked_by' => ['nullable', 'string', 'max:255'],
            'rep' => ['nullable', 'string', 'max:255'],
            'ship_via' => ['nullable', 'string', 'max:255'],
            'create_user' => ['nullable', 'string', 'max:255'],
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
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $data['return_no'] = $validated['reference_no'] ?? null;
            
            $salesReturn = SalesReturn::create($data);

            // Update Customer Balance (Sales Return decreases balance)
            $customer = Customer::find($salesReturn->customer_id);
            if ($customer) {
                $customer->balance -= (float)($salesReturn->total_amount ?? 0);
                $customer->save();
            }

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $salesReturn->items()->create([
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
                        $item['location'] ?? $salesReturn->location_id,
                        (float)$item['qty'],
                        'In',
                        'Sales Return',
                        $salesReturn->id,
                        "Stock returned via Sales Return: " . ($salesReturn->return_no ?? $salesReturn->id)
                    );
                }
            }
        });

        return redirect()->route('sales-returns.index')->with('success', 'Sales Return created successfully.');
    }

    public function show($id)
    {
        $return = SalesReturn::with(['customer', 'items.product'])->findOrFail($id);
        return view('sales_returns.show', compact('return'));
    }

    public function edit($id)
    {
        $return = SalesReturn::with('items')->findOrFail($id);
        $customers = Customer::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        return view('sales_returns.edit', compact('return', 'customers', 'reps', 'terms', 'locations', 'products', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        if ($request->has('items')) {
            $items = collect($request->items)->filter(function($item) {
                return !empty($item['product_id']);
            })->toArray();
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'order_by' => ['nullable', 'string', 'max:255'],
            'checked_by' => ['nullable', 'string', 'max:255'],
            'rep' => ['nullable', 'string', 'max:255'],
            'ship_via' => ['nullable', 'string', 'max:255'],
            'create_user' => ['nullable', 'string', 'max:255'],
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

        \DB::transaction(function () use ($request, $validated, $salesReturn) {
            $oldCustomerId = $salesReturn->customer_id;
            $oldTotalAmount = (float)($salesReturn->total_amount ?? 0);

            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $data['return_no'] = $validated['reference_no'] ?? null;
            
            $salesReturn->update($data);
            $newTotalAmount = (float)($salesReturn->total_amount ?? 0);
            $newCustomerId = $salesReturn->customer_id;

            // Update Customer Balance
            if ($oldCustomerId == $newCustomerId) {
                $customer = Customer::find($newCustomerId);
                if ($customer) {
                    $customer->balance -= ($newTotalAmount - $oldTotalAmount);
                    $customer->save();
                }
            } else {
                // Customer changed
                $oldCustomer = Customer::find($oldCustomerId);
                if ($oldCustomer) {
                    $oldCustomer->balance += $oldTotalAmount; // Reverse old return
                    $oldCustomer->save();
                }
                $newCustomer = Customer::find($newCustomerId);
                if ($newCustomer) {
                    $newCustomer->balance -= $newTotalAmount; // Apply new return
                    $newCustomer->save();
                }
            }

            // Reverse old stock
            foreach ($salesReturn->items as $oldItem) {
                InventoryService::reverseStock(
                    $oldItem->product_id,
                    $oldItem->location ?? $salesReturn->location_id,
                    (float)$oldItem->qty,
                    'In Reverse',
                    'Sales Return',
                    $salesReturn->id,
                    "Reversed old Sales Return item for update: " . ($salesReturn->return_no ?? $salesReturn->id)
                );
            }

            $salesReturn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $salesReturn->items()->create([
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
                        $item['location'] ?? $salesReturn->location_id,
                        (float)$item['qty'],
                        'In',
                        'Sales Return',
                        $salesReturn->id,
                        "Updated via Sales Return: " . ($salesReturn->return_no ?? $salesReturn->id)
                    );
                }
            }
        });

        return redirect()->route('sales-returns.index')->with('success', 'Sales Return updated successfully.');
    }

    public function destroy($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);
        
        \DB::transaction(function () use ($salesReturn) {
            // Update Customer Balance (Reverse Sales Return)
            $customer = Customer::find($salesReturn->customer_id);
            if ($customer) {
                $customer->balance += (float)($salesReturn->total_amount ?? 0);
                $customer->save();
            }

            // Reverse stock
            foreach ($salesReturn->items as $item) {
                InventoryService::reverseStock(
                    $item->product_id,
                    $item->location ?? $salesReturn->location_id,
                    (float)$item->qty,
                    'In Reverse',
                    'Sales Return',
                    $salesReturn->id,
                    "Reversed stock due to Sales Return deletion: " . ($salesReturn->return_no ?? $salesReturn->id)
                );
            }

            $salesReturn->items()->delete();
            $salesReturn->delete();
        });

        return redirect()->route('sales-returns.index')->with('success', 'Sales Return deleted successfully.');
    }
}
