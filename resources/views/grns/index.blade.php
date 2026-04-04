@extends('layouts.admin')

@section('title', 'GRNs')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">GRNs</h4>
            <p class="text-muted small mb-0">List of recorded Good Received Notes</p>
        </div>
        <a href="{{ route('grns.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>New GRN</a>
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
                        <th class="text-muted small fw-bold text-uppercase">GRN No</th>
                        <th class="text-muted small fw-bold text-uppercase">Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Total</th>
                        <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grns as $g)
                        <tr>
                            <td class="ps-4">{{ $g->vendor->company_name ?? $g->vendor->name ?? '—' }}</td>
                            <td>{{ $g->reference_no ?? '—' }}</td>
                            <td>{{ $g->date ?? '—' }}</td>
                            <td>{{ number_format($g->total_amount, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('grns.show', $g->id) }}" class="btn btn-soft-info btn-sm icon-btn">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('grns.edit', $g->id) }}" class="btn btn-soft-success btn-sm icon-btn">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('grns.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm icon-btn">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No GRNs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $grns->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
