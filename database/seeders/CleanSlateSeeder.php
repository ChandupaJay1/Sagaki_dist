<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\InventoryLog;
use App\Models\InventorySummary;
use App\Models\Location;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Vendor;

/**
 * CleanSlateSeeder
 * ─────────────────────────────────────────────────────────────────────────────
 * PURPOSE
 *   Wipe all transactional and demo data, then seed exactly one clean,
 *   traceable dataset so the upper-limit quantity validation and partial-load
 *   GRN / Invoice features can be verified from a known state.
 *
 * SAFETY GUARANTEES
 *   • The `users` table is NEVER touched — all login credentials are preserved.
 *   • `personal_access_tokens` and any permission/role tables are untouched.
 *   • Foreign-key constraints are temporarily disabled only for the duration of
 *     the truncate block, then immediately re-enabled.
 *
 * WHAT IS WIPED
 *   Transactional documents (both headers and item lines):
 *     grn_return_items / grn_returns
 *     sales_return_items / sales_returns
 *     grn_items / grns
 *     invoice_items / invoices
 *     purchase_order_items / purchase_orders
 *     sales_order_items / sales_orders
 *
 *   Inventory ledger (rebuilt from scratch with test data):
 *     inventory_logs
 *     inventory_summaries
 *     products.stock  (reset to 0 across all rows, then set per test product)
 *
 *   Master data that gets re-seeded cleanly:
 *     vendors (all rows replaced by one test vendor)
 *     customers (all rows replaced by one test customer)
 *     products (all rows replaced by one test product)
 *
 *   Preserved master data (NOT wiped):
 *     locations — only "Main Warehouse" is ensured to exist; existing rows kept.
 *
 * WHAT IS SEEDED
 *   Vendor  : "Test Vendor Enterprises"
 *   Customer: "Test Customer Ltd"
 *   Location: "Main Warehouse" (upserted — not duplicated if it already exists)
 *   Product : "Test Item TV 32"  (cost 15000, sale price 20000, stock 50 at Main Warehouse)
 *
 *   Purchase Order : POND00001 — 1 line, Qty = 10, product above, vendor above
 *   Sales Order    : SO00001   — 1 line, Qty = 10, product above, customer above
 *
 * HOW TO RUN
 *   php artisan db:seed --class=CleanSlateSeeder
 */
class CleanSlateSeeder extends Seeder
{
    // ─── Tables that must be wiped in dependency order (children first) ───────
    private const TRANSACTIONAL_TABLES = [
        // Return children before parents
        'grn_return_items',
        'grn_returns',
        'sales_return_items',
        'sales_returns',
        // GRN children before parents
        'grn_items',
        'grns',
        // Invoice children before parents
        'invoice_items',
        'invoices',
        // Order children before parents
        'purchase_order_items',
        'purchase_orders',
        'sales_order_items',
        'sales_orders',
        // Inventory ledger (no FK dependents on these)
        'inventory_logs',
        'inventory_summaries',
    ];

    // ─── Master tables wiped and replaced ────────────────────────────────────
    private const MASTER_TABLES = [
        'products',   // must come before vendors (products FK → vendors)
        'vendors',
        'customers',
    ];

    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════╗');
        $this->command->info('║         CleanSlateSeeder  —  starting            ║');
        $this->command->info('╚══════════════════════════════════════════════════╝');

        // ── STEP 1 : Wipe transactional + master data ─────────────────────────
        $this->wipe();

        // ── STEP 2 : Seed clean test master data ──────────────────────────────
        [$vendor, $customer, $location, $product] = $this->seedMasterData();

        // ── STEP 3 : Seed source documents ────────────────────────────────────
        $this->seedPurchaseOrder($vendor, $product, $location);
        $this->seedSalesOrder($customer, $product, $location);

