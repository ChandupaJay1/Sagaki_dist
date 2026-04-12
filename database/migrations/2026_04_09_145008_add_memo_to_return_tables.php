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
        if (!Schema::hasColumn('sales_returns', 'memo')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->text('memo')->nullable()->after('attent');
            });
        }
        if (!Schema::hasColumn('grn_returns', 'memo')) {
            Schema::table('grn_returns', function (Blueprint $table) {
                $table->text('memo')->nullable()->after('attent');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropColumn(['memo']);
        });
        Schema::table('grn_returns', function (Blueprint $table) {
            $table->dropColumn(['memo']);
        });
    }
};
