<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Grn;
use App\Models\GrnReturn;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;

class SupplierBillTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Get required foreign key dependencies
        $location = Location::first();
        $user = User::first();

        if (!$location || !$user) {
            $this->command->error('Please ensure at least one Location and one User exist before running this seeder.');
            return;
        }

        // 2. Find or Create Demo Vendor
        $vendor = Vendor::firstOrCreate(
            ['name' => 'Demo Vendor'],
            [
                'company_name' => 'Demo Vendor Ltd',
                'category' => 'General',
                'email' => 'demo@vendor.com',
                'phone' => '0771234567',
                'address' => '123 Test Street, Colombo',
                'credit_limit' => 500000,
            ]
        );

        $this->command->info('Demo Vendor created/found with ID: ' . $vendor->id);

        // 3. Generate 3-4 Outstanding Purchase Bills (GRNs)
        $bills = [
            [
                'grn_no' => 'GRN-TEST-001',
                'date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'total_amount' => 150000.50,
            ],
            [
                'grn_no' => 'GRN-TEST-002',
                'date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'total_amount' => 75200.00,
            ],
            [
                'grn_no' => 'GRN-TEST-003',
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'total_amount' => 12500.25,
            ],
            [
                'grn_no' => 'GRN-TEST-004',
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(13)->format('Y-m-d'),
                'total_amount' => 30000.00,
            ],
        ];

        foreach ($bills as $billData) {
            Grn::firstOrCreate(
                ['grn_no' => $billData['grn_no']],
                [
                    'vendor_id' => $vendor->id,
                    'date' => $billData['date'],
                    'due_date' => $billData['due_date'],
                    'total_amount' => $billData['total_amount'],
                    'status' => 'Approved',
                ]
            );
        }

        $this->command->info('Outstanding GRN Bills created.');

        // 4. Generate Vendor Credits (GRN Returns)
        $credits = [
            [
                'return_no' => 'RTN-TEST-001',
                'date' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'total_amount' => 5000.00,
            ],
            [
                'return_no' => 'RTN-TEST-002',
                'date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'total_amount' => 1200.50,
            ],
        ];

        foreach ($credits as $creditData) {
            GrnReturn::firstOrCreate(
                ['return_no' => $creditData['return_no']],
                [
                    'vendor_id' => $vendor->id,
                    'date' => $creditData['date'],
                    'total_amount' => $creditData['total_amount'],
                    'status' => 'Approved',
                ]
            );
        }

        $this->command->info('Vendor Credits (GRN Returns) created.');
        $this->command->info('Test data generation complete. Go to /pay-bills/supplier/create and select "Demo Vendor".');
    }
}
