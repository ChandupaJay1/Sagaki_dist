<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Location;
use App\Models\Product;
use App\Models\Grn;
use App\Models\GrnItem;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Models\Account;

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
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        // Generate next PO Number for display
        $lastOrder = PurchaseOrder::latest()->first();
        if (!$lastOrder) {
            $nextPoNo = 'POND00001';
        } else {
            $lastNo = (int)str_replace('POND', '', $lastOrder->po_no);
            $nextPoNo = 'POND' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('purchase_orders.create', compact('vendors', 'reps', 'terms', 'locations', 'products', 'accounts', 'nextPoNo'));
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

            // Generate PO Number
            $lastOrder = PurchaseOrder::latest()->first();
            if (!$lastOrder) {
                $data['po_no'] = 'POND00001';
            } else {
                $lastNo = (int)str_replace('POND', '', $lastOrder->po_no);
                $data['po_no'] = 'POND' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
            }

            $data['status'] = 'Pending';

            $purchaseOrder = PurchaseOrder::create($data);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $qty = (float)($item['qty'] ?? 0);
                    $rate = (float)($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $discPercent = (float)($item['disc_percent'] ?? 0);
                    $discount = (float)($item['discount'] ?? 0);

                    if ($discPercent > 0 && $discount == 0) {
                        $discount = ($amount * $discPercent) / 100;
                    }

                    $total = $amount - $discount;

                    $purchaseOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'description' => $item['description'] ?? '',
                        'qty' => $qty,
                        'rate' => $rate,
                        'amount' => $amount,
                        'disc_percent' => $discPercent,
                        'discount' => $discount,
                        'total' => $total,
                        'location' => $item['location'] ?? null,
                        'unit' => $item['unit'] ?? null,
                    ]);
                }
            }
        });

        $po = PurchaseOrder::latest()->first();
        $message = "Purchase Order " . ($po ? $po->po_no : '') . " created successfully.";

        if ($request->action === 'save_and_new') {
            return redirect()->route('purchase-orders.create')->with('success', $message);
        }

        return redirect()->route('purchase-orders.index')->with('success', $message);
    }

    /**
     * Prior Purchase Orders for the vendor (Load dropdown on PO create).
     */
    public function ajaxVendorPurchaseOrders(string $vendor)
    {
        $rows = PurchaseOrder::query()
            ->where('vendor_id', $vendor)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get(['id', 'po_no', 'date', 'total_amount']);

        return response()->json($rows->map(function (PurchaseOrder $p) {
            $dateRaw = $p->getRawOriginal('date') ?? $p->date;

            return [
                'id' => $p->id,
                'po_no' => $p->po_no,
                'date' => $dateRaw ? \Illuminate\Support\Carbon::parse($dateRaw)->format('Y-m-d') : null,
                'total_amount' => (float) ($p->total_amount ?? 0),
            ];
        }));
    }

    /**
     * Full Purchase Order header + line items for copying into a new PO form.
     */
    public function ajaxPurchaseOrderDetails(string $po)
    {
        $model = PurchaseOrder::with(['items.product'])->findOrFail($po);

        // Pre-calculate how much has already been received against this PO
        // by summing GrnItem qty across all GRNs whose `load` field = this PO's po_no
        $poNo = $model->po_no;
        $receivedQtyByProduct = GrnItem::whereHas('grn', function ($q) use ($poNo) {
                $q->where('load', $poNo);
            })
            ->select('product_id', \DB::raw('SUM(qty) as total_received'))
            ->groupBy('product_id')
            ->pluck('total_received', 'product_id');

        $items = $model->items->map(function ($item) use ($receivedQtyByProduct) {
            return [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => (float) max(0, $item->qty - ($receivedQtyByProduct[$item->product_id] ?? 0)),
                'original_qty' => (float) $item->qty,
                'received_qty' => (float) ($receivedQtyByProduct[$item->product_id] ?? 0),
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
            'po_no' => $model->po_no,
            'address' => $model->address,
            'delivery_destination' => $model->delivery_destination,
            'load' => $model->load,
            'reference_no' => $model->reference_no,
            'date' => $dateStr($model->date),
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
            'po' => $header,
            'items' => $items,
        ]);
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
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();

        return view('purchase_orders.edit', compact('order', 'vendors', 'reps', 'terms', 'locations', 'products', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

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

        \DB::transaction(function () use ($request, $validated, $purchaseOrder) {
            $data = Arr::except($validated, ['items']);
            foreach (['subtotal', 'header_discount_percent', 'header_discount_amount', 'tax_amount', 'sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount', 'total_amount'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $data[$field] ?: 0;
                }
            }

            $purchaseOrder->update($data);

            $purchaseOrder->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $qty = (float)($item['qty'] ?? 0);
                    $rate = (float)($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $discPercent = (float)($item['disc_percent'] ?? 0);
                    $discount = (float)($item['discount'] ?? 0);

                    if ($discPercent > 0 && $discount == 0) {
                        $discount = ($amount * $discPercent) / 100;
                    }

                    $total = $amount - $discount;

                    $purchaseOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'description' => $item['description'] ?? '',
                        'qty' => $qty,
                        'rate' => $rate,
                        'amount' => $amount,
                        'disc_percent' => $discPercent,
                        'discount' => $discount,
                        'total' => $total,
                        'location' => $item['location'] ?? null,
                        'unit' => $item['unit'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order ' . $purchaseOrder->po_no . ' updated successfully.');
    }

    public function approve($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status === 'Approved') {
            return redirect()->back()->with('error', 'Purchase Order is already approved.');
        }

        $purchaseOrder->status = 'Approved';
        $purchaseOrder->save();

        return redirect()->route('purchase-orders.show', $id)->with('success', 'Purchase Order approved successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
    }
}
