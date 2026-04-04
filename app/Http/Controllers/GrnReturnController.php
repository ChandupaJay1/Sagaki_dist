<?php

namespace App\Http\Controllers;

use App\Models\GrnReturn;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class GrnReturnController extends Controller
{
    public function index()
    {
        $returns = GrnReturn::with('vendor')->latest()->paginate(10);
        return view('grn_returns.index', compact('returns'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        return view('grn_returns.create', compact('vendors', 'products', 'units', 'locations', 'reps', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
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
            $grnReturn = GrnReturn::create($validated);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $grnReturn->items()->create($item);
                }
            }
        });

        return redirect()->route('grn-returns.index')->with('success', 'GRN Return created successfully.');
    }

    public function show($id)
    {
        $return = GrnReturn::with(['vendor', 'items.product'])->findOrFail($id);
        return view('grn_returns.show', compact('return'));
    }

    public function edit($id)
    {
        $return = GrnReturn::with('items')->findOrFail($id);
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        
        return view('grn_returns.edit', compact('return', 'vendors', 'products', 'units', 'locations', 'reps', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $grnReturn = GrnReturn::findOrFail($id);

        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
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

        \DB::transaction(function () use ($request, $validated, $grnReturn) {
            $grnReturn->update($validated);

            $grnReturn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $grnReturn->items()->create($item);
                }
            }
        });

        return redirect()->route('grn-returns.index')->with('success', 'GRN Return updated successfully.');
    }

    public function destroy($id)
    {
        $grnReturn = GrnReturn::findOrFail($id);
        $grnReturn->items()->delete();
        $grnReturn->delete();
        return redirect()->route('grn-returns.index')->with('success', 'GRN Return deleted successfully.');
    }
}
