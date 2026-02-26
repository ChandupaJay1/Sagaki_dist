@extends('layouts.admin')

@section('title', 'Customer Categories')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Customer Categories</h4>
                <p class="text-muted small mb-0">Define segments for your customers (Retail, Wholesale, Key Account, etc.).</p>
            </div>
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0 bg-light p-2 rounded-pill px-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-tables') }}" class="text-decoration-none">Master Tables</a></li>
                    <li class="breadcrumb-item active">Customer Categories</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">Customer Categories</h5>
                <div class="flex-shrink-0">
                    <a href="{{ route('customer-categories.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add New Category
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
                                <th class="border-0 text-muted small fw-bold text-uppercase">Category Name</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Status</th>
                                <th class="pe-4 border-0 text-muted small fw-bold text-uppercase text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                    <td>
                                        @if($category->code)
                                            <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $category->code }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">{{ $category->name }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success-subtle text-success border-0 px-2 py-1 rounded-pill">
                                                <span class="pulse me-1"></span> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1 rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('customer-categories.edit', $category->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                                <i class="ri-edit-2-line fs-16"></i>
                                            </a>
                                            <form action="{{ route('customer-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                                            <p class="mt-2 mb-0">No customer categories created yet.</p>
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
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} records
                        </div>
                        <div>
                            {{ $categories->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

