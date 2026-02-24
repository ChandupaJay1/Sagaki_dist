@extends('layouts.admin')

@section('title', 'Master Tables')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Master Tables</h4>
                <p class="text-muted mb-0">Supporting master data for advanced configuration.</p>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <!-- Customer Category -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('customer-categories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-user-3-line fs-24 text-secondary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Customer Category</h5>
                                <small class="text-muted">Segment your customers</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage customer categories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Unit Master (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-info-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-stack-line fs-24 text-info"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Unit Master</h5>
                            <small class="text-muted">Units & conversions</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Item Category (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-warning-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-price-tag-3-line fs-24 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Item Category</h5>
                            <small class="text-muted">Organize items</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Area (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-map-pin-line fs-24 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Area</h5>
                            <small class="text-muted">Sales areas</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Territory (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-danger-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-community-line fs-24 text-danger"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Territory</h5>
                            <small class="text-muted">Route grouping</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Supplier Category (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-folder-user-line fs-24 text-secondary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Supplier Category</h5>
                            <small class="text-muted">Group suppliers</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Location (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-building-2-line fs-24 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Location</h5>
                            <small class="text-muted">Warehouses / outlets</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

        <!-- Brand Master (Coming Soon) -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 hover-translate border-dashed">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-warning-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                            <i class="ri-vip-diamond-line fs-24 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">Brand Master</h5>
                            <small class="text-muted">Brands listing</small>
                        </div>
                    </div>
                    <span class="badge bg-soft-warning text-warning fw-semibold align-self-start mt-auto">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

