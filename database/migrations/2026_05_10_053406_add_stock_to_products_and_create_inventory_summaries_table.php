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
        // Inventory Summary: Add stock to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock')) {
                $table->decimal('stock', 15, 2)->default(0)->after('cost');
            }
        });

        // Location Summary: Create inventory_summaries table
        if (!Schema::hasTable('inventory_summaries')) {
            Schema::create('inventory_summaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('location_id')->constrained()->onDelete('cascade');
                $table->decimal('qty', 15, 2)->default(0);
                $table->timestamps();
                
                $table->unique(['product_id', 'location_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_summaries');
        
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};
