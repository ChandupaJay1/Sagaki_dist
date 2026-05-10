@extends('layouts.admin')

@section('title', 'Issue Note - View')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Issue Note Details</h4>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('inventory-issues.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ri-arrow-left-line me-1"></i>Back to List</a>
                @if($issue->status == 'Pending')
                    <form action="{{ route('inventory-issues.approve', $issue->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this issue note?')">
                            <i class="ri-check-line me-1"></i>Approve Issue Note
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-soft-secondary py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Issue Note: {{ $issue->issue_no }}</h5>
                    </div>
                    <div class="col-auto">
                        @if($issue->status == 'Approved')
                            <span class="badge bg-success-subtle text-success fs-12 px-3 py-1 rounded-pill">Status: Approved</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fs-12 px-3 py-1 rounded-pill">Status: Pending</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Location</label>
                        <p class="fw-medium mb-0">{{ $issue->location->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Date</label>
                        <p class="fw-medium mb-0">{{ \Carbon\Carbon::parse($issue->date)->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Created By</label>
                        <p class="fw-medium mb-0">{{ $issue->creator->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Account</label>
                        <p class="fw-medium mb-0">{{ $issue->account->name ?? '—' }}</p>
                    </div>
                </div>

                @if($issue->memo)
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Memo / Remarks</label>
                            <p class="fw-medium mb-0 p-3 bg-light rounded">{{ $issue->memo }}</p>
                        </div>
                    </div>
                @endif

                <div class="table-responsive border rounded mt-4">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-4" style="width: 100px;">#</th>
                                <th>Product Description</th>
                                <th class="text-center">Unit</th>
                                <th class="text-end pe-4" style="width: 150px;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issue->items as $index => $item)
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-medium d-block">{{ $item->product->name ?? '—' }}</span>
                                        <small class="text-muted">{{ $item->product->code ?? '—' }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->product->unit ?? '—' }}</td>
                                    <td class="text-end pe-4 fw-bold text-primary">{{ number_format($item->qty, 4) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-4">Total Quantity:</td>
                                <td class="text-end pe-4 fw-bold fs-15 text-primary">{{ number_format($issue->items->sum('qty'), 4) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
