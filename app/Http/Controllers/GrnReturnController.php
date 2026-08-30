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
use App\Services\InventoryService;

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
        $users = User::where('is_active', 1)->orderBy('name')->get();
        $reps = $users;
        $paymentTerms = PaymentTerm::orderBy('days')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        // Generate next Return Number for display
        $lastReturn = GrnReturn::latest()->first();
        if (!$lastReturn || !$lastReturn->return_no) {
            $nextRtnNo = 'GRNR00001';
        } else {
            $lastNo = (int)str_replace('GRNR', '', $lastReturn->return_no);
            $nextRtnNo = 'GRNR' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('grn_returns.create', compact('vendors', 'products', 'units', 'locations', 'users', 'reps', 'paymentTerms', 'accounts', 'nextRtnNo'));
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

        // --- GRN Quantity Upper-Limit Validation for GRN Return ---
        if (!empty($validated['load'])) {
            $sourceGrn = \App\Models\Grn::with('items')->where('grn_no', $validated['load'])->first();
            if ($sourceGrn) {
                $errors = [];
                foreach ($request->items as $idx => $item) {
                    if (empty($item['product_id'])) continue;
                    $productId    = (int)$item['product_id'];
                    $submittedQty = (float)($item['qty'] ?? 0);

                    $grnItem = $sourceGrn->items->firstWhere('product_id', $productId);
                    if (!$grnItem) continue; // product not on source GRN — allow freely

                    $grnQty = (float)$grnItem->qty;

                    // Sum qty already returned in OTHER GRN Returns that reference this same GRN
                    $alreadyReturned = \App\Models\GrnReturnItem::whereHas('grnReturn', function ($q) use ($sourceGrn) {
                            $q->where('load', $sourceGrn->grn_no);
                        })
                        ->where('product_id', $productId)
                        ->sum('qty');

                    $remaining = $grnQty - (float)$alreadyReturned;
                    if ($submittedQty > $remaining + 0.0001) {
                        $productName = \App\Models\Product::find($productId)?->name ?? 'Product #' . $productId;
                        $errors['items.' . $idx . '.qty'] = "Qty for '{$productName}' exceeds returnable GRN balance. GRN Qty: {$grnQty}, Already Returned: {$alreadyReturned}, Remaining: " . round($remaining, 4) . ", You entered: {$submittedQty}.";
                    }
                }
                if (!empty($errors)) {
                    throw \Illuminate\Validation\ValidationException::withMessages($errors);
                }
            }
        }

        $grnReturn = null;
        \DB::transaction(function () use ($request, $validated, &$grnReturn) {
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

                    // Update Inventory
                    InventoryService::updateStock(
                        $item['product_id'],
                        $item['location'] ?? $grnReturn->location_id,
                        -(float)$item['qty'],
                        'Out',
                        'GRN Return',
                        $grnReturn->id,
                        "Returned to Vendor via GRN Return: " . ($grnReturn->return_no ?? $grnReturn->id)
                    );
                }
            }
        });

        if ($request->action === 'save_and_print' && $grnReturn) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'print_url' => route('grn-returns.show', $grnReturn->id) . '?print=true'
                ]);
            }
            return redirect()->route('grn-returns.show', $grnReturn->id)->with('success', 'GRN Return created successfully.');
        }

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

    /**
     * Prior GRN Returns for the vendor (Load dropdown on GRN Return create).
     */
    public function ajaxVendorGrnReturns(string $vendor)
    {
        $rows = GrnReturn::query()
            ->where('vendor_id', $vendor)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get(['id', 'return_no', 'date', 'total_amount']);

        return response()->json($rows->map(function (GrnReturn $r) {
            $dateRaw = $r->getRawOriginal('date') ?? $r->date;

            return [
                'id' => $r->id,
                'return_no' => $r->return_no,
                'date' => $dateRaw ? \Illuminate\Support\Carbon::parse($dateRaw)->format('Y-m-d') : null,
                'total_amount' => (float) ($r->total_amount ?? 0),
            ];
        }));
    }

    /**
     * Full GRN Return header + line items for copying into a new GRN Return form.
     */
    public function ajaxGrnReturnDetails(string $return)
    {
        $model = GrnReturn::with(['items.product'])->findOrFail($return);

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

        $dateStr = static function ($value): ?string {
            if ($value === null || $value === '') {
                return null;
            }
            if ($value instanceof \Carbon\CarbonInterface) {
                return $value->format('Y-m-d');
            }

            return (string) $value;
        };

        $header = [
            'vendor_id' => $model->vendor_id,
            'return_no' => $model->return_no,
            'address' => $model->address,
            'delivery_destination' => $model->delivery_destination,
            'load' => $model->load,
            'reference_no' => $model->reference_no,
            'date' => $dateStr($model->date),
            'invoice_date' => $dateStr($model->invoice_date),
            'expected_date' => $dateStr($model->expected_date),
            'due_date' => $dateStr($model->due_date),
            'order_by' => $model->order_by,
            'checked_by' => $model->checked_by,
            'rep' => $model->rep,
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
            'grn_return' => $header,
            'items' => $items,
        ]);
    }

    public function print()
    {
        $return = GrnReturn::with(['vendor', 'items.product'])->findOrFail($id);
        return view('grn_returns.print', compact('return'));
    }
}
