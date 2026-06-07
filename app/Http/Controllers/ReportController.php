<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Location;
use App\Models\ItemCategory;
use App\Models\User;
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

        // Calculate Good in Transit (Pending Inventory Transfers)
        $goodInTransit = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfers.id', '=', 'inventory_transfer_items.inventory_transfer_id')
            ->where('inventory_transfers.status', 'Pending')
            ->select('inventory_transfer_items.product_id', 'inventory_transfers.site_to', DB::raw('SUM(inventory_transfer_items.qty) as total_qty'))
            ->groupBy('inventory_transfer_items.product_id', 'inventory_transfers.site_to')
            ->get();

        $stockData = [];
        foreach ($products as $product) {
            $itemData = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'locations' => [],
                'good_in_transit' => 0 // Will be calculated per location if needed, or total
            ];

            // Calculate total GIT for this product across all locations
            $itemData['good_in_transit'] = (float)$goodInTransit->where('product_id', $product->id)->sum('total_qty');

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

        // Calculate Good in Transit (Pending Inventory Transfers)
        $gitQuery = DB::table('inventory_transfer_items')
            ->join('inventory_transfers', 'inventory_transfers.id', '=', 'inventory_transfer_items.inventory_transfer_id')
            ->where('inventory_transfers.status', 'Pending');
        
        if ($locationName) {
            $gitQuery->where('inventory_transfers.site_to', $locationName);
        }

        $goodInTransit = $gitQuery->select('inventory_transfer_items.product_id', DB::raw('SUM(inventory_transfer_items.qty) as total_qty'))
            ->groupBy('inventory_transfer_items.product_id')
            ->pluck('total_qty', 'inventory_transfer_items.product_id');

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
            
            // Calculate Good in Transit (Pending Inventory Transfers)
            $goodInTransit = DB::table('inventory_transfer_items')
                ->join('inventory_transfers', 'inventory_transfers.id', '=', 'inventory_transfer_items.inventory_transfer_id')
                ->where('inventory_transfers.status', 'Pending')
                ->where('inventory_transfer_items.product_id', $productId)
                ->sum('inventory_transfer_items.qty');

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

    // -------------------------------------------------------------------------
    // PROFIT REPORT  (Task 1)
    // Logic: Invoice line revenue  vs  product cost (from products.cost)
    // Filterable by: date range, sales rep
    // -------------------------------------------------------------------------
    public function profitReport(Request $request)
    {
        $reps = User::where('is_active', true)
            ->where('role', 'ref')
            ->orderBy('name')
            ->get();

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $repId    = $request->input('rep_id');

        // Only run the query when at least one filter is provided
        $reportData   = collect();
        $summary      = null;

        if ($dateFrom || $dateTo || $repId) {
            // Pull invoice items joined to invoices and products
            $rows = DB::table('invoice_items')
                ->join('invoices',  'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products',  'invoice_items.product_id', '=', 'products.id')
                ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                ->leftJoin('users as reps', 'invoices.rep_id', '=', 'reps.id')
                ->when($dateFrom, fn ($q) => $q->whereDate('invoices.date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('invoices.date', '<=', $dateTo))
                ->when($repId,    fn ($q) => $q->where('invoices.rep_id', $repId))
                ->select([
                    'invoices.invoice_no',
                    'invoices.date',
                    'customers.company_name as customer_name',
                    'reps.name as rep_name',
                    'products.code as product_code',
                    'products.name as product_name',
                    'invoice_items.qty',
                    'invoice_items.rate as sale_rate',
                    'invoice_items.total as sale_total',
                    // Cost per unit comes from the product master (GRN-based average cost)
                    'products.cost as unit_cost',
                ])
                ->orderBy('invoices.date')
                ->orderBy('invoices.invoice_no')
                ->get();

            // Annotate each row with computed profit fields
            $reportData = $rows->map(function ($row) {
                $costTotal   = (float) $row->unit_cost   * (float) $row->qty;
                $saleTotal   = (float) $row->sale_total;
                $profit      = $saleTotal - $costTotal;
                $margin      = $saleTotal > 0 ? ($profit / $saleTotal) * 100 : 0;

                return (object) array_merge((array) $row, [
                    'cost_total'     => $costTotal,
                    'profit'         => $profit,
                    'margin_percent' => $margin,
                ]);
            });

            $summary = [
                'total_revenue' => $reportData->sum('sale_total'),
                'total_cost'    => $reportData->sum('cost_total'),
                'total_profit'  => $reportData->sum('profit'),
                'avg_margin'    => $reportData->avg('margin_percent') ?? 0,
            ];
        }

        return view('reports.profit_report', compact(
            'reps', 'reportData', 'summary', 'dateFrom', 'dateTo', 'repId'
        ));
    }

    // -------------------------------------------------------------------------
    // HISTORICAL SALES & COLLECTIONS REPORT  (Tasks 2 & 8)
    // Breakdown by payment method: Cash | Cheque | Bank Transfer (Online)
    // Filterable by: date range, sales rep
    // -------------------------------------------------------------------------
    public function salesCollectionReport(Request $request)
    {
        $reps = User::where('is_active', true)
            ->where('role', 'ref')
            ->orderBy('name')
            ->get();

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $repId    = $request->input('rep_id');

        $salesData      = collect();
        $collectionData = collect();
        $salesSummary   = null;
        $collSummary    = null;

        if ($dateFrom || $dateTo || $repId) {

            // ── SALES (Invoices) ──────────────────────────────────────────
            $salesData = DB::table('invoices')
                ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                ->leftJoin('users as reps', 'invoices.rep_id', '=', 'reps.id')
                ->when($dateFrom, fn ($q) => $q->whereDate('invoices.date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('invoices.date', '<=', $dateTo))
                ->when($repId,    fn ($q) => $q->where('invoices.rep_id', $repId))
                ->select([
                    'invoices.invoice_no',
                    'invoices.date',
                    'customers.company_name as customer_name',
                    'reps.name as rep_name',
                    'invoices.subtotal',
                    'invoices.header_discount_amount',
                    'invoices.tax_amount',
                    'invoices.total_amount',
                    'invoices.status',
                ])
                ->orderBy('invoices.date')
                ->orderBy('invoices.invoice_no')
                ->get();

            $salesSummary = [
                'total'    => $salesData->sum('total_amount'),
                'subtotal' => $salesData->sum('subtotal'),
                'discount' => $salesData->sum('header_discount_amount'),
                'tax'      => $salesData->sum('tax_amount'),
                'count'    => $salesData->count(),
            ];

            // ── COLLECTIONS (PayBills, Customer type) ────────────────────
            // Joined to invoices via pay_bill_items to capture the rep on the invoice
            $collQuery = DB::table('pay_bills')
                ->leftJoin('customers', 'pay_bills.customer_id', '=', 'customers.id')
                ->where('pay_bills.type', 'Customer')
                ->when($dateFrom, fn ($q) => $q->whereDate('pay_bills.date', '>=', $dateFrom))
                ->when($dateTo,   fn ($q) => $q->whereDate('pay_bills.date', '<=', $dateTo));

            // Rep filter: join through pay_bill_items → invoices
            if ($repId) {
                $collQuery->whereExists(function ($sub) use ($repId) {
                    $sub->select(DB::raw(1))
                        ->from('pay_bill_items')
                        ->join('invoices', 'pay_bill_items.invoice_id', '=', 'invoices.id')
                        ->whereColumn('pay_bill_items.pay_bill_id', 'pay_bills.id')
                        ->where('invoices.rep_id', $repId);
                });
            }

            $collectionData = $collQuery->select([
                    'pay_bills.voucher_no',
                    'pay_bills.date',
                    'customers.company_name as customer_name',
                    'pay_bills.payment_method',
                    'pay_bills.total_amount',
                    'pay_bills.cheque_no',
                    'pay_bills.memo',
                ])
                ->orderBy('pay_bills.date')
                ->orderBy('pay_bills.voucher_no')
                ->get();

            // Payment method breakdown totals
            $collSummary = [
                'total'         => $collectionData->sum('total_amount'),
                'cash'          => $collectionData->where('payment_method', 'Cash')->sum('total_amount'),
                'cheque'        => $collectionData->where('payment_method', 'Cheque')->sum('total_amount'),
                'bank_transfer' => $collectionData->where('payment_method', 'Bank Transfer')->sum('total_amount'),
                'count'         => $collectionData->count(),
            ];
        }

        return view('reports.sales_collection_report', compact(
            'reps', 'salesData', 'salesSummary',
            'collectionData', 'collSummary',
            'dateFrom', 'dateTo', 'repId'
        ));
    }
}
