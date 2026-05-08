<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Location;
use App\Models\Grn;
use Carbon\Carbon;

class PayBillTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Location if not exists
        $location = Location::firstOrCreate(
            ['name' => 'Main'],
            ['is_active' => 1]
        );

        // 2. Create a Test Vendor
        $vendor = Vendor::firstOrCreate(
            ['code' => 'V001'],
            [
                'name' => 'Global Suppliers Ltd',
                'company_name' => 'Global Suppliers Ltd',
                'email' => 'contact@globalsuppliers.com',
                'phone' => '0112345678',
                'address' => '123, Supply Road, Colombo',
                'credit_limit' => 500000.00,
                'terms' => '30 Days Credit'
            ]
        );

        // 3. Create some GRNs (Outstanding Bills) for the vendor
        $bills = [
            [
                'grn_no' => 'GRN-00001',
                'reference_no' => 'REF-101',
                'date' => Carbon::now()->subDays(45)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'total_amount' => 15000.00,
            ],
            [
                'grn_no' => 'GRN-00002',
                'reference_no' => 'REF-102',
                'date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'due_date' => Carbon::now()->format('Y-m-d'),
                'total_amount' => 25500.50,
            ],
            [
                'grn_no' => 'GRN-00003',
                'reference_no' => 'REF-103',
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'total_amount' => 50000.00,
            ],
        ];

        foreach ($bills as $billData) {
            Grn::firstOrCreate(
                ['grn_no' => $billData['grn_no']],
                array_merge($billData, [
                    'vendor_id' => $vendor->id,
                    'location_id' => $location->id,
                    'status' => 'Pending',
                    'address' => $vendor->address,
                ])
            );
        }

        $this->command->info('Test data for Supplier Bills added!');

        // 4. Create a Test Customer
        $customer = \App\Models\Customer::firstOrCreate(
            ['code' => 'C001'],
            [
                'name' => 'John Doe Retailers',
                'company_name' => 'John Doe Retailers',
                'email' => 'john@example.com',
                'phone' => '0771234567',
                'address' => '45, Market Street, Kandy',
                'credit_limit' => 250000.00
            ]
        );

        // 5. Create some Invoices (Outstanding Collections) for the customer
        $invoices = [
            [
                'invoice_no' => 'INV-00001',
                'date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'total_amount' => 12500.00,
                'status' => 'Pending'
            ],
            [
                'invoice_no' => 'INV-00002',
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'total_amount' => 45000.00,
                'status' => 'Pending'
            ],
        ];

        foreach ($invoices as $invData) {
            \App\Models\Invoice::firstOrCreate(
                ['invoice_no' => $invData['invoice_no']],
                array_merge($invData, [
                    'customer_id' => $customer->id,
                    'location_id' => $location->id,
                    'address' => $customer->address,
                ])
            );
        }

        $this->command->info('Test data for Customer Bills added successfully!');
    }
}
