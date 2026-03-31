<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\Customer;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with('customer')->latest()->paginate(10);
        return view('sales_returns.index', compact('returns'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('sales_returns.create', compact('customers', 'reps', 'terms', 'locations', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'return_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'create_user' => ['nullable', 'string', 'max:255'],
            'total_amount' => ['nullable', 'numeric'],
        ]);

        SalesReturn::create($validated);
        return redirect()->route('sales-returns.index')->with('success', 'Sales Return created successfully.');
    }
}
