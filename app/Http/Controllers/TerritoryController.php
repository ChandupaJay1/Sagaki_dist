<?php

namespace App\Http\Controllers;

use App\Models\Territory;
use App\Models\Area;
use Illuminate\Http\Request;

class TerritoryController extends Controller
{
    public function index()
    {
        $territories = Territory::with('areas')->orderBy('name')->paginate(10);
        return view('territories.index', compact('territories'));
    }

    public function create()
    {
        $areas = Area::whereNull('territory_id')->where('is_active', true)->orderBy('name')->get();
        return view('territories.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:territories,name'],
            'is_active' => ['nullable', 'boolean'],
            'area_ids' => ['nullable', 'array'],
            'area_ids.*' => ['integer', 'exists:areas,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $territory = Territory::create($validated);

        if (!empty($validated['area_ids'])) {
            Area::whereIn('id', $validated['area_ids'])->update(['territory_id' => $territory->id]);
        }

        return redirect()->route('territories.index')->with('success', 'Territory created successfully.');
    }

    public function edit(Territory $territory)
    {
        $areas = Area::where('is_active', true)
            ->where(function ($query) use ($territory) {
                $query->whereNull('territory_id')
                      ->orWhere('territory_id', $territory->id);
            })
            ->orderBy('name')
            ->get();
        $selected = $territory->areas()->pluck('id')->toArray();
        return view('territories.edit', compact('territory', 'areas', 'selected'));
    }

    public function update(Request $request, Territory $territory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:territories,name,' . $territory->id],
            'is_active' => ['nullable', 'boolean'],
            'area_ids' => ['nullable', 'array'],
            'area_ids.*' => ['integer', 'exists:areas,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $territory->update($validated);

        Area::where('territory_id', $territory->id)->update(['territory_id' => null]);
        if (!empty($validated['area_ids'])) {
            Area::whereIn('id', $validated['area_ids'])->update(['territory_id' => $territory->id]);
        }

        return redirect()->route('territories.index')->with('success', 'Territory updated successfully.');
    }

    public function destroy(Territory $territory)
    {
        Area::where('territory_id', $territory->id)->update(['territory_id' => null]);
        $territory->delete();
        return redirect()->route('territories.index')->with('success', 'Territory deleted successfully.');
    }
}
