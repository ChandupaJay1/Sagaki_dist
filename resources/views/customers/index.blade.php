@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">All Customers</h4>
                    </div>
                    <div>
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Register New Customer
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($customer->address, 50) ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-soft-primary"><i class="bx bx-edit"></i></button>
                                            <button class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No customers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection