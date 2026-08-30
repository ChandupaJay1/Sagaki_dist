<?php

namespace App\Http\Controllers;

use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Invoice;
use App\Models\Grn;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayBillController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get("type", "Supplier");
        $payments = PayBill::with(["vendor", "customer", "items"])
            ->where("type", $type)
            ->latest()
            ->paginate(10);

        return view("pay_bills.index", compact("payments", "type"));
    }

    public function createSupplier(Request $request)
    {
        return $this->createInternal($request, "Supplier");
    }

    public function createCustomer(Request $request)
    {
        return $this->createInternal($request, "Customer");
    }

    public function create(Request $request)
    {
        $type = $request->get("type", "Supplier");
        return $this->createInternal($request, $type);
    }

    private function createInternal(Request $request, $type)
    {
        $vendors = Vendor::orderBy("company_name")->get();
        $customers = Customer::orderBy("company_name")->get();
        $locations = Location::where("is_active", 1)->orderBy("name")->get();
        $accounts = Account::where("is_active", 1)->orderBy("name")->get();
        $reps = User::where("role", "ref")
            ->where("is_active", 1)
            ->orderBy("name")
            ->get();

        // Generate next Voucher Number
        $lastPayment = PayBill::where("type", $type)
            ->orderBy("id", "desc")
            ->first();
        $prefix = $type === "Supplier" ? "RV/" : "CRV/";
        if (!$lastPayment) {
            $nextVoucherNo = $prefix . "00001";
        } else {
            // Extract the numeric part after /
            $lastNoStr = $lastPayment->voucher_no;
            if (str_contains($lastNoStr, "/")) {
                $parts = explode("/", $lastNoStr);
                $lastNo = (int) end($parts);
            } else {
                $lastNo = (int) preg_replace("/[^0-9]/", "", $lastNoStr);
            }
            $nextVoucherNo =
                $prefix . str_pad($lastNo + 1, 5, "0", STR_PAD_LEFT);
        }

        return view(
            "pay_bills.create",
            compact(
                "vendors",
                "customers",
                "locations",
                "accounts",
                "reps",
                "nextVoucherNo",
                "type",
            ),
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "type" => "required|in:Supplier,Customer",
            "vendor_id" => "required_if:type,Supplier|exists:vendors,id|nullable",
            "customer_id" =>
                "required_if:type,Customer|exists:customers,id|nullable",
            "location_id" => "required|exists:locations,id",
            "voucher_no" => "required|string|max:255",
            "date" => "required|date",
            "total_amount" => "required|numeric|min:0",
            "payment_method" => "required|string",
            "cheque_no" => "nullable|string",
            "pd_cheque_date" => "nullable|date",
            "memo" => "nullable|string",
            "items" => "required|array",
            "items.*.grn_id" =>
                "required_if:type,Supplier|exists:grns,id|nullable",
            "items.*.invoice_id" =>
                "required_if:type,Customer|exists:invoices,id|nullable",
            "items.*.amount_to_pay" => "required|numeric|min:0",
            "items.*.credit_used" => "nullable|numeric|min:0",
            "applied_credits" => "nullable|array",
            "applied_credits.*.id" => "nullable|numeric",
            "applied_credits.*.type" => "nullable|string",
            "applied_credits.*.amount_to_use" => "required|numeric|min:0",
        ]);

        $payBill = DB::transaction(function () use ($validated, $request) {
            $payBill = PayBill::create([
                "type" => $validated["type"],
                "vendor_id" => $validated["vendor_id"] ?? null,
                "customer_id" => $validated["customer_id"] ?? null,
                "location_id" => $validated["location_id"],
                "voucher_no" => $validated["voucher_no"],
                "date" => $validated["date"],
                "total_amount" => $validated["total_amount"],
                "payment_method" => $validated["payment_method"],
                "cheque_no" => $request->cheque_no,
                "pd_cheque_date" => $request->pd_cheque_date,
                "memo" => $request->memo,
                "status" => "Paid",
            ]);

            // 1. Update Customer Balance (Cash part only)
            if (
                $validated["type"] === "Customer" &&
                $validated["customer_id"]
            ) {
                $customer = Customer::find($validated["customer_id"]);
                if ($customer) {
                    $customer->balance -=
                        (float) ($validated["total_amount"] ?? 0);
                    $customer->save();
                }
            }

            $totalCashAllocated = 0;
            $itemsUsingCredit = [];

            // 2. Process Bill Items
            foreach ($request->items as $item) {
                $totalApplied = (float) ($item["amount_to_pay"] ?? 0);
                $creditUsed = (float) ($item["credit_used"] ?? 0);
                $cashUsed = $totalApplied - $creditUsed;
                $totalCashAllocated += $cashUsed;

                if ($totalApplied > 0) {
                    $billNo = "";
                    $billDate = null;
                    $dueDate = null;
                    $billAmount = 0;

                    if ($validated["type"] === "Supplier") {
                        $grn = Grn::find($item["grn_id"]);
                        $billNo = $grn->grn_no;
                        $billDate = $grn->date;
                        $dueDate = $grn->due_date;
                        $billAmount = $grn->total_amount;

                        // Check cumulative payment to update status (using totalApplied)
                        $alreadyPaid = PayBillItem::where(
                            "grn_id",
                            $grn->id,
                        )->sum("amount_to_pay");
                        if (
                            $alreadyPaid + $totalApplied >=
                            $grn->total_amount - 0.01
                        ) {
                            $grn->status = "Paid";
                            $grn->save();
                        }
                    } else {
                        $invoice = Invoice::find($item["invoice_id"]);
                        $billNo = $invoice->invoice_no;
                        $billDate = $invoice->date;
                        $dueDate = $invoice->due_date;
                        $billAmount = $invoice->total_amount;

                        // Check cumulative payment to update status (using totalApplied)
                        $alreadyPaid = PayBillItem::where(
                            "invoice_id",
                            $invoice->id,
                        )->sum("amount_to_pay");
                        if (
                            $alreadyPaid + $totalApplied >=
                            $invoice->total_amount - 0.01
                        ) {
                            $invoice->status = "Paid";
                            $invoice->save();
                        }
                    }

                    // Only create item for the CURRENT PayBill if cash was used
                    if ($cashUsed > 0) {
                        PayBillItem::create([
                            "pay_bill_id" => $payBill->id,
                            "grn_id" => $item["grn_id"] ?? null,
                            "invoice_id" => $item["invoice_id"] ?? null,
                            "bill_no" => $billNo,
                            "bill_date" => $billDate,
                            "due_date" => $dueDate,
                            "bill_amount" => $billAmount,
                            "amount_to_pay" => $cashUsed,
                        ]);
                    }

                    // Collect credit info for source attribution
                    if ($creditUsed > 0) {
                        $itemsUsingCredit[] = [
                            "grn_id" => $item["grn_id"] ?? null,
                            "invoice_id" => $item["invoice_id"] ?? null,
                            "bill_no" => $billNo,
                            "bill_date" => $billDate,
                            "due_date" => $dueDate,
                            "bill_amount" => $billAmount,
                            "amount" => $creditUsed,
                        ];
                    }
                }
            }

            // 3. Process Used Credits (Returns & Payments)
            if (
                isset($request->applied_credits) &&
                is_array($request->applied_credits)
            ) {
                foreach ($request->applied_credits as $creditData) {
                    $amountUsed = (float) ($creditData["amount_to_use"] ?? 0);
                    $creditId = $creditData["id"] ?? null;
                    
                    if ($amountUsed <= 0 || !$creditId) continue;

                    $typeLabel = $creditData["type"] ?? "Return";

                    if (str_starts_with($typeLabel, "Payment")) {
                        // Logic for consuming PayBill surplus
                        $sourcePayment = PayBill::find($creditId);
                        if ($sourcePayment) {
                            $pool = $amountUsed;
                            foreach ($itemsUsingCredit as &$target) {
                                if ($pool <= 0) break;
                                
                                $canTake = min($pool, $target["amount"]);
                                if ($canTake > 0) {
                                    PayBillItem::create([
                                        "pay_bill_id" => $sourcePayment->id,
                                        "grn_id" => $target["grn_id"],
                                        "invoice_id" => $target["invoice_id"],
                                        "bill_no" => $target["bill_no"],
                                        "bill_date" => $target["bill_date"],
                                        "due_date" => $target["due_date"],
                                        "bill_amount" => $target["bill_amount"],
                                        "amount_to_pay" => $canTake,
                                    ]);
                                    $target["amount"] -= $canTake;
                                    $pool -= $canTake;
                                }
                            }
                        }
                    } else {
                        // Logic for consuming Returns
                        if ($validated["type"] === "Supplier") {
                            $return = \App\Models\GrnReturn::find($creditId);
                            if ($return) {
                                $return->total_amount -= $amountUsed;
                                $return->subtotal -= $amountUsed;
                                if ($return->total_amount <= 0.01) {
                                    $return->status = "Used";
                                }
                                $return->save();
                            }
                        } else {
                            $return = \App\Models\SalesReturn::find($creditId);
                            if ($return) {
                                $return->total_amount -= $amountUsed;
                                $return->subtotal -= $amountUsed;
                                if ($return->total_amount <= 0.01) {
                                    $return->status = "Used";
                                }
                                $return->save();
                            }
                        }
                    }
                }
            }

            // 4. Surplus / Overpayment handling.
            //
            // The PayBill.total_amount stores the user's full payment amount
            // (e.g., 16,000). The PayBillItem records only cover the portion
            // explicitly allocated to specific GRNs/invoices (e.g., 14,000).
            //
            // The unallocated surplus (e.g., 2,000) is persisted as the natural
            // difference between PayBill.total_amount and sum(PayBillItem.amount_to_pay).
            //
            // VendorController::getOutstandingBills() detects this difference and
            // returns it as a "Payment" type credit row in the bottom credits table,
            // making it available for set-off against future GRN payments.
            //
            // No separate phantom GrnReturn or SalesReturn record is created here,
            // keeping the ledger clean and preventing duplicate records in the
            // Returns modules.

            $totalItemsAllocated = $payBill
                ->items()
                ->sum("amount_to_pay");
            $surplus = round(
                (float) $payBill->total_amount - $totalItemsAllocated,
                2,
            );

            // If there is a surplus, annotate the PayBill memo for audit visibility
            if ($surplus > 0.01) {
                $surplusFormatted = number_format($surplus, 2);
                $existingMemo = $payBill->memo ?? "";
                $surplusNote =
                    "[Unallocated surplus: LKR {$surplusFormatted} — available as vendor credit]";

                $payBill->memo = $existingMemo
                    ? "{$existingMemo} | {$surplusNote}"
                    : $surplusNote;
                $payBill->save();
            }

            return $payBill;
        });

        if ($request->action === "pay_and_new") {
            $routeName =
                $validated["type"] === "Supplier"
                    ? "pay-bills.supplier.create"
                    : "pay-bills.customer.create";
            return redirect()
                ->route($routeName)
                ->with("success", "Payment recorded successfully.");
        }

        if ($request->action === "save_and_print") {
            return redirect()->route("pay-bills.print", $payBill->id);
        }

        return redirect()
            ->route("pay-bills.index", ["type" => $validated["type"]])
            ->with("success", "Payment recorded successfully.");
    }

    public function print($id)
    {
        $payment = PayBill::with([
            "vendor",
            "customer",
            "items.grn",
            "items.invoice",
        ])->findOrFail($id);

        $outstanding = 0;
        if ($payment->type === "Customer" && $payment->customer) {
            $totalInvoices = \App\Models\Invoice::where(
                "customer_id",
                $payment->customer_id,
            )->sum("total_amount");
            $totalPaid = \App\Models\PayBillItem::whereHas("payBill", function (
                $q,
            ) use ($payment) {
                $q->where("customer_id", $payment->customer_id);
            })->sum("amount_to_pay");

            $totalReturns = \App\Models\SalesReturn::where(
                "customer_id",
                $payment->customer_id,
            )->sum("total_amount");

            $outstanding = $totalInvoices - $totalPaid - $totalReturns;
        } elseif ($payment->type === "Supplier" && $payment->vendor) {
            $totalBills = \App\Models\Grn::where(
                "vendor_id",
                $payment->vendor_id,
            )->sum("total_amount");
            $totalPaid = \App\Models\PayBillItem::whereHas("payBill", function (
                $q,
            ) use ($payment) {
                $q->where("vendor_id", $payment->vendor_id);
            })->sum("amount_to_pay");

            $totalReturns = \App\Models\GrnReturn::where(
                "vendor_id",
                $payment->vendor_id,
            )->sum("total_amount");

            $outstanding = $totalBills - $totalPaid - $totalReturns;
        }

        return view("pay_bills.print", compact("payment", "outstanding"));
    }

    public function show($id)
    {
        $payment = PayBill::with([
            "vendor",
            "customer",
            "items.grn",
            "items.invoice",
        ])->findOrFail($id);
        return view("pay_bills.show", compact("payment"));
    }

    public function destroy($id)
    {
        $payment = PayBill::findOrFail($id);

        DB::transaction(function () use ($payment) {
            // Update Customer Balance (Reverse Payment)
            if ($payment->type === "Customer" && $payment->customer_id) {
                $customer = Customer::find($payment->customer_id);
                if ($customer) {
                    $customer->balance += (float) ($payment->total_amount ?? 0);
                    $customer->save();
                }
            }

            $payment->delete(); // Cascade delete will handle items
        });

        return redirect()
            ->route("pay-bills.index")
            ->with("success", "Payment deleted successfully.");
    }
}
