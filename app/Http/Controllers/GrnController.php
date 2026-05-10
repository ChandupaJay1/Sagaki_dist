<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;
use App\Services\InventoryService;

class GrnController extends Controller
{
    public function index()
    {
        $grns = Grn::with('vendor')->latest()->paginate(10);
        return view('grns.index', compact('grns'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        // Generate next GRN Number for display
        $lastGrn = Grn::latest()->first();
        if (!$lastGrn) {
            $nextGrnNo = 'GRN00001';
        } else {
            $lastNo = (int)str_replace('GRN', '', $lastGrn->grn_no);
            $nextGrnNo = 'GRN' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('grns.create', compact('vendors', 'products', 'units', 'locations', 'terms', 'reps', 'accounts', 'nextGrnNo'));
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
            'order_by' => ['nullable', 'string', 'max:255'],
            'checked_by' => ['nullable', 'string', 'max:255'],
            'rep' => ['nullable', 'string', 'max:255'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'payment_term_id' => ['nullable', 'exists:terms,id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'terms' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'attent' => ['nullable', 'string'],
            'manual_no' => ['nullable', 'string'],
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

            // Generate GRN Number
            $lastGrn = Grn::latest()->first();
            if (!$lastGrn) {
                $data['grn_no'] = 'GRN00001';
            } else {
                $lastNo = (int)str_replace('GRN', '', $lastGrn->grn_no);
                $data['grn_no'] = 'GRN' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
            }

            $data['status'] = 'Pending';
            $grn = Grn::create($data);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $grn->items()->create([
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
            return redirect()->route('grns.create')->with('success', 'GRN created successfully.');
        }

        return redirect()->route('grns.index')->with('success', 'GRN created successfully.');
    }

    public function show($id)
    {
        $grn = Grn::with(['vendor', 'items.product'])->findOrFail($id);
        return view('grns.show', compact('grn'));
    }

    public function edit($id)
    {
        $grn = Grn::with('items')->findOrFail($id);
        
        if ($grn->status === 'Approved') {
            return redirect()->route('grns.show', $id)->with('error', 'Approved GRNs cannot be edited.');
        }

        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        
        return view('grns.edit', compact('grn', 'vendors', 'products', 'units', 'locations', 'terms', 'reps', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $grn = Grn::findOrFail($id);

        if ($grn->status === 'Approved') {
            return redirect()->route('grns.show', $id)->with('error', 'Approved GRNs cannot be updated.');
        }

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
            'manual_no' => ['nullable', 'string'],
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

        \DB::transaction(function () use ($request, $validated, $grn) {
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }
            $grn->update($data);

            $grn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);
                    $grn->items()->create([
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

        return redirect()->route('grns.index')->with('success', 'GRN updated successfully.');
    }

    public function approve($id)
    {
        $grn = Grn::with('items')->findOrFail($id);

        if ($grn->status === 'Approved') {
            return redirect()->back()->with('error', 'GRN is already approved.');
        }

        \DB::transaction(function () use ($grn) {
            $grn->status = 'Approved';
            $grn->save();

            // Update Inventory
            foreach ($grn->items as $item) {
                InventoryService::updateStock(
                    $item->product_id,
                    $item->location ?? $grn->location_id,
                    (float)$item->qty,
                    'In',
                    'GRN',
                    $grn->id,
                    "Received via GRN: " . $grn->grn_no
                );
            }
        });

        return redirect()->route('grns.show', $id)->with('success', 'GRN approved and stock updated successfully.');
    }

    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            $grn = Grn::findOrFail($id);
            
            // Reverse stock ONLY if it was Approved
            if ($grn->status === 'Approved') {
                foreach ($grn->items as $item) {
                    InventoryService::reverseStock(
                        $item->product_id,
                        $item->location ?? $grn->location_id,
                        (float)$item->qty,
                        'In Reverse',
                        'GRN',
                        $grn->id,
                        "Reversed stock due to GRN deletion: " . $grn->grn_no
                    );
                }
            }

            $grn->items()->delete();
            $grn->delete();
        });
        
        return redirect()->route('grns.index')->with('success', 'GRN deleted successfully.');
    }
}
