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
        $vendors = Vendor::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $reps = User::where('is_active', 1)->orderBy('name')->get();
        $terms = PaymentTerm::orderBy('days')->get();
        return view('grn_returns.create', compact('vendors', 'products', 'units', 'locations', 'reps', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'nullable|date',
            'total_amount' => 'nullable|numeric',
        ]);

        GrnReturn::create($request->all());
        return redirect()->route('grn-returns.index')->with('success', 'GRN Return created successfully.');
    }
}
