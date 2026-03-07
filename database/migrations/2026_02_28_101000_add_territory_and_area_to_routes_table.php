<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (!Schema::hasColumn('routes', 'territory_id')) {
                $table->foreignId('territory_id')->nullable()->after('id')->constrained('territories')->nullOnDelete();
            }
            if (!Schema::hasColumn('routes', 'area_id')) {
                $table->foreignId('area_id')->nullable()->after('territory_id')->constrained('areas')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (Schema::hasColumn('routes', 'area_id')) {
                $table->dropConstrainedForeignId('area_id');
            }
            if (Schema::hasColumn('routes', 'territory_id')) {
                $table->dropConstrainedForeignId('territory_id');
            }
        });
    }
};

