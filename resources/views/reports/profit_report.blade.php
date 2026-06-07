@extends('layouts.admin')

@section('title', 'Profit Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Profit Report</h4>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="float-end">
                    <button type="submit" form="filterForm" class="btn btn-info btn-sm me-1">
                        <i class="ri-search-line me-1"></i>View Report
                    </button>
                    <button type="button" class="btn btn-primary btn-sm me-1" onclick="window.print()">
                        <i class="ri-printer-line me-1"></i>Print
                    </button>
                    <a href="{{ route('reports.profit') }}" class="btn btn-warning btn-sm">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <form id="filterForm" method="GET" action="{{ route('reports.profit') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                   value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                   value="{{ $dateTo ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Sales Representative</label>
                            <select name="rep_id" class="form-select form-select-sm">
                                <option value="">— All Active Reps —</option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->id }}" {{ ($repId ?? '') == $rep->id ? 'selected' : '' }}>
                                        {{ $rep->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-sm w-100">
                                <i class="ri-search-line me-1"></i>Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($summary)
{{-- Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body py-2">
                <p class="text-muted small fw-bold mb-1 text-uppercase">Total Revenue</p>
                <h4 class="fw-bold text-primary mb-0">{{ number_format($summary['total_revenue'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body py-2">
                <p class="text-muted small fw-bold mb-1 text-uppercase">Total Cost</p>
                <h4 class="fw-bold text-danger mb-0">{{ number_format($summary['total_cost'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body py-2">
                <p class="text-muted small fw-bold mb-1 text-uppercase">Gross Profit</p>
                <h4 class="fw-bold {{ $summary['total_profit'] >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                    {{ number_format($summary['total_profit'], 2) }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body py-2">
                <p class="text-muted small fw-bold mb-1 text-uppercase">Avg. Margin</p>
                <h4 class="fw-bold text-info mb-0">{{ number_format($summary['avg_margin'], 1) }}%</h4>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Detail Table --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th class="text-start ps-2">Date</th>
                                <th class="text-start">Invoice No.</th>
                                <th class="text-start">Customer</th>
                                <th class="text-start">Rep</th>
                                <th class="text-start">Product Code</th>
                                <th class="text-start">Product Name</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Sale Rate</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Unit Cost</th>
                                <th class="text-end">Total Cost</th>
                                <th class="text-end">Profit</th>
                                <th class="text-end">Margin %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $row)
                                <tr>
                                    <td class="small ps-2">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                                    <td class="small">{{ $row->invoice_no ?? '—' }}</td>
                                    <td class="small">{{ $row->customer_name ?? '—' }}</td>
                                    <td class="small">{{ $row->rep_name ?? '—' }}</td>
                                    <td class="small"><span class="badge bg-light text-dark border">{{ $row->product_code }}</span></td>
                                    <td class="small">{{ $row->product_name }}</td>
                                    <td class="text-end small">{{ number_format($row->qty, 2) }}</td>
                                    <td class="text-end small">{{ number_format($row->sale_rate, 2) }}</td>
                                    <td class="text-end small fw-semibold">{{ number_format($row->sale_total, 2) }}</td>
                                    <td class="text-end small text-muted">{{ number_format($row->unit_cost, 2) }}</td>
                                    <td class="text-end small text-muted">{{ number_format($row->cost_total, 2) }}</td>
                                    <td class="text-end small fw-bold {{ $row->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($row->profit, 2) }}
                                    </td>
                                    <td class="text-end small">
                                        <span class="badge {{ $row->margin_percent >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
                                            {{ number_format($row->margin_percent, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4 text-muted">
                                        @if(!$dateFrom && !$dateTo && !$repId)
                                            Select a date range or rep above and click <strong>View Report</strong> to load data.
                                        @else
                                            No transactions found for the selected filters.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($summary)
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="8" class="text-end pe-2 small">Totals:</td>
                                <td class="text-end small">{{ number_format($summary['total_revenue'], 2) }}</td>
                                <td></td>
                                <td class="text-end small">{{ number_format($summary['total_cost'], 2) }}</td>
                                <td class="text-end small {{ $summary['total_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($summary['total_profit'], 2) }}
                                </td>
                                <td class="text-end small text-info">{{ number_format($summary['avg_margin'], 1) }}%</td>
                            </tr>
                        </tfoot>
                        @endif
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
        .page-content { padding: 0 !important; margin: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table-responsive { overflow: visible !important; }
        .table thead th {
            background-color: #405189 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
        }
        .row.g-3 { display: none !important; }
    }
</style>
@endsection
