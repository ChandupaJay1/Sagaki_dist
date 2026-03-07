<?php

namespace App\Http\Controllers;

use App\Models\SupplierCategory;
use Illuminate\Http\Request;

class SupplierCategoryController extends Controller
{
    public function index()
    {
        $categories = SupplierCategory::orderBy('name')->paginate(10);
        return view('supplier_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('supplier_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:supplier_categories,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        SupplierCategory::create($validated);
        return redirect()->route('supplier-categories.index')->with('success', 'Supplier Category created successfully.');
    }

    public function edit(SupplierCategory $supplierCategory)
    {
        return view('supplier_categories.edit', compact('supplierCategory'));
    }

    public function update(Request $request, SupplierCategory $supplierCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:supplier_categories,name,' . $supplierCategory->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:supplier_categories,code,' . $supplierCategory->id],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        $supplierCategory->update($validated);
        return redirect()->route('supplier-categories.index')->with('success', 'Supplier Category updated successfully.');
    }

    public function destroy(SupplierCategory $supplierCategory)
    {
        $supplierCategory->delete();
        return redirect()->route('supplier-categories.index')->with('success', 'Supplier Category deleted successfully.');
    }
}
