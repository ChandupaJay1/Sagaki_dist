@extends('layouts.admin')

@section('title', 'Edit Terms')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1">Terms - Edit</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Terms - Edit</h5>
                <div class="float-end">
                    <button type="reset" form="editTermsForm" class="btn btn-dark btn-sm me-1">
                        <i class="ri-refresh-line align-middle me-1"></i> Reset
                    </button>
                    <button type="submit" form="editTermsForm" class="btn btn-danger btn-sm me-1">
                        <i class="ri-save-3-line align-middle me-1"></i> Update
                    </button>
                    <a href="{{ route('terms.index') }}" class="btn btn-success btn-sm">
                        <i class="ri-list-check-2 align-middle me-1"></i> All Terms
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="editTermsForm" action="{{ route('terms.update', $term->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label for="days" class="col-sm-3 col-form-label fw-bold">Days <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="days" name="days" value="{{ old('days', $term->days) }}" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="code" class="col-sm-3 col-form-label fw-bold">Code</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $term->code) }}" placeholder="Auto e.g. T30">
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-3 col-form-label fw-bold">Active</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $term->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="is_active">Keep this terms active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

