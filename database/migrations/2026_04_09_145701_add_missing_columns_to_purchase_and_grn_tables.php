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
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'reference_no')) {
                $table->string('reference_no')->nullable()->after('load');
            }
            if (!Schema::hasColumn('purchase_orders', 'expected_date')) {
                $table->date('expected_date')->nullable()->after('date');
            }
            if (!Schema::hasColumn('purchase_orders', 'memo')) {
                $table->text('memo')->nullable()->after('attent');
            }
        });

        Schema::table('grns', function (Blueprint $table) {
            if (!Schema::hasColumn('grns', 'expected_date')) {
                $table->date('expected_date')->nullable()->after('invoice_date');
            }
            if (!Schema::hasColumn('grns', 'memo')) {
                $table->text('memo')->nullable()->after('attent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['reference_no', 'expected_date', 'memo']);
        });
        Schema::table('grns', function (Blueprint $table) {
            $table->dropColumn(['expected_date', 'memo']);
        });
    }
};
