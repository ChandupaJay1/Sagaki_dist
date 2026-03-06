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

        <!-- Category -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('categories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-info-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-bookmark-3-line fs-24 text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Category</h5>
                                <small class="text-muted">General purpose categories</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage categories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('routes.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-route-line fs-24 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Routes</h5>
                                <small class="text-muted">Distribution routes</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage routes and assignments.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Unit Master -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('units.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage units.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Item Category -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('item-categories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage item categories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Product Sub Category -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('product-sub-categories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-warning-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-price-tag-3-line fs-24 text-warning"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Product Sub Category</h5>
                                <small class="text-muted">Sub-categories</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage sub categories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Area -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('areas.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage areas.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Projects -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('projects.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-briefcase-4-line fs-24 text-secondary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Projects</h5>
                                <small class="text-muted">Project master</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage projects.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Accounts -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('accounts.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-bank-card-line fs-24 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Accounts</h5>
                                <small class="text-muted">Financial accounts</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage accounts.
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Territory -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('territories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage territories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Supplier Category -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('supplier-categories.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage supplier categories.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Location -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('locations.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage locations.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Brand Master -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('brands.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
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
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage brands.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Model Master -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('model-masters.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-slideshow-2-line fs-24 text-secondary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Model Master</h5>
                                <small class="text-muted">Models listing</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage models.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Main Products -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('main-products.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-danger-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-star-line fs-24 text-danger"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Main Products</h5>
                                <small class="text-muted">Master product list</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage main products (code + name).
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Currency -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('currencies.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-info-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-exchange-funds-line fs-24 text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Currency</h5>
                                <small class="text-muted">Currencies</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage currencies.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Terms -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('terms.index') }}" class="text-decoration-none">
                <div class="card h-100 hover-translate">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm bg-success-subtle rounded-3 d-flex align-items-center justify-content-center me-2">
                                <i class="ri-calendar-2-line fs-24 text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold text-dark">Terms</h5>
                                <small class="text-muted">Payment terms</small>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small mt-auto">
                            Click to manage terms.
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
