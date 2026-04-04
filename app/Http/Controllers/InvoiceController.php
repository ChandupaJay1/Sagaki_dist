<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use App\Models\Unit;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        return view('invoices.create', compact('customers', 'products', 'locations', 'units', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'villa_type' => ['nullable', 'string', 'max:255'],
            'meal_plan' => ['nullable', 'string', 'max:255'],
            'no_of_pax' => ['nullable', 'integer'],
            'check_in_date' => ['nullable', 'date'],
            'room_type' => ['nullable', 'string', 'max:255'],
            'check_out_date' => ['nullable', 'date'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated) {
            $invoice = Invoice::create($validated);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $invoice->items()->create($item);
                }
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        
        return view('invoices.edit', compact('invoice', 'customers', 'products', 'locations', 'units', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_destination' => ['nullable', 'string', 'max:255'],
            'load' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'villa_type' => ['nullable', 'string', 'max:255'],
            'meal_plan' => ['nullable', 'string', 'max:255'],
            'no_of_pax' => ['nullable', 'integer'],
            'check_in_date' => ['nullable', 'date'],
            'room_type' => ['nullable', 'string', 'max:255'],
            'check_out_date' => ['nullable', 'date'],
            'header_discount_percent' => ['nullable', 'numeric'],
            'header_discount_amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.rate' => ['required', 'numeric'],
        ]);

        \DB::transaction(function () use ($request, $validated, $invoice) {
            $invoice->update($validated);

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $invoice->items()->create($item);
                }
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
