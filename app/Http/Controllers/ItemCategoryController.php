<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::orderBy('name')->paginate(10);

        return view('item_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('item_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:item_categories,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        ItemCategory::create($validated);

        return redirect()->route('item-categories.index')->with('success', 'Item Category created successfully.');
    }

    public function edit(ItemCategory $itemCategory)
    {
        return view('item_categories.edit', compact('itemCategory'));
    }

    public function update(Request $request, ItemCategory $itemCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:item_categories,name,' . $itemCategory->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:item_categories,code,' . $itemCategory->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $itemCategory->update($validated);

        return redirect()->route('item-categories.index')->with('success', 'Item Category updated successfully.');
    }

    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();

        return redirect()->route('item-categories.index')->with('success', 'Item Category deleted successfully.');
    }
}
