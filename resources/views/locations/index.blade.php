@extends('layouts.admin')

@section('title', 'Locations')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Locations</h4>
            <p class="text-muted small mb-0">Warehouses / outlets</p>
        </div>
        <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>Add Location</a>
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
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Code</th>
                        <th class="text-muted small fw-bold text-uppercase">Location Name</th>
                        <th class="text-muted small fw-bold text-uppercase">Contact</th>
                        <th class="text-muted small fw-bold text-uppercase">Vehicle</th>
                        <th class="text-muted small fw-bold text-uppercase">Status</th>
                        <th class="pe-4 text-muted small fw-bold text-uppercase text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                        <tr>
                            <td class="ps-4">
                                @if($location->code)
                                    <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $location->code }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $location->name }}</td>
                            <td class="text-muted">{{ $location->contact_no ?? '—' }}</td>
                            <td class="text-muted">{{ $location->vehicle_no ?? '—' }}</td>
                            <td>
                                @if($location->is_active)
                                    <span class="badge bg-success-subtle text-success border-0 px-2 py-1 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1 rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($location->name !== 'Main Warehouse')
                                        <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                            <i class="ri-edit-2-line fs-16"></i>
                                        </a>
                                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this location?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border-0 rounded-circle text-danger" title="Delete">
                                                <i class="ri-delete-bin-line fs-16"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-light text-muted border fw-normal px-2 py-1">System Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No locations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $locations->links('pagination::bootstrap-4') }}
        </div>
    </div>
    </div>
@endsection

