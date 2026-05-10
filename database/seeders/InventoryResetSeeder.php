<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventoryResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();

        // 1. CLEAR COMPLETELY: Transaction and Summary tables
        $tablesToTruncate = [
            'inventory_summaries',
            'inventory_logs',
            'grn_items',
            'grns',
            'sales_return_items',
            'sales_returns',
            'inventory_transfer_items',
            'inventory_transfers',
            'invoice_items',
            'invoices',
            'grn_return_items',
            'grn_returns',
            'invoice_returns',
            'stock_adjustments',
            'purchase_order_items',
            'purchase_orders',
            'sales_order_items',
            'sales_orders',
            'pay_bill_items',
            'pay_bills',
            'payments',
            'cheques',
        ];

        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("Truncated table: {$table}");
            }
        }

        // 2. RESET TO ZERO: Global Product Stocks
        DB::table('products')->update(['stock' => 0.00]);
        $this->command->info("Reset 'stock' in 'products' table to 0.00 for all products.");

        // 3. Optional: Reset Customer and Vendor balances if they are transaction-based
        if (Schema::hasColumn('customers', 'balance')) {
            DB::table('customers')->update(['balance' => 0.00]);
            $this->command->info("Reset 'balance' in 'customers' table to 0.00.");
        }

        // Enable foreign key checks back
        Schema::enableForeignKeyConstraints();

        $this->command->info("Inventory and transaction data reset successfully!");
    }
}
