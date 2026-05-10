@extends('layouts.admin')

@section('title', 'Issue Notes')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Issue Notes</h4>
            <p class="text-muted small mb-0">Manage inventory issue notes</p>
        </div>
        <a href="{{ route('inventory-issues.create') }}" class="btn btn-primary btn-sm rounded-pill">
            <i class="ri-add-line me-1"></i>New Issue Note
        </a>
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
        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mx-4 mt-4 rounded-3 d-flex align-items-center" role="alert">
                <i class="ri-error-warning-line me-2 fs-18"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Issue No</th>
                        <th class="text-muted small fw-bold text-uppercase">Location</th>
                        <th class="text-muted small fw-bold text-uppercase">Date</th>
                        <th class="text-muted small fw-bold text-uppercase">Created By</th>
                        <th class="text-muted small fw-bold text-uppercase text-center">Status</th>
                        <th class="text-muted small fw-bold text-uppercase text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                        <tr>
                            <td class="ps-4 fw-medium text-primary">{{ $issue->issue_no }}</td>
                            <td>{{ $issue->location->name ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($issue->date)->format('Y-m-d') }}</td>
                            <td>{{ $issue->creator->name ?? '—' }}</td>
                            <td class="text-center">
                                @if($issue->status == 'Approved')
                                    <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Approved</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('inventory-issues.show', $issue->id) }}" class="btn btn-soft-info btn-icon btn-sm rounded-circle" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    
                                    @if($issue->status == 'Pending')
                                        <form action="{{ route('inventory-issues.approve', $issue->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-soft-success btn-icon btn-sm rounded-circle" title="Approve" onclick="return confirm('Are you sure you want to approve this issue note? This will update stock.')">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('inventory-issues.destroy', $issue->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" title="Delete" onclick="return confirm('Are you sure you want to delete this pending issue note?')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No issue notes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $issues->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
