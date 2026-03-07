@extends('layouts.admin')

@section('title', 'Edit Route')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">Edit Route</h4>
                <p class="text-muted small mb-0">Update distribution route details</p>
            </div>
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0 bg-light p-2 rounded-pill px-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('routes.index') }}" class="text-decoration-none">Routes</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h5 class="card-title mb-0 fw-bold text-dark">Route Details</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('routes.update', $route->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-dark">Route Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="name" name="name" value="{{ old('name', $route->name) }}" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-semibold text-dark">Code</label>
                            <input type="text" class="form-control rounded-3" id="code" name="code" value="{{ old('code', $route->code) }}" placeholder="Auto if empty">
                        </div>
                        <div class="col-md-6">
                            <label for="territory_id" class="form-label fw-semibold text-dark">Territory</label>
                            <select class="form-select rounded-3" id="territory_id" name="territory_id">
                                <option value="">— Select Territory —</option>
                                @foreach(\App\Models\Territory::where('is_active', true)->orderBy('name')->get() as $t)
                                    <option value="{{ $t->id }}" data-code="{{ $t->code }}" @selected(old('territory_id', $route->territory_id)==$t->id)>{{ $t->name }}{{ $t->code ? ' (' . $t->code . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label for="description" class="form-label fw-semibold text-dark">Description</label>
                        <textarea class="form-control rounded-3" id="description" name="description" rows="3">{{ old('description', $route->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $route->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="ri-save-line me-1"></i> Update Route
                        </button>
                        <a href="{{ route('routes.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
