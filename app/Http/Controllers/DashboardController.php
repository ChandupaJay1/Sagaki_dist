<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $customerCount = Customer::count();
        $vendorCount = Vendor::count();
        $productCount = Product::count();
        
        // Revenue calculation (sum of total_amount from invoices)
        $totalRevenue = Invoice::sum('total_amount');
        
        // Prepare data for Revenue Summary Chart (Last 6 months)
        $revenueSummary = Invoice::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw("DATE_FORMAT(date, '%b') as month"),
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month_key")
        )
        ->groupBy('month_key', 'month')
        ->orderBy('month_key', 'asc')
        ->get();

        // Prepare data for Orders Overview (Last 7 days)
        $ordersOverview = SalesOrder::select(
            DB::raw('COUNT(*) as count'),
            DB::raw("DATE_FORMAT(order_date, '%a') as day"),
            DB::raw("DATE_FORMAT(order_date, '%Y-%m-%d') as day_key")
        )
        ->where('order_date', '>=', Carbon::now()->subDays(7))
        ->groupBy('day_key', 'day')
        ->orderBy('day_key', 'asc')
        ->get();

        // Top 3 Major Outlets (Using Customers as a proxy for now, or just static as per screenshot)
        $majorOutlets = [
            ['name' => 'Kirindiwela - Main', 'address' => 'No 45, Main St, Kirindiwela', 'rating' => 4.8],
            ['name' => 'Gampaha Hub', 'address' => '122/A, Kandy Rd, Gampaha', 'rating' => 4.6],
            ['name' => 'Nittambuwa Center', 'address' => 'No 8, Highlevel Rd', 'rating' => 4.2],
        ];

        // Fast Moving Items (Mock for now, or use real data from InvoiceItems)
        $fastMovingItems = Product::take(3)->get()->map(function($product) {
            return [
                'name' => $product->name,
                'category' => $product->category ?? 'General',
                'image' => asset('assets/images/food-icon/pic15.png'), // Default placeholder
            ];
        });

        return view('index', compact(
            'customerCount', 
            'vendorCount', 
            'productCount', 
            'totalRevenue',
            'revenueSummary',
            'ordersOverview',
            'majorOutlets',
            'fastMovingItems'
        ));
    }
}
