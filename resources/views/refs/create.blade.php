@extends('layouts.admin')

@section('title', 'Register Ref Agent')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="ri-user-add-line me-2"></i>Register New User</h4>
                    <a href="{{ route('refs.index') }}" class="btn btn-sm btn-outline-light"><i class="ri-arrow-left-line me-1"></i> Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('refs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email address" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" id="mobile_number" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Enter mobile number" value="{{ old('mobile_number') }}" required>
                            @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div id="serial-section">
                            <div class="mb-3">
                                <label class="form-label" for="serial_number">Serial Number (Optional)</label>
                                <input type="text" id="serial_number" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" placeholder="Leave blank to auto-generate" value="{{ old('serial_number') }}">
                                <small class="text-muted">If left blank, a random serial number will be generated automatically.</small>
                                @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="alert alert-info" role="alert">
                                <i class="ri-information-line me-2"></i> The <strong>Serial Number</strong> will be used as the default password for the new Rep Agent.
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-success fw-bold px-4" type="submit"><i class="ri-save-line me-1"></i> Register Ref</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
