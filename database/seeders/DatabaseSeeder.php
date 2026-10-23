<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Area;
use App\Models\Territory;
use App\Models\Route;
use App\Models\Location;
use App\Models\Currency;
use App\Models\PaymentTerm;
use App\Models\ItemCategory;
use App\Models\ModelMaster;
use App\Models\Account;
use App\Models\CustomerCategory;
use App\Models\SupplierCategory;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\InventorySummary;
use App\Models\InventoryLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'mgpdesaman@gmail.com',
            'password' => Hash::make('88222006@V'),
            'role' => 'admin',
            'is_active' => 1,
        ]);

        // 2. Seed Master Tables

        // Locations
        $mainLocation = Location::create(['name' => 'Main Warehouse', 'code' => 'MAIN-WH', 'is_active' => 1]);
        $showroom = Location::create(['name' => 'Showroom', 'code' => 'SHOW-RM', 'is_active' => 1]);

        // Areas
        $area1 = Area::create(['name' => 'Colombo North', 'code' => 'COL-N', 'is_active' => 1]);
        $area2 = Area::create(['name' => 'Colombo South', 'code' => 'COL-S', 'is_active' => 1]);

        // Territories
        $territory1 = Territory::create(['name' => 'Western Province', 'code' => 'WP', 'is_active' => 1]);
        $territory2 = Territory::create(['name' => 'Central Province', 'code' => 'CP', 'is_active' => 1]);

        // Attach Areas to Territories
        DB::table('area_territory')->insert([
            ['area_id' => $area1->id, 'territory_id' => $territory1->id, 'created_at' => now(), 'updated_at' => now()],
            ['area_id' => $area2->id, 'territory_id' => $territory1->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Routes
        Route::create(['name' => 'Route A - Colombo', 'code' => 'RT-A', 'is_active' => 1]);
        Route::create(['name' => 'Route B - Gampaha', 'code' => 'RT-B', 'is_active' => 1]);

        // Categories (Master Categories)
        Category::create(['name' => 'Electronics', 'code' => 'ELEC', 'is_active' => 1]);
        Category::create(['name' => 'Furniture', 'code' => 'FURN', 'is_active' => 1]);

        // Item Categories
        ItemCategory::create(['name' => 'Raw Materials', 'code' => 'RAW', 'is_active' => 1]);
        ItemCategory::create(['name' => 'Finished Goods', 'code' => 'FIN', 'is_active' => 1]);

        // Brands
        Brand::create(['name' => 'Samsung', 'code' => 'SAM', 'is_active' => 1]);
        Brand::create(['name' => 'LG', 'code' => 'LG', 'is_active' => 1]);

        // Model Masters
        ModelMaster::create(['name' => 'Model X', 'code' => 'MDL-X', 'is_active' => 1]);
        ModelMaster::create(['name' => 'Model Y', 'code' => 'MDL-Y', 'is_active' => 1]);

        // Currencies
        Currency::create(['name' => 'Sri Lankan Rupee', 'code' => 'LKR', 'is_active' => 1]);
        Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'is_active' => 1]);

        // Units
        Unit::create(['name' => 'Pieces', 'code' => 'PCS', 'is_active' => 1]);
        Unit::create(['name' => 'Kilograms', 'code' => 'KG', 'is_active' => 1]);
        Unit::create(['name' => 'Liters', 'code' => 'LTR', 'is_active' => 1]);

        // Payment Terms
        PaymentTerm::create(['days' => 0, 'code' => 'CASH', 'is_active' => 1]);
        PaymentTerm::create(['days' => 15, 'code' => 'NET-15', 'is_active' => 1]);
        PaymentTerm::create(['days' => 30, 'code' => 'NET-30', 'is_active' => 1]);

        // Customer Categories
        CustomerCategory::create(['name' => 'Wholesale', 'code' => 'WHOLE', 'is_active' => 1]);
        CustomerCategory::create(['name' => 'Retail', 'code' => 'RET', 'is_active' => 1]);

        // Supplier Categories
        SupplierCategory::create(['name' => 'Local Supplier', 'code' => 'LOC', 'is_active' => 1]);
        SupplierCategory::create(['name' => 'International Supplier', 'code' => 'INT', 'is_active' => 1]);

        // Accounts (Chart of Accounts)
        Account::create(['name' => 'Cash in Hand', 'code' => '1000', 'type' => 'Asset', 'is_active' => 1]);
        Account::create(['name' => 'Bank Account', 'code' => '1100', 'type' => 'Asset', 'is_active' => 1]);
        Account::create(['name' => 'Inventory Asset', 'code' => '1300', 'type' => 'Asset', 'is_active' => 1]);
        Account::create(['name' => 'Sales Income', 'code' => '4000', 'type' => 'Income', 'is_active' => 1]);
        Account::create(['name' => 'Cost of Goods Sold', 'code' => '5000', 'type' => 'Expense', 'is_active' => 1]);

        // Seed some Demo Vendor, Customer, Product and initial inventory logic
        $vendor = Vendor::create([
            'name' => 'Demo Vendor',
            'email' => 'demo@example.com',
            'phone' => '0771234567',
            'address' => 'Colombo'
        ]);

        $customer = Customer::create([
            'name' => 'Demo Customer',
            'email' => 'customer@example.com',
            'phone' => '0779999999',
            'address' => 'Colombo'
        ]);

        $product = Product::create([
            'name' => 'Live Demo Product',
            'code' => 'LIVE-001',
            'sku' => '88888888',
            'category' => 'Electronics',
            'cost' => 100.00,
            'max_sale_price' => 150.00,
            'vendor_id' => $vendor->id,
            'inventory_account' => '1300 - Inventory Asset',
            'cost_account' => '5000 - Cost of Goods Sold',
            'sales_account' => '4000 - Sales Income',
            'description' => 'This product was automatically created for testing.',
            'is_purchase' => 1,
            'is_sale' => 1,
            'stock' => 8.00 // Total stock (3 + 5)
        ]);

        // Inventory Summaries (Location Stock)
        InventorySummary::create([
            'product_id' => $product->id, 
            'location_id' => $mainLocation->id,
            'qty' => 3.00
        ]);

        InventorySummary::create([
            'product_id' => $product->id, 
            'location_id' => $showroom->id,
            'qty' => 5.00
        ]);

        // Inventory Logs (Transaction History for initial stock)
        InventoryLog::create([
            'product_id' => $product->id,
            'location_id' => $mainLocation->id,
            'change_qty' => 3.00,
            'after_qty' => 3.00,
            'type' => 'Opening',
            'reference_type' => 'Initial Seed',
            'reference_id' => 0,
            'description' => 'Initial stock seed for Main Warehouse'
        ]);

        InventoryLog::create([
            'product_id' => $product->id,
            'location_id' => $showroom->id,
            'change_qty' => 5.00,
            'after_qty' => 5.00,
            'type' => 'Opening',
            'reference_type' => 'Initial Seed',
            'reference_id' => 0,
            'description' => 'Initial stock seed for Showroom'
        ]);
    }
}
