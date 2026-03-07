<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            if (!Schema::hasColumn('areas', 'territory_id')) {
                $table->foreignId('territory_id')->nullable()->after('id')->constrained('territories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            if (Schema::hasColumn('areas', 'territory_id')) {
                $table->dropConstrainedForeignId('territory_id');
            }
        });
    }
};

