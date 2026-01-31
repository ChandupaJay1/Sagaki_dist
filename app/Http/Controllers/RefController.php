<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $refs = User::where('role', 'ref')->latest()->paginate(10);
        return view('refs.index', compact('refs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('refs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_number' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => 'ref',
        ]);

        return redirect()->route('refs.index')->with('success', 'Ref Agent registered successfully.');
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
        $ref = User::where('role', 'ref')->findOrFail($id);
        return view('refs.edit', compact('ref'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ref = User::where('role', 'ref')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($ref->id)],
            'mobile_number' => 'required|string|max:15',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $ref->update($data);

        return redirect()->route('refs.index')->with('success', 'Ref Agent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ref = User::where('role', 'ref')->findOrFail($id);
        $ref->delete();
        return redirect()->route('refs.index')->with('success', 'Ref Agent deleted successfully.');
    }
}
