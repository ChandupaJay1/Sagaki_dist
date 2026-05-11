<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\ItemCategory;
use App\Models\Brand;
use App\Models\Account;
use App\Models\Territory;
use App\Models\Area;
use App\Models\Route;
use App\Models\CustomerCategory;
use App\Models\User;

class SystemTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign keys
        Schema::disableForeignKeyConstraints();

        // 1. Clear Transaction Tables
        $this->command->info('Cleaning up transaction tables...');
        DB::table('grn_items')->truncate();
        DB::table('grns')->truncate();
        DB::table('grn_return_items')->truncate();
        DB::table('grn_returns')->truncate();
        DB::table('invoice_items')->truncate();
        DB::table('invoices')->truncate();
        DB::table('invoice_returns')->truncate();
        DB::table('sales_return_items')->truncate();
        DB::table('sales_returns')->truncate();
        DB::table('pay_bill_items')->truncate();
        DB::table('pay_bills')->truncate();
        DB::table('payments')->truncate();
        DB::table('cheques')->truncate();
        DB::table('inventory_logs')->truncate();
        DB::table('inventory_summaries')->truncate();
        DB::table('inventory_transfer_items')->truncate();
        DB::table('inventory_transfers')->truncate();
        DB::table('inventory_issue_items')->truncate();
        DB::table('inventory_issues')->truncate();
        DB::table('stock_adjustment_items')->truncate();
        DB::table('stock_adjustments')->truncate();
        DB::table('purchase_order_items')->truncate();
        DB::table('purchase_orders')->truncate();
        DB::table('sales_order_items')->truncate();
        DB::table('sales_orders')->truncate();

        // 2. Clear Master Tables (except Users)
        $this->command->info('Cleaning up master tables...');
        DB::table('products')->truncate();
        DB::table('units')->truncate();
        DB::table('item_categories')->truncate();
        DB::table('brands')->truncate();
        DB::table('vendors')->truncate();
        DB::table('customers')->truncate();
        DB::table('locations')->truncate();
        DB::table('accounts')->truncate();
        DB::table('routes')->truncate();
        DB::table('areas')->truncate();
        DB::table('territories')->truncate();
        DB::table('customer_categories')->truncate();

        Schema::enableForeignKeyConstraints();
        $this->command->info('Cleanup complete. Starting seeding...');

        // 3. Seed Master Data

        // Locations
        $mainLoc = Location::create(['name' => 'Main Warehouse', 'is_active' => 1]);
        $showroom = Location::create(['name' => 'Showroom', 'is_active' => 1]);
        $this->command->info('Locations seeded.');

        // Units
        $pcs = Unit::create(['name' => 'Pieces', 'code' => 'PCS', 'is_active' => 1]);
        $kg = Unit::create(['name' => 'Kilograms', 'code' => 'KG', 'is_active' => 1]);
        $this->command->info('Units seeded.');

        // Categories
        $cat1 = ItemCategory::create(['name' => 'Electronics', 'code' => 'ELEC', 'is_active' => 1]);
        $cat2 = ItemCategory::create(['name' => 'Hardware', 'code' => 'HARD', 'is_active' => 1]);
        $this->command->info('Categories seeded.');

        // Brands
        $brand1 = Brand::create(['name' => 'Samsung', 'code' => 'SAM', 'is_active' => 1]);
        $brand2 = Brand::create(['name' => 'Generic', 'code' => 'GEN', 'is_active' => 1]);
        $this->command->info('Brands seeded.');

        // Accounts
        $cashAcc = Account::create(['name' => 'Cash Account', 'code' => '1000', 'type' => 'Asset', 'is_active' => 1]);
        $bankAcc = Account::create(['name' => 'HNB Bank', 'code' => '1100', 'type' => 'Asset', 'is_active' => 1]);
        $payableAcc = Account::create(['name' => 'Accounts Payable', 'code' => '2000', 'type' => 'Liability', 'is_active' => 1]);
        $this->command->info('Accounts seeded.');

        // Vendors
        $vendor = Vendor::create([
            'company_name' => 'Main Supplier PVT LTD',
            'name' => 'Supplier One',
            'code' => 'VEND001',
            'mobile_no' => '0771234567',
            'email' => 'supplier@example.com',
            'address' => '123 Supply St, Colombo',
            'currency' => 'LKR',
            'credit_limit' => 500000,
            'bank_name' => 'HNB',
            'bank_account_number' => '1234567890'
        ]);
        $this->command->info('Vendors seeded.');

        // Territory, Area, Route for Customers
        $territory = Territory::create(['name' => 'Western Province', 'is_active' => 1]);
        $area = Area::create(['name' => 'Colombo District', 'is_active' => 1]);
        $area->territories()->attach($territory->id);
        
        $route = Route::create([
            'name' => 'Colombo North', 
            'area_id' => $area->id, 
            'territory_id' => $territory->id, 
            'is_active' => 1
        ]);
        $cusCat = CustomerCategory::create(['name' => 'Wholesale', 'is_active' => 1]);
        
        // Rep User
        $rep = User::where('role', 'ref')->first() ?? User::create([
            'name' => 'Test Rep',
            'email' => 'rep@example.com',
            'password' => bcrypt('password'),
            'role' => 'ref',
            'is_active' => 1
        ]);

        // Customers
        $customer = Customer::create([
            'company_name' => 'Global Retailers',
            'name' => 'Customer One',
            'code' => 'CUS001',
            'mobile_no' => '0711234567',
            'email' => 'customer@example.com',
            'address' => '456 Retail Rd, Kandy',
            'route_id' => $route->id,
            'location_id' => $mainLoc->id,
            'customer_category_id' => $cusCat->id,
            'rep_id' => $rep->id,
            'balance' => 0
        ]);
        $this->command->info('Customers seeded.');

        // Products
        $p1 = Product::create([
            'name' => 'Samsung LED TV 32',
            'code' => 'TV-SAM-32',
            'category' => $cat1->name,
            'brand' => $brand1->name,
            'unit' => $pcs->code,
            'cost' => 45000,
            'max_sale_price' => 55000,
            'min_sale_price' => 52000,
            'is_sale' => true,
            'is_purchase' => true,
            'stock' => 0
        ]);

        $p2 = Product::create([
            'name' => 'Iron Rod 12mm',
            'code' => 'ROD-12MM',
            'category' => $cat2->name,
            'brand' => $brand2->name,
            'unit' => $pcs->code,
            'cost' => 1200,
            'max_sale_price' => 1500,
            'min_sale_price' => 1400,
            'is_sale' => true,
            'is_purchase' => true,
            'stock' => 0
        ]);
        $this->command->info('Products seeded.');

        $this->command->info('System Test Seeding completed successfully!');
    }
}
