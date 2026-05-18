<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\SalesReturn;
use App\Models\Grn;
use App\Models\GrnReturn;

class TestSetoffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Truncate Transactional Tables (Ignore constraints to wipe data cleanly)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('invoices')->truncate();
        DB::table('invoice_items')->truncate();
        DB::table('sales_orders')->truncate();
        DB::table('sales_order_items')->truncate();
        DB::table('sales_returns')->truncate();
        DB::table('sales_return_items')->truncate();
        
        DB::table('grns')->truncate();
        DB::table('grn_items')->truncate();
        DB::table('purchase_orders')->truncate();
        DB::table('purchase_order_items')->truncate();
        DB::table('grn_returns')->truncate();
        DB::table('grn_return_items')->truncate();
        
        DB::table('pay_bills')->truncate();
        DB::table('pay_bill_items')->truncate();
        DB::table('payments')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // 2. Ensure we have at least 1 Test Customer and 1 Test Vendor
        $customer = Customer::first();
        if (!$customer) {
            $customer = Customer::create([
                'name' => 'Test Customer',
                'company_name' => 'Test Customer Corp',
                'mobile_no' => '0710000001',
                'status' => 'active',
                'code' => 'CUST-TEST01',
                'route_id' => 1
            ]);
        }

        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'name' => 'Test Vendor',
                'company_name' => 'Test Vendor Corp',
                'email' => 'vendor@test.com',
                'phone' => '0770000001',
                'status' => 'Active'
            ]);
        }

        // 3. Seed Customer Data
        // Unpaid Invoices
        Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-T001',
            'date' => now()->subDays(5)->toDateString(),
            'total_amount' => 15000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-T002',
            'date' => now()->subDays(2)->toDateString(),
            'total_amount' => 20000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        // Approved Sales Return
        SalesReturn::create([
            'customer_id' => $customer->id,
            'return_no' => 'SR-T001',
            'date' => now()->subDays(1)->toDateString(),
            'total_amount' => 5000.00,
            'subtotal' => 5000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        // 4. Seed Vendor Data
        // Unpaid GRNs
        Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-T001',
            'date' => now()->subDays(10)->toDateString(),
            'total_amount' => 30000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-T002',
            'date' => now()->subDays(4)->toDateString(),
            'total_amount' => 45000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        // Approved GRN Return
        GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'GRNR-T001',
            'date' => now()->subDays(2)->toDateString(),
            'total_amount' => 12000.00,
            'subtotal' => 12000.00,
            'status' => 'Approved',
            'location_id' => 1
        ]);

        $this->command->info('Test data for Return Set-off seeded successfully!');
    }
}
