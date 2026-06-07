<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseClearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting database clearing process...');

        // 1. Set foreign key columns on the users table to NULL to ensure clean state
        $this->command->line('Updating users table: setting route_id and location_id to NULL...');
        DB::table('users')->update([
            'route_id' => null,
            'location_id' => null,
        ]);
        $this->command->info('Users table successfully updated.');

        // 2. Fetch all tables from the database
        $dbName = env('DB_DATABASE', 'sagaki_distribution');
        $keyName = "Tables_in_" . $dbName;
        $tables = DB::select('SHOW TABLES');

        // Exclude system/login tables
        $excludedTables = [
            'migrations',
            'users'
        ];

        // 3. Disable foreign key constraints to allow truncating
        Schema::disableForeignKeyConstraints();

        $this->command->line('Truncating tables...');
        foreach ($tables as $table) {
            $tableName = $table->$keyName;
            
            if (in_array($tableName, $excludedTables)) {
                $this->command->comment("Skipping excluded table: {$tableName}");
                continue;
            }

            try {
                DB::table($tableName)->truncate();
                $this->command->info("Truncated table: {$tableName}");
            } catch (\Exception $e) {
                $this->command->error("Failed to truncate table {$tableName}: " . $e->getMessage());
            }
        }

        // 4. Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();

        $this->command->info('Database clearing process completed successfully!');
    }
}
