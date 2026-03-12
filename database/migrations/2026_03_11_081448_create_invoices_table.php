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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('address')->nullable();
            $table->string('delivery_destination')->nullable();
            $table->string('load')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->string('villa_type')->nullable();
            $table->string('meal_plan')->nullable();
            $table->integer('no_of_pax')->nullable();
            $table->date('check_in_date')->nullable();
            $table->string('room_type')->nullable();
            $table->date('check_out_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
