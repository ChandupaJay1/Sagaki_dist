<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with('territories')->orderBy('name')->paginate(10);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        $territories = \App\Models\Territory::where('is_active', true)->orderBy('name')->get();
        return view('areas.create', compact('territories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:areas,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Area::create($validated);

        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    public function edit(Area $area)
    {
        $territories = \App\Models\Territory::where('is_active', true)->orderBy('name')->get();
        return view('areas.edit', compact('area', 'territories'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:areas,name,' . $area->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:areas,code,' . $area->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $area->update($validated);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
