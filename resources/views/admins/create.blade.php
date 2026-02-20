@extends('layouts.admin')

@section('title', 'Add Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ri-user-add-line me-2"></i>Register New Admin</h4>
                <a href="{{ route('admins.index') }}" class="btn btn-sm btn-outline-light"><i class="ri-arrow-left-line me-1"></i> Back</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admins.store') }}" method="POST">
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
                    
                    <div class="mb-3">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-end">
                        <button class="btn btn-success fw-bold px-4" type="submit"><i class="ri-save-line me-1"></i> Register Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
