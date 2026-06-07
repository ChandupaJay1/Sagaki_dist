@extends('layouts.admin')

@section('title', 'Sales & Collections Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Sales & Collections Report</h4>
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
                    <a href="{{ route('reports.sales-collection') }}" class="btn btn-warning btn-sm">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <form id="filterForm" method="GET" action="{{ route('reports.sales-collection') }}">
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

@if($collSummary)
{{-- Collections Payment Method Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-2">
            <div class="card-body py-2">
                <div class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                    <i class="ri-money-dollar-circle-line text-success fs-20"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Cash</p>
                <h5 class="fw-bold text-success mb-0">{{ number_format($collSummary['cash'], 2) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-2">
            <div class="card-body py-2">
                <div class="avatar-sm bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                    <i class="ri-bank-card-line text-warning fs-20"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Cheques</p>
                <h5 class="fw-bold text-warning mb-0">{{ number_format($collSummary['cheque'], 2) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-2">
            <div class="card-body py-2">
                <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                    <i class="ri-global-line text-info fs-20"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Online / Bank Transfer</p>
                <h5 class="fw-bold text-info mb-0">{{ number_format($collSummary['bank_transfer'], 2) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-2">
            <div class="card-body py-2">
                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                    <i class="ri-wallet-3-line text-primary fs-20"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Total Collected</p>
                <h5 class="fw-bold text-primary mb-0">{{ number_format($collSummary['total'], 2) }}</h5>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ SALES TABLE ══════════════════════════════════════════════════════════ --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-soft-primary py-2 d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0 fw-bold">
                    <i class="ri-file-list-3-line me-1 text-primary"></i>Sales (Invoices)
                    @if($salesSummary)
                        <span class="badge bg-primary-subtle text-primary ms-2">{{ $salesSummary['count'] }} records</span>
                    @endif
                </h6>
                @if($salesSummary)
                    <span class="fw-bold text-primary small">Total: {{ number_format($salesSummary['total'], 2) }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th class="text-start ps-2">Date</th>
                                <th class="text-start">Invoice No.</th>
                                <th class="text-start">Customer</th>
                                <th class="text-start">Rep</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Tax</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesData as $row)
                                <tr>
                                    <td class="small ps-2">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                                    <td class="small fw-semibold">{{ $row->invoice_no ?? '—' }}</td>
                                    <td class="small">{{ $row->customer_name ?? '—' }}</td>
                                    <td class="small">{{ $row->rep_name ?? '—' }}</td>
                                    <td class="text-end small">{{ number_format($row->subtotal ?? 0, 2) }}</td>
                                    <td class="text-end small text-danger">{{ number_format($row->header_discount_amount ?? 0, 2) }}</td>
                                    <td class="text-end small">{{ number_format($row->tax_amount ?? 0, 2) }}</td>
                                    <td class="text-end small fw-bold">{{ number_format($row->total_amount ?? 0, 2) }}</td>
                                    <td class="text-center small">
                                        @if(strtolower($row->status ?? '') === 'paid')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Paid</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $row->status ?? 'Pending' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        @if(!$dateFrom && !$dateTo && !$repId)
                                            Select a date range or rep above and click <strong>View Report</strong> to load data.
                                        @else
                                            No invoices found for the selected filters.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($salesSummary && $salesData->count() > 0)
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end pe-2 small">Totals:</td>
                                <td class="text-end small">{{ number_format($salesSummary['subtotal'], 2) }}</td>
                                <td class="text-end small text-danger">{{ number_format($salesSummary['discount'], 2) }}</td>
                                <td class="text-end small">{{ number_format($salesSummary['tax'], 2) }}</td>
                                <td class="text-end small text-primary">{{ number_format($salesSummary['total'], 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ COLLECTIONS TABLE ════════════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-soft-success py-2 d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0 fw-bold">
                    <i class="ri-funds-line me-1 text-success"></i>Collections (Payments Received)
                    @if($collSummary)
                        <span class="badge bg-success-subtle text-success ms-2">{{ $collSummary['count'] }} records</span>
                    @endif
                </h6>
                @if($collSummary)
                    <span class="fw-bold text-success small">Total: {{ number_format($collSummary['total'], 2) }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-success text-white text-center">
                            <tr>
                                <th class="text-start ps-2">Date</th>
                                <th class="text-start">Voucher No.</th>
                                <th class="text-start">Customer</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-end">Cash</th>
                                <th class="text-end">Cheque</th>
                                <th class="text-end">Online / Bank Transfer</th>
                                <th class="text-end fw-bold">Amount</th>
                                <th class="text-start">Cheque No. / Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($collectionData as $row)
                                <tr>
                                    <td class="small ps-2">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                                    <td class="small fw-semibold">{{ $row->voucher_no ?? '—' }}</td>
                                    <td class="small">{{ $row->customer_name ?? '—' }}</td>
                                    <td class="text-center small">
                                        @php $pm = $row->payment_method ?? '' @endphp
                                        @if($pm === 'Cash')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Cash</span>
                                        @elseif($pm === 'Cheque')
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">Cheque</span>
                                        @elseif($pm === 'Bank Transfer')
                                            <span class="badge bg-info-subtle text-info rounded-pill">Bank Transfer</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $pm }}</span>
                                        @endif
                                    </td>
                                    {{-- Spread amount into the correct column --}}
                                    <td class="text-end small {{ $pm === 'Cash' ? 'fw-bold text-success' : 'text-muted' }}">
                                        {{ $pm === 'Cash' ? number_format($row->total_amount, 2) : '—' }}
                                    </td>
                                    <td class="text-end small {{ $pm === 'Cheque' ? 'fw-bold text-warning' : 'text-muted' }}">
                                        {{ $pm === 'Cheque' ? number_format($row->total_amount, 2) : '—' }}
                                    </td>
                                    <td class="text-end small {{ $pm === 'Bank Transfer' ? 'fw-bold text-info' : 'text-muted' }}">
                                        {{ $pm === 'Bank Transfer' ? number_format($row->total_amount, 2) : '—' }}
                                    </td>
                                    <td class="text-end small fw-bold">{{ number_format($row->total_amount, 2) }}</td>
                                    <td class="small text-muted">{{ $row->cheque_no ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        @if(!$dateFrom && !$dateTo && !$repId)
                                            Select a date range or rep above and click <strong>View Report</strong> to load data.
                                        @else
                                            No payment records found for the selected filters.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($collSummary && $collectionData->count() > 0)
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end pe-2 small">Totals:</td>
                                <td class="text-end small text-success">{{ number_format($collSummary['cash'], 2) }}</td>
                                <td class="text-end small text-warning">{{ number_format($collSummary['cheque'], 2) }}</td>
                                <td class="text-end small text-info">{{ number_format($collSummary['bank_transfer'], 2) }}</td>
                                <td class="text-end small text-primary">{{ number_format($collSummary['total'], 2) }}</td>
                                <td></td>
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        /* Summary cards: keep visible on print */
        .row.g-3 .card { break-inside: avoid; }
    }
</style>
@endsection
