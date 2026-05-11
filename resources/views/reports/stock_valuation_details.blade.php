@extends('layouts.admin')

@section('title', 'Stock Valuation Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Valuation Details</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="float-end">
                    <button type="submit" form="filterForm" class="btn btn-info btn-sm me-1"><i class="ri-search-line me-1"></i>View Report</button>
                    <button type="button" class="btn btn-primary btn-sm me-1" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
                    <a href="{{ route('reports.stock-valuation-details') }}" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</a>
                </div>
            </div>
            <div class="card-body p-3">
                <form id="filterForm" method="GET" action="{{ route('reports.stock-valuation-details') }}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Item</label>
                            <select name="product_id" class="form-select form-select-sm" required>
                                <option value="">Select Item</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        [{{ $product->code }}] {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Item Category</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Item Site</label>
                            <select name="location" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ request('location') == $loc->name ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($selectedProduct)
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">{{ $selectedProduct->code }} - {{ $selectedProduct->name }}</h6>
                <div class="d-flex gap-3">
                    <span class="badge bg-soft-info text-info fs-12 fw-bold border border-info px-3 py-2">
                        <i class="ri-truck-line me-1"></i> Good in Transit: {{ number_format($goodInTransit, 2) }}
                    </span>
                    <span class="badge bg-soft-success text-success fs-12 fw-bold border border-success px-3 py-2">
                        <i class="ri-checkbox-circle-line me-1"></i> Total Available: {{ number_format(($transactions->last()->balance ?? 0) + $goodInTransit, 2) }}
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Reference</th>
                                <th>Qty Change</th>
                                <th>Amount</th>
                                <th>Running Qty</th>
                                <th>Rate</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="small text-center fw-bold">{{ $tx->type }}</td>
                                    <td class="small text-center">{{ $tx->date }}</td>
                                    <td class="small">{{ $tx->party ?: 'Inventory Transaction' }}</td>
                                    <td class="small text-center">{{ $tx->ref_no }}</td>
                                    <td class="text-center small {{ $tx->qty < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($tx->qty, 2) }}
                                    </td>
                                    <td class="text-end small">{{ number_format($tx->qty * $tx->rate, 2) }}</td>
                                    <td class="text-center small fw-bold">{{ number_format($tx->balance, 2) }}</td>
                                    <td class="text-end small">{{ number_format($tx->rate, 2) }}</td>
                                    <td class="text-end small fw-bold">{{ number_format($tx->balance * $tx->rate, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No transactions found for this item</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($transactions) > 0)
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="6" class="text-end">Total</td>
                                <td class="text-center">{{ number_format($transactions->last()->balance, 2) }}</td>
                                <td></td>
                                <td class="text-end">{{ number_format($transactions->last()->balance * $transactions->last()->rate, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="alert alert-info text-center">
            Please select an item to view its valuation details.
        </div>
    </div>
</div>
@endif

<style>
    @media print {
        .topbar, .left-side-menu, .page-title-box, .card-header, #filterForm {
            display: none !important;
        }
        .page-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        .table thead th {
            background-color: #405189 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection
