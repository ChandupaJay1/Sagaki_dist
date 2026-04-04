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
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated) {
            $salesReturn = SalesReturn::create($validated);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $salesReturn->items()->create($item);
                }
            }
        });

        return redirect()->route('sales-returns.index')->with('success', 'Sales Return created successfully.');
    }

    public function show($id)
    {
        $return = SalesReturn::with(['customer', 'items.product'])->findOrFail($id);
        return view('sales_returns.show', compact('return'));
    }

    public function edit($id)
    {
        $return = SalesReturn::with('items')->findOrFail($id);
        $customers = Customer::orderBy('company_name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales_returns.edit', compact('return', 'customers', 'reps', 'terms', 'locations', 'products'));
    }

    public function update(Request $request, $id)
    {
        $salesReturn = SalesReturn::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated, $salesReturn) {
            $salesReturn->update($validated);

            $salesReturn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $salesReturn->items()->create($item);
                }
            }
        });

        return redirect()->route('sales-returns.index')->with('success', 'Sales Return updated successfully.');
    }

    public function destroy($id)
    {
        $salesReturn = SalesReturn::findOrFail($id);
        $salesReturn->items()->delete();
        $salesReturn->delete();
        return redirect()->route('sales-returns.index')->with('success', 'Sales Return deleted successfully.');
    }
}
