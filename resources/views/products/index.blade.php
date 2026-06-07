@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Items & Products</h4>
                <p class="text-muted small mb-0">Manage and track your distribution inventory</p>
            </div>
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0 bg-light p-2 rounded-pill px-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h5 class="card-title mb-0 fw-bold text-dark">Product Inventory</h5>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="min-width: 260px;">
                            <span class="input-group-text bg-light border-end-0 border">
                                <i class="ri-search-line text-muted"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 border bg-light"
                                   placeholder="Search by name or code…"
                                   value="{{ $search ?? '' }}">
                            @if(!empty($search))
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary" title="Clear search">
                                    <i class="ri-close-line"></i>
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">Search</button>
                    </form>
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add New Product
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
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">ID</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Image</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Product Name</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Item Code</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">SKU</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Cost (Rs.)</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">Category</th>
                                <th class="pe-4 border-0 text-muted small fw-bold text-uppercase text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">#{{ $product->id }}</td>
                                    <td>
                                        <div class="avatar-sm bg-light rounded-3 p-1 d-flex align-items-center justify-content-center">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="" class="img-fluid rounded-2" style="max-height: 32px;">
                                            @else
                                                <span class="fw-bold text-primary small">
                                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <div class="text-muted small">{{ $product->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $product->code }}</span></td>
                                    <td class="text-muted">{{ $product->sku }}</td>
                                    <td class="fw-bold text-dark">{{ number_format($product->cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border-0 px-2 py-1 rounded-pill">{{ $product->category }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                                <i class="ri-edit-2-line fs-16"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
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
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-48 opacity-25"></i>
                                            <p class="mt-2 mb-0">No products found in the database.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        @if($products->total() > 0)
                            <div class="text-muted small">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} items
                                @if(!empty($search))
                                    for <strong>"{{ $search }}"</strong>
                                @endif
                            </div>
                        @else
                            <div class="text-muted small">No items found{{ !empty($search) ? ' for <strong>"' . e($search) . '"</strong>' : '' }}</div>
                        @endif
                        <div>
                            {{ $products->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
