@extends('layouts.admin')

@section('title', 'Pay Bills')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Bill Payments</h4>
            <p class="text-muted small mb-0">Record of payments made to vendors</p>
        </div>
        <a href="{{ route('pay-bills.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>New Payment</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success border-0 bg-success-subtle text-success mx-4 mt-4 rounded-3 d-flex align-items-center" role="alert">
                <i class="ri-check-line me-2 fs-18"></i>
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Vendor</th>
                        <th class="text-muted small fw-bold text-uppercase">Voucher No</th>
                        <th class="text-muted small fw-bold text-uppercase">Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Method</th>
                        <th class="text-muted small fw-bold text-uppercase">Total Payment</th>
                        <th class="text-muted small fw-bold text-uppercase">Status</th>
                        <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $p->vendor->company_name ?? $p->vendor->name }}</div>
                                <div class="text-muted extra-small text-uppercase">{{ $p->vendor->code ?? 'V-TR00' }}</div>
                            </td>
                            <td><span class="badge bg-light text-primary border border-primary-subtle px-2">{{ $p->voucher_no }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($p->date)->format('Y-m-d') }}</td>
                            <td>
                                <span class="text-muted small"><i class="ri-bank-card-line me-1"></i>{{ $p->payment_method }}</span>
                            </td>
                            <td><span class="fw-bold text-success">{{ number_format($p->total_amount, 2) }}</span></td>
                            <td>
                                <span class="badge bg-success-subtle text-success badge-soft text-uppercase">{{ $p->status }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('pay-bills.show', $p->id) }}" class="btn btn-soft-info btn-sm icon-btn" title="View Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <form action="{{ route('pay-bills.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm icon-btn" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-bill-line fs-24 mb-2"></i>
                                    <p>No payments found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $payments->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
    .icon-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>
@endsection
