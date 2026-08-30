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
        $filter = request()->query('filter', 'monthly');
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        if ($filter == 'daily') {
            $customerCount = Customer::whereDate('created_at', Carbon::today())->count();
            $vendorCount = Vendor::whereDate('created_at', Carbon::today())->count();
            $productCount = Product::whereDate('created_at', Carbon::today())->count();
        } elseif ($filter == 'weekly') {
            $customerCount = Customer::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $vendorCount = Vendor::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $productCount = Product::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        } elseif ($filter == 'yearly') {
            $customerCount = Customer::whereYear('created_at', Carbon::now()->year)->count();
            $vendorCount = Vendor::whereYear('created_at', Carbon::now()->year)->count();
            $productCount = Product::whereYear('created_at', Carbon::now()->year)->count();
        } else {
            $customerCount = Customer::whereMonth('created_at', Carbon::now()->month)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->count();
            $vendorCount = Vendor::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();
            $productCount = Product::whereMonth('created_at', Carbon::now()->month)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->count();
        }
        
        // Revenue calculation
        $totalRevenue = Invoice::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                               ->sum('total_amount');
        
        // Prepare data for Revenue Summary Chart
        $isDaily = in_array($filter, ['daily', 'weekly']);
        
        $revenueSelect = $isDaily ? 
            [DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(date, '%a') as label"), DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as sort_key")] :
            [DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(date, '%b') as label"), DB::raw("DATE_FORMAT(date, '%Y-%m') as sort_key")];
            
        $revenueSummary = Invoice::select($revenueSelect)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('sort_key', 'label')
            ->orderBy('sort_key', 'asc')
            ->get();

        // Prepare data for Orders Overview
        $ordersSelect = $isDaily ? 
            [DB::raw('COUNT(*) as count'), DB::raw("DATE_FORMAT(order_date, '%a') as label"), DB::raw("DATE_FORMAT(order_date, '%Y-%m-%d') as sort_key")] :
            [DB::raw('COUNT(*) as count'), DB::raw("DATE_FORMAT(order_date, '%b') as label"), DB::raw("DATE_FORMAT(order_date, '%Y-%m') as sort_key")];
            
        $ordersOverview = SalesOrder::select($ordersSelect)
            ->whereBetween('order_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('sort_key', 'label')
            ->orderBy('sort_key', 'asc')
            ->get();

        // Recent Deliveries
        $recentDeliveries = Invoice::select('date', 'payment_method', 'status', 'total_amount')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->take(5)
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

        // Determine Text and label
        $filterText = 'this month';
        $filterLabel = 'Monthly';
        if ($filter == 'daily') {
            $filterText = 'today';
            $filterLabel = 'Daily';
        } elseif ($filter == 'weekly') {
            $filterText = 'this week';
            $filterLabel = 'Weekly';
        } elseif ($filter == 'yearly') {
            $filterText = 'this year';
            $filterLabel = 'Yearly';
        }

        // Calculate counts based on explicit request logic
        $deliveredOrdersQuery = Invoice::query();
        $newOrdersQuery = SalesOrder::query();

        if ($filter == 'daily') {
            $deliveredOrders = $deliveredOrdersQuery->whereDate('created_at', Carbon::today())->count();
            $newOrders = $newOrdersQuery->whereDate('created_at', Carbon::today())->count();
        } elseif ($filter == 'weekly') {
            $deliveredOrders = $deliveredOrdersQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $newOrders = $newOrdersQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        } elseif ($filter == 'yearly') {
            $deliveredOrders = $deliveredOrdersQuery->whereYear('created_at', Carbon::now()->year)->count();
            $newOrders = $newOrdersQuery->whereYear('created_at', Carbon::now()->year)->count();
        } else {
            $deliveredOrders = $deliveredOrdersQuery->whereMonth('created_at', Carbon::now()->month)->count();
            $newOrders = $newOrdersQuery->whereMonth('created_at', Carbon::now()->month)->count();
        }

        return view('index', compact(
            'customerCount', 
            'vendorCount', 
            'productCount', 
            'totalRevenue',
            'revenueSummary',
            'ordersOverview',
            'recentDeliveries',
            'majorOutlets',
            'fastMovingItems',
            'filter',
            'filterText',
            'filterLabel',
            'deliveredOrders',
            'newOrders'
        ));
    }
}
