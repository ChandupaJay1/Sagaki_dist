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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('villa_type');
            $table->dropColumn('meal_plan');
            $table->dropColumn('no_of_pax');
            $table->dropColumn('check_in_date');
            $table->dropColumn('room_type');
            $table->dropColumn('check_out_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('villa_type')->nullable();
            $table->string('meal_plan')->nullable();
            $table->integer('no_of_pax')->nullable();
            $table->date('check_in_date')->nullable();
            $table->string('room_type')->nullable();
            $table->date('check_out_date')->nullable();
        });
    }
};