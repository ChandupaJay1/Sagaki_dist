<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryTransferFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InventoryTransferItem::truncate();
        InventoryTransfer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = Product::limit(5)->get();
        if ($products->isEmpty()) {
            return;
        }

        // Create Transfer 1
        $t1 = InventoryTransfer::create([
            'site_from' => 'Main',
            'site_to' => 'Showroom',
            'transfer_no' => 'TN-00001',
            'date' => date('Y-m-d'),
            'memo' => 'Stock replenishment for Showroom',
            'status' => 'Pending',
        ]);

        foreach ($products as $index => $p) {
            if ($index > 2) break;
            InventoryTransferItem::create([
                'inventory_transfer_id' => $t1->id,
                'product_id' => $p->id,
                'description' => $p->name,
                'onhand' => 100,
                'qty' => 10 + $index,
                'unit' => $p->unit ?? 'PCS',
            ]);
        }

        // Create Transfer 2
        $t2 = InventoryTransfer::create([
            'site_from' => 'Main',
            'site_to' => 'Warehouse A',
            'transfer_no' => 'TN-00002',
            'date' => date('Y-m-d'),
            'memo' => 'Internal transfer',
            'status' => 'Approved',
        ]);

        foreach ($products as $index => $p) {
            if ($index < 2) continue;
            InventoryTransferItem::create([
                'inventory_transfer_id' => $t2->id,
                'product_id' => $p->id,
                'description' => $p->name,
                'onhand' => 50,
                'qty' => 5,
                'unit' => $p->unit ?? 'PCS',
            ]);
        }
    }
}
