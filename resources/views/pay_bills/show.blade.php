@extends('layouts.admin')

@section('title', 'View ' . $payment->type . ' Bill Payment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">View {{ $payment->type }} Bill Payment</h4>
            <div class="page-title-right">
                <a href="{{ route('pay-bills.print', $payment->id) }}" class="btn btn-info btn-sm me-1"><i class="ri-printer-line me-1"></i>Print Voucher</a>
                <a href="{{ route('pay-bills.index', ['type' => $payment->type]) }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-1">{{ $payment->type === 'Supplier' ? 'Vendor' : 'Customer' }}</label>
                        <div class="fs-15 fw-bold text-primary">
                            @if($payment->type === 'Supplier')
                                {{ $payment->vendor->company_name ?? $payment->vendor->name }}
                            @else
                                {{ $payment->customer->company_name ?? $payment->customer->name }}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Payment Info</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Payment Date:</span>
                            <span class="fw-medium">{{ $payment->date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Method:</span>
                            <span class="fw-medium text-primary">{{ $payment->payment_method }}</span>
                        </div>
                        @if($payment->cheque_no)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Cheque No:</span>
                            <span class="fw-medium">{{ $payment->cheque_no }}</span>
                        </div>
                        @endif
                        @if($payment->pd_cheque_date)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">PD Cheque Date:</span>
                            <span class="fw-medium">{{ $payment->pd_cheque_date }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Transaction Recap</h6>
                        <div class="bg-light p-3 rounded-3 text-center border">
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Grand Total Payment</p>
                            <h3 class="fw-bold text-success mb-0">LKR {{ number_format($payment->total_amount, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle text-center">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Bill No</th>
                                <th>Bill Date</th>
                                <th>Date Due</th>
                                <th class="text-end">Amt. Due</th>
                                <th class="text-end">Amt. Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="fw-medium text-primary">{{ $item->bill_no }}</span></td>
                                    <td>{{ $item->bill_date }}</td>
                                    <td>{{ $item->due_date }}</td>
                                    <td class="text-end">{{ number_format($item->bill_amount, 2) }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($item->amount_to_pay, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end text-uppercase">Total Payment Recorded</td>
                                <td class="text-end text-success fs-15">{{ number_format($payment->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($payment->memo)
                    <div class="mt-4 p-3 bg-light rounded border-start border-4 border-info">
                        <h6 class="fw-bold mb-2 text-info"><i class="ri-sticky-note-line me-1"></i>Memo / Remarks:</h6>
                        <p class="mb-0 text-dark small">{{ $payment->memo }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
