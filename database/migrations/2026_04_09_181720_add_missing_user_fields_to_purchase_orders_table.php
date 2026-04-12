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
            if (!Schema::hasColumn('purchase_orders', 'order_by')) {
                $table->string('order_by')->nullable()->after('expected_date');
            }
            if (!Schema::hasColumn('purchase_orders', 'checked_by')) {
                $table->string('checked_by')->nullable()->after('order_by');
            }
            if (!Schema::hasColumn('purchase_orders', 'rep')) {
                $table->string('rep')->nullable()->after('checked_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['order_by', 'checked_by', 'rep']);
        });
    }
};
