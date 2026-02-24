@extends('layouts.admin')

@section('title', 'Create Customer Category')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Customer Category - Create</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-tables') }}">Master Tables</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer-categories.index') }}">Customer Categories</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Customer Category - Create</h5>
                <div class="float-end">
                    <button type="reset" form="createCustomerCategoryForm" class="btn btn-dark btn-sm me-1">
                        <i class="ri-refresh-line align-middle me-1"></i> Reset
                    </button>
                    <button type="submit" form="createCustomerCategoryForm" class="btn btn-danger btn-sm me-1">
                        <i class="ri-add-line align-middle me-1"></i> Create
                    </button>
                    <a href="{{ route('customer-categories.index') }}" class="btn btn-success btn-sm">
                        <i class="ri-list-check-2 align-middle me-1"></i> All Categories
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

                <form id="createCustomerCategoryForm" action="{{ route('customer-categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3 row">
                        <label for="name" class="col-sm-3 col-form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="code" class="col-sm-3 col-form-label fw-bold">Short Code</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="e.g. RET, WHO">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-3 col-form-label fw-bold">Active</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-2" for="is_active">Keep this category active</label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

