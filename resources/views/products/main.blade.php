@extends('layouts.admin')

@section('title', 'Main Products')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Main Products</h4>
            <p class="text-muted small mb-0">List of master products selectable in item creation</p>
        </div>
        <a href="{{ route('main-products.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="ri-add-line me-1"></i>Add Main Product</a>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                <tr>
                    <th class="ps-4 text-muted small fw-bold text-uppercase">Code</th>
                    <th class="text-muted small fw-bold text-uppercase">Product Name</th>
                    <th class="pe-4 text-muted small fw-bold text-uppercase text-end">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $p)
                    <tr>
                        <td class="ps-4">
                            @if($p->code)
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $p->code }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">{{ $p->name }}</td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-light border-0 rounded-circle text-primary" title="Edit">
                                    <i class="ri-edit-2-line fs-16"></i>
                                </a>
                                <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this main product?');">
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
                        <td colspan="3" class="text-center py-5 text-muted">No main products yet. Mark products as Main Product in item create/edit.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
