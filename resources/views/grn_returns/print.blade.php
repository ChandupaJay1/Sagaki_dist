@extends('layouts.admin')

@section('title', 'GRN Return - View Details')

@section('content')
<style>
    @media print {
        #global-preloader, .navbar, .sidebar, footer, .page-title-box { display: none !important; }
        body, .main-content, .page-content, .container-fluid { padding: 0 !important; margin: 0 !important; background: #fff !important; }
        @page { size: A4; margin: 10mm; }
        .card { 
            border: none !important; 
            box-shadow: none !important;
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 210mm !important;
        }
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">GRN Return Details</h4>
            <div class="page-title-right">
                <a href="{{ route('grn-returns.index') }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                <a href="{{ route('grn-returns.edit', $return->id) }}" class="btn btn-primary btn-sm"><i class="ri-pencil-line me-1"></i>Edit Return</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info py-2">
                <h5 class="card-title mb-0">Return No: {{ $return->reference_no ?? '—' }}</h5>
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
                            @php 
                                $totalQty = 0; 
                                $grandTotal = 0;
                            @endphp
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
                                @php 
                                    $totalQty += $item->qty; 
                                    $grandTotal += $item->total;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">Total Qty</td>
                                <td class="text-center">{{ number_format($totalQty, 2) }}</td>
                                <td colspan="4" class="text-end">Grand Total</td>
                                <td class="text-end">{{ number_format($grandTotal, 2) }}</td>
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
@endsection

<script> window.onload = function() { window.print(); } </script>
