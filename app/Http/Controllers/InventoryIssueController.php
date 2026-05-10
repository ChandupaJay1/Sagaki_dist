<?php

namespace App\Http\Controllers;

use App\Models\InventoryIssue;
use App\Models\Product;
use App\Models\Location;
use App\Models\Account;
use App\Models\Unit;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryIssueController extends Controller
{
    public function index()
    {
        $issues = InventoryIssue::with(['location', 'creator'])->latest()->paginate(10);
        return view('inventory_issues.index', compact('issues'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $locations = Location::where('is_active', 1)->orderBy('name')->get();
        $accounts = Account::where('is_active', 1)->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        // Generate next Issue Number
        $lastIssue = InventoryIssue::orderBy('id', 'desc')->first();
        $nextId = $lastIssue ? $lastIssue->id + 1 : 1;
        $issueNo = 'ISN-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return view('inventory_issues.create', compact('products', 'locations', 'accounts', 'units', 'issueNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'account_id' => 'nullable|exists:accounts,id',
            'date' => 'required|date',
            'memo' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Generate Issue Number again to be safe
            $lastIssue = InventoryIssue::orderBy('id', 'desc')->first();
            $nextId = $lastIssue ? $lastIssue->id + 1 : 1;
            $issueNo = 'ISN-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $issue = InventoryIssue::create([
                'issue_no' => $issueNo,
                'location_id' => $validated['location_id'],
                'account_id' => $validated['account_id'],
                'date' => $validated['date'],
                'memo' => $validated['memo'],
                'status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $item) {
                $issue->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);
            }

            return redirect()->route('inventory-issues.index')->with('success', 'Issue Note created successfully. Number: ' . $issueNo);
        });
    }

    public function approve($id)
    {
        return DB::transaction(function () use ($id) {
            $issue = InventoryIssue::with('items.product')->findOrFail($id);

            if ($issue->status !== 'Pending') {
                return redirect()->back()->with('error', 'Only Pending issue notes can be approved.');
            }

            foreach ($issue->items as $item) {
                // Deduct stock
                InventoryService::updateStock(
                    $item->product_id,
                    $issue->location_id,
                    -$item->qty,
                    'Out',
                    'Issue Note',
                    $issue->id,
                    "Issued via Issue Note: " . $issue->issue_no
                );
            }

            $issue->status = 'Approved';
            $issue->save();

            return redirect()->route('inventory-issues.index')->with('success', 'Issue Note approved successfully.');
        });
    }

    public function show($id)
    {
        $issue = InventoryIssue::with(['location', 'account', 'creator', 'items.product'])->findOrFail($id);
        return view('inventory_issues.show', compact('issue'));
    }

    public function destroy($id)
    {
        $issue = InventoryIssue::findOrFail($id);

        if ($issue->status !== 'Pending') {
            return redirect()->back()->with('error', 'Only Pending issue notes can be deleted.');
        }

        $issue->items()->delete();
        $issue->delete();

        return redirect()->route('inventory-issues.index')->with('success', 'Issue Note deleted successfully.');
    }
}
