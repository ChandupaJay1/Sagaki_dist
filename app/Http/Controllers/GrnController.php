<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Location;
use App\Models\User;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    public function index()
    {
        $grns = Grn::with('vendor')->latest()->paginate(10);
        return view('grns.index', compact('grns'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        return view('grns.create', compact('vendors', 'products', 'units', 'locations', 'terms', 'reps'));
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
            $grn = Grn::create($validated);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $grn->items()->create($item);
                }
            }
        });

        return redirect()->route('grns.index')->with('success', 'GRN created successfully.');
    }

    public function show($id)
    {
        $grn = Grn::with(['vendor', 'items.product'])->findOrFail($id);
        return view('grns.show', compact('grn'));
    }

    public function edit($id)
    {
        $grn = Grn::with('items')->findOrFail($id);
        $vendors = Vendor::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->where('name', 'not like', '%Transit%')->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        
        return view('grns.edit', compact('grn', 'vendors', 'products', 'units', 'locations', 'terms', 'reps'));
    }

    public function update(Request $request, $id)
    {
        $grn = Grn::findOrFail($id);

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

        \DB::transaction(function () use ($request, $validated, $grn) {
            $grn->update($validated);

            $grn->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $grn->items()->create($item);
                }
            }
        });

        return redirect()->route('grns.index')->with('success', 'GRN updated successfully.');
    }

    public function destroy($id)
    {
        $grn = Grn::findOrFail($id);
        $grn->items()->delete();
        $grn->delete();
        return redirect()->route('grns.index')->with('success', 'GRN deleted successfully.');
    }
}
