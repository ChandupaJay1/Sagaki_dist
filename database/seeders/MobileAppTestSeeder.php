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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MobileAppTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear ALL existing data for a fresh start
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PayBillItem::truncate();
        PayBill::truncate();
        InvoiceItem::truncate();
        Invoice::truncate();
        SalesReturn::truncate();
        Customer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Test Customer
        $customer = Customer::create([
            'id' => 6, // Explicitly set ID 6 as seen in your logs
            'name' => 'Mobile Test Customer',
            'company_name' => 'Mobile Test PVT LTD',
            'email' => 'mobile_test@example.com',
            'code' => 'CUST-MOB-001',
            'phone' => '0771234567',
            'address' => 'No 123, Test Road, Colombo',
            'currency' => 'LKR'
        ]);

        $product = Product::first() ?? Product::create([
            'name' => 'Test Product',
            'code' => 'P-001',
            'cost' => 500,
            'max_sale_price' => 1000,
            'is_sale' => true
        ]);

        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);

        // 3. Create 3 Invoices (FIFO testing)
        // Invoice 1: 15,000
        $inv1 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-001',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 15000,
            'status' => 'Pending'
        ]);

        // Invoice 2: 20,000
        $inv2 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-002',
            'date' => Carbon::now()->subDays(5),
            'total_amount' => 20000,
            'status' => 'Pending'
        ]);

        // Invoice 3: 10,000
        $inv3 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-003',
            'date' => Carbon::now()->subDays(2),
            'total_amount' => 10000,
            'status' => 'Pending'
        ]);

        // Total Outstanding = 45,000
        
        echo "Test data created successfully!\n";
        echo "Customer ID: 6\n";
        echo "Total Outstanding: 45,000 (INV-001: 15,000, INV-002: 20,000, INV-003: 10,000)\n";
    }
}
