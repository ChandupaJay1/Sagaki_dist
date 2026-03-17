@extends('layouts.admin')

@section('title', 'Invoice - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Invoice</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold">Credit Limit: 0.00</span>
                <span class="text-muted small fw-bold">Rs: 0.00</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-bill-line me-1"></i>Invoice - Create</h5>
                <div class="float-end">
                    <button type="submit" form="createInvoiceForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" form="createInvoiceForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="reset" form="createInvoiceForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
                </div>
            </div>
            <div class="card-body p-3">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="createInvoiceForm" action="{{ route('invoices.store') }}" method="POST">
                    @csrf

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Customer Name <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->company_name ?? $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Site <span class="text-danger">*</span></label>
                            <select name="site" class="form-select form-select-sm">
                                <option value="Main">Main</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Load</label>
                            <select name="load" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <!-- Header Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Address</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="address">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Delivery Destination</label>
                            <textarea name="delivery_destination" class="form-control form-control-sm" rows="2" placeholder="deliver destination">{{ old('delivery_destination') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label small fw-bold mb-0">INV No</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="INV/ 2020/00109" readonly>
                            </div>
                            <div>
                                <label class="form-label small fw-bold mb-0">Date</label>
                                <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Header Row 3 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Villa Type</label>
                            <select name="villa_type" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Meal Plan</label>
                            <select name="meal_plan" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">No of Pax</label>
                            <input type="text" name="no_of_pax" class="form-control form-control-sm" value="{{ old('no_of_pax') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Check In date</label>
                            <input type="date" name="check_in_date" class="form-control form-control-sm" value="{{ old('check_in_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Room Type</label>
                            <select name="room_type" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Check out Date</label>
                            <input type="date" name="check_out_date" class="form-control form-control-sm" value="{{ old('check_out_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-2 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center small">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 15%;">Item Code</th>
                                    <th style="width: 35%;">Description</th>
                                    <th style="width: 10%;">OnHand</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 10%;">Rate(LKR)</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 10%;">Disc %</th>
                                    <th style="width: 10%;">Discount</th>
                                    <th style="width: 10%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><select class="form-select form-select-sm border-0"><option></option></select></td>
                                    <td><input type="text" class="form-control form-control-sm border-0" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end fw-bold" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer Row -->
                    <div class="row g-2 mb-3 justify-content-end align-items-center">
                        <div class="col-md-1 text-end small fw-bold">Qty</div>
                        <div class="col-md-1">
                            <input type="text" class="form-control form-control-sm text-center bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Amount</div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm text-end bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Discount</div>
                        <div class="col-md-1">
                            <input type="text" class="form-control form-control-sm text-end bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Total Amount</div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm text-end bg-light fw-bold" readonly>
                        </div>
                    </div>

                    <!-- Footer Section -->
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="row g-2 mb-2">
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">LKR</span>
                                        <input type="text" class="form-control text-end bg-light" readonly>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm border-danger">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label small fw-bold mb-1">Memo</label>
                                <textarea name="memo" class="form-control form-control-sm" rows="4">{{ old('memo') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card bg-light border-0 shadow-none">
                                <div class="card-body p-2">
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount %</label>
                                            <input type="text" class="form-control form-control-sm text-center">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount</label>
                                            <input type="text" class="form-control form-control-sm text-end">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" class="form-control form-control-sm text-end w-50 bg-white" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold h6 text-primary mb-0">Total</span>
                                        <input type="text" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerSelect = document.querySelector('select[name="customer_id"]');
        const addressTextarea = document.querySelector('textarea[name="address"]');
        const deliveryDestinationTextarea = document.querySelector('textarea[name="delivery_destination"]');

        function fetchCustomerDetails(customerId) {
            if (customerId) {
                fetch(`/api/customers/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea) deliveryDestinationTextarea.value = data.delivery_address || '';
                    })
                    .catch(error => console.error('Error fetching customer details:', error));
            }
        }

        // Standard change event
        customerSelect.addEventListener('change', function () {
            fetchCustomerDetails(this.value);
        });

        // For TomSelect support
        setTimeout(() => {
            if (customerSelect.tomselect) {
                customerSelect.tomselect.on('change', function (value) {
                    fetchCustomerDetails(value);
                });
            }
        }, 500);
    });
</script>
@endpush
