@extends('layouts.admin')

@section('title', 'Transfer Details — ' . $transfer->transfer_no)

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Transfer Details</h4>
                <p class="text-muted small mb-0">{{ $transfer->transfer_no }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('inventory-transfers.index') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Transfers
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Header Summary Card --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="card-title fw-bold mb-0">Transfer Summary</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Transfer No.</p>
                        <p class="fw-bold mb-0">{{ $transfer->transfer_no ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Date</p>
                        <p class="fw-bold mb-0">{{ $transfer->date ? \Carbon\Carbon::parse($transfer->date)->format('d M Y') : '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Source Location (From)</p>
                        <p class="fw-semibold mb-0 text-primary">
                            <i class="ri-map-pin-line me-1"></i>{{ $transfer->site_from ?? '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Destination Location (To)</p>
                        <p class="fw-semibold mb-0 text-success">
                            <i class="ri-map-pin-2-line me-1"></i>{{ $transfer->site_to ?? '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Rep / Agent</p>
                        <p class="fw-bold mb-0">{{ $transfer->repAgent?->name ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Status</p>
                        <p class="mb-0">
                            @if($transfer->status === 'Approved')
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">
                                    <i class="ri-check-line me-1"></i>Approved
                                </span>
                            @elseif($transfer->status === 'Rejected')
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-semibold">
                                    <i class="ri-close-line me-1"></i>Rejected
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-semibold">
                                    <i class="ri-time-line me-1"></i>Pending
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($transfer->memo)
                        <div class="col-12">
                            <p class="text-muted small mb-1">Memo / Notes</p>
                            <p class="mb-0 text-dark">{{ $transfer->memo }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px; background: linear-gradient(135deg, #667eea22, #764ba222);">
            <div class="card-body d-flex flex-column justify-content-center px-4">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="ri-arrow-left-right-line fs-24 text-primary"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Items Transferred</h6>
                    <h2 class="fw-bold text-primary mb-0">{{ $transfer->items->count() }}</h2>
                    <p class="text-muted small mb-0">distinct product lines</p>
                </div>
                <hr class="my-3">
                <div class="text-center">
                    <h6 class="fw-bold text-dark">Total Quantity</h6>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($transfer->items->sum('qty'), 2) }}</h3>
                    <p class="text-muted small mb-0">units moved</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Items Table --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="card-title fw-bold mb-0">
                    <i class="ri-list-check-2 me-2 text-primary"></i>Transferred Items
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">#</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Product Code</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Product Name</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Description</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase text-end">On Hand (Before)</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase text-end">Qty Transferred</th>
                                <th class="pe-4 border-0 text-muted small fw-bold text-uppercase">Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfer->items as $index => $item)
                                <tr>
                                    <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                                    <td>
                                        @if($item->product)
                                            <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                                {{ $item->product->code ?? '—' }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->product?->name ?? 'Unknown Product' }}</div>
                                    </td>
                                    <td class="text-muted">{{ $item->description ?: '—' }}</td>
                                    <td class="text-end fw-medium text-muted">{{ number_format($item->onhand ?? 0, 2) }}</td>
                                    <td class="text-end">
                                        <span class="fw-bold text-dark fs-14">{{ number_format($item->qty, 2) }}</span>
                                    </td>
                                    <td class="pe-4 text-muted">{{ $item->unit ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-48 opacity-25 d-block mb-2"></i>
                                            No items found for this transfer.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($transfer->items->count() > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="5" class="ps-4 fw-bold text-dark text-end pe-3">Total Quantity:</td>
                                    <td class="text-end fw-bold text-primary fs-14">
                                        {{ number_format($transfer->items->sum('qty'), 2) }}
                                    </td>
                                    <td class="pe-4"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
