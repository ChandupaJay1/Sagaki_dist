<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PaymentMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Accounts
        $accounts = [
            ['name' => 'Cash in Hand', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Bank BOC', 'type' => 'Asset', 'is_active' => 1],
            ['name' => 'Bank Commercial', 'type' => 'Asset', 'is_active' => 1],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(
                ['name' => $acc['name']],
                ['type' => $acc['type'], 'is_active' => $acc['is_active']]
            );
        }

        // 2. Seed Locations (Sites)
        $locations = [
            ['name' => 'Main Warehouse', 'is_active' => 1, 'contact_no' => '0112000000'],
            ['name' => 'Colombo Branch', 'is_active' => 1, 'contact_no' => '0112111111'],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(
                ['name' => $loc['name']],
                ['is_active' => $loc['is_active'], 'contact_no' => $loc['contact_no']]
            );
        }

        // 3. Seed Users (Reps)
        $reps = [
            ['name' => 'Admin Rep', 'email' => 'admin_rep@example.com', 'role' => 'ref', 'is_active' => 1],
            ['name' => 'Sales Rep 1', 'email' => 'sales_rep1@example.com', 'role' => 'ref', 'is_active' => 1],
        ];

        foreach ($reps as $rep) {
            User::firstOrCreate(
                ['email' => $rep['email']],
                [
                    'name'      => $rep['name'],
                    'role'      => $rep['role'],
                    'is_active' => $rep['is_active'],
                    'password'  => Hash::make('password123'),
                ]
            );
        }

        $this->command->info("Successfully populated Payment Master Data (Accounts, Locations, Reps)!");
    }
}
