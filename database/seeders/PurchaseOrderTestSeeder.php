<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;

class PurchaseOrderTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find or create 'Demo Vendor'
        $vendor = Vendor::firstOrCreate(
            ['company_name' => 'Demo Vendor'],
            [
                'code' => 'DEMO-V001',
                'name' => 'Demo Vendor Contact',
                'email' => 'demo_vendor@example.com',
                'phone' => '0771112223',
                'address' => '456 Supplier Road, Colombo',
            ]
        );

        // 2. Ensure we have at least 3 dummy products to use as items
        $products = collect();
        for ($i = 1; $i <= 3; $i++) {
            $product = Product::firstOrCreate(
                ['name' => "Test Material $i"],
                [
                    'code' => "MAT-00$i",
                    'is_purchase' => 1,
                    'is_sale' => 1,
                    'cost' => $i * 1500,
                    'unit' => 'PCs',
                ]
            );
            $products->push($product);
        }

        // 3. Define Purchase Orders
        $ordersData = [
            [
                'po_no'        => 'PO-TEST-001',
                'reference_no' => 'REF-001',
                'date'         => Carbon::now()->subDays(10)->format('Y-m-d'),
                'expected_date'=> Carbon::now()->subDays(3)->format('Y-m-d'),
                'status'       => 'Pending',
                'memo'         => 'Urgent order for project A',
                'items' => [
                    ['product' => $products[0], 'qty' => 10, 'rate' => 1500],
                    ['product' => $products[1], 'qty' => 5,  'rate' => 3000],
                ]
            ],
            [
                'po_no'        => 'PO-TEST-002',
                'reference_no' => 'REF-002',
                'date'         => Carbon::now()->subDays(5)->format('Y-m-d'),
                'expected_date'=> Carbon::now()->addDays(2)->format('Y-m-d'),
                'status'       => 'Approved',
                'memo'         => 'Approved and waiting for delivery',
                'items' => [
                    ['product' => $products[2], 'qty' => 20, 'rate' => 4500],
                    ['product' => $products[0], 'qty' => 50, 'rate' => 1400], // Bulk discount applied to rate
                ]
            ],
            [
                'po_no'        => 'PO-TEST-003',
                'reference_no' => 'REF-003',
                'date'         => Carbon::now()->subDays(20)->format('Y-m-d'),
                'expected_date'=> Carbon::now()->subDays(15)->format('Y-m-d'),
                'status'       => 'Completed',
                'memo'         => 'Order fully received and completed',
                'items' => [
                    ['product' => $products[1], 'qty' => 15, 'rate' => 3000],
                ]
            ],
            [
                'po_no'        => 'PO-TEST-004',
                'reference_no' => 'REF-004',
                'date'         => Carbon::now()->format('Y-m-d'),
                'expected_date'=> Carbon::now()->addDays(7)->format('Y-m-d'),
                'status'       => 'Pending',
                'memo'         => 'Standard monthly restock',
                'items' => [
                    ['product' => $products[0], 'qty' => 100, 'rate' => 1500],
                    ['product' => $products[1], 'qty' => 100, 'rate' => 3000],
                    ['product' => $products[2], 'qty' => 100, 'rate' => 4500],
                ]
            ],
        ];

        // 4. Create the records
        foreach ($ordersData as $data) {
            
            // Calculate totals
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += ($item['qty'] * $item['rate']);
            }

            // Create PO
            $po = PurchaseOrder::create([
                'vendor_id'    => $vendor->id,
                'location_id'  => 1, // Default main location
                'po_no'        => $data['po_no'],
                'reference_no' => $data['reference_no'],
                'date'         => $data['date'],
                'expected_date'=> $data['expected_date'],
                'subtotal'     => $subtotal,
                'total_amount' => $subtotal, // Assuming no tax/discount for simplicity
                'status'       => $data['status'],
                'memo'         => $data['memo'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);

            // Create PO Items
            foreach ($data['items'] as $item) {
                $lineTotal = $item['qty'] * $item['rate'];
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id'        => $item['product']->id,
                    'description'       => $item['product']->name,
                    'qty'               => $item['qty'],
                    'rate'              => $item['rate'],
                    'amount'            => $lineTotal,
                    'total'             => $lineTotal,
                    'unit'              => $item['product']->unit,
                ]);
            }
        }

        $this->command->info("Successfully populated Purchase Orders and Items for Demo Vendor!");
    }
}
