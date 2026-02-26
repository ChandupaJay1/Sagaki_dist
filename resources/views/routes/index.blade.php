@extends('layouts.admin')

@section('title', 'Distribution Routes')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Distribution Routes</h4>
                <p class="text-muted small mb-0">Assign routes to customers and rep agents for delivery</p>
            </div>
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0 bg-light p-2 rounded-pill px-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Routes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">Routes</h5>
                <div class="flex-shrink-0">
                    <a href="{{ route('routes.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add Route
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
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">Route</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Code</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Area</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Customers</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Rep Agents</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Status</th>
                                <th class="pe-4 border-0 text-muted small fw-bold text-uppercase text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $route)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $route->name }}</div>
                                        @if($route->description)
                                            <div class="text-muted small">{{ Str::limit($route->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($route->code)
                                            <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $route->code }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $route->area ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border-0 px-2 py-1 rounded-pill">{{ $route->customers_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border-0 px-2 py-1 rounded-pill">{{ $route->refs_count }}</span>
                                    </td>
                                    <td>
                                        @if($route->is_active)
                                            <span class="badge bg-success-subtle text-success border-0 px-2 py-1 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1 rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2 align-items-center">
                                            <a href="{{ route('routes.show', $route->id) }}" class="btn btn-sm btn-primary rounded-pill px-3" title="Assign customers & rep agents">
                                                <i class="ri-user-add-line me-1"></i> Manage
                                            </a>
                                            <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                                <i class="ri-edit-2-line fs-16"></i>
                                            </a>
                                            <form action="{{ route('routes.destroy', $route->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this route? Customers and reps will be unassigned.');">
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
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-route-line fs-48 opacity-25"></i>
                                            <p class="mt-2 mb-0">No routes yet. Create a route to assign to customers and rep agents.</p>
                                            <a href="{{ route('routes.create') }}" class="btn btn-primary btn-sm mt-3 rounded-pill">Add first route</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
