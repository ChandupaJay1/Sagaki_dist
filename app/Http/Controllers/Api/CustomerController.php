<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }

    public function getOutstandingInvoices($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Fetch Invoices for this customer that are not fully paid
        $invoices = \App\Models\Invoice::where('customer_id', $id)
            ->where('status', '!=', 'Paid')
            ->select('id', 'date', 'invoice_no', 'total_amount')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function($invoice) {
                $paid = \App\Models\PayBillItem::where('invoice_id', $invoice->id)->sum('amount_to_pay');
                $invoice->total_amount = round($invoice->total_amount - $paid, 2);
                return $invoice;
            })
            ->filter(function($invoice) {
                return $invoice->total_amount > 0.01;
            })
            ->values();

        // Fetch Sales Returns (Credits) for this customer
        $credits = \App\Models\SalesReturn::where('customer_id', $id)
            ->where('total_amount', '>', 0.01) // Only show credits with balance
            ->select('id', 'date', 'return_no', 'total_amount')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'customer' => $customer,
            'invoices' => $invoices,
            'credits' => $credits
        ]);
    }
}
