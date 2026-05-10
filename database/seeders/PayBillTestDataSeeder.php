<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Grn;
use App\Models\GrnReturn;
use App\Models\Location;
use App\Models\Account;
use App\Models\User;

class PayBillTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure a Location and Account exist
        $location = Location::firstOrCreate(['name' => 'Main'], ['is_active' => 1]);
        $account = Account::firstOrCreate(['name' => 'Cash Account'], ['is_active' => 1]);
        $rep = User::where('role', 'ref')->first() ?: User::factory()->create(['role' => 'ref']);

        // 2. Create a Test Vendor
        $vendor = Vendor::firstOrCreate(
            ['email' => 'testvendor@example.com'],
            [
                'company_name' => 'Test Vendor (Calculation Test)',
                'name' => 'Test Vendor',
                'mobile_no' => '0771234567',
            ]
        );

        // 3. Create Outstanding Bills (GRNs)
        // Bill 1: 20,000
        Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-20K',
            'date' => now()->subDays(5)->format('Y-m-d'),
            'due_date' => now()->addDays(25)->format('Y-m-d'),
            'total_amount' => 20000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id,
        ]);

        // Bill 2: 50,000
        Grn::create([
            'vendor_id' => $vendor->id,
            'grn_no' => 'GRN-50K',
            'date' => now()->subDays(10)->format('Y-m-d'),
            'due_date' => now()->addDays(20)->format('Y-m-d'),
            'total_amount' => 50000,
            'status' => 'Pending',
            'location_id' => $location->id,
            'account_id' => $account->id,
        ]);

        // 4. Create Available Credits (GRN Returns)
        // Credit 1: 30,000
        GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'RET-30K',
            'date' => now()->subDays(2)->format('Y-m-d'),
            'subtotal' => 30000,
            'total_amount' => 30000,
            'status' => 'Approved',
            'location_id' => $location->id,
        ]);

        // Credit 2: 10,000
        GrnReturn::create([
            'vendor_id' => $vendor->id,
            'return_no' => 'RET-10K',
            'date' => now()->subDays(1)->format('Y-m-d'),
            'subtotal' => 10000,
            'total_amount' => 10000,
            'status' => 'Approved',
            'location_id' => $location->id,
        ]);
    }
}
