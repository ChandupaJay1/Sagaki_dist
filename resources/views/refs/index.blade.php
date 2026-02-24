@extends('layouts.admin')

@section('title', 'Ref Agents')

@section('content')
    <style>
        /* Premium Theme Support */
        .card-header-custom {
            background: linear-gradient(45deg, #4e3abb, #604ae3);
            color: white;
            border-bottom: none;
        }

        /* Dark Mode Consistency */
        html[data-bs-theme="dark"] .card-header-custom {
            background: linear-gradient(45deg, #2a1b7a, #4e3abb) !important;
        }

        html[data-bs-theme="dark"] .bg-light,
        html[data-bs-theme="dark"] .card-footer.bg-light,
        html[data-bs-theme="dark"] .bg-white {
            background-color: #212529 !important;
            color: #ced4da !important;
        }

        html[data-bs-theme="dark"] .table thead th {
            background-color: #2c3035 !important;
            color: #a59cf5 !important;
            border-bottom-color: #373b3e;
        }

        html[data-bs-theme="dark"] .avatar-sm.bg-light,
        html[data-bs-theme="dark"] .avatar-lg.bg-light {
            background-color: #2c3035 !important;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header card-header-custom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 fw-bold text-white"><i class="ri-team-line me-2"></i>Rep Agent List</h4>
                    <a href="{{ route('refs.create') }}" class="btn btn-outline-light fw-bold">
                        <i class="ri-add-line me-1"></i> New Rep Agent
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
                            <i class="ri-check-double-line me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Route</th>
                                    <th>Serial Number</th>
                                    <th>Expires At</th>
                                    <th>Status</th>
                                    <th>Registered At</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($refs as $ref)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center me-3">
                                                    <i class="ri-user-star-line text-primary fs-18"></i>
                                                </div>
                                                <h6 class="mb-0 fs-14 fw-semibold text-reset">{{ $ref->name }}</h6>
                                            </div>
                                        </td>
                                        <td>{{ $ref->email }}</td>
                                        <td>{{ $ref->mobile_number ?? 'N/A' }}</td>
                                        <td>
                                            <form action="{{ route('refs.update-route', $ref->id) }}" method="POST" class="d-inline" id="route-form-ref-{{ $ref->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="route_id" class="form-select form-select-sm" style="width: auto; min-width: 140px;" onchange="this.form.submit()">
                                                    <option value="">No route</option>
                                                    @foreach($routes ?? [] as $r)
                                                        <option value="{{ $r->id }}" {{ ($ref->route_id == $r->id) ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                            <small class="text-muted d-block mt-1">Change route here</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">{{ $ref->serial_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($ref->serial_expires_at)
                                                <span class="badge {{ $ref->serial_expires_at < now() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                                    {{ \Carbon\Carbon::parse($ref->serial_expires_at)->format('Y-m-d') }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ref->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Disconnected</span>
                                            @endif
                                        </td>
                                        <td>{{ $ref->created_at->format('Y-m-d') }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <form action="{{ route('refs.toggle-status', $ref->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($ref->is_active)
                                                        <button type="submit" class="btn btn-sm btn-soft-warning" data-bs-toggle="tooltip" title="Disconnect">
                                                            <i class="ri-shut-down-line"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-soft-success" data-bs-toggle="tooltip" title="Connect">
                                                            <i class="ri-plug-line"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                                <a href="{{ route('refs.edit', $ref->id) }}"
                                                    class="btn btn-sm btn-soft-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('refs.destroy', $ref->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this Ref Agent?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div
                                                class="avatar-lg bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                                                <i class="ri-user-add-line text-muted display-5"></i>
                                            </div>
                                            <h5 class="text-muted mb-1">No Ref Agents Found</h5>
                                            <p class="text-muted mb-3">Get started by registering your first Ref Agent.</p>
                                            <a href="{{ route('refs.create') }}" class="btn btn-sm btn-primary">Create
                                                Ref Agent</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top bg-light text-center">
                    {{ $refs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
