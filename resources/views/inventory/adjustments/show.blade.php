@extends('layouts.admin')

@section('title', 'Stock Adjustment - View')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Adjustment Details</h4>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('stock-adjustments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-information-line me-1"></i>Adjustment: {{ $adjustment->adjustment_no }}</h5>
                <div class="float-end">
                    @if($adjustment->status == 'Pending')
                        <form action="{{ route('stock-adjustments.approve', $adjustment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm me-1" onclick="return confirm('Approve this stock adjustment?')"><i class="ri-check-line me-1"></i>Approve Adjustment</button>
                        </form>
                        <form action="{{ route('stock-adjustments.destroy', $adjustment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pending adjustment?')"><i class="ri-delete-bin-line me-1"></i>Delete</button>
                        </form>
                    @else
                        <span class="badge bg-success fs-12"><i class="ri-checkbox-circle-line me-1"></i>Approved</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <p class="text-muted mb-1">Adjustment Number</p>
                        <h6 class="fw-bold">{{ $adjustment->adjustment_no }}</h6>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">Date</p>
                        <h6 class="fw-bold">{{ $adjustment->date }}</h6>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">Location</p>
                        <h6 class="fw-bold">{{ $adjustment->location->name ?? 'N/A' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">Status</p>
                        <h6>
                            @if($adjustment->status == 'Pending')
                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Approved</span>
                            @endif
                        </h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <p class="text-muted mb-1">Memo / Reason</p>
                        <p class="mb-0">{{ $adjustment->memo ?: 'No memo provided.' }}</p>
                    </div>
                </div>

                <div class="table-responsive border rounded">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="bg-light text-center">
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Current Qty</th>
                                <th>New Qty</th>
                                <th>Adjustment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adjustment->items as $index => $item)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="fw-bold">{{ $item->product->code }}</span></td>
                                    <td class="text-start">{{ $item->product->name }}</td>
                                    <td>{{ number_format($item->current_qty, 2) }}</td>
                                    <td>{{ number_format($item->new_qty, 2) }}</td>
                                    <td>
                                        <span class="fw-bold {{ $item->adjustment_qty > 0 ? 'text-success' : ($item->adjustment_qty < 0 ? 'text-danger' : '') }}">
                                            {{ $item->adjustment_qty > 0 ? '+' : '' }}{{ number_format($item->adjustment_qty, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold text-center">
                            <tr>
                                <td colspan="5" class="text-end">Total Adjustment Qty</td>
                                <td>
                                    @php $totalAdj = $adjustment->items->sum('adjustment_qty'); @endphp
                                    <span class="{{ $totalAdj > 0 ? 'text-success' : ($totalAdj < 0 ? 'text-danger' : '') }}">
                                        {{ $totalAdj > 0 ? '+' : '' }}{{ number_format($totalAdj, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4 row">
                    <div class="col-md-6 text-muted small">
                        Created By: {{ $adjustment->creator->name ?? 'System' }} at {{ $adjustment->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
