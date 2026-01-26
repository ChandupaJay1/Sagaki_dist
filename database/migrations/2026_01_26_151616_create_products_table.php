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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('sku')->nullable(); // Barcode
            
            // Product Classification
            $table->boolean('is_main_product')->default(false);
            $table->foreignId('main_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('description')->nullable();

            // Location
            $table->string('floor')->nullable();
            $table->string('rack')->nullable();
            $table->string('row')->nullable();
            $table->string('bin')->nullable();
            $table->string('location')->nullable();
            
            // Inventory & Alerts
            $table->integer('reorder_point')->nullable();
            $table->integer('alert_quantity')->nullable();
            $table->integer('qty_in_bulk')->nullable();

            // Attributes / Toggles
            $table->boolean('is_purchase')->default(false);
            $table->boolean('is_sale')->default(false);
            $table->boolean('is_production')->default(false);
            $table->boolean('is_serialized')->default(false);
            $table->boolean('is_stock_report')->default(false);
            $table->boolean('is_price_level')->default(false);
            $table->boolean('is_multi_price')->default(false);

            // Accounts & Relations
            $table->string('project')->nullable();
            $table->string('supplier_warranty')->nullable();
            $table->string('customer_warranty')->nullable();
            
            // If vendor table exists, we can constrain, else just nullable string or unsignedBigInteger
            // Checked earlier: vendor table exists (migrated just now)
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete(); 
            
            $table->string('inventory_account')->nullable();
            $table->string('cost_account')->nullable();
            $table->string('sales_account')->nullable();

            // Pricing
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('max_sale_price', 15, 2)->nullable();
            $table->decimal('min_sale_price', 15, 2)->nullable();
            $table->decimal('max_wholesale_price', 15, 2)->nullable();
            $table->decimal('min_wholesale_price', 15, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();

            $table->string('image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
