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

        // Get all transaction data in bulk for performance
        $grnIn = DB::table('grn_items')->select('product_id', 'location', DB::raw('SUM(qty) as total'))->groupBy('product_id', 'location')->get()->groupBy('product_id');
        $invoiceOut = DB::table('invoice_items')->select('product_id', 'location', DB::raw('SUM(qty) as total'))->groupBy('product_id', 'location')->get()->groupBy('product_id');
        $grnReturnOut = DB::table('grn_return_items')->select('product_id', 'location', DB::raw('SUM(qty) as total'))->groupBy('product_id', 'location')->get()->groupBy('product_id');
        $salesReturnIn = DB::table('sales_return_items')->select('product_id', 'location', DB::raw('SUM(qty) as total'))->groupBy('product_id', 'location')->get()->groupBy('product_id');
        $invoiceReturnIn = DB::table('invoice_returns')->select('product_id', DB::raw('SUM(qty) as total'))->groupBy('product_id')->get()->pluck('total', 'product_id');

        $transferIn = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('inventory_transfers.status', 'Approved')
            ->select('product_id', 'inventory_transfers.site_to as location', DB::raw('SUM(qty) as total'))
            ->groupBy('product_id', 'inventory_transfers.site_to')
            ->get()->groupBy('product_id');

        $transferOut = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('inventory_transfers.status', 'Approved')
            ->select('product_id', 'inventory_transfers.site_from as location', DB::raw('SUM(qty) as total'))
            ->groupBy('product_id', 'inventory_transfers.site_from')
            ->get()->groupBy('product_id');

        $stockData = [];
        foreach ($products as $product) {
            $itemData = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'locations' => []
            ];

            $totalStock = 0;
            foreach ($locations as $location) {
                $locName = $location->name;
                
                $in = ($grnIn->get($product->id)?->where('location', $locName)->first()?->total ?? 0) +
                     ($salesReturnIn->get($product->id)?->where('location', $locName)->first()?->total ?? 0) +
                     ($transferIn->get($product->id)?->where('location', $locName)->first()?->total ?? 0);
                
                // Invoice returns are currently not site-specific in the DB? 
                // Let's assume they go to 'Main Stock' or just add to the first location for now if no site info.
                if ($locName == 'Main Stock' || $locName == 'Main') {
                    $in += $invoiceReturnIn->get($product->id) ?? 0;
                }

                $out = ($invoiceOut->get($product->id)?->where('location', $locName)->first()?->total ?? 0) +
                      ($grnReturnOut->get($product->id)?->where('location', $locName)->first()?->total ?? 0) +
                      ($transferOut->get($product->id)?->where('location', $locName)->first()?->total ?? 0);

                $qty = $in - $out;
                $itemData['locations'][$locName] = $qty;
                $totalStock += $qty;
            }

            if ($request->has('no_zero') && $totalStock <= 0) {
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

        $location = $request->location;
        
        // Bulk fetch stock data
        $grnIn = DB::table('grn_items')->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $grnIn->where('location', $location);
        $grnIn = $grnIn->groupBy('product_id')->get()->pluck('total', 'product_id');

        $invoiceOut = DB::table('invoice_items')->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $invoiceOut->where('location', $location);
        $invoiceOut = $invoiceOut->groupBy('product_id')->get()->pluck('total', 'product_id');

        $grnReturnOut = DB::table('grn_return_items')->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $grnReturnOut->where('location', $location);
        $grnReturnOut = $grnReturnOut->groupBy('product_id')->get()->pluck('total', 'product_id');

        $salesReturnIn = DB::table('sales_return_items')->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $salesReturnIn->where('location', $location);
        $salesReturnIn = $salesReturnIn->groupBy('product_id')->get()->pluck('total', 'product_id');

        $invoiceReturnIn = DB::table('invoice_returns')->select('product_id', DB::raw('SUM(qty) as total'));
        // No location in invoice_returns?
        $invoiceReturnIn = $invoiceReturnIn->groupBy('product_id')->get()->pluck('total', 'product_id');

        $transferIn = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('inventory_transfers.status', 'Approved')
            ->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $transferIn->where('inventory_transfers.site_to', $location);
        $transferIn = $transferIn->groupBy('product_id')->get()->pluck('total', 'product_id');

        $transferOut = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfer_items.inventory_transfer_id', '=', 'inventory_transfers.id')
            ->where('inventory_transfers.status', 'Approved')
            ->select('product_id', DB::raw('SUM(qty) as total'));
        if ($location) $transferOut->where('inventory_transfers.site_from', $location);
        $transferOut = $transferOut->groupBy('product_id')->get()->pluck('total', 'product_id');

        $reportData = [];
        foreach ($products as $product) {
            $in = ($grnIn->get($product->id) ?? 0) + 
                  ($salesReturnIn->get($product->id) ?? 0) + 
                  ($transferIn->get($product->id) ?? 0);
            
            if (!$location || $location == 'Main Stock' || $location == 'Main') {
                $in += $invoiceReturnIn->get($product->id) ?? 0;
            }

            $out = ($invoiceOut->get($product->id) ?? 0) + 
                   ($grnReturnOut->get($product->id) ?? 0) + 
                   ($transferOut->get($product->id) ?? 0);

            $onHand = $in - $out;
            
            if ($request->has('no_zero') && $onHand <= 0) {
                continue;
            }

            $reportData[] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'category' => $product->category,
                'site' => $product->location,
                'class' => $product->model,
                'on_hand' => $onHand,
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

        if ($productId) {
            $selectedProduct = Product::find($productId);
            
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

        return view('reports.stock_valuation_details', compact('products', 'categories', 'locations', 'transactions', 'selectedProduct'));
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
