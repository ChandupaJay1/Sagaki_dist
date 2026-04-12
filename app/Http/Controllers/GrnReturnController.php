<?php

namespace App\Http\Controllers;

use App\Models\GrnReturn;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;

class GrnReturnController extends Controller
{
    public function index()
    {
        $returns = GrnReturn::with('vendor')->latest()->paginate(10);
        return view('grn_returns.index', compact('returns'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        // Generate next Return Number for display
        $lastReturn = GrnReturn::latest()->first();
        if (!$lastReturn) {
            $nextReturnNo = 'GRNR00001';
        } else {
            $lastNo = (int)str_replace('GRNR', '', $lastReturn->return_no);
            $nextReturnNo = 'GRNR' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('grn_returns.create', compact('vendors', 'products', 'units', 'locations', 'reps', 'terms', 'accounts', 'nextReturnNo'));
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
            'vendor_id' => ['required', 'exists:vendors,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'invoice_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'dispatch_no' => ['nullable', 'string'],
            'order_by' => ['nullable', 'string', 'max:255'],
            'checked_by' => ['nullable', 'string', 'max:255'],
            'rep' => ['nullable', 'string', 'max:255'],
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
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
            'items.*.amount' => ['nullable', 'numeric'],
            'items.*.disc_percent' => ['nullable', 'numeric'],
            'items.*.discount' => ['nullable', 'numeric'],
            'items.*.total' => ['nullable', 'numeric'],
            'items.*.location' => ['nullable', 'string'],
            'items.*.unit' => ['nullable', 'string'],
        ]);

        \DB::transaction(function () use ($request, $validated) {
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }

            // Generate Return Number
            $lastReturn = GrnReturn::latest()->first();
            if (!$lastReturn) {
                $data['return_no'] = 'GRNR00001';
            } else {
                $lastNo = (int)str_replace('GRNR', '', $lastReturn->return_no);
                $data['return_no'] = 'GRNR' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
            }
            
            $grnReturn = GrnReturn::create($data);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $grnReturn->items()->create([
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

        if ($request->action === 'save_and_new') {
            return redirect()->route('grn-returns.create')->with('success', 'GRN Return created successfully.');
        }

        return redirect()->route('grn-returns.index')->with('success', 'GRN Return created successfully.');
    }

    public function show($id)
    {
        $return = GrnReturn::with(['vendor', 'items.product'])->findOrFail($id);
        return view('grn_returns.show', compact('return'));
    }

    public function edit($id)
    {
        $return = GrnReturn::with('items')->findOrFail($id);
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        
        return view('grn_returns.edit', compact('return', 'vendors', 'products', 'units', 'locations', 'reps', 'terms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $grnReturn = GrnReturn::findOrFail($id);

        if ($request->has('items')) {
            $items = collect($request->items)->filter(function($item) {
                return !empty($item['product_id']);
            })->toArray();
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'invoice_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'dispatch_no' => ['nullable', 'string'],
            'order_by' => ['nullable', 'string', 'max:255'],
            'checked_by' => ['nullable', 'string', 'max:255'],
            'rep' => ['nullable', 'string', 'max:255'],
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

        \DB::transaction(function () use ($request, $validated, $grnReturn) {
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            
            $grnReturn->update($data);

            $grnReturn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $grnReturn->items()->create([
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

        return redirect()->route('grn-returns.index')->with('success', 'GRN Return updated successfully.');
    }

    public function destroy($id)
    {
        $grnReturn = GrnReturn::findOrFail($id);
        $grnReturn->items()->delete();
        $grnReturn->delete();
        return redirect()->route('grn-returns.index')->with('success', 'GRN Return deleted successfully.');
    }
}
