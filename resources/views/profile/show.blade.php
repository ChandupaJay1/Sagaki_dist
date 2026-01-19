@extends('layouts.admin')

@section('title', 'My Account')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card shadow-md border-0 rounded-3">
             <div class="card-header bg-primary text-white py-3">
                <h4 class="card-title mb-0 fw-bold"><i class="ri-account-circle-line me-2"></i>My Account</h4>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-checkbox-circle-line me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                 @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-4 text-primary fw-bold text-uppercase fs-13 border-bottom pb-2">Profile Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <h5 class="mb-4 text-primary fw-bold text-uppercase fs-13 border-bottom pb-2 mt-5">Change Password <small class="text-muted text-lowercase fw-normal ms-2">(Leave blank if not changing)</small></h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                             <label for="current_password" class="form-label fw-semibold">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        <div class="col-md-4">
                            <label for="new_password" class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="col-md-4">
                            <label for="new_password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                        </div>
                    </div>

                    <div class="text-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="ri-save-line me-1"></i> Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
