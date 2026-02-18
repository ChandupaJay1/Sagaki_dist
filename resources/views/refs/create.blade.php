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
                        <div class="mb-3">
                            <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="toggleFields(this.value)">
                                <option value="" selected disabled>Select Role</option>
                                <option value="ref" {{ old('role') == 'ref' ? 'selected' : '' }}>Rep Agent</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div id="password-section" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div id="serial-section" style="display: none;">
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

                        <script>
                            function toggleFields(role) {
                                const passwordSection = document.getElementById('password-section');
                                const serialSection = document.getElementById('serial-section');
                                const passwordInput = document.getElementById('password');

                                if (role === 'admin') {
                                    passwordSection.style.display = 'block';
                                    serialSection.style.display = 'none';
                                    passwordInput.required = true;
                                } else if (role === 'ref') {
                                    passwordSection.style.display = 'none';
                                    serialSection.style.display = 'block';
                                    passwordInput.required = false;
                                    passwordInput.value = '';
                                } else {
                                    passwordSection.style.display = 'none';
                                    serialSection.style.display = 'none';
                                }
                            }

                            // Run on load
                            window.onload = function() {
                                const role = document.getElementById('role').value;
                                if (role) toggleFields(role);
                            };
                        </script>
                        <div class="text-end">
                            <button class="btn btn-success fw-bold px-4" type="submit"><i class="ri-save-line me-1"></i> Register Ref</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
