<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(10);
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name'],
            'contact_no' => ['nullable', 'string', 'max:20'],
            'vehicle_no' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        Location::create($validated);
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        if ($location->name === 'Main Stock') {
            return redirect()->route('locations.index')->with('error', 'Main Stock location cannot be edited.');
        }
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        if ($location->name === 'Main Stock') {
            return redirect()->route('locations.index')->with('error', 'Main Stock location cannot be updated.');
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name,' . $location->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:locations,code,' . $location->id],
            'contact_no' => ['nullable', 'string', 'max:20'],
            'vehicle_no' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        $location->update($validated);
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        if ($location->name === 'Main Stock') {
            return redirect()->route('locations.index')->with('error', 'Main Stock location cannot be deleted.');
        }
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
