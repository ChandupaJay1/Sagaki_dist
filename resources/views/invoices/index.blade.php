@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Invoices</h4>
            <p class="text-muted small mb-0">List of recorded invoices</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>New Invoice</a>
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
                        <th class="text-muted small fw-bold text-uppercase">Invoice No</th>
                        <th class="text-muted small fw-bold text-uppercase">Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Total</th>
                        <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $i)
                        <tr>
                            <td class="ps-4">{{ $i->customer->company_name ?? $i->customer->name ?? '—' }}</td>
                            <td>{{ $i->invoice_no ?? '—' }}</td>
                            <td>{{ $i->date ?? '—' }}</td>
                            <td>{{ number_format($i->total_amount, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('invoices.show', $i->id) }}" class="btn btn-soft-info btn-sm icon-btn" title="View Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('invoices.print', $i->id) }}" class="btn btn-soft-primary btn-sm icon-btn" title="Print / Reprint" target="_blank">
                                        <i class="ri-printer-line"></i>
                                    </a>
                                    <a href="{{ route('invoices.edit', $i->id) }}" class="btn btn-soft-success btn-sm icon-btn" title="Edit Invoice">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
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
                            <td colspan="4" class="text-center py-5 text-muted">No invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $invoices->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
