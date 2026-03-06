<?php

namespace App\Http\Controllers;

use App\Models\ModelMaster;
use Illuminate\Http\Request;

class ModelMasterController extends Controller
{
    public function index()
    {
        $models = ModelMaster::orderBy('name')->paginate(10);
        return view('model_masters.index', compact('models'));
    }

    public function create()
    {
        return view('model_masters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:model_masters,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        ModelMaster::create($validated);
        return redirect()->route('model-masters.index')->with('success', 'Model created successfully.');
    }

    public function edit(ModelMaster $modelMaster)
    {
        return view('model_masters.edit', compact('modelMaster'));
    }

    public function update(Request $request, ModelMaster $modelMaster)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:model_masters,name,' . $modelMaster->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:model_masters,code,' . $modelMaster->id],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        $modelMaster->update($validated);
        return redirect()->route('model-masters.index')->with('success', 'Model updated successfully.');
    }

    public function destroy(ModelMaster $modelMaster)
    {
        $modelMaster->delete();
        return redirect()->route('model-masters.index')->with('success', 'Model deleted successfully.');
    }
}
