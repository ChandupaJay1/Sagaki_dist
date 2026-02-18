@extends('layouts.admin')

@section('title', 'Edit Vendor')

@section('content')
    <style>
        .form-label-custom {
            font-weight: 600;
            color: #4e3abb;
            /* Deep Primary */
            margin-bottom: 0;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        /* Dark Mode Support */
        html[data-bs-theme="dark"] .form-label-custom {
            color: #a59cf5;
            /* Lighter Green for Dark Mode */
        }

        html[data-bs-theme="dark"] .card-header-custom {
            background: linear-gradient(45deg, #2a1b7a, #4e3abb);
        }

        html[data-bs-theme="dark"] .section-title {
            color: #a59cf5;
            border-bottom-color: #2c3035;
        }

        html[data-bs-theme="dark"] .input-group-text {
            background-color: #2c3035;
            color: #604ae3;
            border-color: #495057;
        }

        html[data-bs-theme="dark"] .form-control,
        html[data-bs-theme="dark"] .form-select {
            background-color: #212529;
            border-color: #495057;
            color: #fff;
        }

        html[data-bs-theme="dark"] .form-control:focus,
        html[data-bs-theme="dark"] .form-select:focus {
            background-color: #2c3035;
            border-color: #604ae3;
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #604ae3;
            box-shadow: 0 0 0.25rem rgba(96, 74, 227, 0.25);
        }

        .card-header-custom {
            background: linear-gradient(45deg, #4e3abb, #604ae3);
            color: white;
        }

        .section-title {
            color: #604ae3;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            color: #604ae3;
            border-right: 0;
        }

        .form-control,
        .form-select {
            border-left: 0;
        }

        /* Fix overlap since we removed border-left */
        .input-group .form-control:focus,
        .input-group .form-select:focus {
            z-index: 3;
        }

        .input-group-text+.form-control,
        .input-group-text+.form-select {
            border-left: 1px solid #ced4da;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header card-header-custom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0 fw-bold text-white"><i class="ri-edit-2-line me-2"></i>Edit Vendor</h4>
                        <p class="mb-0 text-white-50 fs-13 mt-1">Update details for
                            {{ $vendor->company_name ?? $vendor->name }}
                        </p>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="btn btn-outline-light btn-sm"><i
                            class="ri-arrow-left-line me-1"></i> Back to List</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-5">
                            <!-- Left Column: Basic Information -->
                            <div class="col-lg-6 border-end-lg">
                                <h5 class="section-title"><i class="ri-building-line me-2"></i>General Details</h5>

                                <!-- Code -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="code" class="form-label-custom">Code <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-barcode-line"></i></span>
                                            <input type="text" class="form-control bg-light" id="code" name="code"
                                                value="{{ old('code', $vendor->code) }}" placeholder="VEN/00015">
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Name -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="company_name" class="form-label-custom">Company Name</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                                            <input type="text" class="form-control" id="company_name" name="company_name"
                                                value="{{ old('company_name', $vendor->company_name) }}"
                                                placeholder="Business/Company Name">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Person (Name) -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="name" class="form-label-custom">Contact Person Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-user-star-line"></i></span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $vendor->name) }}" placeholder="Primary Contact Name">
                                        </div>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="category" class="form-label-custom">Category</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
                                            <select class="form-select" id="category" name="category">
                                                <option value="">Select Category</option>
                                                <option value="Wholesale" {{ old('category', $vendor->category) == 'Wholesale' ? 'selected' : '' }}>Wholesale
                                                </option>
                                                <option value="Retail" {{ old('category', $vendor->category) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                                <option value="Distributor" {{ old('category', $vendor->category) == 'Distributor' ? 'selected' : '' }}>Distributor
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Office No -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="main_office_no" class="form-label-custom">Main Office No</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                            <input type="text" class="form-control" id="main_office_no"
                                                name="main_office_no"
                                                value="{{ old('main_office_no', $vendor->main_office_no) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Office No 2 -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="main_office_no_2" class="form-label-custom">Main Office No 2</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                            <input type="text" class="form-control" id="main_office_no_2"
                                                name="main_office_no_2"
                                                value="{{ old('main_office_no_2', $vendor->main_office_no_2) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile No -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="mobile_no" class="form-label-custom">Mobile No</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-smartphone-line"></i></span>
                                            <input type="text" class="form-control" id="mobile_no" name="mobile_no"
                                                value="{{ old('mobile_no', $vendor->mobile_no) }}" placeholder="07XXXXXXXX">
                                        </div>
                                    </div>
                                </div>

                                <!-- Fax -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="fax" class="form-label-custom">Fax</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-printer-line"></i></span>
                                            <input type="text" class="form-control" id="fax" name="fax"
                                                value="{{ old('fax', $vendor->fax) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Email -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="email" class="form-label-custom">Main Email <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email', $vendor->email) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- CC Email -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="cc_email" class="form-label-custom">CC Email</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-mail-send-line"></i></span>
                                            <input type="email" class="form-control" id="cc_email" name="cc_email"
                                                value="{{ old('cc_email', $vendor->cc_email) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Website -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="website" class="form-label-custom">Website</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-global-line"></i></span>
                                            <input type="text" class="form-control" id="website" name="website"
                                                value="{{ old('website', $vendor->website) }}" placeholder="http://www.">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column: Financial & Delivery -->
                            <div class="col-lg-6">
                                <h5 class="section-title"><i class="ri-map-pin-line me-2"></i>Address & Billing</h5>

                                <!-- Address -->
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label for="address" class="form-label-custom pt-1">Address <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="address" name="address" rows="2"
                                            placeholder="Registered Address">{{ old('address', $vendor->address) }}</textarea>
                                    </div>
                                </div>

                                <!-- Deliver To -->
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label for="delivery_address" class="form-label-custom pt-1">Deliver to <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="delivery_address" name="delivery_address"
                                            rows="2"
                                            placeholder="Delivery Address">{{ old('delivery_address', $vendor->delivery_address) }}</textarea>
                                    </div>
                                </div>

                                <!-- Currency -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="currency" class="form-label-custom">Currency <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="ri-money-dollar-circle-line"></i></span>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="LKR" {{ old('currency', $vendor->currency) == 'LKR' ? 'selected' : '' }}>LKR</option>
                                                <option value="USD" {{ old('currency', $vendor->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="account_payables" class="form-label-custom">Account <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-secure-payment-line"></i></span>
                                            <select class="form-select" id="account_payables" name="account_payables">
                                                <option value="Account Payables" {{ old('account_payables', $vendor->account_payables) == 'Account Payables' ? 'selected' : '' }}>
                                                    Account Payables</option>
                                                <option value="Other" {{ old('account_payables', $vendor->account_payables) == 'Other' ? 'selected' : '' }}>Other
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="terms" class="form-label-custom">Terms</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-file-list-2-line"></i></span>
                                            <select class="form-select" id="terms" name="terms">
                                                <option value="Cash Only" {{ old('terms', $vendor->terms) == 'Cash Only' ? 'selected' : '' }}>Cash Only</option>
                                                <option value="30 Days Credit" {{ old('terms', $vendor->terms) == '30 Days Credit' ? 'selected' : '' }}>30 Days Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- VAT No -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="vat_no" class="form-label-custom">VAT No</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-percent-line"></i></span>
                                            <input type="text" class="form-control" id="vat_no" name="vat_no"
                                                value="{{ old('vat_no', $vendor->vat_no) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- SVAT No -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="svat_no" class="form-label-custom">SVAT No</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-percent-line"></i></span>
                                            <input type="text" class="form-control" id="svat_no" name="svat_no"
                                                value="{{ old('svat_no', $vendor->svat_no) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Credit Limit -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="credit_limit" class="form-label-custom">Credit Limit</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">0.00</span>
                                            <input type="number" step="0.01" class="form-control text-end" id="credit_limit"
                                                name="credit_limit"
                                                value="{{ old('credit_limit', $vendor->credit_limit) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact 1 -->
                                <div class="row mb-2 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="contact_person_1" class="form-label-custom">Contact Person 1</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-user-line"></i></span>
                                            <input type="text" class="form-control" id="contact_person_1"
                                                name="contact_person_1"
                                                value="{{ old('contact_person_1', $vendor->contact_person_1) }}"
                                                placeholder="FullName 1">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact 2 -->
                                <div class="row mb-2 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="contact_person_2" class="form-label-custom">Contact 2</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-user-2-line"></i></span>
                                            <input type="text" class="form-control" id="contact_person_2"
                                                name="contact_person_2"
                                                value="{{ old('contact_person_2', $vendor->contact_person_2) }}"
                                                placeholder="FullName 2">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact 3 -->
                                <div class="row mb-2 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="contact_person_3" class="form-label-custom">Contact 3</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
                                            <input type="text" class="form-control" id="contact_person_3"
                                                name="contact_person_3"
                                                value="{{ old('contact_person_3', $vendor->contact_person_3) }}"
                                                placeholder="FullName 3">
                                        </div>
                                    </div>
                                </div>

                                <!-- Print Name On Cheque -->
                                <div class="row mb-2 align-items-center">
                                    <div class="col-sm-4">
                                        <label for="print_name_on_cheque" class="form-label-custom">Print Name On
                                            Cheque</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-bank-card-2-line"></i></span>
                                            <input type="text" class="form-control" id="print_name_on_cheque"
                                                name="print_name_on_cheque"
                                                value="{{ old('print_name_on_cheque', $vendor->print_name_on_cheque) }}"
                                                placeholder="Print Name">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12">
                                <h5 class="section-title"><i class="ri-bank-card-line me-2"></i>Bank Info</h5>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank_name" class="form-label fw-bold text-muted small text-uppercase">Bank
                                    Name</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted"><i class="ri-bank-line"></i></span>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name"
                                        placeholder="ABC Bank" value="{{ old('bank_name', $vendor->bank_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank_branch"
                                    class="form-label fw-bold text-muted small text-uppercase">Branch</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted"><i class="ri-git-branch-line"></i></span>
                                    <input type="text" class="form-control" id="bank_branch" name="bank_branch"
                                        placeholder="Colombo Branch" value="{{ old('bank_branch', $vendor->bank_branch) }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank_account_number"
                                    class="form-label fw-bold text-muted small text-uppercase">Account Number</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted"><i class="ri-hashtag"></i></span>
                                    <input type="text" class="form-control" id="bank_account_number"
                                        name="bank_account_number" placeholder="000-000-0000"
                                        value="{{ old('bank_account_number', $vendor->bank_account_number) }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="reset" class="btn btn-light px-4 me-2">Reset</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="ri-save-line me-1"></i>
                                Update Vendor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection