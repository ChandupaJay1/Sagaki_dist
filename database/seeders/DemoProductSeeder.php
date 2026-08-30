<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendor = \App\Models\Vendor::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo Vendor', 'phone' => '0771234567', 'address' => 'Colombo']
        );

        \App\Models\Product::updateOrCreate(
            ['code' => 'LIVE-001'],
            [
            'name' => 'Live Demo Product',
            'sku' => '88888888',
            'category' => 'Electronics',
            'cost' => 5000,
            'max_sale_price' => 7500,
            'vendor_id' => $vendor->id,
            'inventory_account' => '1300 - Inventory Asset',
            'cost_account' => '8000 - Cost of Goods Sold',
            'sales_account' => '7000 - Sales Income',
            'description' => 'This product was automatically created for demonstration purposes.',
            'is_purchase' => true,
            'is_sale' => true
        ]);
    }
}
