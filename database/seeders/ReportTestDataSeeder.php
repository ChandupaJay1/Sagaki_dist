<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Location;
use App\Models\ItemCategory;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Grn;
use App\Models\Invoice;
use App\Models\SalesReturn;
use App\Models\InventoryTransfer;
use Illuminate\Support\Facades\DB;

class ReportTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Locations if they don't exist
        $locations = ['Main Stock', 'Showroom', 'Testing', 'Transit'];
        foreach ($locations as $locName) {
            Location::firstOrCreate(['name' => $locName], ['is_active' => 1]);
        }

        // 2. Create Categories
        $categories = ['DISPLAY', 'KITCHEN SCALE', 'BATHROOM SCALE'];
        foreach ($categories as $catName) {
            ItemCategory::firstOrCreate(['name' => $catName]);
        }

        // 3. Create a Vendor and a Customer
        $vendor = Vendor::firstOrCreate(
            ['code' => 'V001'],
            [
                'name' => 'Global Suppliers',
                'email' => 'vendor@example.com',
                'phone' => '0112233445'
            ]
        );
        $customer = Customer::firstOrCreate(
            ['code' => 'C001'],
            [
                'name' => 'Walk-in Customer',
                'email' => 'customer@example.com',
                'phone' => '0771234567',
                'password' => bcrypt('password')
            ]
        );

        // 4. Create Products
        $p1 = Product::firstOrCreate(
            ['code' => 'SDS0001'],
            [
                'name' => 'KITCHEN SCALE MANUAL 5KG',
                'category' => 'KITCHEN SCALE',
                'cost' => 750.00,
                'location' => 'Main Stock',
                'is_sale' => 1,
                'is_purchase' => 1
            ]
        );

        $p2 = Product::firstOrCreate(
            ['code' => 'SDS0002'],
            [
                'name' => 'SF 400 10Kg ELECTRONIC SCALE',
                'category' => 'KITCHEN SCALE',
                'cost' => 1200.00,
                'location' => 'Main Stock',
                'is_sale' => 1,
                'is_purchase' => 1
            ]
        );

        // 5. Create GRN (Stock In)
        $grn = Grn::create([
            'grn_no' => 'GRN/' . date('Y/m/') . '0001',
            'vendor_id' => $vendor->id,
            'date' => date('Y-m-d', strtotime('-10 days')),
            'total_amount' => 100000,
            'status' => 'Created'
        ]);

        $grn->items()->create([
            'product_id' => $p1->id,
            'description' => $p1->name,
            'qty' => 50,
            'rate' => 750,
            'amount' => 37500,
            'total' => 37500,
            'location' => 'Main Stock'
        ]);

        $grn->items()->create([
            'product_id' => $p2->id,
            'description' => $p2->name,
            'qty' => 30,
            'rate' => 1200,
            'amount' => 36000,
            'total' => 36000,
            'location' => 'Main Stock'
        ]);

        // 6. Create Invoice (Stock Out)
        $invoice = Invoice::create([
            'invoice_no' => 'INV/' . date('Y/m/') . '0001',
            'customer_id' => $customer->id,
            'date' => date('Y-m-d', strtotime('-5 days')),
            'total_amount' => 5000,
            'status' => 'Created'
        ]);

        $invoice->items()->create([
            'product_id' => $p1->id,
            'description' => $p1->name,
            'qty' => 5,
            'rate' => 1000,
            'amount' => 5000,
            'total' => 5000,
            'location' => 'Main Stock'
        ]);

        // 7. Create Sales Return (Stock In)
        $return = SalesReturn::create([
            'return_no' => 'SR/' . date('Y/m/') . '0001',
            'customer_id' => $customer->id,
            'date' => date('Y-m-d', strtotime('-2 days')),
            'total_amount' => 1000,
            'status' => 'Created'
        ]);

        $return->items()->create([
            'product_id' => $p1->id,
            'description' => $p1->name,
            'qty' => 1,
            'rate' => 1000,
            'amount' => 1000,
            'total' => 1000,
            'location' => 'Main Stock'
        ]);

        // 8. Create Inventory Transfer (Main -> Showroom)
        $transfer = InventoryTransfer::create([
            'transfer_no' => 'TN-' . str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT),
            'site_from' => 'Main Stock',
            'site_to' => 'Showroom',
            'date' => date('Y-m-d', strtotime('-1 days')),
            'status' => 'Approved'
        ]);

        $transfer->items()->create([
            'product_id' => $p1->id,
            'description' => $p1->name,
            'qty' => 10,
            'onhand' => 46 // 50 - 5 + 1
        ]);

        $transfer->items()->create([
            'product_id' => $p2->id,
            'description' => $p2->name,
            'qty' => 5,
            'onhand' => 30
        ]);

        $this->command->info('Report test data seeded successfully!');
        $this->command->info('P1 (SDS0001): In=50+1+0, Out=5+10, Balance=36 (Main: 26, Showroom: 10)');
        $this->command->info('P2 (SDS0002): In=30, Out=5, Balance=25 (Main: 25, Showroom: 5)');
    }
}
