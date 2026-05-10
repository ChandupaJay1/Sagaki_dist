<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceReturn;
use App\Models\Payment;
use App\Models\Cheque;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Store a newly created invoice from mobile app.
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
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|numeric|min:0',
                'items.*.rate' => 'required|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
                'return_items' => 'nullable|array',
                'return_items.*.product_id' => 'required|exists:products,id',
                'return_items.*.qty' => 'required|numeric|min:0',
                'return_items.*.rate' => 'required|numeric|min:0',
                'return_items.*.discount' => 'nullable|numeric|min:0',
                'payment_cash' => 'nullable|numeric|min:0',
                'payment_bank' => 'nullable|numeric|min:0',
                'cheques' => 'nullable|array',
                'cheques.*.cheque_no' => 'required|string',
                'cheques.*.date' => 'required|date',
                'cheques.*.bank_name' => 'required|string',
                'cheques.*.amount' => 'required|numeric|min:0',
            ]);

            return DB::transaction(function () use ($validated) {
                // Generate Invoice Number
                $invoiceNo = 'INV-' . strtoupper(uniqid());

                // 1. Insert Invoice
                $invoice = Invoice::create([
                    'customer_id' => $validated['customer_id'],
                    'invoice_no' => $invoiceNo,
                    'date' => $validated['date'],
                    'subtotal' => $validated['sub_total'],
                    'header_discount_amount' => $validated['discount_amount'] ?? 0,
                    'total_amount' => $validated['net_total'],
                    'rep_id' => auth()->id(),
                    'status' => 'Created',
                    // Setting a default location if not provided
                    'location_id' => DB::table('locations')->where('is_active', 1)->value('id'),
                ]);

                // 2. Save Items and Update Stock
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
                        // Using default location from invoice
                        'location' => DB::table('locations')->where('id', $invoice->location_id)->value('name'),
                    ]);

                    // Update Stock (Decrement)
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    $oldQty = (float)($product->qty_in_bulk ?? 0);
                    $newQty = $oldQty - $qty;
                    $product->update(['qty_in_bulk' => $newQty]);

                    // Inventory Log
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'location_id' => $invoice->location_id,
                        'reference_type' => 'Invoice',
                        'reference_id' => $invoice->id,
                        'change_qty' => -$qty,
                        'after_qty' => $newQty,
                        'type' => 'Sale',
                        'description' => "Invoice #{$invoice->invoice_no} Sale"
                    ]);
                }

                // 3. Save Returns and Update Stock
                if (!empty($validated['return_items'])) {
                    foreach ($validated['return_items'] as $return) {
                        $returnQty = (float)$return['qty'];
                        $returnRate = (float)$return['rate'];
                        $returnDiscount = (float)($return['discount'] ?? 0);
                        $returnTotal = ($returnQty * $returnRate) - $returnDiscount;
                        
                        InvoiceReturn::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $return['product_id'],
                            'qty' => $returnQty,
                            'rate' => $returnRate,
                            'discount' => $returnDiscount,
                            'total' => $returnTotal,
                        ]);

                        // Update Stock (Increment)
                        $product = Product::lockForUpdate()->find($return['product_id']);
                        $oldQty = (float)($product->qty_in_bulk ?? 0);
                        $newQty = $oldQty + $returnQty;
                        $product->update(['qty_in_bulk' => $newQty]);

                        // Inventory Log
                        InventoryLog::create([
                            'product_id' => $product->id,
                            'location_id' => $invoice->location_id,
                            'reference_type' => 'Invoice',
                            'reference_id' => $invoice->id,
                            'change_qty' => $returnQty,
                            'after_qty' => $newQty,
                            'type' => 'Return',
                            'description' => "Invoice #{$invoice->invoice_no} Return"
                        ]);
                    }
                }

                // 4. Process Payments
                $totalPaid = 0;

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

                // 5. Update Customer Balance
                $customer = Customer::lockForUpdate()->find($validated['customer_id']);
                // Balance increases by net_total and decreases by totalPaid
                $customer->balance += ($validated['net_total'] - $totalPaid);
                $customer->save();

                // 6. Return specific JSON format
                return response()->json([
                    'success' => true,
                    'message' => "Invoice created successfully #{$invoice->invoice_no}",
                    'data' => null
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
