@extends('layouts.admin')

@section('title', 'Areas')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Areas</h4>
            <p class="text-muted small mb-0">Manage delivery areas</p>
        </div>
        <a href="{{ route('areas.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>Add Area</a>
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
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Code</th>
                        <th class="text-muted small fw-bold text-uppercase">Area Name</th>
                        <th class="text-muted small fw-bold text-uppercase">Territories</th>
                        <th class="text-muted small fw-bold text-uppercase">Status</th>
                        <th class="pe-4 text-muted small fw-bold text-uppercase text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($areas as $area)
                        <tr>
                            <td class="ps-4">
                                @if($area->code)
                                    <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $area->code }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $area->name }}</td>
                            <td class="text-muted">
                                @if($area->territories->count())
                                    @foreach($area->territories as $territory)
                                        <span class="badge bg-light text-dark border fw-medium px-2 py-1 me-1">{{ $territory->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($area->is_active)
                                    <span class="badge bg-success-subtle text-success border-0 px-2 py-1 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1 rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                        <i class="ri-edit-2-line fs-16"></i>
                                    </a>
                                    <form action="{{ route('areas.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this area?');">
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
                            <td colspan="4" class="text-center py-5 text-muted">No areas created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $areas->links('pagination::bootstrap-4') }}
        </div>
    </div>
    </div>
@endsection
