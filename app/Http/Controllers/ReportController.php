<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Location;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stockBySiteSummary(Request $request)
    {
        $locations = Location::where('is_active', 1)->orderBy('name')->get();
        $categories = ItemCategory::orderBy('name')->get();
        
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->get();

        // Get bulk summary data for performance
        $summaries = DB::table('inventory_summaries')
            ->select('product_id', 'location_id', 'qty')
            ->get()
            ->groupBy('product_id');

        // Calculate Good in Transit (Pending GRNs)
        $goodInTransit = DB::table('grn_items')
            ->join('grns', 'grn_items.grn_id', '=', 'grns.id')
            ->where('grns.status', 'Pending')
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->pluck('total_qty', 'product_id');

        $stockData = [];
        foreach ($products as $product) {
            $itemData = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'locations' => [],
                'good_in_transit' => (float)($goodInTransit->get($product->id) ?? 0)
            ];

            $mainStockTotal = 0;
            $allowedMainStockLocations = ['Main', 'Main Warehouse', 'Showroom', 'Testing', 'Transit'];

            foreach ($locations as $location) {
                $qty = $summaries->get($product->id)?->where('location_id', $location->id)->first()?->qty ?? 0;
                $itemData['locations'][$location->name] = (float)$qty;
                
                if (in_array(trim($location->name), $allowedMainStockLocations)) {
                    $mainStockTotal += (float)$qty;
                }
            }
            
            $itemData['total_stock'] = $mainStockTotal;

            if ($request->has('no_zero') && $mainStockTotal <= 0) {
                continue;
            }

            $stockData[] = $itemData;
        }

        return view('reports.stock_by_site', compact('locations', 'categories', 'stockData'));
    }

    public function stockValuationSummary(Request $request)
    {
        $categories = ItemCategory::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->orderBy('name')->get();

        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->get();

        $locationName = $request->location;
        $locationId = $locationName ? Location::where('name', $locationName)->first()?->id : null;
        
        // Bulk fetch summary data
        $summaryQuery = DB::table('inventory_summaries');
        if ($locationId) {
            $summaryQuery->where('location_id', $locationId);
        }
        $summaries = $summaryQuery->select('product_id', DB::raw('SUM(qty) as total'))->groupBy('product_id')->get()->pluck('total', 'product_id');

        // Calculate Good in Transit (Pending GRNs)
        $gitQuery = DB::table('grn_items')
            ->join('grns', 'grn_items.grn_id', '=', 'grns.id')
            ->where('grns.status', 'Pending');
        
        if ($locationId) {
            $gitQuery->where('grns.location_id', $locationId);
        }

        $goodInTransit = $gitQuery->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->pluck('total_qty', 'product_id');

        $reportData = [];
        foreach ($products as $product) {
            $onHand = $summaries->get($product->id) ?? 0;
            $git = $goodInTransit->get($product->id) ?? 0;
            
            if ($request->has('no_zero') && ($onHand <= 0 && $git <= 0)) {
                continue;
            }

            $reportData[] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'category' => $product->category,
                'site' => $locationName ?: 'All',
                'class' => $product->model,
                'on_hand' => (float)$onHand,
                'git' => (float)$git,
                'avg_cost' => $product->cost,
                'asset_value' => $onHand * $product->cost
            ];
        }

        return view('reports.stock_valuation_summary', compact('categories', 'locations', 'reportData'));
    }

    public function stockValuationDetails(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $categories = ItemCategory::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->orderBy('name')->get();

        $productId = $request->product_id;
        $transactions = [];
        $selectedProduct = null;
        $goodInTransit = 0;

        if ($productId) {
            $selectedProduct = Product::find($productId);
            
            // Calculate Good in Transit (Pending GRNs)
            $goodInTransit = DB::table('grn_items')
                ->join('grns', 'grn_items.grn_id', '=', 'grns.id')
                ->where('grns.status', 'Pending')
                ->where('product_id', $productId)
                ->sum('qty');

            // Fetch all transactions for this product
            $grns = DB::table('grn_items')
                ->join('grns', 'grn_items.grn_id', '=', 'grns.id')
                ->where('product_id', $productId)
                ->select('grns.date', 'grns.grn_no as ref_no', 'grn_items.qty', 'grn_items.rate', DB::raw("'GRN' as type"), DB::raw("'' as party"));

            $invoices = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                ->where('product_id', $productId)
                ->select('invoices.date', 'invoices.invoice_no as ref_no', DB::raw("-invoice_items.qty as qty"), 'invoice_items.rate', DB::raw("'INVOICE' as type"), 'customers.name as party');

            $salesReturns = DB::table('sales_return_items')
                ->join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')
                ->leftJoin('customers', 'sales_returns.customer_id', '=', 'customers.id')
                ->where('product_id', $productId)
                ->select('sales_returns.date', 'sales_returns.return_no as ref_no', 'sales_return_items.qty', 'sales_return_items.rate', DB::raw("'SALES RETURN' as type"), 'customers.name as party');

            $grnReturns = DB::table('grn_return_items')
                ->join('grn_returns', 'grn_return_items.grn_return_id', '=', 'grn_returns.id')
                ->where('product_id', $productId)
                ->select('grn_returns.date', 'grn_returns.return_no as ref_no', DB::raw("-grn_return_items.qty as qty"), 'grn_return_items.rate', DB::raw("'GRN RETURN' as type"), DB::raw("'' as party"));

            // Union all transactions
            $transactions = $grns->union($invoices)->union($salesReturns)->union($grnReturns)
                ->orderBy('date')
                ->get();

            // Calculate running balance
            $balance = 0;
            foreach ($transactions as $tx) {
                $balance += $tx->qty;
                $tx->balance = $balance;
            }
        }

        return view('reports.stock_valuation_details', compact('products', 'categories', 'locations', 'transactions', 'selectedProduct', 'goodInTransit'));
    }

    private function calculateStock($productId, $location = null)
    {
        $grnIn = DB::table('grn_items')->where('product_id', $productId);
        if ($location) $grnIn->where('location', $location);
        $grnIn = $grnIn->sum('qty');

        $invoiceOut = DB::table('invoice_items')->where('product_id', $productId);
        if ($location) $invoiceOut->where('location', $location);
        $invoiceOut = $invoiceOut->sum('qty');

        $grnReturnOut = DB::table('grn_return_items')->where('product_id', $productId);
        if ($location) $grnReturnOut->where('location', $location);
        $grnReturnOut = $grnReturnOut->sum('qty');

        $salesReturnIn = DB::table('sales_return_items')->where('product_id', $productId);
        if ($location) $salesReturnIn->where('location', $location);
        $salesReturnIn = $salesReturnIn->sum('qty');

        $invoiceReturnIn = DB::table('invoice_returns')->where('product_id', $productId)->sum('qty');

        // Transfers
        $transferIn = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('product_id', $productId)
            ->where('inventory_transfers.status', 'Approved');
        if ($location) $transferIn->where('inventory_transfers.site_to', $location);
        $transferIn = $transferIn->sum('qty');

        $transferOut = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('product_id', $productId)
            ->where('inventory_transfers.status', 'Approved');
        if ($location) $transferOut->where('inventory_transfers.site_from', $location);
        $transferOut = $transferOut->sum('qty');

        return ($grnIn + $salesReturnIn + $invoiceReturnIn + $transferIn) - ($invoiceOut + $grnReturnOut + $transferOut);
    }
}
