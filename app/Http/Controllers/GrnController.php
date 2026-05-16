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
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GrnReturnItem;

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

        // --- PO Quantity Upper-Limit Validation ---
        if (!empty($validated['load'])) {
            $sourcePo = PurchaseOrder::with('items')->where('po_no', $validated['load'])->first();
            if ($sourcePo) {
                $errors = [];
                foreach ($request->items as $idx => $item) {
                    if (empty($item['product_id'])) continue;
                    $productId    = (int)$item['product_id'];
                    $submittedQty = (float)($item['qty'] ?? 0);

                    $poItem = $sourcePo->items->firstWhere('product_id', $productId);
                    if (!$poItem) continue; // product not on PO — allow freely

                    $poQty = (float)$poItem->qty;

                    // Sum qty already received in OTHER GRNs that reference this same PO
                    $alreadyReceived = \App\Models\GrnItem::whereHas('grn', function ($q) use ($sourcePo) {
                            $q->where('load', $sourcePo->po_no);
                        })
                        ->where('product_id', $productId)
                        ->sum('qty');

                    $remaining = $poQty - (float)$alreadyReceived;
                    if ($submittedQty > $remaining + 0.0001) { // tiny float tolerance
                        $productName = \App\Models\Product::find($productId)?->name ?? 'Product #' . $productId;
                        $errors['items.' . $idx . '.qty'] = "Qty for '{$productName}' exceeds remaining PO balance. PO Qty: {$poQty}, Already Received: {$alreadyReceived}, Remaining: " . round($remaining, 4) . ", You entered: {$submittedQty}.";
                    }
                }
                if (!empty($errors)) {
                    throw \Illuminate\Validation\ValidationException::withMessages($errors);
                }
            }
        }

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

                    $grnItem = $grn->items()->create([
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

                    // Update Inventory Immediately (Even if Pending)
                    InventoryService::updateStock(
                        $grnItem->product_id,
                        $grnItem->location ?? $grn->location_id,
                        (float)$grnItem->qty,
                        'In',
                        'GRN',
                        $grn->id,
                        "Received via GRN (Pending): " . $grn->grn_no
                    );
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
        $grn = Grn::with('items')->findOrFail($id);

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

            // Reverse old stock
            foreach ($grn->items as $oldItem) {
                InventoryService::reverseStock(
                    $oldItem->product_id,
                    $oldItem->location ?? $grn->location_id,
                    (float)$oldItem->qty,
                    'In Reverse',
                    'GRN',
                    $grn->id,
                    "Reversed stock for GRN Update: " . $grn->grn_no
                );
            }

            $grn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $amountCalc = (float)($item['qty'] ?? 0) * (float)($item['rate'] ?? 0);
                    $discPercent = isset($item['disc_percent']) && $item['disc_percent'] !== '' ? (float)$item['disc_percent'] : 0;
                    $discountVal = isset($item['discount']) && $item['discount'] !== '' ? (float)$item['discount'] : 0;
                    $amountVal = isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : $amountCalc;
                    $totalVal = isset($item['total']) && $item['total'] !== '' ? (float)$item['total'] : ($amountVal - $discountVal);

                    $grnItem = $grn->items()->create([
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

                    // Add new stock
                    InventoryService::updateStock(
                        $grnItem->product_id,
                        $grnItem->location ?? $grn->location_id,
                        (float)$grnItem->qty,
                        'In',
                        'GRN',
                        $grn->id,
                        "Updated via GRN: " . $grn->grn_no
                    );
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
        });

        return redirect()->route('grns.show', $id)->with('success', 'GRN approved successfully.');
    }

    /**
     * Prior GRNs for the vendor (Load dropdown on GRN create).
     */
    public function ajaxVendorGrns(string $vendor)
    {
        $rows = Grn::query()
            ->where('vendor_id', $vendor)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get(['id', 'grn_no', 'date', 'total_amount']);

        return response()->json($rows->map(function (Grn $g) {
            $dateRaw = $g->getRawOriginal('date') ?? $g->date;

            return [
                'id' => $g->id,
                'grn_no' => $g->grn_no,
                'date' => $dateRaw ? \Illuminate\Support\Carbon::parse($dateRaw)->format('Y-m-d') : null,
                'total_amount' => (float) ($g->total_amount ?? 0),
            ];
        }));
    }

    /**
     * Full GRN header + line items for copying into a new GRN form.
     */
    public function ajaxGrnDetails(string $grn)
    {
        $model = Grn::with(['items.product'])->findOrFail($grn);

        // Pre-calculate how much has already been returned against this GRN
        $grnNo = $model->grn_no;
        $returnedQtyByProduct = \App\Models\GrnReturnItem::whereHas('grnReturn', function ($q) use ($grnNo) {
                $q->where('load', $grnNo);
            })
            ->select('product_id', \DB::raw('SUM(qty) as total_returned'))
            ->groupBy('product_id')
            ->pluck('total_returned', 'product_id');

        $items = $model->items->map(function ($item) use ($returnedQtyByProduct) {
            return [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => (float) max(0, $item->qty - ($returnedQtyByProduct[$item->product_id] ?? 0)),
                'original_qty' => (float) $item->qty,
                'returned_qty' => (float) ($returnedQtyByProduct[$item->product_id] ?? 0),
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
            'grn_no' => $model->grn_no,
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
            'manual_no' => $model->manual_no,
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
            'grn' => $header,
            'items' => $items,
        ]);
    }

    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            $grn = Grn::with('items')->findOrFail($id);

            // Reverse stock for all GRNs (since stock is updated immediately on creation)
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

            $grn->items()->delete();
            $grn->delete();
        });

        return redirect()->route('grns.index')->with('success', 'GRN deleted successfully.');
    }
}
