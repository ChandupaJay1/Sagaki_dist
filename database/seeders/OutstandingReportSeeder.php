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
use App\Models\Account;
use Carbon\Carbon;

class OutstandingReportSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or Create Customer
        $customer = Customer::firstOrCreate(
            ['email' => 'test_customer@example.com'],
            [
                'name' => 'Test Report Customer',
                'company_name' => 'Report Testing PVT LTD',
                'code' => 'CUST-R001',
                'phone' => '0112233445',
                'address' => 'No 100, Colombo Road, Kaduwela',
                'currency' => 'LKR'
            ]
        );

        $product = Product::first() ?? Product::create([
            'name' => 'Test Product',
            'code' => 'P-001',
            'cost' => 1000,
            'max_sale_price' => 1500,
            'is_sale' => true
        ]);

        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);
        $account = Account::first() ?? Account::create(['name' => 'Cash Account', 'code' => '1000', 'is_active' => 1]);

        // 2. Create 3 Invoices (Total = 50,000)
        $inv1 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-TEST-001',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 20000,
            'status' => 'Pending'
        ]);
        $inv1->items()->create(['product_id' => $product->id, 'qty' => 10, 'rate' => 2000, 'total' => 20000, 'description' => 'Item 1']);

        $inv2 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-TEST-002',
            'date' => Carbon::now()->subDays(5),
            'total_amount' => 15000,
            'status' => 'Pending'
        ]);
        $inv2->items()->create(['product_id' => $product->id, 'qty' => 10, 'rate' => 1500, 'total' => 15000, 'description' => 'Item 2']);

        $inv3 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-TEST-003',
            'date' => Carbon::now()->subDays(2),
            'total_amount' => 15000,
            'status' => 'Pending'
        ]);
        $inv3->items()->create(['product_id' => $product->id, 'qty' => 10, 'rate' => 1500, 'total' => 15000, 'description' => 'Item 3']);

        // 3. Create a Payment (Paid 10,000 for INV-001)
        $payment = PayBill::create([
            'type' => 'Customer',
            'customer_id' => $customer->id,
            'voucher_no' => 'CRV-TEST-999',
            'date' => Carbon::now(),
            'total_amount' => 10000,
            'payment_method' => 'Cash',
            'location_id' => $location->id,
            'status' => 'Paid'
        ]);

        PayBillItem::create([
            'pay_bill_id' => $payment->id,
            'invoice_id' => $inv1->id,
            'bill_no' => $inv1->invoice_no,
            'bill_date' => $inv1->date,
            'bill_amount' => 20000,
            'amount_to_pay' => 10000
        ]);

        // Expected Outstanding: (20000 + 15000 + 15000) - 10000 = 40,000
    }
}
