@extends('layouts.admin')

@section('title', 'Pay Bills - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Pay Bills</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold">Rs: <span id="headerTotalAmount">0.00</span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-money-dollar-circle-line me-1"></i>Pay Bills</h5>
                <div class="float-end">
                    <button type="submit" form="createPayBillForm" name="action" value="pay_and_new" class="btn btn-info btn-sm me-1"><i class="ri-add-circle-fill me-1"></i>Pay And New</button>
                    <button type="submit" form="createPayBillForm" name="action" value="pay_selected" class="btn btn-success btn-sm me-1"><i class="ri-check-fill me-1"></i>Pay Selected Bill</button>
                    <button type="button" class="btn btn-light border btn-sm text-dark me-1"><i class="ri-printer-fill me-1 text-muted"></i>Save And Print</button>
                    <button type="reset" form="createPayBillForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createPayBillForm" action="{{ route('pay-bills.store') }}" method="POST">
                    @csrf

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Vendor Name <span class="text-danger">*</span></label>
                            <select name="vendor_id" id="vendorSelect" class="form-select form-select-sm" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->company_name ?? $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Site <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Site --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ $loc->name == 'Main' ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Voucher No</label>
                            <input type="text" name="voucher_no" class="form-control form-control-sm bg-light" value="{{ $nextVoucherNo }}" readonly>
                        </div>
                    </div>

                    <!-- Header Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Balance</label>
                            <input type="text" id="vendorBalance" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="paymentMethod" class="form-select form-select-sm" required>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Cheque No</label>
                            <input type="text" name="cheque_no" id="chequeNo" class="form-control form-control-sm" placeholder="Cheque No" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Header Row 3 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <textarea name="memo" class="form-control form-control-sm" rows="1" placeholder="Memo"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                            <input type="text" id="lkrTotalAmount" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">PD Cheque Date</label>
                            <input type="date" name="pd_cheque_date" id="pdChequeDate" class="form-control form-control-sm" disabled>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="billsTable" style="border-top:2px solid #3577f1;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="fw-bold py-2">Date Due</th>
                                    <th class="fw-bold py-2">Ref No</th>
                                    <th class="fw-bold py-2">Bill No</th>
                                    <th class="fw-bold py-2">Type</th>
                                    <th class="fw-bold py-2">Amt.Due</th>
                                    <th class="fw-bold py-2">Discount</th>
                                    <th class="fw-bold py-2">Credit Used</th>
                                    <th class="fw-bold py-2" style="width: 150px;">Amt.To Pay</th>
                                </tr>
                            </thead>
                            <tbody id="billsTableBody">
                                <tr class="empty-row">
                                    <td colspan="8" class="py-4 text-muted small italic bg-light">Select a vendor to load outstanding bills.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <!-- Left Panel -->
                        <div class="col-md-7">
                            <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#credits-tab" role="tab" aria-selected="true">Available Credits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#discount-tab" role="tab" aria-selected="false">Discount</a>
                                </li>
                            </ul>
                            <div class="tab-content text-muted p-0">
                                <div class="tab-pane active" id="credits-tab" role="tabpanel">
                                    <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center mb-3 border">
                                        <div>
                                            <p class="mb-2 text-dark small fw-medium">Number of credit available</p>
                                            <p class="mb-0 text-dark small fw-medium">This vendor has credit available <span class="fw-bold ms-5 fs-15" id="availableCreditSpan">0.00</span></p>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm"><i class="ri-bank-card-line me-1"></i>View</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="discount-tab" role="tabpanel">
                                    <div class="bg-light p-3 rounded mb-3 border">
                                        <p class="text-muted small mb-0">No discounts applied.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Credits Table -->
                            <div class="table-responsive mb-3 border rounded">
                                <table class="table table-sm table-bordered mb-0 align-middle text-center" style="border-top:2px solid #3577f1;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="fw-bold py-2 small">Date</th>
                                            <th class="fw-bold py-2 small">Transaction No</th>
                                            <th class="fw-bold py-2 small">Type</th>
                                            <th class="fw-bold py-2 small">Credit Amount</th>
                                            <th class="fw-bold py-2 small">Credit Balance</th>
                                            <th class="fw-bold py-2 small">Amount To Use</th>
                                        </tr>
                                    </thead>
                                    <tbody id="creditsTableBody">
                                        <tr>
                                            <td colspan="6" class="py-3 bg-light text-muted small">No credits available</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-end p-2 border-top bg-white">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="ri-add-circle-fill me-1"></i>Set Credit</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Panel (Totals) -->
                        <div class="col-md-5">
                            <div class="bg-light p-3 rounded mb-3 border">
                                <h6 class="fw-bold mb-3 text-dark fs-14">Total Payment</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2 small text-dark">
                                    <span style="width: 120px;">Amount Due</span>
                                    <span>:</span>
                                    <span id="summaryAmountDue" class="text-end flex-grow-1 fw-medium">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 small text-dark">
                                    <span style="width: 120px;">Payment</span>
                                    <span>:</span>
                                    <span id="summaryPayment" class="text-end flex-grow-1 fw-medium">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 small text-dark">
                                    <span style="width: 120px;">Credit</span>
                                    <span>:</span>
                                    <span id="summaryCredit" class="text-end flex-grow-1 fw-medium">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 small text-dark">
                                    <span style="width: 120px;">Discount</span>
                                    <span>:</span>
                                    <span id="summaryDiscount" class="text-end flex-grow-1 fw-medium">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3 small text-dark fw-bold fs-13 border-top pt-2">
                                    <span style="width: 120px;">Total Payment</span>
                                    <span>:</span>
                                    <span id="summaryTotalPayment" class="text-end flex-grow-1">0.00</span>
                                    <input type="hidden" name="total_amount" id="totalToPayInput" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="d-flex justify-content-end align-items-center gap-2 mt-4 border-top pt-3">
                        <button type="submit" name="action" value="pay_and_new" class="btn btn-info btn-sm"><i class="ri-add-circle-fill me-1"></i>Pay And New</button>
                        <button type="submit" name="action" value="pay_selected" class="btn btn-success btn-sm"><i class="ri-check-fill me-1"></i>Pay Selected Bill</button>
                        <button type="button" class="btn btn-light border btn-sm text-dark"><i class="ri-printer-fill me-1 text-muted"></i>Save And Print</button>
                        <button type="reset" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #billsTable th, #billsTable td { padding: 0.25rem 0.5rem !important; font-size: 0.75rem !important; }
    #billsTable .form-control-sm { padding: 0.1rem 0.3rem !important; font-size: 0.75rem !important; min-height: 26px !important; }
    .bg-soft-secondary { background-color: rgba(235, 237, 240, 0.5); }
    .btn-info { background-color: #0dcaf0; border-color: #0dcaf0; color: #fff; }
    .btn-info:hover { background-color: #0bb5d9; border-color: #0bb5d9; color: #fff; }
    .btn-success { background-color: #0ab39c; border-color: #0ab39c; }
    .btn-warning { background-color: #f7b84b; border-color: #f7b84b; color: #fff; }
    .nav-tabs-custom.nav-success .nav-link.active { color: #0ab39c; }
    .nav-tabs-custom.nav-success .nav-link.active::after { background-color: #0ab39c; }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const vendorSelectWrapper = document.getElementById('vendorSelect');
        const billsTableBody = document.getElementById('billsTableBody');
        const vendorBalanceInput = document.getElementById('vendorBalance');
        const lkrTotalAmountInput = document.getElementById('lkrTotalAmount');
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const chequeNoInput = document.getElementById('chequeNo');
        const pdChequeDateInput = document.getElementById('pdChequeDate');

        // Handle Vendor selection
        const initVendorSelect = () => {
            if (vendorSelectWrapper.tomselect) {
                vendorSelectWrapper.tomselect.on('change', function(value) {
                    fetchOutstandingBills(value);
                });
            } else {
                // Wait for global init or do it manually if needed
                setTimeout(initVendorSelect, 100);
            }
        };
        initVendorSelect();

        // Handle Payment Method change
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'Cheque') {
                chequeNoInput.disabled = false;
                pdChequeDateInput.disabled = false;
            } else {
                chequeNoInput.disabled = true;
                pdChequeDateInput.disabled = true;
                chequeNoInput.value = '';
                pdChequeDateInput.value = '';
            }
        });

        function fetchOutstandingBills(vendorId) {
            if (!vendorId) {
                clearTable();
                return;
            }

            // Show loading
            billsTableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-center bg-light"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading bills...</td></tr>';

            fetch(`/api/vendors/${vendorId}/outstanding-bills`)
                .then(response => response.json())
                .then(data => {
                    if (data.vendor) {
                        vendorBalanceInput.value = parseFloat(data.vendor.credit_limit || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                    }
                    
                    if (data.bills && data.bills.length > 0) {
                        renderBills(data.bills);
                    } else {
                        billsTableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-muted bg-light">No outstanding bills found.</td></tr>';
                        updateTotals();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    billsTableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-danger bg-light">Error loading bills. Please try again.</td></tr>';
                });
        }

        function renderBills(bills) {
            let html = '';
            bills.forEach((bill, index) => {
                const totalAmount = parseFloat(bill.total_amount) || 0;
                html += `
                <tr class="bill-row">
                    <td>${bill.due_date || '—'}</td>
                    <td>${bill.reference_no || '—'}</td>
                    <td>${bill.grn_no || '—'}</td>
                    <td class="text-muted">Bill</td>
                    <td class="text-end">${totalAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td><input type="text" class="form-control form-control-sm text-end bg-light" readonly value="0.00"></td>
                    <td><input type="text" class="form-control form-control-sm text-end bg-light" readonly value="0.00"></td>
                    <td>
                        <input type="hidden" name="items[${index}][grn_id]" value="${bill.id}">
                        <input type="number" name="items[${index}][amount_to_pay]" class="form-control form-control-sm text-end pay-input" 
                               step="any" min="0" max="${totalAmount}" data-due="${totalAmount}" placeholder="0.00">
                    </td>
                </tr>`;
            });
            billsTableBody.innerHTML = html;
            initTableEvents();
            updateTotals();
        }

        function clearTable() {
            billsTableBody.innerHTML = '<tr class="empty-row"><td colspan="8" class="py-4 text-muted small italic bg-light">Select a vendor to load outstanding bills.</td></tr>';
            vendorBalanceInput.value = '0.00';
            updateTotals();
        }

        function initTableEvents() {
            document.querySelectorAll('.pay-input').forEach(input => {
                input.addEventListener('input', updateTotals);
                
                // Allow double click to set full amount
                input.addEventListener('dblclick', function() {
                    this.value = this.dataset.due;
                    updateTotals();
                });
            });
        }

        function updateTotals() {
            let totalDue = 0;
            let totalPay = 0;

            document.querySelectorAll('.bill-row').forEach(row => {
                const payInput = row.querySelector('.pay-input');
                totalDue += parseFloat(payInput.dataset.due) || 0;
                totalPay += parseFloat(payInput.value) || 0;
            });

            document.getElementById('summaryAmountDue').textContent = totalDue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('summaryPayment').textContent = totalPay.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('summaryTotalPayment').textContent = totalPay.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('headerTotalAmount').textContent = totalPay.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('totalToPayInput').value = totalPay.toFixed(2);
            lkrTotalAmountInput.value = totalPay.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    });
</script>
@endpush
