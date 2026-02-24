@extends('layouts.admin')

@section('title', 'Manage Route: ' . $route->name)

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="fw-bold text-dark mb-1">Assign to route: {{ $route->name }}</h4>
                <p class="text-muted small mb-0">Add or remove customers and rep agents for this distribution route</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('routes.index') }}" class="btn btn-light rounded-pill"><i class="ri-arrow-left-line me-1"></i> All Routes</a>
                <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-outline-primary rounded-pill">Edit Route</a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 bg-success-subtle text-success rounded-3 d-flex align-items-center mb-4" role="alert">
        <i class="ri-check-line me-2 fs-18"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">Customers on this route</h5>
                <span class="badge bg-primary rounded-pill">{{ $route->customers->count() }}</span>
            </div>
            <div class="card-body p-4">
                @if($customersNotOnRoute->isNotEmpty())
                    <form action="{{ route('routes.assign-customer', $route->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select customer to add…</option>
                                @foreach($customersNotOnRoute as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} — {{ $c->company_name ?? $c->email }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                @else
                    <p class="text-muted small mb-4">All customers are already assigned to a route. Assign from Customers list or remove from another route first.</p>
                @endif
                @if($route->customers->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($route->customers as $c)
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                                <div>
                                    <span class="fw-semibold">{{ $c->name }}</span>
                                    <span class="text-muted small d-block">{{ $c->company_name ?? $c->email }}</span>
                                </div>
                                <form action="{{ route('routes.unassign-customer', [$route->id, $c->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this customer from route?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No customers assigned. Use the dropdown above to add.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">Rep agents on this route</h5>
                <span class="badge bg-info rounded-pill">{{ $route->refs->count() }}</span>
            </div>
            <div class="card-body p-4">
                @if($refsNotOnRoute->isNotEmpty())
                    <form action="{{ route('routes.assign-ref', $route->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <select name="ref_id" class="form-select" required>
                                <option value="">Select rep agent to add…</option>
                                @foreach($refsNotOnRoute as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }} — {{ $r->email }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                @else
                    <p class="text-muted small mb-4">All rep agents are already assigned. Assign from Rep Agents list or remove from another route first.</p>
                @endif
                @if($route->refs->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($route->refs as $r)
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                                <div>
                                    <span class="fw-semibold">{{ $r->name }}</span>
                                    <span class="text-muted small d-block">{{ $r->email }}</span>
                                </div>
                                <form action="{{ route('routes.unassign-ref', [$route->id, $r->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this rep from route?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No rep agents assigned. Use the dropdown above to add.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
