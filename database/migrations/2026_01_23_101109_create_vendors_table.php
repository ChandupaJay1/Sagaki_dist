<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('category')->nullable();
            $table->string('main_office_no')->nullable();
            $table->string('main_office_no_2')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->unique();
            $table->string('cc_email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('currency')->default('LKR');
            $table->string('account_payables')->nullable();
            $table->string('terms')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('svat_no')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0.00);
            $table->string('contact_person_1')->nullable();
            $table->string('contact_person_2')->nullable();
            $table->string('contact_person_3')->nullable();
            $table->string('print_name_on_cheque')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
