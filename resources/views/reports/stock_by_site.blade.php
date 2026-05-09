@extends('layouts.admin')

@section('title', 'Stock by Site Summary')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock by Site Summary</h4>
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
                    <a href="{{ route('reports.stock-by-site') }}" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</a>
                </div>
            </div>
            <div class="card-body p-3">
                <form id="filterForm" method="GET" action="{{ route('reports.stock-by-site') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Class</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                                <th style="width: 100px;">Item</th>
                                <th>Item Description</th>
                                @foreach($locations as $location)
                                    <th>{{ $location->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockData as $data)
                                <tr>
                                    <td class="fw-bold text-center small">{{ $data['code'] }}</td>
                                    <td class="small">{{ $data['name'] }}</td>
                                    @foreach($locations as $location)
                                        <td class="text-center small">{{ number_format($data['locations'][$location->name], 2) }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($locations) + 2 }}" class="text-center">No data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
