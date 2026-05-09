<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Grn;
use App\Models\GrnItem;
use App\Models\GrnReturn;
use App\Models\GrnReturnItem;
use App\Models\Location;
use App\Models\Account;
use Carbon\Carbon;

class GrnReturnTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or Create Vendor
        $vendor = Vendor::firstOrCreate(
            ['email' => 'supplier1@example.com'],
            [
                'name' => 'Main Supplier PVT LTD',
                'code' => 'VEN-001',
                'phone' => '0112345678',
                'address' => 'No 45, Industrial Zone, Kaduwela',
                'mobile_no' => '0778899001',
                'currency' => 'LKR'
            ]
        );

        // 2. Get or Create Products
        $product1 = Product::firstOrCreate(
            ['code' => 'PROD-001'],
            [
                'name' => 'Cement Bag 50kg',
                'sku' => 'CEM-50',
                'cost' => 1200,
                'max_sale_price' => 1500,
                'vendor_id' => $vendor->id,
                'is_purchase' => true,
                'is_sale' => true
            ]
        );

        $product2 = Product::firstOrCreate(
            ['code' => 'PROD-002'],
            [
                'name' => 'Steel Rod 12mm',
                'sku' => 'STL-12',
                'cost' => 2500,
                'max_sale_price' => 3000,
                'vendor_id' => $vendor->id,
                'is_purchase' => true,
                'is_sale' => true
            ]
        );

        // 3. Get Location and Account
        $location = Location::first() ?? Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);
        $account = Account::where('code', '2000')->first() ?? Account::create(['name' => 'Accounts Payable', 'code' => '2000', 'type' => 'Liability', 'is_active' => 1]);

        // 4. Create a GRN first (so we have something to return)
        $grn = Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-2026-0001',
            'date' => Carbon::now()->subDays(5),
            'location_id' => $location->id,
            'account_id' => $account->id,
            'status' => 'Completed',
            'subtotal' => 37000,
            'total_amount' => 37000,
        ]);

        GrnItem::create([
            'grn_id' => $grn->id,
            'product_id' => $product1->id,
            'qty' => 10,
            'rate' => 1200,
            'amount' => 12000,
            'total' => 12000,
        ]);

        GrnItem::create([
            'grn_id' => $grn->id,
            'product_id' => $product2->id,
            'qty' => 10,
            'rate' => 2500,
            'amount' => 25000,
            'total' => 25000,
        ]);

        // 5. Create GRN Return
        $grnReturn = GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'RET-2026-0001',
            'reference_no' => $grn->grn_no,
            'date' => Carbon::now(),
            'expected_date' => Carbon::now()->addDays(2),
            'location_id' => $location->id,
            'account_id' => $account->id,
            'status' => 'Pending',
            'memo' => 'Returning damaged items from GRN-2026-0001',
            'subtotal' => 4900,
            'total_amount' => 4900,
        ]);

        GrnReturnItem::create([
            'grn_return_id' => $grnReturn->id,
            'product_id' => $product1->id,
            'qty' => 2,
            'rate' => 1200,
            'amount' => 2400,
            'total' => 2400,
            'unit' => 'PCS'
        ]);

        GrnReturnItem::create([
            'grn_return_id' => $grnReturn->id,
            'product_id' => $product2->id,
            'qty' => 1,
            'rate' => 2500,
            'amount' => 2500,
            'total' => 2500,
            'unit' => 'PCS'
        ]);

        // 6. Create another GRN Return with different status
        $grnReturn2 = GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'RET-2026-0002',
            'date' => Carbon::now()->subDays(1),
            'location_id' => $location->id,
            'account_id' => $account->id,
            'status' => 'Completed',
            'memo' => 'Wrong items received',
            'subtotal' => 2500,
            'total_amount' => 2500,
        ]);

        GrnReturnItem::create([
            'grn_return_id' => $grnReturn2->id,
            'product_id' => $product2->id,
            'qty' => 1,
            'rate' => 2500,
            'amount' => 2500,
            'total' => 2500,
            'unit' => 'PCS'
        ]);
    }
}
