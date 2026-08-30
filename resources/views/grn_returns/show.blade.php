@extends('layouts.admin')

@section('title', 'GRN Return - View Details')

@section('content')
<!-- Header Buttons (Visible on Screen, Hidden on Print) -->
<div class="row d-print-none mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">GRN Return Details</h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('grn-returns.index') }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                <a href="{{ route('grn-returns.edit', $return->id) }}" class="btn btn-primary btn-sm"><i class="ri-pencil-line me-1"></i>Edit Return</a>
                <button type="button" class="btn btn-info btn-sm" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print Return</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. ORIGINAL SCREEN VIEW (Hidden on Print)  -->
<!-- ========================================== -->
<div class="row d-print-none">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Return No: <span class="text-primary">{{ $return->return_no ?? $return->id }}</span></h5>
                @if($return->reference_no)
                    <span class="badge bg-light text-dark border small">Ref: {{ $return->reference_no }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Vendor Details</h6>
                        <p class="fw-bold mb-1">{{ $return->vendor->company_name ?? $return->vendor->name }}</p>
                        <p class="text-muted mb-1">{{ $return->address }}</p>
                        <p class="text-muted mb-0">Delivery: {{ $return->delivery_destination }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Return Info</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Date:</span>
                            <span class="fw-medium">{{ $return->date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Load:</span>
                            <span class="fw-medium">{{ $return->load }}</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Item Code</th>
                                <th>Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Disc%</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalQty = 0; @endphp
                            @foreach($return->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->product->code ?? '—' }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    <td class="text-center">{{ $item->disc_percent }}%</td>
                                    <td class="text-end">{{ number_format($item->discount, 2) }}</td>
                                    <td class="text-end fw-medium">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @php $totalQty += $item->qty; @endphp
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">Total Qty</td>
                                <td class="text-center">{{ number_format($totalQty, 2) }}</td>
                                <td colspan="4" class="text-end">Grand Total</td>
                                <td class="text-end">{{ number_format($return->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($return->memo)
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2">Memo / Remarks:</h6>
                        <p class="mb-0">{{ $return->memo }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. PREMIUM PRINT VIEW (Visible ONLY on Print) -->
<!-- ========================================== -->
<div class="d-none d-print-block print-document">
    
    <!-- Document Title -->
    <div class="text-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold mb-1" style="color: #2c3e50; letter-spacing: 1px;">GOODS RECEIPT NOTE RETURN</h2>
        <h5 class="text-muted">RTN #{{ $return->return_no ?? $return->id }}</h5>
    </div>

    <div class="row mb-5">
        <!-- Left: Vendor Details -->
        <div class="col-6">
            <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Vendor Details</h6>
            <h5 class="fw-bold mb-1 text-dark">{{ $return->vendor->company_name ?? $return->vendor->name }}</h5>
            @if($return->address)
                <p class="text-muted mb-1" style="font-size: 0.95rem;">{{ $return->address }}</p>
            @endif
            @if($return->delivery_destination)
                <p class="text-muted mb-0" style="font-size: 0.95rem;"><span class="fw-bold">DELIVERY:</span> {{ $return->delivery_destination }}</p>
            @endif
        </div>

        <!-- Right: Return Info -->
        <div class="col-6 text-end">
            <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Return Info</h6>
            <table class="table-sm table-borderless ms-auto text-end" style="width: auto;">
                <tbody>
                    <tr>
                        <td class="text-muted text-uppercase fw-bold" style="font-size: 0.8rem; padding-right: 15px;">Date:</td>
                        <td class="fw-bold text-dark" style="font-size: 0.95rem;">{{ \Carbon\Carbon::parse($return->date)->format('Y-m-d') }}</td>
                    </tr>
                    @if($return->reference_no)
                    <tr>
                        <td class="text-muted text-uppercase fw-bold" style="font-size: 0.8rem; padding-right: 15px;">Reference:</td>
                        <td class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $return->reference_no }}</td>
                    </tr>
                    @endif
                    @if($return->load)
                    <tr>
                        <td class="text-muted text-uppercase fw-bold" style="font-size: 0.8rem; padding-right: 15px;">Load:</td>
                        <td class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $return->load }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle print-table" style="width: 100%;">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th class="text-center" style="width: 5%; color: #495057; font-weight: 700;">#</th>
                    <th class="text-start" style="width: 15%; color: #495057; font-weight: 700;">ITEM CODE</th>
                    <th class="text-start" style="width: 25%; color: #495057; font-weight: 700;">DESCRIPTION</th>
                    <th class="text-end" style="width: 10%; color: #495057; font-weight: 700;">QTY</th>
                    <th class="text-end" style="width: 12%; color: #495057; font-weight: 700;">RATE</th>
                    <th class="text-end" style="width: 12%; color: #495057; font-weight: 700;">AMOUNT</th>
                    <th class="text-end" style="width: 8%; color: #495057; font-weight: 700;">DISC%</th>
                    <th class="text-end" style="width: 13%; color: #495057; font-weight: 700;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php $totalQty = 0; @endphp
                @foreach($return->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-start text-dark fw-medium">{{ $item->product->code ?? '—' }}</td>
                        <td class="text-start">{{ $item->description }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->qty, 2) }}</td>
                        <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        <td class="text-end">{{ $item->disc_percent }}%</td>
                        <td class="text-end fw-bold text-dark">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @php $totalQty += $item->qty; @endphp
                @endforeach
            </tbody>
            <tfoot style="background-color: #f8f9fa;">
                <tr>
                    <td colspan="3" class="text-end text-uppercase fw-bold text-muted" style="font-size: 0.85rem;">Total Qty</td>
                    <td class="text-end fw-bold text-dark fs-6">{{ number_format($totalQty, 2) }}</td>
                    <td colspan="3" class="text-end text-uppercase fw-bold" style="font-size: 1.1rem; color: #2c3e50;">Grand Total</td>
                    <td class="text-end fw-bold" style="font-size: 1.2rem; color: #2c3e50; border-top: 2px solid #343a40;">
                        {{ number_format($return->total_amount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($return->memo)
        <div class="mt-4 p-3 bg-light border rounded">
            <h6 class="fw-bold mb-2 text-uppercase text-muted" style="font-size: 0.8rem;">Memo / Remarks:</h6>
            <p class="mb-0 text-dark">{{ $return->memo }}</p>
        </div>
    @endif
    
    <div class="mt-5 text-center text-muted" style="font-size: 0.85rem;">
        <p>This is a computer generated document. No signature is required.</p>
    </div>

</div>

<style>
/* Document Base Styling */
.print-document {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
}
.print-table th, .print-table td {
    padding: 12px 15px !important;
    vertical-align: middle;
}

/* Print Specific CSS */
@media print {
    @page {
        size: A4;
        margin: 10mm;
    }
    
    /* Hide non-printable areas */
    .no-print, .no-print *, header, footer, .navbar, .sidebar, #global-preloader {
        display: none !important;
    }
    
    /* Reset margins and shadows for print */
    body, .main-content, .page-content, .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
        background-color: #fff !important;
    }
    
    .print-document {
        padding: 0 !important;
        box-shadow: none !important;
        display: block !important;
    }
    
    /* Fix table overflow and width */
    .table-responsive {
        overflow: visible !important;
    }
    
    table {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Force background colors on print */
    .print-table thead th, .print-table tfoot td {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<script>
    window.addEventListener('load', function() {
        // Automatically trigger print if redirected from 'Save & Print'
        if (window.location.search.indexOf('print=true') > -1 || document.referrer.indexOf('/grns-returns/create') > -1) {
            setTimeout(function() {
                window.print();
            }, 800);
        }
    });
</script>
@endsection
