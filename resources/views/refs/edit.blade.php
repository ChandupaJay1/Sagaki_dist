@extends('layouts.admin')

@section('title', 'Edit Ref Agent')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 fw-bold"><i class="ri-edit-line me-2"></i>Edit Rep Agent</h4>
                    <a href="{{ route('refs.index') }}" class="btn btn-sm btn-outline-light"><i class="ri-arrow-left-line me-1"></i> Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('refs.update', $ref->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" value="{{ old('name', $ref->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email address" value="{{ old('email', $ref->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" id="mobile_number" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Enter mobile number" value="{{ old('mobile_number', $ref->mobile_number) }}" required>
                            @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="route_id">Distribution Route</label>
                            <select class="form-select" id="route_id" name="route_id">
                                <option value="">No route</option>
                                @foreach($routes ?? [] as $r)
                                    <option value="{{ $r->id }}" data-code="{{ $r->code }}" {{ old('route_id', $ref->route_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}{{ $r->code ? ' (' . $r->code . ')' : '' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Assign or remove route. You can also change this from the rep agent list.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="serial_number">Serial Number</label>
                            <input type="text" id="serial_number" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" placeholder="Enter serial number" value="{{ old('serial_number', $ref->serial_number) }}">
                            @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                             @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary fw-bold px-4" type="submit"><i class="ri-save-line me-1"></i> Update Rep</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
