<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['territory.areas'])->withCount(['customers', 'refs'])->latest()->get();
        return view('routes.index', compact('routes'));
    }

    /**
     * Show route and manage assignments (customers & rep agents on this route).
     */
    public function show(string $id)
    {
        $route = Route::findOrFail($id);
        $route->load(['customers', 'refs']);
        $customersNotOnRoute = Customer::whereNull('route_id')->orWhere('route_id', '!=', $id)->orderBy('name')->get();
        $refsNotOnRoute = User::where('role', 'ref')->where(function ($q) use ($id) {
            $q->whereNull('route_id')->orWhere('route_id', '!=', $id);
        })->orderBy('name')->get();
        return view('routes.show', compact('route', 'customersNotOnRoute', 'refsNotOnRoute'));
    }

    public function create()
    {
        $territories = \App\Models\Territory::where('is_active', true)->orderBy('name')->get();
        return view('routes.create', compact('territories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:routes,name',
            'territory_id' => 'nullable|exists:territories,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Route::create([
            'name' => $request->name,
            'territory_id' => $request->territory_id,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('routes.index')->with('success', 'Distribution route created successfully.');
    }

    public function edit(string $id)
    {
        $route = Route::findOrFail($id);
        return view('routes.edit', compact('route'));
    }

    public function update(Request $request, string $id)
    {
        $route = Route::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:routes,name,' . $id,
            'territory_id' => 'nullable|exists:territories,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $route->update([
            'name' => $request->name,
            'territory_id' => $request->territory_id,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
    }

    public function destroy(string $id)
    {
        $route = Route::findOrFail($id);
        $route->delete();
        return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    }

    /**
     * Assign a customer to this route (from manage page).
     */
    public function assignCustomer(Request $request, string $id)
    {
        $route = Route::findOrFail($id);
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        Customer::where('id', $request->customer_id)->update(['route_id' => $route->id]);
        return redirect()->route('routes.show', $id)->with('success', 'Customer assigned to route.');
    }

    /**
     * Remove a customer from this route.
     */
    public function unassignCustomer(string $route, string $customer)
    {
        Customer::where('id', $customer)->where('route_id', $route)->update(['route_id' => null]);
        return redirect()->route('routes.show', $route)->with('success', 'Customer removed from route.');
    }

    /**
     * Assign a ref to this route (from manage page).
     */
    public function assignRef(Request $request, string $id)
    {
        $route = Route::findOrFail($id);
        $request->validate(['ref_id' => 'required|exists:users,id']);
        User::where('id', $request->ref_id)->where('role', 'ref')->update(['route_id' => $route->id]);
        return redirect()->route('routes.show', $id)->with('success', 'Rep agent assigned to route.');
    }

    /**
     * Remove a ref from this route.
     */
    public function unassignRef(string $route, string $ref)
    {
        User::where('id', $ref)->where('role', 'ref')->where('route_id', $route)->update(['route_id' => null]);
        return redirect()->route('routes.show', $route)->with('success', 'Rep agent removed from route.');
    }
}
