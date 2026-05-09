<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Route;
use App\Models\Area;
use App\Models\Territory;
use App\Models\Customer;
use App\Models\InventoryTransfer;
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
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'company_name',
                    'mobile_no as mobile_number',
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
     * Get customers for a specific route (Requested by Android App)
     * 
     * @param string $id Route ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function routeCustomersByRouteId($id)
    {
        try {
            $customers = Customer::where('route_id', $id)
                ->orderBy('name')
                ->get();

            $formattedCustomers = $customers->map(function ($customer) {
                // Calculate Outstanding
                $totalInvoices = \App\Models\Invoice::where('customer_id', $customer->id)->sum('total_amount');
                $totalPaid = \App\Models\PayBillItem::whereHas('payBill', function($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                })->sum('amount_to_pay');
                $totalReturns = \App\Models\SalesReturn::where('customer_id', $customer->id)->sum('total_amount');
                
                $outstanding = ($totalInvoices - $totalPaid) - $totalReturns;

                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'address' => $customer->address,
                    'mobile_number' => $customer->mobile_no,
                    'route_id' => $customer->route_id,
                    'outstanding_balance' => (double)$outstanding,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Customers retrieved successfully',
                'data' => [
                    'route_id' => (int)$id,
                    'customers' => $formattedCustomers,
                ]
            ], 200);

        } catch (\Throwable $e) {
            Log::error('routeCustomersByRouteId error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of transfer notes
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferNotes()
    {
        try {
            $transfers = InventoryTransfer::latest()->get();

            $data = $transfers->map(function ($t) {
                return [
                    'id' => $t->id,
                    'tn_number' => $t->transfer_no,
                    'date' => $t->date,
                    'from_location' => $t->site_from,
                    'to_location' => $t->site_to,
                    'memo' => $t->memo,
                    'status' => $t->status,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Throwable $e) {
            Log::error('transferNotes error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transfer note details
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferNoteDetails($id)
    {
        try {
            $t = InventoryTransfer::with(['items.product'])->find($id);

            if (!$t) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer note not found'
                ], 404);
            }

            $data = [
                'id' => $t->id,
                'tn_number' => $t->transfer_no,
                'date' => $t->date,
                'from_location' => $t->site_from,
                'to_location' => $t->site_to,
                'memo' => $t->memo,
                'status' => $t->status,
                'items' => $t->items->map(function ($item) {
                    return [
                        'item_code' => $item->product ? $item->product->code : '',
                        'item_name' => $item->product ? $item->product->name : $item->description,
                        'qty' => (string)$item->qty,
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Throwable $e) {
            Log::error('transferNoteDetails error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update transfer note status
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTransferNoteStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Approved,Rejected,Pending'
            ]);

            $transfer = InventoryTransfer::findOrFail($id);
            $transfer->status = $validated['status'];
            $transfer->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => [
                    'id' => $transfer->id,
                    'status' => $transfer->status
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('updateTransferNoteStatus error: ' . $e->getMessage());
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
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'company_name',
                    'mobile_no as mobile_number',
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

    /**
     * Store multiple payments from mobile app
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePayments(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'payments' => 'required|array|min:1',
                'payments.*.method' => 'required|string|in:CASH,BANK,CHEQUE',
                'payments.*.amount' => 'required|numeric|min:0',
                'payments.*.bank_reference' => 'nullable|string',
                'payments.*.cheque_no' => 'nullable|string',
                'payments.*.bank_name' => 'nullable|string',
                'payments.*.date' => 'nullable|date',
            ]);

            \DB::beginTransaction();

            $createdPayments = [];

            foreach ($validated['payments'] as $paymentData) {
                // Generate a unique voucher number for each payment
                $voucherNo = 'CP-' . strtoupper(\Illuminate\Support\Str::random(8));

                $payment = \App\Models\PayBill::create([
                    'type' => 'Customer',
                    'customer_id' => $validated['customer_id'],
                    'voucher_no' => $voucherNo,
                    'date' => $paymentData['date'] ?? now()->format('Y-m-d'),
                    'total_amount' => $paymentData['amount'],
                    'payment_method' => $paymentData['method'] === 'BANK' ? 'Bank Transfer' : ucfirst(strtolower($paymentData['method'])),
                    'cheque_no' => $paymentData['cheque_no'] ?? $paymentData['bank_reference'] ?? null,
                    'pd_cheque_date' => $paymentData['method'] === 'CHEQUE' ? ($paymentData['date'] ?? null) : null,
                    'memo' => 'Mobile App Payment - ' . ($paymentData['bank_name'] ?? ''),
                    'status' => 'Paid',
                    // Assuming a default location/site for mobile payments
                    'location_id' => \App\Models\Location::first()->id ?? null,
                ]);

                $createdPayments[] = $payment;
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All payments recorded successfully',
                'data' => $createdPayments
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            \DB::rollBack();
            Log::error('storePayments error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while recording payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
