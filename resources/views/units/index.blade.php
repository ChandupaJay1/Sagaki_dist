@extends('layouts.admin')

@section('title', 'Unit Master')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Unit Master</h4>
                <p class="text-muted small mb-0">Define measurement units (Nos, Kg, Ltr, etc.).</p>
            </div>
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0 bg-light p-2 rounded-pill px-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-tables') }}" class="text-decoration-none">Master Tables</a></li>
                    <li class="breadcrumb-item active">Unit Master</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">Units</h5>
                <div class="flex-shrink-0">
                    <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add New Unit
                    </a>
                </div>
            </div>
            <div class="card-body p-0 mt-3">
                @if(session('success'))
                    <div class="alert alert-success border-0 bg-success-subtle text-success mx-4 mb-4 rounded-3 d-flex align-items-center" role="alert">
                        <i class="ri-check-line me-2 fs-18"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">#</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Code</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Unit Name</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Status</th>
                                <th class="pe-4 border-0 text-muted small fw-bold text-uppercase text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</td>
                                    <td>
                                        @if($unit->code)
                                            <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $unit->code }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">{{ $unit->name }}</td>
                                    <td>
                                        @if($unit->is_active)
                                            <span class="badge bg-success-subtle text-success border-0 px-2 py-1 rounded-pill">
                                                <span class="pulse me-1"></span> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1 rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                                <i class="ri-edit-2-line fs-16"></i>
                                            </a>
                                            <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this unit?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border-0 rounded-circle text-danger" title="Delete">
                                                    <i class="ri-delete-bin-line fs-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-48 opacity-25"></i>
                                            <p class="mt-2 mb-0">No units created yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-top">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="text-muted small">
                            Showing {{ $units->firstItem() }} to {{ $units->lastItem() }} of {{ $units->total() }} records
                        </div>
                        <div>
                            {{ $units->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

