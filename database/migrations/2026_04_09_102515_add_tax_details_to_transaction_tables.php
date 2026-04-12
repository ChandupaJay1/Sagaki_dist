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
                if (!Schema::hasColumn($table, 'sscl_percent')) {
                    $table_blueprint->decimal('sscl_percent', 15, 2)->default(0)->after('status');
                }
                if (!Schema::hasColumn($table, 'sscl_amount')) {
                    $table_blueprint->decimal('sscl_amount', 15, 2)->default(0)->after('sscl_percent');
                }
                if (!Schema::hasColumn($table, 'vat_percent')) {
                    $table_blueprint->decimal('vat_percent', 15, 2)->default(0)->after('sscl_amount');
                }
                if (!Schema::hasColumn($table, 'vat_amount')) {
                    $table_blueprint->decimal('vat_amount', 15, 2)->default(0)->after('vat_percent');
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
                $table_blueprint->dropColumn(['sscl_percent', 'sscl_amount', 'vat_percent', 'vat_amount']);
            });
        }
    }
};
