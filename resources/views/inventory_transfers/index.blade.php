@extends('layouts.admin')

@section('title', 'Inventory Transfers')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Inventory Transfers</h4>
            <p class="text-muted small mb-0">List of recorded inventory transfers</p>
        </div>
        <a href="{{ route('inventory-transfers.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>New Transfer</a>
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
                        <th class="ps-4 text-muted small fw-bold text-uppercase">From</th>
                        <th class="text-muted small fw-bold text-uppercase">To</th>
                        <th class="text-muted small fw-bold text-uppercase">Transfer No</th>
                        <th class="text-muted small fw-bold text-uppercase">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $t)
                        <tr>
                            <td class="ps-4">{{ $t->site_from ?? '—' }}</td>
                            <td>{{ $t->site_to ?? '—' }}</td>
                            <td>{{ $t->transfer_no ?? '—' }}</td>
                            <td>{{ $t->date ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No inventory transfers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $transfers->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
