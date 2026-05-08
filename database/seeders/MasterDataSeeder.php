<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Area;
use App\Models\Territory;
use App\Models\Route;
use App\Models\CustomerCategory;
use App\Models\Category;
use App\Models\ProductSubCategory;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Currency;
use App\Models\ItemCategory;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Accounts
        $accounts = [
            ['name' => 'Main Cash Account', 'code' => '1000', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Petty Cash', 'code' => '1010', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'HNB Bank Account', 'code' => '1100', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Commercial Bank Account', 'code' => '1110', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Inventory Asset', 'code' => '1300', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Accounts Receivable', 'code' => '1200', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Accounts Payable', 'code' => '2000', 'type' => 'Liability', 'is_active' => 1],
            ['name' => 'Sales Income', 'code' => '4000', 'type' => 'Income', 'is_active' => 1],
            ['name' => 'Cost of Goods Sold', 'code' => '5000', 'type' => 'Expense', 'is_active' => 1],
            ['name' => 'Electricity Expense', 'code' => '6000', 'type' => 'Expense', 'is_active' => 1],
            ['name' => 'Rent Expense', 'code' => '6010', 'type' => 'Expense', 'is_active' => 1],
        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(['code' => $acc['code']], $acc);
        }

        // 2. Territories, Areas, Routes
        $territory = Territory::updateOrCreate(['code' => 'TER-001'], ['name' => 'Western Province', 'is_active' => 1]);
        
        $areas = [
            ['name' => 'Colombo', 'code' => 'ARE-001', 'is_active' => 1],
            ['name' => 'Gampaha', 'code' => 'ARE-002', 'is_active' => 1],
        ];

        foreach ($areas as $areaData) {
            $area = Area::updateOrCreate(['code' => $areaData['code']], $areaData);
            $area->territories()->syncWithoutDetaching([$territory->id]);

            Route::updateOrCreate(
                ['code' => 'ROU-' . $areaData['code']],
                [
                    'name' => $areaData['name'] . ' Route 01',
                    'area_id' => $area->id,
                    'territory_id' => $territory->id,
                    'is_active' => 1
                ]
            );
        }

        // 3. Customer Categories
        $customerCats = ['Retail', 'Wholesale', 'Corporate', 'Distributor'];
        foreach ($customerCats as $cat) {
            CustomerCategory::updateOrCreate(['name' => $cat], ['is_active' => 1]);
        }

        // 4. Item Categories & Sub Categories
        $itemCats = [
            'Electronics' => ['Mobile Phones', 'Laptops', 'Accessories'],
            'Beverages' => ['Soft Drinks', 'Juices', 'Water'],
            'Groceries' => ['Rice', 'Flour', 'Sugar'],
        ];

        foreach ($itemCats as $mainCat => $subCats) {
            $category = ItemCategory::updateOrCreate(['name' => $mainCat], ['is_active' => 1]);
            foreach ($subCats as $sub) {
                ProductSubCategory::updateOrCreate(
                    ['name' => $sub],
                    ['item_category_id' => $category->id, 'is_active' => 1]
                );
            }
        }

        // 5. Units
        $units = [
            ['name' => 'Each', 'code' => 'PCS', 'is_active' => 1],
            ['name' => 'Kilogram', 'code' => 'KG', 'is_active' => 1],
            ['name' => 'Liter', 'code' => 'L', 'is_active' => 1],
            ['name' => 'Box', 'code' => 'BOX', 'is_active' => 1],
            ['name' => 'Packet', 'code' => 'PKT', 'is_active' => 1],
        ];
        foreach ($units as $u) {
            Unit::updateOrCreate(['code' => $u['code']], $u);
        }

        // 6. Brands
        $brands = ['Samsung', 'Apple', 'Coca-Cola', 'Anchor', 'Munchee'];
        foreach ($brands as $b) {
            Brand::updateOrCreate(['name' => $b], ['is_active' => 1]);
        }

        // 7. Currencies
        Currency::updateOrCreate(['code' => 'LKR'], ['name' => 'Sri Lankan Rupee', 'is_active' => 1]);
        Currency::updateOrCreate(['code' => 'USD'], ['name' => 'US Dollar', 'is_active' => 1]);

        // 8. Locations (Warehouses)
        Location::updateOrCreate(['name' => 'Main Warehouse'], ['is_active' => 1]);
        Location::updateOrCreate(['name' => 'Showroom'], ['is_active' => 1]);
    }
}
