@extends('layouts.admin')

@section('title', 'GRN - View Details')

@section('content')
<style>
        #print-area {
            margin: 0 auto;
            width: 100%;
            max-width: 210mm;
            padding: 10mm;
            box-sizing: border-box;
            background: #fff;
        }
        @media print {
            #global-preloader { display: none !important; }
            body { padding: 0; margin: 0; background: #fff !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            @page { size: A4; margin: 10mm; }
            #print-area {
                padding: 0 !important;
                margin: 0 auto !important;
                max-width: 210mm !important;
            }
        
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 26px; color: #2c3e50; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #555; font-size: 14px; font-weight: bold; }
        
        .info-section { width: 100%; margin-bottom: 30px; display: table; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; }
        .info-box.right { text-align: right; }
        
        .info-box h3 { margin: 0 0 10px; font-size: 14px; text-transform: uppercase; border-bottom: 2px solid #eee; padding-bottom: 5px; display: inline-block; color: #2c3e50; }
        .info-box p { margin: 4px 0; font-size: 13px; color: #333; }
        
        .table-print { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; }
        .table-print th, .table-print td { border-bottom: 1px solid #ddd; padding: 8px 10px; }
        .table-print th { background-color: #f8f9fa !important; font-weight: bold; color: #2c3e50; border-top: 2px solid #2c3e50; border-bottom: 2px solid #2c3e50; }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        
        .totals-section { width: 100%; display: flex; justify-content: flex-end; margin-top: 15px; }
        .totals-table { width: 320px; border-collapse: collapse; font-size: 14px; }
        .totals-table th, .totals-table td { padding: 8px 10px; text-align: right; }
        .totals-table th { text-align: left; color: #2c3e50; }
        
        .total-row th, .total-row td { border-top: 2px solid #2c3e50; font-weight: bold; font-size: 16px; color: #000; }
        .footer-note { margin-top: 40px; text-align: center; font-size: 11px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
    }
</style>

<div class="d-print-none">
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">GRN Details</h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('grns.index') }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                @if($grn->status !== 'Approved')
                    <a href="{{ route('grns.edit', $grn->id) }}" class="btn btn-primary btn-sm"><i class="ri-pencil-line me-1"></i>Edit GRN</a>
                    <form action="{{ route('grns.approve', $grn->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this GRN and update stock?')"><i class="ri-checkbox-circle-line me-1"></i>Approve GRN</button>
                    </form>
                @endif
                <button type="button" class="btn btn-info btn-sm" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print GRN</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="card-title mb-0">GRN No: <span class="text-primary">{{ $grn->grn_no }}</span></h5>
                    @if($grn->status === 'Approved')
                        <span class="badge bg-success"><i class="ri-checkbox-circle-line me-1"></i>Approved</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="ri-time-line me-1"></i>{{ $grn->status ?? 'Pending' }}</span>
                    @endif
                </div>
                @if($grn->reference_no)
                    <span class="badge bg-light text-dark border small">Ref: {{ $grn->reference_no }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Vendor Details</h6>
                        <p class="fw-bold mb-1">{{ $grn->vendor->company_name ?? $grn->vendor->name }}</p>
                        <p class="text-muted mb-1">{{ $grn->address }}</p>
                        <p class="text-muted mb-0">Delivery: {{ $grn->delivery_destination }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">GRN Info</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Date:</span>
                            <span class="fw-medium">{{ $grn->date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Load:</span>
                            <span class="fw-medium">{{ $grn->load }}</span>
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
                            @foreach($grn->items as $index => $item)
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
                                <td class="text-end">{{ number_format($grn->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($grn->memo)
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2">Memo / Remarks:</h6>
                        <p class="mb-0">{{ $grn->memo }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<div class="d-none d-print-block" id="print-area">
    <div class="header">
        <h1>Goods Receipt Note</h1>
        <p>GRN NO: {{ $grn->grn_no ?? 'N/A' }}</p>
    </div>

    <div class="info-section">
        <div class="info-box text-left">
            <h3>Vendor Details</h3>
            <p><strong>{{ $grn->vendor->company_name ?? $grn->vendor->name ?? 'N/A' }}</strong></p>
            @if($grn->address)<p>{{ $grn->address }}</p>@endif
            @if($grn->delivery_destination)<p>Delivery: {{ $grn->delivery_destination }}</p>@endif
        </div>
        <div class="info-box right">
            <h3>GRN Info</h3>
            <p><strong>Date:</strong> {{ $grn->date }}</p>
            @if($grn->reference_no)<p><strong>Reference:</strong> {{ $grn->reference_no }}</p>@endif
            @if($grn->load)<p><strong>Load:</strong> {{ $grn->load }}</p>@endif
        </div>
    </div>

    <table class="table-print">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="15%" class="text-left">Item Code</th>
                <th width="35%" class="text-left">Description</th>
                <th width="10%" class="text-right">Qty</th>
                <th width="12%" class="text-right">Rate</th>
                @if($grn->items->sum('discount') > 0)
                <th width="10%" class="text-right">Discount</th>
                @endif
                <th width="13%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grn->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->product->code ?? '—' }}</td>
                    <td class="text-left">{{ $item->description }}</td>
                    <td class="text-right">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                    @if($grn->items->sum('discount') > 0)
                    <td class="text-right">{{ $item->discount > 0 ? number_format($item->discount, 2) : '-' }}</td>
                    @endif
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <th>Subtotal</th>
                <td>{{ number_format($grn->subtotal ?? $grn->total_amount, 2) }}</td>
            </tr>
            @if($grn->header_discount_amount > 0)
            <tr>
                <th>Discount</th>
                <td>-{{ number_format($grn->header_discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <th>Grand Total</th>
                <td>{{ number_format($grn->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        <p>This is a computer generated document.</p>
    </div>
</div>
@endsection

<script> window.onload = function() { window.print(); } </script>
