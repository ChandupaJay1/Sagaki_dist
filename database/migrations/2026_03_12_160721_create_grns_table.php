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
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->string('address')->nullable();
            $table->string('delivery_destination')->nullable();
            $table->string('load')->nullable();
            $table->string('grn_no')->nullable();
            $table->date('date')->nullable();
            $table->string('order_by')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('reference_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('attent')->nullable();
            $table->string('terms')->nullable();
            $table->date('due_date')->nullable();
            $table->string('manual_no')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grns');
    }
};
