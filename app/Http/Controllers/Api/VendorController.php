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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
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
            return response()->json(['message' => 'Vendor not found'], 404);
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
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:vendors,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'nullable|string',
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
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully'], 200);
    }

    public function getOutstandingBills($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        // Fetch Outstanding Bills (Note: GRNs are excluded here as they are treated as Credits)
        // We only fetch records that are explicitly marked as Bills or Invoices from the supplier if applicable.
        // Currently, we return an empty collection for bills because GRNs are now moved to Credits.
        $bills = collect([]);

        // Fetch GRN Returns (Credits) for this vendor
        $grnReturns = \App\Models\GrnReturn::where('vendor_id', $id)
            ->where('total_amount', '>', 0.01) // Only show credits with balance
            ->select('id', 'date', 'return_no', 'total_amount')
            ->orderBy('date', 'desc')
            ->get();

        // Fetch Approved GRNs as Credits (Requirement: GRN is a Credit, not a Bill)
        $grnCredits = \App\Models\Grn::where('vendor_id', $id)
            ->where('status', 'Approved') // Requirement: Only Approved GRNs
            ->where('total_amount', '>', 0.01)
            ->select('id', 'date', 'due_date', 'reference_no', 'grn_no as return_no', 'total_amount')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function($grn) {
                // Check if any payment already applied to this GRN (though treated as credit, it might have partial usage)
                $paid = \App\Models\PayBillItem::where('grn_id', $grn->id)->sum('amount_to_pay');
                $grn->total_amount = round($grn->total_amount - $paid, 2);
                return $grn;
            })
            ->filter(function($grn) {
                return $grn->total_amount > 0.01;
            });

        // Merge GRN Returns and Approved GRN Credits
        $credits = $grnReturns->concat($grnCredits)->sortByDesc('date')->values();

        return response()->json([
            'vendor' => $vendor,
            'bills' => $bills,
            'credits' => $credits
        ]);
    }
}
