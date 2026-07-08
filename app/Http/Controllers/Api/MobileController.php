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
use App\Models\Invoice;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Http\Controllers\InventoryTransferController as WebTransferController;

class MobileController extends Controller
{
    /**
     * Get home dashboard statistics for the mobile app
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomeStats(Request $request)
    {
        try {
            $user = $request->user();
            $period = $request->query('period', 'daily'); // daily, weekly, monthly
            
            $startDate = Carbon::now();
            $endDate = Carbon::now();

            if ($period === 'daily') {
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
            } elseif ($period === 'weekly') {
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
            } elseif ($period === 'monthly') {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }

            // 1. Calculate Invoice Sales
            $invoiceSales = Invoice::where('rep_id', $user->id)
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->sum('total_amount');

            // 2. Calculate Returns Total
            $returnsTotal = SalesReturn::where('rep', $user->name)
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->sum('total_amount');

            // 3. New Customers (Assigned to this rep's route)
            $newCustomersCount = Customer::where('route_id', $user->route_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // 4. Weekly Sales Data (Last 7 days for the chart)
            $weeklySales = [];
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $amount = Invoice::where('rep_id', $user->id)
                    ->whereDate('date', $day->format('Y-m-d'))
                    ->sum('total_amount');
                
                $weeklySales[] = [
                    'day' => $day->format('D'),
                    'amount' => (double)$amount
                ];
            }

            // 5. Assigned Route and Customers
            $assignedRoute = null;
            if ($user->route_id) {
                $route = Route::find($user->route_id);
                $customers = Customer::where('route_id', $user->route_id)
                    ->orderBy('name')
                    ->limit(10) // Limit to avoid huge response
                    ->get(['id', 'name', 'address']);

                $assignedRoute = [
                    'route_name' => $route ? $route->name : 'N/A',
                    'customers' => $customers
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'invoice_sales' => (double)$invoiceSales,
                    'returns_total' => (double)$returnsTotal,
                    'new_customers' => $newCustomersCount,
                    'weekly_sales' => $weeklySales,
                    'assigned_route' => $assignedRoute
                ]
            ], 200);

        } catch (\Throwable $e) {
            Log::error('getHomeStats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
     * Get list of approved products for sales (Requested by Android App)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function approvedItems()
    {
        try {
            $user = auth()->user();
            
            // Get all saleable products
            $products = \App\Models\Product::where('is_sale', 1)
                ->orderBy('name')
                ->get();

            $data = $products->map(function ($p) use ($user) {
                // Calculate available qty: (approved transfers) - (invoiced) + (returned)
                $approvedQty = \App\Models\InventoryTransferItem::where('product_id', $p->id)
                    ->whereHas('inventoryTransfer', function($query) use ($user) {
                        $query->where('status', 'Approved')
                              ->where('rep_agent_id', $user->id);
                    })->sum('qty');

                $invoicedQty = \App\Models\InvoiceItem::where('product_id', $p->id)
                    ->whereHas('invoice', function($query) use ($user) {
                        $query->where('rep_id', $user->id)
                              ->whereIn('status', ['Created', 'Partial', 'Paid']);
                    })->sum('qty');

                $returnedQty = \App\Models\SalesReturnItem::where('product_id', $p->id)
                    ->whereHas('salesReturn', function($query) use ($user) {
                        $query->where('rep', $user->name);
                    })->sum('qty');

                $availableQty = $approvedQty - $invoicedQty + $returnedQty;
                if ($availableQty < 0) $availableQty = 0;

                return [
                    'id' => (int)$p->id,
                    'name' => (string)$p->name,
                    'item_code' => (string)$p->code,
                    'qty' => (double)$availableQty,
                    'selling_price' => (double)$p->max_sale_price,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Throwable $e) {
            Log::error('approvedItems error: ' . $e->getMessage());
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferNotes(Request $request)
    {
        try {
            // 1. Authenticate and get the logged-in Rep Agent via the Bearer Token
            $authRepId = $request->user()->id;

            // 2. Filter query to only return notes where 'rep_agent_id' matches this user
            $transfers = InventoryTransfer::where('rep_agent_id', $authRepId)
                ->latest()
                ->get();

            $data = $transfers->map(function ($t) {
                return [
                    'id' => (int)$t->id,
                    'tn_number' => (string)($t->transfer_no ?? ''),
                    'date' => (string)$t->date,
                    'from_location' => (string)$t->site_from,
                    'to_location' => (string)$t->site_to,
                    'memo' => (string)($t->memo ?? ''),
                    'status' => (string)$t->status,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Transfer notes fetched successfully',
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferNoteDetails(Request $request, $id)
    {
        try {
            $t = InventoryTransfer::with(['items.product'])->find($id);

            if (!$t) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer note not found'
                ], 404);
            }

            // Security Filter: Ensure the requested transfer belongs to the authenticated Rep Agent
            if ($t->rep_agent_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to transfer note'
                ], 403);
            }

            $data = [
                'id' => (int)$t->id,
                'tn_number' => (string)($t->transfer_no ?? ''),
                'date' => (string)$t->date,
                'from_location' => (string)$t->site_from,
                'to_location' => (string)$t->site_to,
                'memo' => (string)($t->memo ?? ''),
                'status' => (string)$t->status,
                'items' => $t->items->map(function ($item) {
                    return [
                        'item_code' => $item->product ? (string)$item->product->code : '',
                        'item_name' => $item->product ? (string)$item->product->name : ($item->description ?? ''),
                        'qty' => (double)$item->qty,
                        'unit' => (string)($item->unit ?? ''),
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

            return \DB::transaction(function () use ($validated, $id, $request) {
                $transfer = InventoryTransfer::with('items')->findOrFail($id);

                // Security Filter: Ensure the requested transfer belongs to the authenticated Rep Agent
                if ($transfer->rep_agent_id !== $request->user()->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to update transfer note status'
                    ], 403);
                }

                $oldStatus = $transfer->status;
                $newStatus = $validated['status'];

                if ($oldStatus !== 'Approved' && $newStatus === 'Approved') {
                    // Transitioning to Approved: Update Inventory
                    WebTransferController::completeTransfer($transfer);
                } elseif ($oldStatus === 'Approved' && $newStatus !== 'Approved') {
                    // Reversing Approval: Reverse Destination only
                    WebTransferController::reverseTransfer($transfer);
                }

                $transfer->status = $newStatus;
                $transfer->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated and inventory synchronized successfully',
                    'data' => [
                        'id' => $transfer->id,
                        'status' => $transfer->status
                    ]
                ], 200);
            });

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
                // Generate a unique voucher number for each payment - matching web prefix
                $voucherNo = 'CRV/MOBILE/' . strtoupper(\Illuminate\Support\Str::random(6));

                $amountToSettle = $paymentData['amount'];

                $payment = \App\Models\PayBill::create([
                    'type' => 'Customer',
                    'customer_id' => $validated['customer_id'],
                    'voucher_no' => $voucherNo,
                    'date' => $paymentData['date'] ?? now()->format('Y-m-d'),
                    'total_amount' => $amountToSettle,
                    'payment_method' => $paymentData['method'] === 'BANK' ? 'Bank Transfer' : ucfirst(strtolower($paymentData['method'])),
                    'cheque_no' => $paymentData['cheque_no'] ?? $paymentData['bank_reference'] ?? null,
                    'pd_cheque_date' => $paymentData['method'] === 'CHEQUE' ? ($paymentData['date'] ?? null) : null,
                    'memo' => 'Mobile App Payment - ' . ($paymentData['bank_name'] ?? ''),
                    'status' => 'Paid',
                    // Assuming a default location/site for mobile payments
                    'location_id' => \App\Models\Location::first()->id ?? null,
                ]);

                // Update Customer Balance
                $customer = Customer::find($validated['customer_id']);
                if ($customer) {
                    $customer->balance -= (float)$paymentData['amount'];
                    $customer->save();
                }

                // --- Automatic Invoice Settlement Logic ---
                // Find outstanding invoices for this customer
                $invoices = \App\Models\Invoice::where('customer_id', $validated['customer_id'])
                    ->where('status', '!=', 'Paid')
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($invoices as $invoice) {
                    if ($amountToSettle <= 0) break;

                    // Calculate remaining balance for this invoice
                    $totalPaid = \App\Models\PayBillItem::where('invoice_id', $invoice->id)->sum('amount_to_pay');
                    $balance = $invoice->total_amount - $totalPaid;

                    if ($balance > 0) {
                        $settleNow = min($amountToSettle, $balance);

                        \App\Models\PayBillItem::create([
                            'pay_bill_id' => $payment->id,
                            'invoice_id' => $invoice->id,
                            'bill_no' => $invoice->invoice_no,
                            'bill_date' => $invoice->date,
                            'bill_amount' => $invoice->total_amount,
                            'amount_to_pay' => $settleNow,
                        ]);

                        $amountToSettle -= $settleNow;

                        // Update invoice status if fully paid
                        if (round($balance - $settleNow, 2) <= 0) {
                            $invoice->status = 'Paid';
                            $invoice->save();
                        } else {
                            $invoice->status = 'Partial';
                            $invoice->save();
                        }
                    }
                }

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
