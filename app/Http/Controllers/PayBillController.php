<?php

namespace App\Http\Controllers;

use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\Vendor;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayBillController extends Controller
{
    public function index()
    {
        $payments = PayBill::with('vendor')->latest()->paginate(10);
        return view('pay_bills.index', compact('payments'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $locations = Location::where('is_active', 1)->orderBy('name')->get();
        
        // Generate next Voucher Number
        $lastPayment = PayBill::orderBy('id', 'desc')->first();
        if (!$lastPayment) {
            $nextVoucherNo = 'RV/00001';
        } else {
            // Extract the numeric part after RV/
            $lastNoStr = $lastPayment->voucher_no;
            if (str_contains($lastNoStr, '/')) {
                $parts = explode('/', $lastNoStr);
                $lastNo = (int) end($parts);
            } else {
                $lastNo = (int) preg_replace('/[^0-9]/', '', $lastNoStr);
            }
            $nextVoucherNo = 'RV/' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('pay_bills.create', compact('vendors', 'locations', 'nextVoucherNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'voucher_no' => 'required|unique:pay_bills,voucher_no',
            'payment_method' => 'required|string',
            'cheque_no' => 'nullable|string',
            'pd_cheque_date' => 'nullable|date',
            'memo' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.grn_id' => 'required|exists:grns,id',
            'items.*.amount_to_pay' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $payBill = PayBill::create([
                'vendor_id' => $validated['vendor_id'],
                'location_id' => $validated['location_id'],
                'voucher_no' => $validated['voucher_no'],
                'date' => $validated['date'],
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'cheque_no' => $validated['cheque_no'],
                'pd_cheque_date' => $validated['pd_cheque_date'],
                'memo' => $validated['memo'],
                'status' => 'Paid',
            ]);

            foreach ($request->items as $item) {
                if (isset($item['amount_to_pay']) && $item['amount_to_pay'] > 0) {
                    $grn = \App\Models\Grn::find($item['grn_id']);
                    PayBillItem::create([
                        'pay_bill_id' => $payBill->id,
                        'grn_id' => $item['grn_id'],
                        'bill_no' => $grn->grn_no,
                        'bill_date' => $grn->date,
                        'due_date' => $grn->due_date,
                        'bill_amount' => $grn->total_amount,
                        'amount_to_pay' => $item['amount_to_pay'],
                    ]);
                }
            }
        });

        if ($request->action === 'pay_and_new') {
            return redirect()->route('pay-bills.create')->with('success', 'Payment recorded successfully.');
        }

        return redirect()->route('pay-bills.index')->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        $payment = PayBill::with(['vendor', 'items.grn'])->findOrFail($id);
        return view('pay_bills.show', compact('payment'));
    }

    public function destroy($id)
    {
        $payment = PayBill::findOrFail($id);
        $payment->delete(); // Cascade delete will handle items
        return redirect()->route('pay-bills.index')->with('success', 'Payment deleted successfully.');
    }
}
