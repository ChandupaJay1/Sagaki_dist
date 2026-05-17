<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Vendor::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:vendors,email",
            "phone" => "required|string|max:20",
            "address" => "nullable|string",
        ]);

        $vendor = Vendor::create($validated);

        return response()->json($vendor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(["message" => "Vendor not found"], 404);
        }

        return response()->json($vendor, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(["message" => "Vendor not found"], 404);
        }

        $validated = $request->validate([
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:vendors,email," . $id,
            "phone" => "sometimes|required|string|max:20",
            "address" => "nullable|string",
        ]);

        $vendor->update($validated);

        return response()->json($vendor, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(["message" => "Vendor not found"], 404);
        }

        $vendor->delete();

        return response()->json(
            ["message" => "Vendor deleted successfully"],
            200,
        );
    }

    public function getOutstandingBills($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(["message" => "Vendor not found"], 404);
        }

        // ── TOP TABLE: Outstanding GRNs ────────────────────────────────────────
        // Business rule: a GRN must be APPROVED before it can be settled via
        // the Payments module. Pending GRNs are intentionally excluded here so
        // they cannot be selected for payment until a supervisor approves them.
        $bills = \App\Models\Grn::where("vendor_id", $id)
            ->where("status", "Approved")
            ->orderBy("date", "desc")
            ->get()
            ->map(function ($grn) {
                $paid = \App\Models\PayBillItem::where("grn_id", $grn->id)->sum(
                    "amount_to_pay",
                );
                $dueAmount = round($grn->total_amount - $paid, 2);

                return [
                    "id" => $grn->id,
                    "date" => $grn->date,
                    "due_date" => $grn->due_date,
                    "type" => "GRN",
                    "grn_no" =>
                        $grn->manual_no ?: ($grn->reference_no ?: $grn->grn_no),
                    "original_amount" => round((float) $grn->total_amount, 2),
                    "total_amount" => $dueAmount,
                ];
            })
            ->filter(function ($item) {
                return $item["total_amount"] > 0.01;
            })
            ->values();

        // ── BOTTOM TABLE: Credits (Returns + Overpayments) ─────────────────────

        // 1. GRN Returns — items sent back to the supplier
        $grnReturns = \App\Models\GrnReturn::where("vendor_id", $id)
            ->where("total_amount", ">", 0.01)
            ->orderBy("date", "desc")
            ->get()
            ->map(function ($return) {
                return [
                    "id" => $return->id,
                    "date" => $return->date,
                    "ref_no" => $return->return_no,
                    "type" => "Return",
                    "total_amount" => round((float) $return->total_amount, 2),
                ];
            })
            ->toBase(); // Convert to base collection to avoid Eloquent merge issues

        // 2. Past Payments — overpayments or direct payments never fully set off
        //    against a specific GRN. We find PayBill records for this vendor
        //    where total_amount paid exceeds the sum of allocated PayBillItem amounts.
        //    Items are eager-loaded to avoid N+1 queries and null-reference crashes.
        $pastPayments = \App\Models\PayBill::where("vendor_id", $id)
            ->where("type", "Supplier")
            ->with("items")
            ->orderBy("date", "desc")
            ->get()
            ->map(function ($payment) {
                $totalPaid = (float) ($payment->total_amount ?? 0);
                // Defensive: Ensure items is treated as a collection and cast sum to float
                $allocated = (float) ($payment->items ? $payment->items->sum("amount_to_pay") : 0);
                $unallocated = round($totalPaid - $allocated, 2);

                if ($unallocated > 0.01) {
                        $methodLabel = $payment->payment_method ?? 'Cash';
                        return [
                            "id" => $payment->id,
                            "date" => $payment->date,
                            "ref_no" => $payment->voucher_no ?? ("PAY-" . $payment->id),
                            "type" => 'Payment (' . trim($methodLabel) . ')',
                            "total_amount" => $unallocated,
                        ];
                }

                return null;
            })
            ->filter()
            ->values()
            ->toBase();

        // Merge returns and payments into a single base collection
        $credits = $grnReturns->merge($pastPayments)->values();

        return response()->json([
            "vendor" => $vendor,
            "bills" => $bills,
            "credits" => $credits,
        ]);
    }
}
