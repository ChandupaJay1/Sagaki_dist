@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Product Master - Create</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary">
                <h5 class="card-title mb-0">Product Master - Create</h5>
                <div class="float-end">
                    <button type="reset" form="createProductForm" class="btn btn-dark btn-sm me-1"><i class="ri-refresh-line align-middle me-1"></i> Reset</button>
                    <button type="submit" form="createProductForm" class="btn btn-danger btn-sm me-1"><i class="ri-add-line align-middle me-1"></i> Create</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-sm"><i class="ri-home-line align-middle me-1"></i> Home</a>
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

                <form id="createProductForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            
                            <!-- Product Name & Validation -->
                            <div class="mb-3 row">
                                <label for="name" class="col-sm-4 col-form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    <small class="text-danger">Special Characters Not Allowed.</small>
                                </div>
                            </div>

                            <!-- Product Code & Validation -->
                            <div class="mb-3 row">
                                <label for="code" class="col-sm-4 col-form-label fw-bold">Product Code <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                                    <small class="text-danger">Special Characters Not Allowed.</small>
                                </div>
                            </div>

                            <!-- SKU (Barcode) -->
                            <div class="mb-3 row">
                                <label for="sku" class="col-sm-4 col-form-label fw-bold">SKU (Barcode)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}">
                                </div>
                            </div>

                            <!-- Main Product (select from master only) -->
                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Main Product</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="main_product_id">
                                        <option value="">Select Main Product</option>
                                        @foreach($mainProducts as $mp)
                                            <option value="{{ $mp->id }}" {{ old('main_product_id') == $mp->id ? 'selected' : '' }}>
                                                {{ $mp->name }}{{ $mp->code ? ' (' . $mp->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Product Category -->
                            <div class="mb-3 row">
                                <label for="category" class="col-sm-4 col-form-label fw-bold">Product Category</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        @foreach($categories ?? [] as $cat)
                                            <option value="{{ $cat->name }}" data-code="{{ $cat->code }}" {{ old('category') == $cat->name ? 'selected' : '' }}>
                                                {{ $cat->name }}{{ $cat->code ? ' (' . $cat->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Product Sub Category -->
                            <div class="mb-3 row">
                                <label for="sub_category" class="col-sm-4 col-form-label fw-bold">Product Sub Category</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="sub_category" name="sub_category">
                                        <option value="">Select Sub Category</option>
                                        @foreach($subCategories ?? [] as $sc)
                                            <option value="{{ $sc->name }}" data-code="{{ $sc->code }}" {{ old('sub_category') == $sc->name ? 'selected' : '' }}>
                                                {{ $sc->name }}{{ $sc->code ? ' (' . $sc->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Brand Name -->
                            <div class="mb-3 row">
                                <label for="brand" class="col-sm-4 col-form-label fw-bold">Brand Name</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">Select Brand</option>
                                        @foreach($brands ?? [] as $b)
                                            <option value="{{ $b->name }}" data-code="{{ $b->code }}" {{ old('brand') == $b->name ? 'selected' : '' }}>
                                                {{ $b->name }}{{ $b->code ? ' (' . $b->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Product Model -->
                            <div class="mb-3 row">
                                <label for="model" class="col-sm-4 col-form-label fw-bold">Product Model</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="model" name="model">
                                        <option value="">Select Model</option>
                                        @foreach($models ?? [] as $m)
                                            <option value="{{ $m->name }}" data-code="{{ $m->code }}" {{ old('model') == $m->name ? 'selected' : '' }}>
                                                {{ $m->name }}{{ $m->code ? ' (' . $m->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3 row">
                                <label for="description" class="col-sm-4 col-form-label fw-bold">Description</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <!-- Locations: Floor, Rack, Row, Bin -->
                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold">Floor</label>
                                    <input type="text" class="form-control" name="floor" value="{{ old('floor') }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold">Rack</label>
                                    <input type="text" class="form-control" name="rack" value="{{ old('rack') }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold">Row</label>
                                    <input type="text" class="form-control" name="row" value="{{ old('row') }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold">Bin</label>
                                    <input type="text" class="form-control" name="bin" value="{{ old('bin') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="reorder_point" class="col-sm-4 col-form-label fw-bold">ReOrder Point(Min)</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="reorder_point" name="reorder_point" placeholder="reOrder point" value="{{ old('reorder_point') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="alert_quantity" class="col-sm-4 col-form-label fw-bold">Alert Quantity</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="alert_quantity" name="alert_quantity" placeholder="reOrder point" value="{{ old('alert_quantity') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="location" class="col-sm-4 col-form-label fw-bold">Location</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="location" name="location">
                                        <option value="">-- Select --</option>
                                        @foreach($locations ?? [] as $loc)
                                            <option value="{{ $loc->name }}" data-code="{{ $loc->code }}" {{ (old('location') == $loc->name || $loc->name == 'Main Warehouse') ? 'selected' : '' }}>
                                                {{ $loc->name }}{{ $loc->code ? ' (' . $loc->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="project" class="col-sm-4 col-form-label fw-bold">Project</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="project" name="project">
                                        <option value="">-- Select --</option>
                                        @foreach($projects ?? [] as $prj)
                                            <option value="{{ $prj->name }}" data-code="{{ $prj->code }}" {{ old('project') == $prj->name ? 'selected' : '' }}>
                                                {{ $prj->name }}{{ $prj->code ? ' (' . $prj->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Toggles Grid: Purchase, Sale, Production... -->
                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Purchase</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_purchase" value="1" {{ old('is_purchase') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Production</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_production" value="1" {{ old('is_production') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Stock Report</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_stock_report" value="1" {{ old('is_stock_report') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Sale</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_sale" value="1" {{ old('is_sale') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Serialized</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_serialized" value="1" {{ old('is_serialized') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Price Level</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_price_level" value="1" {{ old('is_price_level') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Qty in Bulk</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="qty_in_bulk" value="{{ old('qty_in_bulk') }}">
                                </div>
                            </div>
                            
                            <hr>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Supplier Warranty</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="supplier_warranty" placeholder="Supplier Warranty" value="{{ old('supplier_warranty') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Customer Warranty</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="customer_warranty" placeholder="Customer Warranty" value="{{ old('customer_warranty') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Vendor</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="vendor_id">
                                        <option value="">-- Select --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Cost <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="cost" value="{{ old('cost', '0.00') }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Unit</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="unit">
                                        <option value="">-- Select --</option>
                                        @foreach($units ?? [] as $u)
                                            <option value="{{ $u->name }}" data-code="{{ $u->code }}" {{ old('unit') == $u->name ? 'selected' : '' }}>
                                                {{ $u->name }}{{ $u->code ? ' (' . $u->code . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Max Sale Price</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="max_sale_price" value="{{ old('max_sale_price', '450000.00') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Min Sale Price</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="min_sale_price" value="{{ old('min_sale_price', '0.00') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Max Whole Sale Price</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="max_wholesale_price" value="{{ old('max_wholesale_price', '0.00') }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Min Whole Sale Price</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="min_wholesale_price" value="{{ old('min_wholesale_price', '0.00') }}">
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Multi Price</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_multi_price" value="1" {{ old('is_multi_price') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Discount %</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control text-end" name="discount_percentage" value="{{ old('discount_percentage', '0') }}">
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Image Upload -->
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-bold">Add Image</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-2">
                                        <label class="input-group-text" for="image_path">Choose File</label>
                                        <label class="form-control text-muted" id="file_name_label">No file chosen</label>
                                        <input type="file" class="d-none" id="image_path" name="image_path" onchange="document.getElementById('file_name_label').textContent = this.files[0].name" accept=".jpg,.jpeg">
                                    </div>
                                    <small class="text-muted d-block">&bull; jpeg,jpg Only</small>
                                    <small class="text-muted d-block">&bull; Max size 5MB</small>
                                    
                                    <div class="mt-3 border p-1 d-inline-block" style="width: 150px; height: 150px; background: #fff;">
                                        <!-- Placeholder for image preview -->
                                        <img src="" id="image_preview" alt="" class="img-fluid w-100 h-100 object-fit-cover" style="display: none;">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Bottom Accounts Section (Full Width or distributed) -->
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">* Inventory Account</label>
                            <select class="form-select" name="inventory_account">
                                <option value="">Select Account</option>
                                @foreach($accounts ?? [] as $acc)
                                    <option value="{{ $acc->code ? ($acc->code.' - '.$acc->name) : $acc->name }}" {{ old('inventory_account') == ($acc->code ? ($acc->code.' - '.$acc->name) : $acc->name) ? 'selected' : '' }}>
                                        {{ $acc->name }}{{ $acc->code ? ' (' . $acc->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">* Cost Account</label>
                            <select class="form-select" name="cost_account">
                                <option value="">Select Account</option>
                                @foreach($accounts ?? [] as $acc)
                                    <option value="{{ $acc->code ? ($acc->code.' - '.$acc->name) : $acc->name }}" {{ old('cost_account') == ($acc->code ? ($acc->code.' - '.$acc->name) : $acc->name) ? 'selected' : '' }}>
                                        {{ $acc->name }}{{ $acc->code ? ' (' . $acc->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">* Sales Account</label>
                            <select class="form-select" name="sales_account">
                                <option value="">Select Account</option>
                                @foreach($accounts ?? [] as $acc)
                                    <option value="{{ $acc->code ? ($acc->code.' - '.$acc->name) : $acc->name }}" {{ old('sales_account') == ($acc->code ? ($acc->code.' - '.$acc->name) : $acc->name) ? 'selected' : '' }}>
                                        {{ $acc->name }}{{ $acc->code ? ' (' . $acc->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Bottom Grid Table Placeholder (per screenshot) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead style="background-color: #074f51; color: white;">
                                    <tr>
                                        <th style="width: 40%;">Item Name</th>
                                        <th style="width: 30%;">Sales Price</th>
                                        <th style="width: 30%;">Barcode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image_path').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image_preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
