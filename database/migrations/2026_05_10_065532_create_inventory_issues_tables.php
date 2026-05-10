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
        Schema::create('inventory_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no')->unique();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('account_id')->nullable()->constrained();
            $table->date('date');
            $table->text('memo')->nullable();
            $table->string('status')->default('Pending');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('inventory_issue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_issue_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->decimal('qty', 15, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_issue_items');
        Schema::dropIfExists('inventory_issues');
    }
};
