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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->string('company_name')->nullable()->after('name');
            $table->string('category')->nullable()->after('company_name');
            $table->string('main_office_no')->nullable()->after('category');
            $table->string('main_office_no_2')->nullable()->after('main_office_no');
            $table->string('mobile_no')->nullable()->after('main_office_no_2');
            $table->string('fax')->nullable()->after('mobile_no');
            $table->string('cc_email')->nullable()->after('email');
            $table->string('website')->nullable()->after('cc_email');
            
            // Billing / Commercial Details
            $table->string('currency')->default('LKR')->after('address');
            $table->string('account_payables')->nullable()->after('currency');
            $table->string('terms')->nullable()->after('account_payables');
            $table->string('vat_no')->nullable()->after('terms');
            $table->string('svat_no')->nullable()->after('vat_no');
            $table->decimal('credit_limit', 15, 2)->default(0.00)->after('svat_no');
            
            // Contacts
            $table->string('contact_person_1')->nullable()->after('credit_limit');
            $table->string('contact_person_2')->nullable()->after('contact_person_1');
            $table->string('contact_person_3')->nullable()->after('contact_person_2');
            
            // Bank Details
            $table->string('print_name_on_cheque')->nullable()->after('contact_person_3');
            $table->string('bank_name')->nullable()->after('print_name_on_cheque');
            $table->string('bank_branch')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_branch');

            // Make password nullable as this is a CRM record now
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'company_name',
                'category',
                'main_office_no',
                'main_office_no_2',
                'mobile_no',
                'fax',
                'cc_email',
                'website',
                'currency',
                'account_payables',
                'terms',
                'vat_no',
                'svat_no',
                'credit_limit',
                'contact_person_1',
                'contact_person_2',
                'contact_person_3',
                'print_name_on_cheque',
                'bank_name',
                'bank_branch',
                'bank_account_number'
            ]);

            $table->string('password')->nullable(false)->change();
        });
    }
};
