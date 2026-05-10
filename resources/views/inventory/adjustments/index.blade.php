@extends('layouts.admin')

@section('title', 'Stock Adjustment - List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Adjustment</h4>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i>New Adjustment</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Adjustment History</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Adj No</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adjustments as $adj)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $adj->adjustment_no }}</span></td>
                                    <td>{{ $adj->date }}</td>
                                    <td>{{ $adj->location->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($adj->status == 'Pending')
                                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @endif
                                    </td>
                                    <td>{{ $adj->creator->name ?? 'System' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('stock-adjustments.show', $adj->id) }}" class="btn btn-soft-info btn-sm btn-icon"><i class="ri-eye-line"></i></a>
                                            
                                            @if($adj->status == 'Pending')
                                                <form action="{{ route('stock-adjustments.approve', $adj->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-soft-success btn-sm btn-icon" title="Approve" onclick="return confirm('Approve this adjustment?')"><i class="ri-check-line"></i></button>
                                                </form>

                                                <form action="{{ route('stock-adjustments.destroy', $adj->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm btn-icon" title="Delete" onclick="return confirm('Delete this adjustment?')"><i class="ri-delete-bin-line"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No adjustments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $adjustments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
