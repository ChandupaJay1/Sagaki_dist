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
                        <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td class="ps-4">{{ $o->customer->company_name ?? $o->customer->name ?? '—' }}</td>
                            <td>{{ $o->reference_no ?? '—' }}</td>
                            <td>{{ $o->order_date ?? '—' }}</td>
                            <td>{{ $o->expected_date ?? '—' }}</td>
                            <td>{{ number_format($o->total_amount, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('sales-orders.show', $o->id) }}" class="btn btn-soft-info btn-sm icon-btn" title="View Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('sales-orders.edit', $o->id) }}" class="btn btn-soft-success btn-sm icon-btn" title="Edit Order">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('sales-orders.destroy', $o->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
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
