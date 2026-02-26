<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
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
        $refs = User::with('route')->where('role', 'ref')->latest()->paginate(10);
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        return view('refs.index', compact('refs', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        return view('refs.create', compact('routes'));
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
            'route_id' => 'nullable|exists:routes,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'role' => 'ref',
            'is_active' => true,
            'route_id' => $request->route_id,
        ];

        $serialNumber = $request->serial_number;
        if (empty($serialNumber)) {
            $serialNumber = User::generateSerialNumber();
        }
        $userData['serial_number'] = $serialNumber;
        $userData['password'] = Hash::make($serialNumber);
        $userData['serial_expires_at'] = now()->addMonths(5);

        User::create($userData);

        return redirect()->route('refs.index')->with('success', 'User registered successfully.');
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
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        return view('refs.edit', compact('ref', 'routes'));
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
            'serial_number' => ['nullable', 'string', Rule::unique('users')->ignore($ref->id)],
            'route_id' => 'nullable|exists:routes,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'serial_number' => $request->serial_number,
            'route_id' => $request->route_id,
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

    public function toggleStatus(string $id)
    {
        $ref = User::where('role', 'ref')->findOrFail($id);
        $ref->is_active = !$ref->is_active;
        $ref->save();

        $status = $ref->is_active ? 'connected' : 'disconnected';
        return redirect()->route('refs.index')->with('success', "Ref Agent account has been $status.");
    }

    /**
     * Update only the route assignment (from list page).
     */
    public function updateRoute(Request $request, User $ref)
    {
        if ($ref->role !== 'ref') {
            abort(404);
        }
        $request->validate(['route_id' => 'nullable|exists:routes,id']);
        $ref->update(['route_id' => $request->route_id]);
        return redirect()->route('refs.index')->with('success', 'Route updated for rep agent.');
    }
}
