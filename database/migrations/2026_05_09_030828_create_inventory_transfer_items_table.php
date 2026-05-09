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
        if (!Schema::hasTable('inventory_transfer_items')) {
            Schema::create('inventory_transfer_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_transfer_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained();
                $table->string('description')->nullable();
                $table->decimal('onhand', 15, 4)->default(0);
                $table->decimal('qty', 15, 4)->default(0);
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
        Schema::dropIfExists('inventory_transfer_items');
    }
};
