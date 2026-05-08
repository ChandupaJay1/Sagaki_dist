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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
            'address' => 'nullable|string',
            'location' => 'nullable|string',
            'mobile_no' => 'required|string|max:20|unique:customers,mobile_no',
            'main_office_no' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:active,inactive,pending',
        ], [
            'mobile_no.unique' => 'The mobile number is already registered.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'error' => 'Validation Error'
            ], 422);
        }

        $validated = $validator->validated();

        // Add default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        // Check for email, if not provided, generate a dummy one or handle based on migration
        $validated['email'] = $request->email ?? 'cust_' . \Illuminate\Support\Str::random(8) . '@example.com';

        // Handle rep_id from authenticated user if they are a ref
        if ($request->user() && $request->user()->role === 'ref') {
            $validated['rep_id'] = $request->user()->id;
        }

        // Generate a code if not provided
        $validated['code'] = 'CUST-' . strtoupper(\Illuminate\Support\Str::random(6));

        Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer registered successfully!',
            'data' => null
        ], 200);
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
