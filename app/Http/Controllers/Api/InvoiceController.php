<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Payment;
use App\Models\Cheque;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Store a newly created invoice/return from mobile app.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Merge current date if date is missing
            if (!$request->has('date')) {
                $request->merge(['date' => date('Y-m-d')]);
            }

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'sub_total' => 'required|numeric',
                'discount_amount' => 'nullable|numeric',
                'net_total' => 'required|numeric',
                'date' => 'required|date',
                'items' => 'nullable|array',
                'items.*.product_id' => 'required_with:items|exists:products,id',
                'items.*.qty' => 'required_with:items|numeric|min:0',
                'items.*.rate' => 'required_with:items|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
                'return_items' => 'nullable|array',
                'return_items.*.product_id' => 'required_with:return_items|exists:products,id',
                'return_items.*.qty' => 'required_with:return_items|numeric|min:0',
                'return_items.*.rate' => 'required_with:return_items|numeric|min:0',
                'return_items.*.discount' => 'nullable|numeric|min:0',
                'payment_cash' => 'nullable|numeric|min:0',
                'payment_bank' => 'nullable|numeric|min:0',
                'cheques' => 'nullable|array',
                'cheques.*.cheque_no' => 'required|string',
                'cheques.*.date' => 'required|date',
                'cheques.*.bank_name' => 'required|string',
                'cheques.*.amount' => 'required|numeric|min:0',
            ]);

            if (empty($validated['items']) && empty($validated['return_items'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['items' => ['At least one item or return item is required.']]
                ], 422);
            }

            return DB::transaction(function () use ($validated) {
                $invoice = null;
                $salesReturn = null;
                $totalPaid = 0;
                $totalReturnAmount = 0;
                $locationId = DB::table('locations')->where('is_active', 1)->value('id');
                $locationName = DB::table('locations')->where('id', $locationId)->value('name');

                // 1. Process Sales (Invoice)
                if (!empty($validated['items'])) {
                    $invoiceNo = 'INV-' . strtoupper(uniqid());
                    $invoice = Invoice::create([
                        'customer_id' => $validated['customer_id'],
                        'invoice_no' => $invoiceNo,
                        'date' => $validated['date'],
                        'subtotal' => $validated['sub_total'],
                        'header_discount_amount' => $validated['discount_amount'] ?? 0,
                        'total_amount' => $validated['net_total'],
                        'rep_id' => auth()->id(),
                        'status' => 'Created',
                        'location_id' => $locationId,
                    ]);

                    foreach ($validated['items'] as $item) {
                        $qty = (float)$item['qty'];
                        $rate = (float)$item['rate'];
                        $discount = (float)($item['discount'] ?? 0);
                        $amount = $qty * $rate;
                        $total = $amount - $discount;

                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $item['product_id'],
                            'qty' => $qty,
                            'rate' => $rate,
                            'discount' => $discount,
                            'amount' => $amount,
                            'total' => $total,
                            'location' => $locationName,
                        ]);

                        // Update Stock (Decrement)
                        InventoryService::updateStock(
                            $item['product_id'],
                            $locationId,
                            -$qty,
                            'Sale',
                            'Invoice',
                            $invoice->id,
                            "Invoice #{$invoice->invoice_no} Sale"
                        );
                    }

                    // 2. Process Payments (Only if Invoice exists)
                    // Cash Payment
                    if (($validated['payment_cash'] ?? 0) > 0) {
                        Payment::create([
                            'invoice_id' => $invoice->id,
                            'method' => 'Cash',
                            'amount' => $validated['payment_cash'],
                        ]);
                        $totalPaid += $validated['payment_cash'];
                    }

                    // Bank Payment
                    if (($validated['payment_bank'] ?? 0) > 0) {
                        Payment::create([
                            'invoice_id' => $invoice->id,
                            'method' => 'Bank',
                            'amount' => $validated['payment_bank'],
                        ]);
                        $totalPaid += $validated['payment_bank'];
                    }

                    // Cheque Payments
                    if (!empty($validated['cheques'])) {
                        foreach ($validated['cheques'] as $chequeData) {
                            $payment = Payment::create([
                                'invoice_id' => $invoice->id,
                                'method' => 'Cheque',
                                'amount' => $chequeData['amount'],
                            ]);

                            Cheque::create([
                                'payment_id' => $payment->id,
                                'cheque_no' => $chequeData['cheque_no'],
                                'date' => $chequeData['date'],
                                'bank_name' => $chequeData['bank_name'],
                            ]);

                            $totalPaid += $chequeData['amount'];
                        }
                    }
                }

                // 3. Process Returns (Sales Return)
                if (!empty($validated['return_items'])) {
                    $returnNo = 'SRT-' . strtoupper(uniqid());
                    
                    // Calculate return subtotal and total
                    $returnSubtotal = 0;
                    foreach ($validated['return_items'] as $return) {
                        $returnSubtotal += ((float)$return['qty'] * (float)$return['rate']);
                        $totalReturnAmount += (((float)$return['qty'] * (float)$return['rate']) - (float)($return['discount'] ?? 0));
                    }

                    $salesReturn = SalesReturn::create([
                        'customer_id' => $validated['customer_id'],
                        'return_no' => $returnNo,
                        'date' => $validated['date'],
                        'subtotal' => $returnSubtotal,
                        'total_amount' => $totalReturnAmount,
                        'location_id' => $locationId,
                        'status' => 'Created',
                        'rep' => auth()->user()->name ?? 'Mobile App',
                        'memo' => $invoice ? "Linked to Invoice #{$invoice->invoice_no}" : "Mobile App Return",
                        'reference_no' => $invoice ? $invoice->invoice_no : null,
                    ]);

                    foreach ($validated['return_items'] as $return) {
                        $returnQty = (float)$return['qty'];
                        $returnRate = (float)$return['rate'];
                        $returnDiscount = (float)($return['discount'] ?? 0);
                        $returnAmount = $returnQty * $returnRate;
                        $returnTotal = $returnAmount - $returnDiscount;
                        
                        SalesReturnItem::create([
                            'sales_return_id' => $salesReturn->id,
                            'product_id' => $return['product_id'],
                            'qty' => $returnQty,
                            'rate' => $returnRate,
                            'amount' => $returnAmount,
                            'discount' => $returnDiscount,
                            'total' => $returnTotal,
                            'location' => $locationName,
                        ]);

                        // Update Stock (Increment)
                        InventoryService::updateStock(
                            $return['product_id'],
                            $locationId,
                            $returnQty,
                            'Return',
                            'Sales Return',
                            $salesReturn->id,
                            "Sales Return #{$salesReturn->return_no} Return"
                        );
                    }
                }

                // 4. Update Customer Balance
                $customer = Customer::lockForUpdate()->find($validated['customer_id']);
                // Sale adds to balance, Payment subtracts, Return subtracts
                $saleAmount = $invoice ? (float)$validated['net_total'] : 0;
                $customer->balance += ($saleAmount - $totalPaid - $totalReturnAmount);
                $customer->save();

                // 5. Return Response
                $message = "";
                if ($invoice && $salesReturn) {
                    $message = "Invoice #{$invoice->invoice_no} and Sales Return #{$salesReturn->return_no} created successfully";
                } elseif ($invoice) {
                    $message = "Invoice #{$invoice->invoice_no} created successfully";
                } elseif ($salesReturn) {
                    $message = "Sales Return #{$salesReturn->return_no} created successfully";
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'invoice_no' => $invoice ? $invoice->invoice_no : null,
                        'return_no' => $salesReturn ? $salesReturn->return_no : null,
                    ]
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Invoice API Store Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
