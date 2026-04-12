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
        Schema::table('grn_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('grn_returns', 'expected_date')) {
                $table->date('expected_date')->nullable()->after('invoice_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grn_returns', function (Blueprint $table) {
            $table->dropColumn(['expected_date']);
        });
    }
};
