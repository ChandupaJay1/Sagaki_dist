<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Location;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\InventorySummary;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'mgpdesaman@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Locations
        $loc1 = Location::updateOrCreate(['name' => 'Main Warehouse'], ['is_active' => 1]);
        $loc2 = Location::updateOrCreate(['name' => 'Showroom'], ['is_active' => 1]);

        // 3. Demo Vendor
        $vendor = Vendor::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo Vendor', 'phone' => '0771234567', 'address' => 'Colombo']
        );

        // 4. Product
        $product = Product::updateOrCreate(
            ['code' => 'LIVE-001'],
            [
                'name' => 'Live Demo Product',
                'sku' => '88888888',
                'category' => 'Electronics',
                'cost' => 100.00,
                'max_sale_price' => 150.00,
                'vendor_id' => $vendor->id,
                'inventory_account' => '1300 - Inventory Asset',
                'cost_account' => '5000 - Cost of Goods Sold',
                'sales_account' => '4000 - Sales Income',
                'description' => 'This product was automatically created for testing.',
                'is_purchase' => true,
                'is_sale' => true,
                'stock' => 8.00 // Total stock (3 + 5)
            ]
        );

        // 5. Inventory Summaries (Location Stock)
        InventorySummary::updateOrCreate(
            ['product_id' => $product->id, 'location_id' => $loc1->id],
            ['qty' => 3.00]
        );

        InventorySummary::updateOrCreate(
            ['product_id' => $product->id, 'location_id' => $loc2->id],
            ['qty' => 5.00]
        );

        // 6. Inventory Logs (Transaction History for initial stock)
        InventoryLog::create([
            'product_id' => $product->id,
            'location_id' => $loc1->id,
            'change_qty' => 3.00,
            'after_qty' => 3.00,
            'type' => 'Opening',
            'reference_type' => 'Initial Seed',
            'reference_id' => 0,
            'description' => 'Initial stock seed for Main Warehouse'
        ]);

        InventoryLog::create([
            'product_id' => $product->id,
            'location_id' => $loc2->id,
            'change_qty' => 5.00,
            'after_qty' => 5.00,
            'type' => 'Opening',
            'reference_type' => 'Initial Seed',
            'reference_id' => 0,
            'description' => 'Initial stock seed for Showroom'
        ]);
    }
}
