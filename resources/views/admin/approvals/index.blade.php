@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pending Approvals</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Registered At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center me-3">
                                                <i class="ri-user-line fs-18"></i>
                                            </div>
                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $user->name }}</h6>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_number }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger-subtle text-danger">Admin</span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary">Rep Agent</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <form action="{{ route('approvals.approve', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-soft-success" data-bs-toggle="tooltip" title="Approve">
                                                    <i class="ri-check-line fs-16"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('approvals.reject', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject and remove this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger" data-bs-toggle="tooltip" title="Reject">
                                                    <i class="ri-close-line fs-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No pending approvals found.</td>
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
