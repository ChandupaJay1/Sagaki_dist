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
        Schema::table('sales_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_returns', 'reference_no')) {
                $table->string('reference_no')->nullable()->after('load');
            }
            if (!Schema::hasColumn('sales_returns', 'expected_date')) {
                $table->date('expected_date')->nullable()->after('date');
            }
            if (!Schema::hasColumn('sales_returns', 'order_by')) {
                $table->string('order_by')->nullable()->after('expected_date');
            }
            if (!Schema::hasColumn('sales_returns', 'checked_by')) {
                $table->string('checked_by')->nullable()->after('order_by');
            }
            if (!Schema::hasColumn('sales_returns', 'rep')) {
                $table->string('rep')->nullable()->after('checked_by');
            }
            if (!Schema::hasColumn('sales_returns', 'ship_via')) {
                $table->string('ship_via')->nullable()->after('rep');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropColumn(['reference_no', 'expected_date', 'order_by', 'checked_by', 'rep', 'ship_via']);
        });
    }
};
