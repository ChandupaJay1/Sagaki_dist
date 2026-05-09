@extends('layouts.admin')

@section('title', 'Stock Valuation Summary')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Valuation Summary</h4>
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
                    <a href="{{ route('reports.stock-valuation-summary') }}" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</a>
                </div>
            </div>
            <div class="card-body p-3">
                <form id="filterForm" method="GET" action="{{ route('reports.stock-valuation-summary') }}">
                    <div class="row g-2">
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
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Item name or code" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="no_zero" id="noZero" value="1" {{ request('no_zero') ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold" for="noZero">
                                    No Zero
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Item Category</th>
                                <th>Site</th>
                                <th>Class</th>
                                <th>On Hand</th>
                                <th>AVG. Cost</th>
                                <th>Asset Value</th>
                                <th style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAssetValue = 0;
                            @endphp
                            @forelse($reportData as $data)
                                @php
                                    $totalAssetValue += $data['asset_value'];
                                @endphp
                                <tr>
                                    <td class="fw-bold small">{{ $data['code'] }}</td>
                                    <td class="small">{{ $data['name'] }}</td>
                                    <td class="small">{{ $data['category'] }}</td>
                                    <td class="small text-center">{{ $data['site'] }}</td>
                                    <td class="small text-center">{{ $data['class'] }}</td>
                                    <td class="text-center small">{{ number_format($data['on_hand'], 2) }}</td>
                                    <td class="text-end small">{{ number_format($data['avg_cost'], 2) }}</td>
                                    <td class="text-end small">{{ number_format($data['asset_value'], 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('reports.stock-valuation-details', ['product_id' => $data['id']]) }}" class="btn btn-soft-primary btn-sm btn-icon"><i class="ri-eye-line"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="7" class="text-end">Total Asset Value</td>
                                <td class="text-end">{{ number_format($totalAssetValue, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .topbar, .left-side-menu, .page-title-box, .card-header, #filterForm, .btn-icon {
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
