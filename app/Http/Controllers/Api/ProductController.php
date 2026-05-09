<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Get stock balance for a product at a specific location.
     */
    public function stock(Request $request, string $id)
    {
        $location = $request->input('location');
        
        // Summing up quantities from various transaction tables
        $grnIn = \Illuminate\Support\Facades\DB::table('grn_items')->where('product_id', $id)->where('location', $location)->sum('qty');
        $invoiceOut = \Illuminate\Support\Facades\DB::table('invoice_items')->where('product_id', $id)->where('location', $location)->sum('qty');
        $grnReturnOut = \Illuminate\Support\Facades\DB::table('grn_return_items')->where('product_id', $id)->where('location', $location)->sum('qty');
        $salesReturnIn = \Illuminate\Support\Facades\DB::table('sales_return_items')->where('product_id', $id)->where('location', $location)->sum('qty');
        $invoiceReturnIn = \Illuminate\Support\Facades\DB::table('invoice_returns')->where('product_id', $id)->sum('qty'); // Assuming all returns are at the same location for now
        
        $stock = ($grnIn + $salesReturnIn + $invoiceReturnIn) - ($invoiceOut + $grnReturnOut);
        
        return response()->json(['stock' => (float) $stock], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
        ]);

        $product->update($validated);

        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
