<?php

namespace App\Http\Controllers;

use App\Models\ProductSubCategory;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = ProductSubCategory::with('category')->orderBy('name')->paginate(10);
        return view('product_sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = ItemCategory::where('is_active', true)->orderBy('name')->get();
        return view('product_sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_sub_categories,name'],
            'item_category_id' => ['nullable', 'exists:item_categories,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        ProductSubCategory::create($validated);
        return redirect()->route('product-sub-categories.index')->with('success', 'Product Sub Category created successfully.');
    }

    public function edit(ProductSubCategory $productSubCategory)
    {
        $categories = ItemCategory::where('is_active', true)->orderBy('name')->get();
        return view('product_sub_categories.edit', ['subCategory' => $productSubCategory, 'categories' => $categories]);
    }

    public function update(Request $request, ProductSubCategory $productSubCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_sub_categories,name,' . $productSubCategory->id],
            'item_category_id' => ['nullable', 'exists:item_categories,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        $productSubCategory->update($validated);
        return redirect()->route('product-sub-categories.index')->with('success', 'Product Sub Category updated successfully.');
    }

    public function destroy(ProductSubCategory $productSubCategory)
    {
        $productSubCategory->delete();
        return redirect()->route('product-sub-categories.index')->with('success', 'Product Sub Category deleted successfully.');
    }
}

