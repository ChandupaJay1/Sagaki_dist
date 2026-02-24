<?php

namespace App\Http\Controllers;

use App\Models\CustomerCategory;
use Illuminate\Http\Request;

class CustomerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CustomerCategory::orderBy('name')->paginate(10);

        return view('customer_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:customer_categories,code'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        CustomerCategory::create($validated);

        return redirect()
            ->route('customer-categories.index')
            ->with('success', 'Customer Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerCategory $customerCategory)
    {
        return view('customer_categories.edit', compact('customerCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerCategory $customerCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:customer_categories,code,' . $customerCategory->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $customerCategory->update($validated);

        return redirect()
            ->route('customer-categories.index')
            ->with('success', 'Customer Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerCategory $customerCategory)
    {
        $customerCategory->delete();

        return redirect()
            ->route('customer-categories.index')
            ->with('success', 'Customer Category deleted successfully.');
    }
}

