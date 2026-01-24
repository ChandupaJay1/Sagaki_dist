@extends('layouts.admin')

@section('title', 'Customers')

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
                    <h4 class="card-title mb-0 fw-bold text-white"><i class="ri-group-line me-2"></i>Customer List</h4>
                    <a href="{{ route('customers.create') }}" class="btn btn-outline-light fw-bold">
                        <i class="ri-add-line me-1"></i> New Customer
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
                                    <th class="ps-4">Code</th>
                                    <th>Company</th>
                                    <th>Contact Person Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $customer->code ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-light rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center me-3">
                                                    <i class="ri-building-line text-primary fs-18"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-14 fw-semibold">
                                                        {{ $customer->company_name ?? 'N/A' }}
                                                    </h6>
                                                    <span class="text-muted fs-12">{{ $customer->category }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->name ?? 'N/A' }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->mobile_no ?? $customer->phone }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="{{ route('customers.edit', $customer->id) }}"
                                                    class="btn btn-sm btn-soft-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
                                        <td colspan="6" class="text-center py-5">
                                            <div
                                                class="avatar-lg bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                                                <i class="ri-user-add-line text-muted display-5"></i>
                                            </div>
                                            <h5 class="text-muted mb-1">No Customers Found</h5>
                                            <p class="text-muted mb-3">Get started by creating your first customer.</p>
                                            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">Create
                                                Customer</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top bg-light text-center">
                    <small class="text-muted">Showing all customers</small>
                </div>
            </div>
        </div>
    </div>
@endsection