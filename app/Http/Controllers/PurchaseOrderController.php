<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

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
        return view('purchase_orders.create', compact('vendors', 'reps', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'po_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'purchase_req_no' => ['nullable', 'string', 'max:255'],
            'total_amount' => ['nullable', 'numeric'],
        ]);

        PurchaseOrder::create($validated);
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');
    }
}
