<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\Invoice;
use Carbon\Carbon;

class CustomerTransactionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find or create 'Demo Customer'
        $customer = Customer::firstOrCreate(
            ['name' => 'Demo Customer'],
            [
                'code' => 'DEMO-C001',
                'email' => 'demo_customer@example.com',
                'phone' => '0770000000',
                'address' => '123 Demo Street, Colombo',
            ]
        );

        // 2. Generate a single Invoice of 330,000.50 so the customer has an initial balance
        $invoice = Invoice::create([
            'customer_id'  => $customer->id,
            'invoice_no'   => 'INV-TEST-001',
            'date'         => Carbon::now()->subDays(15)->format('Y-m-d'),
            'total_amount' => 330000.50,
            'status'       => 'Pending', // It has a 60,000 balance left after the payments below
        ]);

        // 3. Generate 4 customer payment transactions totaling 270,000.50
        $transactions = [
            [
                'type'           => 'Customer',
                'customer_id'    => $customer->id,
                'voucher_no'     => 'RCPT-TEST-001',
                'date'           => Carbon::now()->subDays(10)->format('Y-m-d'),
                'total_amount'   => 50000.00,
                'payment_method' => 'Cash',
                'status'         => 'Completed',
                'memo'           => 'Initial cash payment',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'type'           => 'Customer',
                'customer_id'    => $customer->id,
                'voucher_no'     => 'RCPT-TEST-002',
                'date'           => Carbon::now()->subDays(5)->format('Y-m-d'),
                'total_amount'   => 120000.50,
                'payment_method' => 'Bank Transfer',
                'status'         => 'Completed',
                'memo'           => 'Direct deposit to main account',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'type'           => 'Customer',
                'customer_id'    => $customer->id,
                'voucher_no'     => 'RCPT-TEST-003',
                'date'           => Carbon::now()->subDays(2)->format('Y-m-d'),
                'total_amount'   => 75000.00,
                'payment_method' => 'Cheque',
                'cheque_no'      => 'CHQ-88223311',
                'pd_cheque_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'status'         => 'Pending',
                'memo'           => 'Post-dated cheque pending clearance',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'type'           => 'Customer',
                'customer_id'    => $customer->id,
                'voucher_no'     => 'RCPT-TEST-004',
                'date'           => Carbon::now()->format('Y-m-d'),
                'total_amount'   => 25000.00,
                'payment_method' => 'Cash',
                'status'         => 'Pending', // Partially pays the remaining balance
                'memo'           => 'Partial payment, pending allocation',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ];

        // Create the PayBills and map them to the invoice via PayBillItem
        foreach ($transactions as $transaction) {
            $payBill = PayBill::create($transaction);
            
            // Allocate the payment entirely to the invoice
            PayBillItem::create([
                'pay_bill_id'   => $payBill->id,
                'invoice_id'    => $invoice->id,
                'bill_no'       => $invoice->invoice_no,
                'bill_date'     => $invoice->date,
                'bill_amount'   => $invoice->total_amount,
                'amount_to_pay' => $transaction['total_amount'], // Allocate full receipt amount to invoice
            ]);
        }
    }
}
