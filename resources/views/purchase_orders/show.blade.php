@extends('layouts.admin')

@section('title', 'Purchase Order - View Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Purchase Order Details</h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                @if($order->status !== 'Approved')
                    <a href="{{ route('purchase-orders.edit', $order->id) }}" class="btn btn-primary btn-sm"><i class="ri-pencil-line me-1"></i>Edit Order</a>
                    <form action="{{ route('purchase-orders.approve', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this Purchase Order?')"><i class="ri-checkbox-circle-line me-1"></i>Approve Order</button>
                    </form>
                @endif
                <button type="button" class="btn btn-info btn-sm" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print PO</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="card-title mb-0">PO No: <span class="text-primary">{{ $order->po_no }}</span></h5>
                    @if($order->status === 'Approved')
                        <span class="badge bg-success"><i class="ri-checkbox-circle-line me-1"></i>Approved</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="ri-time-line me-1"></i>{{ $order->status ?? 'Pending' }}</span>
                    @endif
                </div>
                @if($order->reference_no)
                    <span class="badge bg-light text-dark border small">Ref: {{ $order->reference_no }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Vendor Details</h6>
                        <p class="fw-bold mb-1">{{ $order->vendor->company_name ?? $order->vendor->name }}</p>
                        <p class="text-muted mb-1">{{ $order->address }}</p>
                        <p class="text-muted mb-0">Delivery: {{ $order->delivery_destination }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Order Info</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Date:</span>
                            <span class="fw-medium">{{ $order->date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Load:</span>
                            <span class="fw-medium">{{ $order->load }}</span>
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
                            @foreach($order->items as $index => $item)
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
                                <td class="text-end">{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->memo)
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2">Memo / Remarks:</h6>
                        <p class="mb-0">{{ $order->memo }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
