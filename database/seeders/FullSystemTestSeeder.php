<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Grn;
use App\Models\GrnItem;
use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\SalesReturn;
use App\Models\GrnReturn;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FullSystemTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear ALL relevant tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InventoryTransferItem::truncate();
        InventoryTransfer::truncate();
        PayBillItem::truncate();
        PayBill::truncate();
        InvoiceItem::truncate();
        Invoice::truncate();
        SalesReturn::truncate();
        GrnItem::truncate();
        Grn::truncate();
        GrnReturn::truncate();
        Customer::truncate();
        Vendor::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ensure Basic Infrastructure exists
        $location = Location::first() ?? Location::create(['name' => 'Main Stock', 'is_active' => 1]);
        $showroom = Location::where('name', 'Showroom')->first() ?? Location::create(['name' => 'Showroom', 'is_active' => 1]);
        
        $account = Account::where('code', '2000')->first() ?? Account::create([
            'name' => 'Accounts Payable', 
            'code' => '2000', 
            'type' => 'Liability', 
            'is_active' => 1
        ]);

        $product1 = Product::first() ?? Product::create([
            'name' => 'Live Demo Product',
            'code' => 'LIVE-001',
            'cost' => 500,
            'max_sale_price' => 1000,
            'is_sale' => true,
            'is_purchase' => true
        ]);

        $product2 = Product::find(2) ?? Product::create([
            'name' => 'Cement Bag 50kg',
            'code' => 'PROD-001',
            'cost' => 1200,
            'max_sale_price' => 1500,
            'is_sale' => true,
            'is_purchase' => true
        ]);

        // 3. Setup Test User for Mobile
        $testUser = User::where('email', 'admin@admin.com')->first();
        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'Admin'
            ]);
        }
        
        // 4. MOBILE APP TEST DATA (Customer & Invoices)
        $customer = Customer::create([
            'name' => 'Mobile Test Customer',
            'company_name' => 'Mobile Test Store',
            'email' => 'customer@example.com',
            'code' => 'CUST-001',
            'phone' => '0771234567',
            'mobile_no' => '0771234567',
            'address' => 'No 123, Galle Road, Colombo',
            'currency' => 'LKR'
        ]);

        // Invoices for FIFO Testing
        $inv1 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-00001',
            'date' => Carbon::now()->subDays(15),
            'total_amount' => 10000,
            'status' => 'Pending'
        ]);

        $inv2 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-00002',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 25000,
            'status' => 'Pending'
        ]);

        $inv3 = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-00003',
            'date' => Carbon::now()->subDays(5),
            'total_amount' => 15000,
            'status' => 'Pending'
        ]);

        // 5. WEB UI TEST DATA (Vendor & GRNs)
        $vendor = Vendor::create([
            'name' => 'Main Supplier PVT LTD',
            'email' => 'supplier@example.com',
            'phone' => '0112233445',
            'address' => 'No 45, Industrial Zone, Colombo',
            'company_name' => 'Main Supplier Group',
        ]);

        // GRN 1: Partially Paid
        $grn1 = Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-2026-001',
            'reference_no' => 'REF-001',
            'date' => Carbon::now()->subDays(20),
            'due_date' => Carbon::now()->subDays(5),
            'subtotal' => 100000,
            'total_amount' => 100000,
            'status' => 'Partial',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);
        
        // Payment for GRN 1
        $payment1 = PayBill::create([
            'type' => 'Supplier',
            'vendor_id' => $vendor->id,
            'voucher_no' => 'PV-00001',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 40000,
            'payment_method' => 'Cash',
            'status' => 'Paid',
            'location_id' => $location->id
        ]);
        PayBillItem::create([
            'pay_bill_id' => $payment1->id,
            'grn_id' => $grn1->id,
            'bill_no' => $grn1->grn_no,
            'bill_date' => $grn1->date,
            'bill_amount' => 100000,
            'amount_to_pay' => 40000
        ]);

        // GRN 2: Pending
        $grn2 = Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-2026-002',
            'reference_no' => 'REF-002',
            'date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->addDays(10),
            'subtotal' => 50000,
            'total_amount' => 50000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);

        // 6. INVENTORY TRANSFERS
        $transfer = InventoryTransfer::create([
            'site_from' => 'Main Stock',
            'site_to' => 'Showroom',
            'transfer_no' => 'TN-00001',
            'date' => Carbon::now()->format('Y-m-d'),
            'memo' => 'Test Transfer from Seeder',
            'status' => 'Pending',
        ]);

        InventoryTransferItem::create([
            'inventory_transfer_id' => $transfer->id,
            'product_id' => $product1->id,
            'description' => $product1->name,
            'onhand' => 500,
            'qty' => 50,
            'unit' => 'PCS',
        ]);

        InventoryTransferItem::create([
            'inventory_transfer_id' => $transfer->id,
            'product_id' => $product2->id,
            'description' => $product2->name,
            'onhand' => 200,
            'qty' => 20,
            'unit' => 'BAGS',
        ]);

        echo "Full System Test Data Seeded Successfully!\n";
        echo "------------------------------------------\n";
        echo "Mobile Test Customer: CUST-001 (Outstanding: 50,000)\n";
        echo "Web Test Vendor: Main Supplier PVT LTD (Outstanding: 110,000)\n";
        echo "Transfer Note: TN-00001 (2 Items)\n";
    }
}
