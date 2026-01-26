<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = \App\Models\Vendor::all();
        $mainProducts = \App\Models\Product::where('is_main_product', true)->get();
        return view('products.create', compact('vendors', 'mainProducts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products|max:255',
            'sku' => 'nullable|string|max:255',
            'is_main_product' => 'boolean',
            'main_product_id' => 'nullable|exists:products,id',
            'category' => 'nullable|string',
            'sub_category' => 'nullable|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'description' => 'nullable|string',
            
            'floor' => 'nullable|string',
            'rack' => 'nullable|string',
            'row' => 'nullable|string',
            'bin' => 'nullable|string',
            'location' => 'nullable|string',
            
            'reorder_point' => 'nullable|numeric',
            'alert_quantity' => 'nullable|numeric',
            'qty_in_bulk' => 'nullable|numeric',

            'vendor_id' => 'nullable|exists:vendors,id',
            
            'cost' => 'nullable|numeric',
            'max_sale_price' => 'nullable|numeric',
            'min_sale_price' => 'nullable|numeric',
            'image_path' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Handle boolean fields which might not be present in request
        $booleans = [
            'is_main_product', 'is_purchase', 'is_sale', 'is_production', 
            'is_serialized', 'is_stock_report', 'is_price_level', 'is_multi_price'
        ];
        
        foreach ($booleans as $boolField) {
            $validated[$boolField] = $request->has($boolField);
        }

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        \App\Models\Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $vendors = \App\Models\Vendor::all();
        $mainProducts = \App\Models\Product::where('is_main_product', true)->where('id', '!=', $id)->get();
        return view('products.edit', compact('product', 'vendors', 'mainProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code,' . $id,
            'sku' => 'nullable|string|max:255',
            'is_main_product' => 'boolean',
            'main_product_id' => 'nullable|exists:products,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'cost' => 'nullable|numeric',
            'image_path' => 'nullable|image|max:5120',
        ]);

        // Handle boolean fields
        $booleans = [
            'is_main_product', 'is_purchase', 'is_sale', 'is_production', 
            'is_serialized', 'is_stock_report', 'is_price_level', 'is_multi_price'
        ];
        
        foreach ($booleans as $boolField) {
            $validated[$boolField] = $request->has($boolField);
        }

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $formattedData = array_merge($request->except(['_token', '_method', 'image_path']), $validated);
        $product->update($formattedData);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