        $this->command->info('');
        $this->command->info('✓  CleanSlateSeeder complete. Database is clean and ready for testing.');
        $this->command->info('');
        $this->command->table(
            ['Entity', 'Value'],
            [
                ['Vendor',    'Test Vendor Enterprises'],
                ['Customer',  'Test Customer Ltd'],
                ['Location',  'Main Warehouse'],
                ['Product',   'Test Item TV 32  (stock = 50 @ Main Warehouse)'],
                ['PO',        'POND00001  —  Qty 10  (status: Pending)'],
                ['SO',        'SO00001    —  Qty 10  (status: Pending)'],
            ]
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Truncate all transactional and master tables safely.
     * Foreign-key checks are disabled only for the duration of the truncations
     * and immediately restored afterwards.
     */
    private function wipe(): void
    {
        $this->command->line('  → Wiping transactional tables …');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            foreach (self::TRANSACTIONAL_TABLES as $table) {
                DB::table($table)->truncate();
                $this->command->line("     truncated: {$table}");
            }

            // Reset products.stock to 0 for all rows (inventory re-applied below)
            DB::table('products')->update(['stock' => 0]);
            $this->command->line('     reset: products.stock → 0');

            foreach (self::MASTER_TABLES as $table) {
                DB::table($table)->truncate();
                $this->command->line("     truncated: {$table}");
            }

        } finally {
            // Always re-enable FK checks, even if an exception was thrown
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->line('  ✓ Wipe complete.');
    }

    /**
     * Create one vendor, one customer, ensure Main Warehouse exists,
     * create one product with stock, seed the inventory ledger.
     *
     * @return array{Vendor, Customer, Location, Product}
     */
    private function seedMasterData(): array
    {
        $this->command->line('  → Seeding master data …');

        // ── Vendor ────────────────────────────────────────────────────────────
        $vendor = Vendor::create([
            'name'         => 'Test Vendor Enterprises',
            'company_name' => 'Test Vendor Enterprises',
            'email'        => 'vendor@testenterprises.com',
            'phone'        => '0112345678',
            'address'      => '123 Vendor Street, Colombo 03',
            'currency'     => 'LKR',
            'credit_limit' => 500000.00,
        ]);
        $this->command->line("     vendor created  -> id={$vendor->id}  [{$vendor->company_name}]");

        // ── Customer ──────────────────────────────────────────────────────────
        $customer = Customer::create([
            'name'         => 'Test Customer Ltd',
            'company_name' => 'Test Customer Ltd',
            'email'        => 'customer@testcustomer.com',
            'phone'        => '0113456789',
            'address'      => '456 Customer Avenue, Colombo 07',
            'currency'     => 'LKR',
            'credit_limit' => 300000.00,
            'balance'      => 0.00,
            'password'     => bcrypt('password'),
        ]);
        $this->command->line("     customer created -> id={$customer->id}  [{$customer->company_name}]");

        // ── Location — upsert so existing rows are preserved ──────────────────
        $location = Location::updateOrCreate(
            ['name' => 'Main Warehouse'],
            ['code' => 'MW001', 'is_active' => 1]
        );
        $this->command->line("     location upsert -> id={$location->id}  [{$location->name}]");

        // ── Product ───────────────────────────────────────────────────────────
        $product = Product::create([
            'name'              => 'Test Item TV 32',
            'code'              => 'TV-TEST-32',
            'sku'               => 'TV32TEST001',
            'category'          => 'Electronics',
            'description'       => 'Clean-slate test product for flow validation.',
            'cost'              => 15000.00,
            'max_sale_price'    => 20000.00,
            'min_sale_price'    => 14000.00,
            'unit'              => 'PCS',
            'vendor_id'         => $vendor->id,
            'is_purchase'       => true,
            'is_sale'           => true,
            'is_stock_report'   => true,
            'inventory_account' => '1300 - Inventory Asset',
            'cost_account'      => '5000 - Cost of Goods Sold',
            'sales_account'     => '4000 - Sales Income',
            'stock'             => 50.00,   // global stock total
        ]);
        $this->command->line("     product created  -> id={$product->id}  [{$product->name}]  (stock=50)");

        // ── Inventory Summary — 50 units at Main Warehouse ────────────────────
        InventorySummary::updateOrCreate(
            ['product_id' => $product->id, 'location_id' => $location->id],
            ['qty' => 50.00]
        );

        // ── Inventory Log — opening balance entry ─────────────────────────────
        InventoryLog::create([
            'product_id'     => $product->id,
            'location_id'    => $location->id,
            'change_qty'     => 50.00,
            'after_qty'      => 50.00,
            'type'           => 'Opening',
            'reference_type' => 'CleanSlateSeeder',
            'reference_id'   => 0,
            'description'    => 'Opening stock seeded by CleanSlateSeeder for flow testing',
        ]);

        $this->command->line('  ✓ Master data seeded.');

        return [$vendor, $customer, $location, $product];
    }

    /**
     * Seed one Purchase Order (POND00001) with Qty = 10.
     */
    private function seedPurchaseOrder(Vendor $vendor, Product $product, Location $location): void
    {
        $this->command->line('  → Seeding Purchase Order …');

        $rate   = $product->cost;          // 15 000.00
        $qty    = 10;
        $amount = $qty * $rate;            // 150 000.00

        $po = PurchaseOrder::create([
            'vendor_id'    => $vendor->id,
            'po_no'        => 'POND00001',
            'date'         => now()->toDateString(),
            'location_id'  => $location->id,
            'address'      => $vendor->address,
            'subtotal'     => $amount,
            'total_amount' => $amount,
            'status'       => 'Pending',
            'memo'         => 'Clean-slate test PO — Qty 10 of Test Item TV 32',
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id'        => $product->id,
            'description'       => $product->name,
            'qty'               => $qty,
            'rate'              => $rate,
            'amount'            => $amount,
            'disc_percent'      => 0,
            'discount'          => 0,
            'total'             => $amount,
            'location'          => $location->name,
            'unit'              => $product->unit ?? 'PCS',
        ]);

        $this->command->line("  ✓ PO created → id={$po->id}  no={$po->po_no}  qty={$qty}  total=" . number_format($amount, 2));
    }

    /**
     * Seed one Sales Order (SO00001) with Qty = 10.
     */
    private function seedSalesOrder(Customer $customer, Product $product, Location $location): void
    {
        $this->command->line('  → Seeding Sales Order …');

        $rate   = $product->max_sale_price ?? $product->cost; // 20 000.00
        $qty    = 10;
        $amount = $qty * $rate;                                // 200 000.00

        $so = SalesOrder::create([
            'customer_id'  => $customer->id,
            'order_no'     => 'SO00001',
            'order_date'   => now()->toDateString(),
            'location_id'  => $location->id,
            'address'      => $customer->address,
            'subtotal'     => $amount,
            'total_amount' => $amount,
            'status'       => 'Pending',
            'memo'         => 'Clean-slate test SO — Qty 10 of Test Item TV 32',
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $so->id,
            'product_id'     => $product->id,
            'description'    => $product->name,
            'qty'            => $qty,
            'rate'           => $rate,
            'amount'         => $amount,
            'disc_percent'   => 0,
            'discount'       => 0,
            'total'          => $amount,
            'location'       => $location->name,
            'unit'           => $product->unit ?? 'PCS',
        ]);

        $this->command->line("  ✓ SO created → id={$so->id}  no={$so->order_no}  qty={$qty}  total=" . number_format($amount, 2));
    }
}
