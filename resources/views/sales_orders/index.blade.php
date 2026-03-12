@extends('layouts.admin')

@section('title', 'Sales Orders')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Sales Orders</h4>
            <p class="text-muted small mb-0">List of recorded sales orders</p>
        </div>
        <a href="{{ route('sales-orders.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>New Sales Order</a>
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
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Customer</th>
                        <th class="text-muted small fw-bold text-uppercase">Reference</th>
                        <th class="text-muted small fw-bold text-uppercase">Order Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Expected Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td class="ps-4">{{ $o->customer->name ?? '—' }}</td>
                            <td>{{ $o->reference_no ?? '—' }}</td>
                            <td>{{ $o->order_date ?? '—' }}</td>
                            <td>{{ $o->expected_date ?? '—' }}</td>
                            <td>{{ number_format($o->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No sales orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
