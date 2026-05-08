<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Route;
use App\Models\Area;
use App\Models\Territory;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MobileController extends Controller
{
    /**
     * Get current user's route information
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRoute(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $routeInfo = null;

            if ($user->route_id) {
                $route = Route::with(['areaRef', 'territory'])->find($user->route_id);

                if ($route) {
                    $routeInfo = [
                        'id' => $route->id,
                        'name' => $route->name,
                        'code' => $route->code,
                        'area' => $route->areaRef ? $route->areaRef->name : null,
                        'area_id' => $route->area_id,
                        'territory' => $route->territory ? $route->territory->name : null,
                        'territory_id' => $route->territory_id,
                        'description' => $route->description,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile_number' => $user->mobile_number,
                        'role' => $user->role,
                    ],
                    'route' => $routeInfo,
                ]
            ], 200);

        } catch (\Throwable $e) {
            Log::error('myRoute error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers assigned to current user's route
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRouteCustomers(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->route_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No route assigned to this user'
                ], 404);
            }

            $customers = Customer::where('route_id', $user->route_id)
                ->where('is_active', 1)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'company_name',
                    'mobile_number',
                    'address',
                    'route_id',
                ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'route_id' => $user->route_id,
                    'customers' => $customers,
                ]
            ], 200);

        } catch (\Throwable $e) {
            Log::error('myRouteCustomers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all areas
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function areas()
    {
        try {
            $areas = Area::where('is_active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            return response()->json([
                'success' => true,
                'data' => $areas
            ], 200);

        } catch (\Throwable $e) {
            Log::error('areas error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all territories
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function territories()
    {
        try {
            $territories = Territory::where('is_active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            return response()->json([
                'success' => true,
                'data' => $territories
            ], 200);

        } catch (\Throwable $e) {
            Log::error('territories error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all routes
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function routes()
    {
        try {
            $routes = Route::with(['areaRef', 'territory'])
                ->where('is_active', 1)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'code',
                    'area_id',
                    'territory_id',
                    'description',
                ]);

            return response()->json([
                'success' => true,
                'data' => $routes
            ], 200);

        } catch (\Throwable $e) {
            Log::error('routes error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update current user's route
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMyRoute(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'route_id' => 'required|exists:routes,id',
            ]);

            $user->route_id = $validated['route_id'];
            $user->save();

            $route = Route::with(['areaRef', 'territory'])->find($user->route_id);
            
            $routeInfo = null;
            if ($route) {
                $routeInfo = [
                    'id' => $route->id,
                    'name' => $route->name,
                    'code' => $route->code,
                    'area' => $route->areaRef ? $route->areaRef->name : null,
                    'area_id' => $route->area_id,
                    'territory' => $route->territory ? $route->territory->name : null,
                    'territory_id' => $route->territory_id,
                    'description' => $route->description,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully',
                'data' => [
                    'route' => $routeInfo
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('updateMyRoute error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers for a specific route
     * 
     * @param string $id Route ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function routeCustomers($id)
    {
        try {
            $customers = Customer::where('route_id', $id)
                ->where('is_active', 1)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'company_name',
                    'mobile_number',
                    'address',
                    'route_id',
                ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'route_id' => $id,
                    'customers' => $customers,
                ]
            ], 200);

        } catch (\Throwable $e) {
            Log::error('routeCustomers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
