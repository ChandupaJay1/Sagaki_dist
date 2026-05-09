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
use App\Models\PaymentTerm;
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
        PaymentTerm::truncate();
        Product::truncate();
        User::where('email', '!=', 'admin@admin.com')->delete(); // Clear test reps
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Infrastructure & Master Data
        $location = Location::first() ?? Location::create(['name' => 'Main Stock', 'is_active' => 1]);
        $showroom = Location::where('name', 'Showroom')->first() ?? Location::create(['name' => 'Showroom', 'is_active' => 1]);
        
        $account = Account::where('code', '2000')->first() ?? Account::create([
            'name' => 'Accounts Payable', 
            'code' => '2000', 
            'type' => 'Liability', 
            'is_active' => 1
        ]);

        $cashAccount = Account::where('code', '1000')->first() ?? Account::create([
            'name' => 'Cash in Hand', 
            'code' => '1000', 
            'type' => 'Asset', 
            'is_active' => 1
        ]);

        // Terms
        $term1 = PaymentTerm::create(['days' => 0, 'is_active' => 1]);
        $term2 = PaymentTerm::create(['days' => 30, 'is_active' => 1]);

        // Products
        $product1 = Product::create([
            'name' => 'Live Demo Product',
            'code' => 'LIVE-001',
            'cost' => 500,
            'max_sale_price' => 1000,
            'is_sale' => true,
            'is_purchase' => true,
            'unit' => 'PCS'
        ]);

        $product2 = Product::create([
            'name' => 'Cement Bag 50kg',
            'code' => 'PROD-001',
            'cost' => 1200,
            'max_sale_price' => 1500,
            'is_sale' => true,
            'is_purchase' => true,
            'unit' => 'BAGS'
        ]);

        $product3 = Product::create([
            'name' => 'Steel Rod 12mm',
            'code' => 'PROD-002',
            'cost' => 2500,
            'max_sale_price' => 3000,
            'is_sale' => true,
            'is_purchase' => true,
            'unit' => 'PCS'
        ]);

        // 3. Users & Reps
        $admin = User::where('email', 'admin@admin.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'Admin'
            ]);
        }

        $rep = User::create([
            'name' => 'Sales Rep 01',
            'email' => 'rep1@example.com',
            'password' => Hash::make('password'),
            'role' => 'ref',
            'mobile_number' => '0711111111'
        ]);
        
        // 4. CUSTOMERS (For Mobile & Web Sales)
        $customer1 = Customer::create([
            'name' => 'Mobile Test Customer',
            'company_name' => 'Mobile Test Store',
            'email' => 'customer1@example.com',
            'code' => 'CUST-001',
            'phone' => '0771234567',
            'mobile_no' => '0771234567',
            'address' => 'No 123, Galle Road, Colombo',
            'delivery_address' => 'No 123, Galle Road, Colombo (Warehouse)',
            'currency' => 'LKR',
            'rep_id' => $rep->id,
            'credit_limit' => 100000
        ]);

        $customer2 = Customer::create([
            'name' => 'Kandy Retailers',
            'company_name' => 'Kandy Super Mart',
            'email' => 'customer2@example.com',
            'code' => 'CUST-002',
            'phone' => '0812233445',
            'mobile_no' => '0812233445',
            'address' => 'No 45, Peradeniya Road, Kandy',
            'currency' => 'LKR',
            'rep_id' => $rep->id,
            'credit_limit' => 50000
        ]);

        // Invoices for FIFO Testing & Load Dropdown
        Invoice::create(['customer_id' => $customer1->id, 'invoice_no' => 'INV-2026-001', 'date' => Carbon::now()->subDays(15), 'total_amount' => 10000, 'status' => 'Pending']);
        Invoice::create(['customer_id' => $customer1->id, 'invoice_no' => 'INV-2026-002', 'date' => Carbon::now()->subDays(10), 'total_amount' => 25000, 'status' => 'Pending']);
        Invoice::create(['customer_id' => $customer1->id, 'invoice_no' => 'INV-2026-003', 'date' => Carbon::now()->subDays(5), 'total_amount' => 15000, 'status' => 'Pending']);
        Invoice::create(['customer_id' => $customer1->id, 'invoice_no' => 'INV-2026-004', 'date' => Carbon::now()->subDays(2), 'total_amount' => 5000, 'status' => 'Pending']);

        Invoice::create(['customer_id' => $customer2->id, 'invoice_no' => 'INV-KANDY-001', 'date' => Carbon::now()->subDays(12), 'total_amount' => 30000, 'status' => 'Pending']);
        Invoice::create(['customer_id' => $customer2->id, 'invoice_no' => 'INV-KANDY-002', 'date' => Carbon::now()->subDays(8), 'total_amount' => 12500, 'status' => 'Pending']);

        // 5. VENDORS (For Web Purchasing & GRN Returns)
        $vendor1 = Vendor::create([
            'name' => 'Main Supplier PVT LTD',
            'company_name' => 'Main Supplier Group',
            'email' => 'supplier1@example.com',
            'phone' => '0112233445',
            'address' => 'No 45, Industrial Zone, Colombo',
            'delivery_address' => 'Gate 02, Industrial Zone, Colombo'
        ]);

        $vendor2 = Vendor::create([
            'name' => 'Global Imports',
            'company_name' => 'Global Imports (Pvt) Ltd',
            'email' => 'supplier2@example.com',
            'phone' => '0119988776',
            'address' => 'No 88, Port View, Colombo 13',
        ]);

        // GRNs for Load Dropdown & Testing
        $grn1 = Grn::create([
            'vendor_id' => $vendor1->id,
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

        Grn::create([
            'vendor_id' => $vendor1->id,
            'grn_no' => 'GRN-2026-002',
            'reference_no' => 'REF-002',
            'date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->addDays(20),
            'subtotal' => 75000,
            'total_amount' => 75000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);

        Grn::create([
            'vendor_id' => $vendor2->id,
            'grn_no' => 'GRN-GLOBAL-001',
            'reference_no' => 'IMP-99',
            'date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'subtotal' => 250000,
            'total_amount' => 250000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);
        
        $payment1 = PayBill::create([
            'type' => 'Supplier',
            'vendor_id' => $vendor1->id,
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

        // 6. INVENTORY TRANSFERS
        $transfer = InventoryTransfer::create([
            'site_from' => 'Main Stock',
            'site_to' => 'Showroom',
            'transfer_no' => 'TN-00001',
            'date' => Carbon::now()->format('Y-m-d'),
            'memo' => 'Test Transfer from Seeder',
            'status' => 'Pending',
        ]);

        InventoryTransferItem::create(['inventory_transfer_id' => $transfer->id, 'product_id' => $product1->id, 'description' => $product1->name, 'onhand' => 500, 'qty' => 50, 'unit' => 'PCS']);
        InventoryTransferItem::create(['inventory_transfer_id' => $transfer->id, 'product_id' => $product2->id, 'description' => $product2->name, 'onhand' => 200, 'qty' => 20, 'unit' => 'BAGS']);

        echo "Full System Test Data Seeded Successfully!\n";
        echo "------------------------------------------\n";
        echo "Customers: Mobile Test Customer, Kandy Retailers\n";
        echo "Vendors: Main Supplier PVT LTD, Global Imports\n";
        echo "Reps: Sales Rep 01\n";
        echo "Terms: Cash, 30 Days\n";
    }
}
