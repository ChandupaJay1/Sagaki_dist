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
        $tables = ['sales_orders', 'invoices', 'purchase_orders', 'sales_returns', 'grns', 'grn_returns'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_blueprint) use ($table) {
                if (!Schema::hasColumn($table, 'subtotal')) {
                    $table_blueprint->decimal('subtotal', 15, 2)->default(0)->after('total_amount');
                }
                if (!Schema::hasColumn($table, 'tax_amount')) {
                    $table_blueprint->decimal('tax_amount', 15, 2)->default(0)->after('subtotal');
                }
                if (!Schema::hasColumn($table, 'header_discount_percent')) {
                    $table_blueprint->decimal('header_discount_percent', 15, 2)->default(0)->after('tax_amount');
                }
                if (!Schema::hasColumn($table, 'header_discount_amount')) {
                    $table_blueprint->decimal('header_discount_amount', 15, 2)->default(0)->after('header_discount_percent');
                }
                if (!Schema::hasColumn($table, 'status')) {
                    $table_blueprint->string('status')->default('active')->after('header_discount_amount');
                }
                if (!Schema::hasColumn($table, 'terms')) {
                    $table_blueprint->string('terms')->nullable()->after('status');
                }
                if (!Schema::hasColumn($table, 'due_date')) {
                    $table_blueprint->date('due_date')->nullable()->after('terms');
                }
                if (!Schema::hasColumn($table, 'attent')) {
                    $table_blueprint->string('attent')->nullable()->after('due_date');
                }
                if (!Schema::hasColumn($table, 'location_id')) {
                    $table_blueprint->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete()->after('attent');
                }
                if (!Schema::hasColumn($table, 'payment_term_id')) {
                    $table_blueprint->foreignId('payment_term_id')->nullable()->constrained('terms')->nullOnDelete()->after('location_id');
                }
                
                // Table specific fields
                if ($table === 'sales_orders') {
                    if (!Schema::hasColumn($table, 'rep_id')) {
                        $table_blueprint->foreignId('rep_id')->nullable()->constrained('users')->nullOnDelete();
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['sales_orders', 'invoices', 'purchase_orders', 'sales_returns', 'grns', 'grn_returns'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_blueprint) use ($table) {
                $columns = ['subtotal', 'tax_amount', 'header_discount_percent', 'header_discount_amount', 'status', 'terms', 'due_date', 'attent', 'location_id', 'payment_term_id'];
                if ($table === 'sales_orders') {
                    $columns[] = 'rep_id';
                }
                $table_blueprint->dropColumn($columns);
            });
        }
    }
};
