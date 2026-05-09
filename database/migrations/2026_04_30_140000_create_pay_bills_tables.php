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
        if (!Schema::hasTable('pay_bills')) {
            Schema::create('pay_bills', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('Supplier'); // Supplier or Customer
                $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
                $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
                $table->string('voucher_no')->unique();
                $table->date('date');
                $table->decimal('total_amount', 15, 2)->default(0.00);
                $table->string('payment_method')->nullable();
                $table->string('cheque_no')->nullable();
                $table->date('pd_cheque_date')->nullable();
                $table->text('memo')->nullable();
                $table->string('status')->default('Paid');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pay_bill_items')) {
            Schema::create('pay_bill_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pay_bill_id')->constrained('pay_bills')->cascadeOnDelete();
                $table->foreignId('grn_id')->nullable()->constrained('grns')->nullOnDelete();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->string('bill_no')->nullable();
                $table->date('bill_date')->nullable();
                $table->date('due_date')->nullable();
                $table->decimal('bill_amount', 15, 2)->default(0.00);
                $table->decimal('amount_to_pay', 15, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_bill_items');
        Schema::dropIfExists('pay_bills');
    }
};
