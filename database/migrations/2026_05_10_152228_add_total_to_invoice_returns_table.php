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
        Schema::table('invoice_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_returns', 'total')) {
                $table->decimal('total', 15, 2)->after('discount')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_returns', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
};
