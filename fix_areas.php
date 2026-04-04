\Illuminate\Support\Facades\Schema::table('areas', function (\Illuminate\Database\Schema\Blueprint $table) {
    if (!\Illuminate\Support\Facades\Schema::hasColumn('areas', 'territory_id')) {
        $table->foreignId('territory_id')->nullable()->after('id')->constrained('territories')->nullOnDelete();
        echo "Column territory_id added successfully.\n";
    } else {
        echo "Column territory_id already exists.\n";
    }
});
