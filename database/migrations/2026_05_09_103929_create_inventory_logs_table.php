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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->integer('location_id')->nullable();
            $table->string('reference_type'); // Invoice, GRN, etc.
            $table->unsignedBigInteger('reference_id');
            $table->decimal('change_qty', 15, 2);
            $table->decimal('after_qty', 15, 2);
            $table->string('type'); // Sale, Return, Adjustment, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
