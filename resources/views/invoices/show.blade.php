@extends('layouts.admin')

@section('title', 'Invoice - View Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Invoice Details</h4>
            <div class="page-title-right">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm"><i class="ri-pencil-line me-1"></i>Edit Invoice</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info py-2">
                <h5 class="card-title mb-0">Invoice No: {{ $invoice->invoice_no ?? '—' }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-3">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Customer Details</h6>
                        <p class="fw-bold mb-1">{{ $invoice->customer->company_name ?? $invoice->customer->name }}</p>
                        <p class="text-muted mb-1">{{ $invoice->address }}</p>
                        <p class="text-muted mb-0">Delivery: {{ $invoice->delivery_destination }}</p>
                    </div>
                    <div class="col-sm-3">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Invoice Info</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Date:</span>
                            <span class="fw-medium">{{ $invoice->date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Load:</span>
                            <span class="fw-medium">{{ $invoice->load }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Payment Details</h6>
                        @php
                            // 1. Try to find the payment method with multiple fallbacks
                            $method = $invoice->payment_method ?? $invoice->payment_type ?? 'N/A';
                            
                            // Try to get instant payment details
                            $instantPayment = $invoice->payments()->latest()->first();
                            if ($instantPayment && ($method == 'N/A' || empty($method))) {
                                $method = $instantPayment->method;
                            }
                            
                            // Try to get settlement payment details
                            $settlementItem = \App\Models\PayBillItem::where('invoice_id', $invoice->id)->with('payBill')->latest()->first();
                            if ($settlementItem && $settlementItem->payBill && ($method == 'N/A' || empty($method))) {
                                $method = $settlementItem->payBill->payment_method;
                            }

                            // 2. Try to find Cheque details with fallbacks
                            $chequeNo = $invoice->cheque_no ?? '—';
                            $chequeDate = $invoice->cheque_date ?? '—';
                            
                            if ($chequeNo == '—' && $instantPayment && $instantPayment->method == 'Cheque') {
                                $cheque = $instantPayment->cheques()->first();
                                $chequeNo = $cheque->cheque_no ?? '—';
                                $chequeDate = $cheque->date ?? '—';
                            }
                            
                            if ($chequeNo == '—' && $settlementItem && $settlementItem->payBill) {
                                $chequeNo = $settlementItem->payBill->cheque_no ?? '—';
                                $chequeDate = $settlementItem->payBill->pd_cheque_date ?? '—';
                            }

                            // 3. Try to find Bank/Reference details with fallbacks
                            $referenceNo = $invoice->reference_no ?? $invoice->bank_reference ?? '—';
                            if ($referenceNo == '—' && $settlementItem && $settlementItem->payBill) {
                                $referenceNo = $settlementItem->payBill->memo ?? '—';
                            }
                            
                            // Normalize Bank method name
                            if ($method == 'Bank') $method = 'Bank Transfer';

                            // Styling Logic
                            $badgeClass = 'bg-soft-secondary text-secondary';
                            $badgeStyle = '';
                            
                            if($method == 'Cash') {
                                $badgeStyle = 'background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);';
                                $badgeClass = 'text-success';
                            } elseif($method == 'Cheque') {
                                $badgeStyle = 'background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);';
                                $badgeClass = 'text-warning';
                            } elseif($method == 'Bank Transfer') {
                                $badgeStyle = 'background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);';
                                $badgeClass = 'text-info';
                            }
                        @endphp
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Method:</span>
                            <span class="badge {{ $badgeClass }} px-2.5 py-1" style="{{ $badgeStyle }}">{{ $method }}</span>
                        </div>

                        @if($method == 'Cheque' || $chequeNo != '—')
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Cheque No:</span>
                                <span class="fw-bold">{{ $chequeNo }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Cheque Date:</span>
                                <span class="fw-bold"><i class="ri-calendar-line me-1"></i>{{ $chequeDate }}</span>
                            </div>
                        @elseif($method == 'Bank Transfer' || $referenceNo != '—')
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Ref No:</span>
                                <span class="fw-bold">{{ $referenceNo }}</span>
                            </div>
                        @endif
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
                            @foreach($invoice->items as $index => $item)
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
                                <td class="text-end">{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            @if(isset($outstanding))
                            <tr class="table-danger">
                                <td colspan="8" class="text-end">Customer Remaining Outstanding</td>
                                <td class="text-end text-danger">LKR {{ number_format($outstanding, 2) }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
