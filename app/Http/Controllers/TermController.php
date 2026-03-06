<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = PaymentTerm::orderBy('days')->paginate(10);
        return view('terms.index', compact('terms'));
    }

    public function create()
    {
        return view('terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:0', 'unique:terms,days'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        PaymentTerm::create($validated);
        return redirect()->route('terms.index')->with('success', 'Terms created successfully.');
    }

    public function edit(PaymentTerm $term)
    {
        return view('terms.edit', compact('term'));
    }

    public function update(Request $request, PaymentTerm $term)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:0', 'unique:terms,days,' . $term->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:terms,code,' . $term->id],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['code'])) {
            $validated['code'] = 'T' . $validated['days'];
        }
        $term->update($validated);
        return redirect()->route('terms.index')->with('success', 'Terms updated successfully.');
    }

    public function destroy(PaymentTerm $term)
    {
        $term->delete();
        return redirect()->route('terms.index')->with('success', 'Terms deleted successfully.');
    }
}
