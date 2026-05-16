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

        // Fetch Outstanding GRNs.
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

                // CRITICAL: Frontend looks for 'grn_no' and 'total_amount'
                return [
                    "id" => $grn->id,
                    "date" => $grn->date,
                    "grn_no" =>
                        $grn->manual_no ?: ($grn->reference_no ?: $grn->grn_no),
                    "total_amount" => $dueAmount,
                ];
            })
            ->filter(function ($item) {
                return $item["total_amount"] > 0.01;
            })
            ->values();

        // Fetch GRN Returns (Credits) for this vendor
        // We ensure this list is EMPTY for our test to avoid confusion
        $grnReturns = \App\Models\GrnReturn::where("vendor_id", $id)
            ->where("total_amount", ">", 0.01)
            ->select("id", "date", "return_no", "total_amount")
            ->orderBy("date", "desc")
            ->get();

        return response()->json([
            "vendor" => $vendor,
            "bills" => $bills, // These will show in TOP table
            "invoices" => $bills, // Backup key just in case
            "credits" => $grnReturns, // These will show in BOTTOM table
        ]);
    }
}
