<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('area_territory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('territory_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Data migration: Copy existing territory_id from areas to area_territory
        $existingAreas = DB::table('areas')->whereNotNull('territory_id')->get();
        foreach ($existingAreas as $area) {
            DB::table('area_territory')->insert([
                'area_id' => $area->id,
                'territory_id' => $area->territory_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop territory_id from areas table
        Schema::table('areas', function (Blueprint $table) {
            $table->dropForeign(['territory_id']);
            $table->dropColumn('territory_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->foreignId('territory_id')->nullable()->constrained()->onDelete('set null');
        });

        // Copy back from area_territory to areas (taking only one territory if multiple exist)
        $pivotData = DB::table('area_territory')->orderBy('created_at')->get();
        foreach ($pivotData as $link) {
            DB::table('areas')->where('id', $link->area_id)->update(['territory_id' => $link->territory_id]);
        }

        Schema::dropIfExists('area_territory');
    }
};
