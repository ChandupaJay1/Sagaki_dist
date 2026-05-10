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
        if (!Schema::hasTable('stock_adjustments')) {
            Schema::create('stock_adjustments', function (Blueprint $table) {
                $table->id();
                $table->string('adjustment_no')->unique();
                $table->foreignId('location_id')->constrained();
                $table->date('date');
                $table->text('memo')->nullable();
                $table->string('status')->default('Pending');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('stock_adjustment_items')) {
            Schema::create('stock_adjustment_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stock_adjustment_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained();
                $table->decimal('current_qty', 15, 4)->default(0);
                $table->decimal('new_qty', 15, 4)->default(0);
                $table->decimal('adjustment_qty', 15, 4)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('stock_adjustments');
    }
};
