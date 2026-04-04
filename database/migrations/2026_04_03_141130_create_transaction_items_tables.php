<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns to header tables
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'header_discount_percent')) {
                $table->decimal('header_discount_percent', 8, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('invoices', 'header_discount_amount')) {
                $table->decimal('header_discount_amount', 15, 2)->default(0)->after('header_discount_percent');
            }
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'rep_id')) {
                $table->foreignId('rep_id')->nullable()->constrained('users')->after('customer_id');
            }
            if (!Schema::hasColumn('sales_orders', 'terms')) {
                $table->string('terms')->nullable()->after('expected_date');
            }
            if (!Schema::hasColumn('sales_orders', 'due_date')) {
                $table->date('due_date')->nullable()->after('terms');
            }
            if (!Schema::hasColumn('sales_orders', 'attent')) {
                $table->string('attent')->nullable()->after('due_date');
            }
            if (!Schema::hasColumn('sales_orders', 'header_discount_percent')) {
                $table->decimal('header_discount_percent', 8, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('sales_orders', 'header_discount_amount')) {
                $table->decimal('header_discount_amount', 15, 2)->default(0)->after('header_discount_percent');
            }
        });

        // Add similar missing columns to other tables as needed...
        $headerTables = ['purchase_orders', 'sales_returns', 'grns', 'grn_returns'];
        foreach ($headerTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'header_discount_percent')) {
                    $table->decimal('header_discount_percent', 8, 2)->default(0)->after('total_amount');
                }
                if (!Schema::hasColumn($tableName, 'header_discount_amount')) {
                    $table->decimal('header_discount_amount', 15, 2)->default(0)->after('header_discount_percent');
                }
            });
        }

        // --- Create Item Tables ---

        $itemTables = [
            'invoice_items' => 'invoice_id',
            'sales_order_items' => 'sales_order_id',
            'sales_return_items' => 'sales_return_id',
            'purchase_order_items' => 'purchase_order_id',
            'grn_items' => 'grn_id',
            'grn_return_items' => 'grn_return_id',
        ];

        foreach ($itemTables as $tableName => $foreignKey) {
            Schema::create($tableName, function (Blueprint $table) use ($foreignKey, $tableName) {
                $table->id();
                $table->foreignId($foreignKey)->constrained(str_replace('_items', 's', $tableName))->onDelete('cascade');
                $table->foreignId('product_id')->constrained();
                $table->string('description')->nullable();
                $table->decimal('qty', 15, 4)->default(0);
                $table->decimal('rate', 15, 2)->default(0);
                $table->decimal('amount', 15, 2)->default(0);
                $table->decimal('disc_percent', 8, 2)->default(0);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->string('location')->nullable();
                $table->string('unit')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_return_items');
        Schema::dropIfExists('grn_items');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('sales_return_items');
        Schema::dropIfExists('sales_order_items');
        Schema::dropIfExists('invoice_items');
        
        // Note: We usually don't drop columns in down() for complex multi-table migrations 
        // to avoid accidental data loss if not carefully managed.
    }
};
