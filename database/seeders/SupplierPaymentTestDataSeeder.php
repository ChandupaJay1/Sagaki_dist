<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Grn;
use App\Models\GrnItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\PayBill;
use App\Models\PayBillItem;
use App\Models\GrnReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierPaymentTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing data to avoid duplication for this test
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // We don't truncate everything like mobile seeder to keep system usable
        // but we clean up our specific test vendor
        $vendorEmail = 'test_supplier@example.com';
        $oldVendor = Vendor::where('email', $vendorEmail)->first();
        if ($oldVendor) {
            PayBillItem::whereHas('payBill', function($q) use ($oldVendor) {
                $q->where('vendor_id', $oldVendor->id);
            })->delete();
            PayBill::where('vendor_id', $oldVendor->id)->delete();
            GrnItem::whereHas('grn', function($q) use ($oldVendor) {
                $q->where('vendor_id', $oldVendor->id);
            })->delete();
            Grn::where('vendor_id', $oldVendor->id)->delete();
            GrnReturn::where('vendor_id', $oldVendor->id)->delete();
            $oldVendor->delete();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Test Supplier
        $vendor = Vendor::create([
            'name' => 'Main Supplier PVT LTD',
            'email' => $vendorEmail,
            'phone' => '0112233445',
            'address' => 'No 45, Industrial Zone, Colombo',
            'company_name' => 'Main Supplier Group',
        ]);

        $product = Product::first() ?? Product::create([
            'name' => 'Test Item',
            'code' => 'T-001',
            'cost' => 100,
            'is_purchase' => true
        ]);

        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);

        // 3. Get Location and Account
        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);
        $account = \App\Models\Account::where('code', '2000')->first() ?? \App\Models\Account::create(['name' => 'Accounts Payable', 'code' => '2000', 'type' => 'Liability', 'is_active' => 1]);

        // 4. Create 3 GRNs (Supplier Bills)
        // GRN 1: 100,000 (Already Partially Paid)
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
        
        // Create a partial payment of 40,000 for GRN 1
        $payment1 = PayBill::create([
            'type' => 'Supplier',
            'vendor_id' => $vendor->id,
            'voucher_no' => 'PV-001',
            'date' => Carbon::now()->subDays(10),
            'total_amount' => 40000,
            'payment_method' => 'Bank Transfer',
            'status' => 'Paid',
            'location_id' => $location->id
            // account_id removed as it doesn't exist in pay_bills table
        ]);
        PayBillItem::create([
            'pay_bill_id' => $payment1->id,
            'grn_id' => $grn1->id,
            'bill_no' => $grn1->grn_no,
            'bill_date' => $grn1->date,
            'bill_amount' => 100000,
            'amount_to_pay' => 40000
        ]);

        // GRN 2: 50,000 (Pending)
        $grn2 = Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-2026-002',
            'reference_no' => 'REF-002',
            'date' => Carbon::now()->subDays(15),
            'due_date' => Carbon::now()->addDays(5),
            'subtotal' => 50000,
            'total_amount' => 50000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);

        // GRN 3: 25,000 (Pending)
        $grn3 = Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-2026-003',
            'reference_no' => 'REF-003',
            'date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(15),
            'subtotal' => 25000,
            'total_amount' => 25000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);

        // 5. Create a Supplier Return (Credit)
        GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'GRET-001',
            'date' => Carbon::now()->subDays(2),
            'subtotal' => 10000,
            'total_amount' => 10000,
            'status' => 'Completed',
            'location_id' => $location->id,
            'account_id' => $account->id
        ]);

        echo "Supplier Test Data created successfully!\n";
        echo "Supplier: Main Supplier PVT LTD\n";
        echo "GRN 1: 100,000 (Paid: 40,000, Balance: 60,000)\n";
        echo "GRN 2: 50,000 (Balance: 50,000)\n";
        echo "GRN 3: 25,000 (Balance: 25,000)\n";
        echo "Total Outstanding Bills: 135,000\n";
        echo "Supplier Returns (Credits): 10,000\n";
    }
}
