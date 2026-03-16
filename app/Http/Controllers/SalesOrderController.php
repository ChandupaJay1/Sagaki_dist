<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->latest()->paginate(10);
        return view('sales_orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get(); // Assuming reps are users
        $terms = PaymentTerm::orderBy('days')->get();
        return view('sales_orders.create', compact('customers', 'locations', 'reps', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'order_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string'],
            'total_amount' => ['nullable', 'numeric'],
        ]);

        SalesOrder::create($validated);
        return redirect()->route('sales-orders.index')->with('success', 'Sales Order created successfully.');
    }
}

