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
        $vendors = Vendor::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        return view('grns.create', compact('vendors', 'products', 'units', 'locations', 'terms', 'reps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'nullable|date',
            'total_amount' => 'nullable|numeric',
        ]);

        Grn::create($request->all());
        return redirect()->route('grns.index')->with('success', 'GRN created successfully.');
    }
}
