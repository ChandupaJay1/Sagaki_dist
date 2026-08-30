<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;

class CustomerOutstandingInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find the 'Demo Customer' created previously
        $customer = Customer::where('name', 'Demo Customer')->first();
        
        if (!$customer) {
            $this->command->error("Demo Customer not found. Please run CustomerTransactionTestSeeder first.");
            return;
        }

        // 2. Generate 4 outstanding Sales Invoices for this customer
        $invoices = [
            [
                'customer_id'    => $customer->id,
                'location_id'    => 1,
                'rep_id'         => 1,
                'invoice_no'     => 'INV-OUT-1001',
                'date'           => Carbon::now()->subDays(20)->format('Y-m-d'),
                'total_amount'   => 150000.00,
                'subtotal'       => 150000.00,
                'payment_method' => 'Credit',
                'status'         => 'Pending',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'customer_id'    => $customer->id,
                'location_id'    => 1,
                'rep_id'         => 1,
                'invoice_no'     => 'INV-OUT-1002',
                'date'           => Carbon::now()->subDays(12)->format('Y-m-d'),
                'total_amount'   => 85500.25,
                'subtotal'       => 85500.25,
                'payment_method' => 'Credit',
                'status'         => 'Pending',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'customer_id'    => $customer->id,
                'location_id'    => 1,
                'rep_id'         => 1,
                'invoice_no'     => 'INV-OUT-1003',
                'date'           => Carbon::now()->subDays(7)->format('Y-m-d'),
                'total_amount'   => 45000.00,
                'subtotal'       => 45000.00,
                'payment_method' => 'Credit',
                'status'         => 'Pending',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'customer_id'    => $customer->id,
                'location_id'    => 1,
                'rep_id'         => 1,
                'invoice_no'     => 'INV-OUT-1004',
                'date'           => Carbon::now()->subDays(1)->format('Y-m-d'),
                'total_amount'   => 210000.75,
                'subtotal'       => 210000.75,
                'payment_method' => 'Credit',
                'status'         => 'Pending',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::create($invoiceData);
        }

        $this->command->info("Successfully created 4 outstanding invoices for Demo Customer!");
    }
}
