<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\SalesReturn;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinalOutstandingTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing test data for this specific customer to avoid confusion
        $email = 'outstanding_test@example.com';
        $oldCustomer = Customer::where('email', $email)->first();
        if ($oldCustomer) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            PayBillItem::whereHas('payBill', function($q) use ($oldCustomer) {
                $q->where('customer_id', $oldCustomer->id);
            })->delete();
            PayBill::where('customer_id', $oldCustomer->id)->delete();
            InvoiceItem::whereHas('invoice', function($q) use ($oldCustomer) {
                $q->where('customer_id', $oldCustomer->id);
            })->delete();
            Invoice::where('customer_id', $oldCustomer->id)->delete();
            SalesReturn::where('customer_id', $oldCustomer->id)->delete();
            $oldCustomer->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 2. Create Fresh Customer
        $customer = Customer::create([
            'name' => 'Outstanding Test User',
            'company_name' => 'Outstanding Analytics LTD',
            'email' => $email,
            'code' => 'CUST-OUT-001',
            'phone' => '0771122334',
            'address' => 'No 50, Galle Road, Colombo',
            'currency' => 'LKR'
        ]);

        $product = Product::first() ?? Product::create([
            'name' => 'Premium Cement',
            'code' => 'PC-001',
            'cost' => 1000,
            'max_sale_price' => 2000,
            'is_sale' => true
        ]);

        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);

        // --- THE SCENARIO ---
        
        // A. Create Invoice 1: LKR 50,000.00
        $inv1 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-OUT-001',
            'date' => Carbon::now()->subDays(15),
            'total_amount' => 50000,
            'status' => 'Pending'
        ]);
        $inv1->items()->create(['product_id' => $product->id, 'qty' => 25, 'rate' => 2000, 'total' => 50000]);

        // B. Create Invoice 2: LKR 30,000.00
        $inv2 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-OUT-002',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 30000,
            'status' => 'Pending'
        ]);
        $inv2->items()->create(['product_id' => $product->id, 'qty' => 15, 'rate' => 2000, 'total' => 30000]);

        // TOTAL INVOICES = 80,000.00

        // C. Customer Returns Goods: LKR 5,000.00
        SalesReturn::create([
            'customer_id' => $customer->id,
            'return_no' => 'RET-OUT-001',
            'date' => Carbon::now()->subDays(5),
            'total_amount' => 5000,
            'status' => 'Completed',
            'location_id' => $location->id
        ]);

        // D. Customer Pays for Invoice 1: LKR 20,000.00
        $payment = PayBill::create([
            'type' => 'Customer',
            'customer_id' => $customer->id,
            'voucher_no' => 'VOU-OUT-001',
            'date' => Carbon::now()->subDays(2),
            'total_amount' => 20000,
            'payment_method' => 'Bank Transfer',
            'location_id' => $location->id,
            'status' => 'Paid'
        ]);

        PayBillItem::create([
            'pay_bill_id' => $payment->id,
            'invoice_id' => $inv1->id,
            'bill_no' => $inv1->invoice_no,
            'bill_date' => $inv1->date,
            'bill_amount' => 50000,
            'amount_to_pay' => 20000
        ]);

        // --- FINAL CALCULATION ---
        // Invoices: 50,000 + 30,000 = 80,000
        // Returns: 5,000
        // Payments: 20,000
        // OUTSTANDING = 80,000 - 5,000 - 20,000 = 55,000.00
    }
}
