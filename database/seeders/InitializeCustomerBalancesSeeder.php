<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PayBillItem;
use App\Models\SalesReturn;

class InitializeCustomerBalancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $totalInvoices = Invoice::where('customer_id', $customer->id)->sum('total_amount');
            
            $totalPaid = PayBillItem::whereHas('payBill', function($q) use ($customer) {
                $q->where('customer_id', $customer->id);
            })->sum('amount_to_pay');
            
            $totalReturns = SalesReturn::where('customer_id', $customer->id)->sum('total_amount');
            
            $balance = ($totalInvoices - $totalPaid) - $totalReturns;
            
            $customer->balance = $balance;
            $customer->save();
            
            $this->command->info("Initialized balance for customer: {$customer->name} -> {$balance}");
        }
    }
}
