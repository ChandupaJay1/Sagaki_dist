@extends('layouts.admin')

@section('title', $type . ' Bills - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $type === 'Customer' ? 'Customer Payment' : 'Supplier Payment' }}</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Rs: <span id="headerTotalAmount">0.00</span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-money-dollar-circle-line me-1"></i>{{ $type }} Bills</h5>
                <div class="float-end">
                    <button type="submit" form="createPayBillForm" name="action" value="pay_and_new" class="btn btn-info btn-sm me-1"><i class="ri-add-circle-fill me-1"></i>Pay And New</button>
                    <button type="submit" form="createPayBillForm" name="action" value="pay_selected" class="btn btn-success btn-sm me-1"><i class="ri-check-fill me-1"></i>Pay Selected Bill</button>
                    <button type="submit" form="createPayBillForm" name="action" value="save_and_print" class="btn btn-light border btn-sm text-dark me-1"><i class="ri-printer-fill me-1 text-muted"></i>Save And Print</button>
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
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" id="entityBalance">
                    <span id="creditCount" class="d-none"></span>

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            @if($type === 'Supplier')
                                <label class="form-label small fw-bold mb-1">Paid To <span class="text-danger">*</span></label>
                                <select name="vendor_id" id="vendorSelect" class="form-select form-select-sm" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}">{{ $v->company_name ?? $v->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <label class="form-label small fw-bold mb-1">Received From <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customerSelect" class="form-select form-select-sm" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->company_name ?? $c->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Class</label>
                            <select class="form-select form-select-sm bg-light" disabled>
                                <option value="">Select Class</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Site <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                <option value="">Select Site</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ $loc->name == 'Main' ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                            <select name="account_id" id="account_id" class="form-select form-select-sm" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}" 
                                        {{ (
                                            ($type === 'Supplier' && trim($acc->name) === 'Accounts Payable') || 
                                            ($type === 'Customer' && trim($acc->name) === 'Accounts Receivable')
                                        ) ? 'selected' : '' }}>
                                        {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Header Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Deposit To <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" disabled>
                                <option value="">LKR</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Amount <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 small">LKR</span>
                                <input type="text" id="displayAmount" class="form-control form-control-sm border-start-0 text-end" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small fw-bold mb-1">Ex.Rate</label>
                            <input type="text" class="form-control form-control-sm text-center bg-light" value="1.00" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Pmt.Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="paymentMethod" class="form-select form-select-sm" required>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Cheque No</label>
                            <input type="text" name="cheque_no" id="chequeNo" class="form-control form-control-sm" placeholder="Cheque No" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Cus Pay No</label>
                            <input type="text" name="voucher_no" class="form-control form-control-sm bg-light" value="{{ $nextVoucherNo }}" readonly>
                        </div>
                    </div>

                    <!-- Header Row 3 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <textarea name="memo" class="form-control form-control-sm" rows="1" placeholder="Memo"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Receipt Number</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Receipt Number">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                            <input type="text" id="lkrTotalAmount" class="form-control form-control-sm bg-light text-end" readonly placeholder="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Rep <span class="text-danger">*</span></label>
                            <select name="rep_id" class="form-select form-select-sm">
                                <option value="">Select Rep</option>
                                @foreach($reps ?? [] as $rep)
                                    <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small fw-bold mb-1">Pmt.Type</label>
                            <select class="form-select form-select-sm bg-light" disabled>
                                <option value="">Select Pmt.Type</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small fw-bold mb-1">Cheque Date</label>
                            <input type="date" name="pd_cheque_date" id="pdChequeDate" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small fw-bold mb-1">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Job No</label>
                            <select class="form-select form-select-sm bg-light" disabled>
                                <option value="">Select Job No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm mb-0 align-middle text-center" id="billsTable">
                            <thead>
                                <tr>
                                    <th class="fw-bold py-2" style="width: 40px;"><input type="checkbox" class="form-check-input" id="selectAllBills"></th>
                                    <th class="fw-bold py-2">Date</th>
                                    <th class="fw-bold py-2">Type</th>
                                    <th class="fw-bold py-2">Number</th>
                                    <th class="fw-bold py-2">Orig.Amt.</th>
                                    <th class="fw-bold py-2">Amt.Due</th>
                                    <th class="fw-bold py-2">Discount</th>
                                    <th class="fw-bold py-2" style="width: 150px;">New Payment</th>
                                </tr>
                            </thead>
                            <tbody id="billsTableBody">
                                <tr class="empty-row">
                                    <td colspan="9" class="py-4 text-muted small italic bg-light">Select a {{ $type === 'Supplier' ? 'vendor' : 'customer' }} to load outstanding bills.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <!-- Left Panel: Tabs -->
                        <div class="col-md-7">
                            <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#credits-tab" role="tab" aria-selected="true">Available Credits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#discount-tab" role="tab" aria-selected="false">Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#credit-card-tab" role="tab" aria-selected="false">Credit Card</a>
                                </li>
                            </ul>
                            <div class="tab-content text-muted p-0">
                                <div class="tab-pane active" id="credits-tab" role="tabpanel">
                                    <div class="credit-alert-box d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <p class="mb-2 text-dark small fw-medium">Number of credit available: <span id="creditCount">0</span></p>
                                            <p class="mb-0 text-dark small fw-medium">This {{ $type === 'Supplier' ? 'vendor' : 'customer' }} has credit available <span class="fw-bold ms-5 fs-15" id="availableCreditSpan">0.00</span></p>
                                        </div>
                                        <button type="button" id="viewCreditsBtn" class="btn btn-primary btn-sm"><i class="ri-bank-card-line me-1"></i>View Credits</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="discount-tab" role="tabpanel">
                                    <div class="bg-light p-3 rounded mb-3 border">
                                        <p class="text-muted small mb-0">No discounts applied.</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="credit-card-tab" role="tabpanel">
                                    <div class="bg-light p-3 rounded mb-3 border">
                                        <p class="text-muted small mb-0">No credit card info.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Panel: Amount for Select Invoice -->
                        <div class="col-md-5">
                            <div class="bg-light p-3 rounded mb-3 border">
                                <h6 class="fw-bold mb-3 text-dark fs-14">Amount for Select Invoice</h6>
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-4">
                                        <label class="form-label small mb-0">Total Due</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" id="summaryAmountDue" class="form-control form-control-sm text-end bg-light fw-bold" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-4">
                                        <label class="form-label small mb-0">Applied Credit</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" id="summaryCredit" class="form-control form-control-sm text-end bg-light fw-bold text-info" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-4">
                                        <label class="form-label small mb-0">Cash Payment</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" id="summaryPayment" class="form-control form-control-sm text-end bg-light fw-bold text-success" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-0 border-top pt-2 mt-2">
                                    <div class="col-4">
                                        <label class="form-label small mb-0 fw-bold">Total Payment</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" id="summaryTotalPayment" class="form-control form-control-sm text-end bg-light fw-bold text-primary" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-0 d-none">
                                    <div class="col-4">
                                        <label class="form-label small mb-0">Discount</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" id="summaryDiscount" class="form-control form-control-sm text-end bg-light" readonly value="0.00">
                                    </div>
                                </div>
                                <input type="hidden" name="total_amount" id="totalToPayInput" value="0">
                            </div>
                        </div>
                    </div>

                    <!-- Full Width Credits Table (Blue Circled in Image) -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive mb-3 border rounded shadow-sm">
                                <table class="table table-sm table-bordered mb-0 align-middle text-center" style="border-top:2px solid #3577f1;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="fw-bold py-2 small" style="width: 40px;"><input type="checkbox" class="form-check-input" id="selectAllCredits"></th>
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
                                            <td colspan="7" class="py-3 bg-light text-muted small">No credits available</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="appliedCreditsSummary" class="p-2 bg-light border-top d-none">
                                    <span class="small text-muted"><i class="ri-checkbox-circle-line text-success me-1"></i> <span id="appliedCreditsCount">0</span> credits applied.</span>
                                    <button type="button" class="btn btn-link btn-sm p-0 ms-2 text-decoration-none" id="clearCreditsBtn">Clear All</button>
                                </div>
                                <div class="text-end p-2 border-top bg-white">
                                    <button type="button" id="setCreditBtn" class="btn btn-primary btn-sm"><i class="ri-settings-3-line me-1"></i>Set Credit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="d-flex justify-content-end align-items-center gap-2 mt-2 border-top pt-3">
                        <button type="submit" name="action" value="pay_and_new" class="btn btn-info btn-sm"><i class="ri-add-circle-fill me-1"></i>Save & New</button>
                        <button type="submit" name="action" value="pay_selected" class="btn btn-success btn-sm"><i class="ri-save-line me-1"></i>Save & Close</button>
                        <button type="submit" name="action" value="save_and_print" class="btn btn-light border btn-sm text-dark"><i class="ri-printer-fill me-1 text-muted"></i>Save & Print</button>
                        <button type="reset" class="btn btn-warning btn-sm text-white"><i class="ri-refresh-line me-1"></i>Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium UI Enhancements */
    :root {
        --sg-primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --sg-surface-light: #ffffff;
        --sg-border-light: #e2e8f0;
        --sg-text-muted: #64748b;
    }

    /* Card & Header Styling */
    .card {
        border: none !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    .card-header {
        background: #f8fafc !important;
        border-bottom: 1px solid var(--sg-border-light) !important;
        padding: 1rem 1.25rem !important;
    }

    .card-title {
        font-weight: 700 !important;
        color: #1e293b !important;
        letter-spacing: -0.02em;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 0.75rem !important;
        margin-bottom: 0.4rem !important;
    }

    .form-control, .form-select {
        border-radius: 10px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem !important;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    }

    .form-control:read-only, .form-control:disabled {
        background-color: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        color: #64748b !important;
    }

    /* Table Styling - Professional Look */
    .table-responsive {
        border-radius: 12px !important;
        overflow: hidden;
        border: 1px solid #e2e8f0 !important;
    }

    .table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 0.7rem !important;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 0.75rem !important;
    }

    .table tbody td {
        padding: 0.75rem !important;
        vertical-align: middle !important;
        color: #334155 !important;
        font-size: 0.85rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .bill-row:hover td, .credit-row:hover td {
        background-color: rgba(53, 119, 241, 0.05) !important;
    }

    /* Summary Panel */
    .summary-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
    }

    .total-row {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-top: 1rem;
    }

    /* Buttons Modernization */
    .btn-sm {
        border-radius: 8px !important;
        padding: 0.4rem 0.8rem !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
    }

    .btn-info { background-color: #0ea5e9 !important; border: none !important; }
    .btn-success { background-color: #10b981 !important; border: none !important; }
    .btn-warning { background-color: #f59e0b !important; border: none !important; color: white !important; }
    
    .btn:hover {
        transform: translateY(-1px);
        filter: brightness(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Dark Mode Support (using Layout's theme attribute) */
    [data-bs-theme="dark"] .card { background-color: #1e293b !important; border: 1px solid #334155 !important; }
    [data-bs-theme="dark"] .card-header { background-color: #0f172a !important; border-bottom: 1px solid #334155 !important; }
    [data-bs-theme="dark"] .card-title { color: #f1f5f9 !important; }
    [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select {
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    [data-bs-theme="dark"] .form-control:read-only { background-color: #1e293b !important; color: #94a3b8 !important; }
    [data-bs-theme="dark"] .table thead th { background-color: #0f172a !important; color: #94a3b8 !important; border-bottom-color: #334155 !important; }
    [data-bs-theme="dark"] .table tbody td { color: #cbd5e1 !important; border-bottom-color: #334155 !important; }
    [data-bs-theme="dark"] .summary-box { background: #0f172a !important; border-color: #334155 !important; }
    [data-bs-theme="dark"] .total-row { background: #1e293b !important; color: white !important; }
    [data-bs-theme="dark"] .text-dark { color: #f1f5f9 !important; }
    [data-bs-theme="dark"] .bg-light { background-color: #0f172a !important; color: #94a3b8 !important; }

    /* Additional Refinements */
    .credit-alert-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    [data-bs-theme="dark"] .credit-alert-box {
        background: rgba(20, 83, 45, 0.2);
        border-color: rgba(34, 197, 94, 0.3);
    }

    .nav-tabs-custom .nav-link {
        font-weight: 600;
        border-radius: 8px 8px 0 0;
        padding: 0.6rem 1.2rem;
        border: none;
        color: var(--sg-text-muted);
    }

    .nav-tabs-custom .nav-link.active {
        background: white;
        color: #4f46e5 !important;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.03);
    }

    [data-bs-theme="dark"] .nav-tabs-custom .nav-link.active {
        background: #1e293b;
        color: #818cf8 !important;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const type = "{{ $type }}";
        const entitySelectWrapper = document.getElementById(type === 'Supplier' ? 'vendorSelect' : 'customerSelect');
        const billsTableBody = document.getElementById('billsTableBody');
        const entityBalanceInput = document.getElementById('entityBalance');
        const displayAmountInput = document.getElementById('displayAmount');
        const lkrTotalAmountInput = document.getElementById('lkrTotalAmount');
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const chequeNoInput = document.getElementById('chequeNo');
        const pdChequeDateInput = document.getElementById('pdChequeDate');
        const availableCreditSpan = document.getElementById('availableCreditSpan');
        const creditCountSpan = document.getElementById('creditCount');
        const creditsTableBody = document.getElementById('creditsTableBody');
        const viewCreditsBtn = document.getElementById('viewCreditsBtn');
        const appliedCreditsSummary = document.getElementById('appliedCreditsSummary');
        const appliedCreditsCount = document.getElementById('appliedCreditsCount');
        const clearCreditsBtn = document.getElementById('clearCreditsBtn');
        const setCreditBtn = document.getElementById('setCreditBtn');

        let cachedCredits = [];

        // ── Debounce utility ──────────────────────────────────────────────────
        function debounce(fn, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        // ── Handle Set Credit Button (Matching Algorithm) ──────────────────────
        setCreditBtn.addEventListener('click', function() {
            // 1. Identify Credit Sources & Initial States
            let creditSources = [];
            document.querySelectorAll('.credit-row').forEach(row => {
                const cb = row.querySelector('.credit-checkbox');
                const useInput = row.querySelector('.credit-use-input');
                const balanceCell = row.querySelector('.remaining-balance-cell');
                const amountUsedHidden = row.querySelector('.credit-amount-used-hidden');
                const initialBalance = parseFloat(balanceCell.dataset.initial) || 0;

                if (cb.checked) {
                    creditSources.push({
                        row: row,
                        input: useInput,
                        hidden: amountUsedHidden,
                        cell: balanceCell,
                        initial: initialBalance,
                        available: initialBalance,
                        used: 0
                    });
                } else {
                    // Reset UI for unchecked rows
                    if(useInput) useInput.value = '0.00';
                    if (amountUsedHidden) amountUsedHidden.value = '0.00';
                    balanceCell.textContent = initialBalance.toLocaleString(undefined, {minimumFractionDigits: 2});
                }
            });

            if (creditSources.length === 0) {
                alert('Please select at least one credit record with a balance first.');
                return;
            }

            // 2. Identify Targets & Allocate sequentially
            const checkedBills = document.querySelectorAll('.bill-checkbox:checked');
            if (checkedBills.length === 0) {
                alert('Please select at least one bill first.');
                return;
            }

            let creditIdx = 0;
            checkedBills.forEach(cb => {
                const row = cb.closest('tr');
                const payInput = row.querySelector('.pay-input');
                const creditHidden = row.querySelector('.credit-used-hidden');
                const amtDue = parseFloat(payInput.dataset.due) || 0;
                let billAllocatedFromCredit = 0;

                // Sequentially consume from credit pool
                while (billAllocatedFromCredit < amtDue && creditIdx < creditSources.length) {
                    let source = creditSources[creditIdx];
                    let needed = amtDue - billAllocatedFromCredit;
                    let take = Math.min(needed, source.available);
                    
                    billAllocatedFromCredit += take;
                    source.available -= take;
                    source.used += take;

                    if (source.available <= 0.005) creditIdx++;
                }

                // Update bill fields
                if(creditHidden) creditHidden.value = billAllocatedFromCredit.toFixed(2);
                const currentVal = parseFloat(payInput.value) || 0;
                if(payInput) payInput.value = Math.max(currentVal, billAllocatedFromCredit).toFixed(2);
            });

            // 3. Update Credit Row UI (Dynamic Balance Deduction)
            creditSources.forEach(source => {
                // Update inputs
                if(source.input) source.input.value = source.used.toFixed(2);
                if (source.hidden) source.hidden.value = source.used.toFixed(2);
                
                // DYNAMIC SUBTRACTION: Update the "Credit Balance" text node
                const remaining = source.initial - source.used;
                if(source.cell) source.cell.textContent = remaining.toLocaleString(undefined, {minimumFractionDigits: 2});
            });

            // 4. Trigger Recalculation (Sync summary boxes & header)
            updateTotals(false, false);
            
            // Visual feedback
            const originalHtml = this.innerHTML;
            if(this) this.innerHTML = '<i class="ri-check-line me-1"></i>Credit Applied';
            if(this) this.classList.remove('btn-primary');
            if(this) this.classList.add('btn-success');
            
            setTimeout(() => {
                this.innerHTML = originalHtml;
                if(this) this.classList.remove('btn-success');
                if(this) this.classList.add('btn-primary');
            }, 2000);
        });

        // Handle Clear Credits
        clearCreditsBtn.addEventListener('click', function() {
            document.querySelectorAll('.credit-checkbox').forEach(cb => {
                cb.checked = false;
            });
            updateTotals();
        });

        // Handle Entity selection
        const initEntitySelect = () => {
            if (entitySelectWrapper.tomselect) {
                entitySelectWrapper.tomselect.on('change', function(value) {
                    fetchOutstandingBills(value);
                });
            } else {
                setTimeout(initEntitySelect, 100);
            }
        };
        initEntitySelect();

        // Handle View Credits button
        viewCreditsBtn.addEventListener('click', function() {
            renderCredits(cachedCredits);
            // Scroll to credits table
            creditsTableBody.closest('.table-responsive').scrollIntoView({ behavior: 'smooth' });
        });

        // Handle main Amount input — debounced waterfall distribution
        // When the user changes the global amount, redistribute across all checked rows.
        const debouncedDistribute = debounce(function() {
            distributeWaterfall();
        }, 200);
        displayAmountInput.addEventListener('input', debouncedDistribute);
        displayAmountInput.addEventListener('change', debouncedDistribute);

        // Handle Payment Method change
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'Cheque') {
                chequeNoInput.disabled = false;
                pdChequeDateInput.disabled = false;
            } else {
                chequeNoInput.disabled = true;
                pdChequeDateInput.disabled = true;
                if(chequeNoInput) chequeNoInput.value = '';
                if(pdChequeDateInput) pdChequeDateInput.value = '';
            }
        });

        // Form Validation before submission
        document.getElementById('createPayBillForm').addEventListener('submit', function(e) {
            const entityId = entitySelectWrapper.value;
            const totalPay = parseFloat(document.getElementById('totalToPayInput').value) || 0;
            
            // Check if any credit is applied by looking at the credit table inputs
            const totalCreditUsed = Array.from(document.querySelectorAll('.credit-amount-used-hidden'))
                                        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

            if (!entityId) {
                e.preventDefault();
                alert(`Please select a ${type === 'Supplier' ? 'Vendor' : 'Customer'} first.`);
                return false;
            }

            // USER REQUIREMENT 2: Allow saving with 0.00 amount if credit is selected
            if (totalPay <= 0 && totalCreditUsed <= 0) {
                e.preventDefault();
                alert('Please enter a payment amount or select a credit to use.');
                return false;
            }
        });

        function fetchOutstandingBills(entityId) {
            if (!entityId) {
                clearTable();
                return;
            }

            if(billsTableBody) billsTableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center bg-light"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</td></tr>';

            const baseUrl = "{{ url('/') }}";
            const endpoint = type === 'Supplier' 
                ? `${baseUrl}/api/vendors/${entityId}/outstanding-bills` 
                : `${baseUrl}/api/customers/${entityId}/outstanding-invoices`;

            fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
                }
            })
                .then(async response => {
                    if (!response.ok) {
                        const errText = await response.text();
                        console.error('API Error Response:', errText);
                        throw new Error(`HTTP error! status: ${response.status} - ${errText.substring(0, 100)}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const entity = type === 'Supplier' ? data.vendor : data.customer;
                    const items = type === 'Supplier' ? data.bills : data.invoices;

                    if (entity) {
                        if (entityBalanceInput) {
                            if(entityBalanceInput) entityBalanceInput.value = parseFloat(entity.credit_limit || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                        }
                    }
                    
                    cachedCredits = data.credits || [];
                    let totalCredit = cachedCredits.reduce((sum, c) => sum + parseFloat(c.total_amount), 0);
                    
                    if (availableCreditSpan) {
                        if(availableCreditSpan) availableCreditSpan.textContent = totalCredit.toLocaleString(undefined, {minimumFractionDigits: 2});
                    }
                    if (creditCountSpan) {
                        if(creditCountSpan) creditCountSpan.textContent = cachedCredits.length;
                    }
                    
                    // Automatically render credits so they appear in the table immediately
                    renderCredits(cachedCredits);

                    if (items && items.length > 0) {
                        renderBills(items);
                    } else {
                        if (billsTableBody) {
                            if(billsTableBody) billsTableBody.innerHTML = `<tr><td colspan="9" class="py-4 text-muted bg-light">No outstanding ${type === 'Supplier' ? 'bills' : 'invoices'} found.</td></tr>`;
                        }
                        updateTotals();
                    }
                })
                .catch(error => {
                    console.error('Full Error:', error);
                    alert('System Error: ' + error.message);
                    if(billsTableBody) billsTableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-danger bg-light">Error loading data. Please try again.</td></tr>';
                });
        }

        function renderCredits(credits) {
            if (!credits || credits.length === 0) {
                if(creditsTableBody) creditsTableBody.innerHTML = '<tr><td colspan="7" class="py-3 bg-light text-muted small">No credits available</td></tr>';
                return;
            }

            let html = '';
            credits.forEach((credit, index) => {
                const amount = parseFloat(credit.total_amount) || 0;
                // Use the explicit type label from API: "Return" or "Payment"
                const typeLabel = credit.type || 'Return';
                // Use ref_no (unified field from API) with fallback to return_no for legacy data
                const refNo = credit.ref_no || credit.return_no || '—';
                // Style badge based on type
                const badgeClass = typeLabel === 'Payment'
                    ? 'bg-warning-subtle text-warning'
                    : 'bg-success-subtle text-success';

                html += `
                <tr class="credit-row">
                    <td>
                        <input type="checkbox" class="form-check-input credit-checkbox" data-amount="${amount}" name="applied_credits[${index}][id]" value="${credit.id}">
                        <input type="hidden" name="applied_credits[${index}][type]" value="${typeLabel}">
                        <input type="hidden" name="applied_credits[${index}][amount_to_use]" class="credit-amount-used-hidden" value="0.00">
                    </td>
                    <td class="small">${credit.date || '—'}</td>
                    <td class="small text-primary fw-medium">${refNo}</td>
                    <td class="small"><span class="badge ${badgeClass} small">${typeLabel}</span></td>
                    <td class="text-end small">${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td class="text-end small remaining-balance-cell" data-initial="${amount}">${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-end credit-use-input" 
                               step="any" min="0" max="${amount}" data-available="${amount}" placeholder="0.00" readonly>
                    </td>
                </tr>`;
            });
            if(creditsTableBody) creditsTableBody.innerHTML = html;
            initCreditEvents();
        }

        function renderBills(items) {
            let html = '';
            items.forEach((item, index) => {
                const dueAmount = parseFloat(item.total_amount) || 0;
                const origAmount = parseFloat(item.original_amount) || dueAmount;
                const billNo = type === 'Supplier' ? item.grn_no : item.invoice_no;
                const idField = type === 'Supplier' ? 'grn_id' : 'invoice_id';
                // Use the type label from API: "GRN" for suppliers, "Invoice" for customers
                const typeLabel = item.type || (type === 'Supplier' ? 'GRN' : 'Invoice');

                if (item.is_return) {
                    html += `
                    <tr class="bill-row return-row bg-danger-subtle bg-opacity-10">
                        <td><input type="checkbox" class="form-check-input bill-checkbox" data-due="${dueAmount}" data-is-return="true"></td>
                        <td>${item.date || '—'}</td>
                        <td><span class="badge bg-danger text-white small">${typeLabel}</span></td>
                        <td>${billNo || '—'}</td>
                        <td class="text-end orig-amt-cell text-danger">${origAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-end amt-due-cell text-danger">${dueAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td><input type="text" class="form-control form-control-sm text-end bg-light" readonly value="0.00"></td>
                        <td>
                            <input type="hidden" name="applied_credits[r_${index}][id]" value="${item.id}">
                            <input type="hidden" name="applied_credits[r_${index}][type]" value="Return">
                            <input type="hidden" name="applied_credits[r_${index}][amount_to_use]" class="credit-amount-used-hidden pay-input-hidden" value="0.00">
                            <input type="number" class="form-control form-control-sm text-end pay-input text-danger fw-bold" 
                                   step="any" max="0" min="${dueAmount}" data-due="${dueAmount}" placeholder="0.00">
                        </td>
                    </tr>`;
                } else {
                    html += `
                    <tr class="bill-row">
                        <td><input type="checkbox" class="form-check-input bill-checkbox" data-due="${dueAmount}"></td>
                        <td>${item.date || '—'}</td>
                        <td><span class="badge bg-primary-subtle text-primary small">${typeLabel}</span></td>
                        <td>${billNo || '—'}</td>
                        <td class="text-end orig-amt-cell">${origAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-end amt-due-cell">${dueAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td><input type="text" class="form-control form-control-sm text-end bg-light" readonly value="0.00"></td>
                        <td>
                            <input type="hidden" name="items[${index}][${idField}]" value="${item.id}">
                            <input type="hidden" name="items[${index}][credit_used]" class="credit-used-hidden" value="0.00">
                            <input type="number" name="items[${index}][amount_to_pay]" class="form-control form-control-sm text-end pay-input" 
                                   step="any" min="0" max="${dueAmount}" data-due="${dueAmount}" placeholder="0.00">
                        </td>
                    </tr>`;
                }
            });
            if(billsTableBody) billsTableBody.innerHTML = html;
            initTableEvents();
            updateTotals();
        }

        function clearTable() {
            if(billsTableBody) billsTableBody.innerHTML = `<tr class="empty-row"><td colspan="9" class="py-4 text-muted small italic bg-light">Select a ${type === 'Supplier' ? 'vendor' : 'customer'} to load outstanding bills.</td></tr>`;
            if(creditsTableBody) creditsTableBody.innerHTML = '<tr><td colspan="7" class="py-3 bg-light text-muted small">No credits available</td></tr>';
            if(availableCreditSpan) availableCreditSpan.textContent = '0.00';
            cachedCredits = [];
            if(displayAmountInput) displayAmountInput.value = '0.00';
            updateTotals();
        }

        // ── Waterfall Distribution Algorithm ──────────────────────────────────
        // Reads the global Amount field, then cascades downward through checked
        // bill rows in DOM order, allocating min(remaining pool, row amt due)
        // to each row's New Payment input. Any surplus is tracked for the
        // overpayment display.
        function distributeWaterfall() {
            const rawAmount = displayAmountInput.value.replace(/,/g, '');
            let remainingPool = parseFloat(rawAmount) || 0;

            // Offset the cash pool by adding the absolute value of any selected returns
            let returnOffsets = 0;
            document.querySelectorAll('.bill-row.return-row').forEach(row => {
                const cb = row.querySelector('.bill-checkbox');
                const payInput = row.querySelector('.pay-input');
                if (cb.checked) {
                    returnOffsets += Math.abs(parseFloat(payInput.value) || 0);
                }
            });
            remainingPool += returnOffsets;

            document.querySelectorAll('.bill-row:not(.return-row)').forEach(row => {
                const cb = row.querySelector('.bill-checkbox');
                const payInput = row.querySelector('.pay-input');
                const amtDue = parseFloat(payInput.dataset.due) || 0;

                if (cb.checked) {
                    // Allocate the lesser of the bill's due amount or the remaining pool
                    const allocated = Math.min(amtDue, Math.max(0, remainingPool));
                    if(payInput) payInput.value = allocated.toFixed(2);
                    remainingPool -= allocated;
                } else {
                    if(payInput) payInput.value = '0.00';
                }
            });

            updateTotals(false, false);
        }

        function initTableEvents() {
            const selectAllBills = document.getElementById('selectAllBills');
            
            selectAllBills.checked = false;

            // ── Select All checkbox: toggle all then waterfall distribute ──────
            selectAllBills.addEventListener('change', function() {
                document.querySelectorAll('.bill-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                distributeWaterfall();
            });

            // ── Individual bill checkbox: toggle then waterfall distribute ─────
            document.querySelectorAll('.bill-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    // If unchecked, zero out the row immediately before redistributing
                    if (!this.checked) {
                        const payInput = this.closest('tr').querySelector('.pay-input');
                        if(payInput) payInput.value = '0.00';
                        const hiddenInput = this.closest('tr').querySelector('.pay-input-hidden');
                        if(hiddenInput) hiddenInput.value = '0.00';
                    } else if (this.dataset.isReturn === 'true') {
                        // Auto-fill full negative amount for returns when checked
                        const payInput = this.closest('tr').querySelector('.pay-input');
                        if(payInput) payInput.value = parseFloat(this.dataset.due).toFixed(2);
                        const hiddenInput = this.closest('tr').querySelector('.pay-input-hidden');
                        if(hiddenInput) hiddenInput.value = Math.abs(parseFloat(this.dataset.due)).toFixed(2);
                    }

                    distributeWaterfall();

                    // Sync Select All state
                    const total = document.querySelectorAll('.bill-checkbox').length;
                    const checked = document.querySelectorAll('.bill-checkbox:checked').length;
                    selectAllBills.checked = (total > 0 && checked === total);
                });
            });

            // ── Manual override on New Payment fields ─────────────────────────
            // Debounced handler validates the manual entry against:
            //   1. The row's Amt.Due ceiling
            //   2. The remaining global pool (global amount minus other rows)
            const debouncedManualOverride = debounce(function(input) {
                const row = input.closest('tr');
                const cb = row.querySelector('.bill-checkbox');
                let val = parseFloat(input.value) || 0;
                const amtDue = parseFloat(input.dataset.due) || 0;

                // Auto-check if a positive value is entered
                if (val > 0) {
                    cb.checked = true;
                } else {
                    cb.checked = false;
                }

                // Guard 1: Cannot exceed the row's outstanding amount
                if (val > amtDue) {
                    val = amtDue;
                    if(input) input.value = val.toFixed(2);
                }

                // Guard 2: Cannot cause total to overflow the global amount
                const rawGlobal = displayAmountInput.value.replace(/,/g, '');
                const globalAmount = parseFloat(rawGlobal) || 0;

                // Sum all OTHER checked rows' pay-input values (invoices subtract from pool, returns add to pool)
                let otherInvoicesTotal = 0;
                let otherReturnsTotal = 0;
                document.querySelectorAll('.bill-row').forEach(otherRow => {
                    if (otherRow === row) return;
                    const otherCb = otherRow.querySelector('.bill-checkbox');
                    const otherPay = otherRow.querySelector('.pay-input');
                    if (otherCb.checked) {
                        const val = parseFloat(otherPay.value) || 0;
                        if (val > 0) otherInvoicesTotal += val;
                        else otherReturnsTotal += Math.abs(val);
                    }
                });

                const maxForThisRow = Math.max(0, (globalAmount + otherReturnsTotal) - otherInvoicesTotal);
                
                // If it is a positive invoice, restrict it
                if (val > 0 && val > maxForThisRow) {
                    val = maxForThisRow;
                    if(input) input.value = val.toFixed(2);
                } else if (val < 0) {
                    // Sync the hidden positive value if it's a return
                    const hiddenInput = row.querySelector('.pay-input-hidden');
                    if(hiddenInput) hiddenInput.value = Math.abs(val).toFixed(2);
                }

                // Sync Select All state
                const total = document.querySelectorAll('.bill-checkbox').length;
                const checked = document.querySelectorAll('.bill-checkbox:checked').length;
                selectAllBills.checked = (total > 0 && checked === total);

                updateTotals(true, false);
            }, 200);

            document.querySelectorAll('.pay-input').forEach(input => {
                // keyup + input events for manual typing
                input.addEventListener('input', function() {
                    debouncedManualOverride(this);
                });

                input.addEventListener('keyup', function() {
                    debouncedManualOverride(this);
                });

                // Format on blur
                input.addEventListener('blur', function() {
                    const val = parseFloat(this.value) || 0;
                    if(this) this.value = val.toFixed(2);
                    updateTotals(true, false);
                });
                
                // Double-click sets the lesser of full due or remaining pool
                input.addEventListener('dblclick', function() {
                    const row = this.closest('tr');
                    const cb = row.querySelector('.bill-checkbox');
                    const due = parseFloat(this.dataset.due) || 0;

                    const rawGlobal = displayAmountInput.value.replace(/,/g, '');
                    const globalAmount = parseFloat(rawGlobal) || 0;

                    let otherInvoicesTotal = 0;
                    let otherReturnsTotal = 0;
                    document.querySelectorAll('.bill-row').forEach(otherRow => {
                        if (otherRow === row) return;
                        const otherCb = otherRow.querySelector('.bill-checkbox');
                        const otherPay = otherRow.querySelector('.pay-input');
                        if (otherCb.checked) {
                            const val = parseFloat(otherPay.value) || 0;
                            if (val > 0) otherInvoicesTotal += val;
                            else otherReturnsTotal += Math.abs(val);
                        }
                    });

                    if (due < 0) {
                        // Return row double-click
                        if(this) this.value = due.toFixed(2);
                        const hiddenInput = row.querySelector('.pay-input-hidden');
                        if(hiddenInput) hiddenInput.value = Math.abs(due).toFixed(2);
                    } else {
                        // Invoice row double-click
                        const maxForThisRow = Math.max(0, (globalAmount + otherReturnsTotal) - otherInvoicesTotal);
                        if(this) this.value = Math.min(due, maxForThisRow).toFixed(2);
                    }
                    
                    cb.checked = true;
                    updateTotals(true, false);
                });
            });
        }

        function initCreditEvents() {
            const selectAllCredits = document.getElementById('selectAllCredits');
            
            selectAllCredits.checked = false;
            selectAllCredits.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.credit-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateTotals(false, false);
            });

            document.querySelectorAll('.credit-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateTotals(false, false);
                    
                    // Update Select All state
                    const allChecked = document.querySelectorAll('.credit-checkbox:checked').length === document.querySelectorAll('.credit-checkbox').length;
                    selectAllCredits.checked = allChecked;
                });
            });

            document.querySelectorAll('.credit-use-input').forEach(input => {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    const cb = row.querySelector('.credit-checkbox');
                    const val = parseFloat(this.value) || 0;
                    
                    cb.checked = val > 0;
                    updateTotals(false, false);
                });
            });
        }

        function updateTotals(isUserAction = false, isSetCreditAction = false) {
            let totalOrigDue = 0;
            let totalCashApplied = 0;
            let totalCreditApplied = 0;
            let selectedCreditsCount = 0;
            
            // 1. Determine available credits from checkboxes
            let totalSelectedCreditBalance = 0;
            document.querySelectorAll('.credit-row').forEach(row => {
                const cb = row.querySelector('.credit-checkbox');
                const useInput = row.querySelector('.credit-use-input');
                const amountUsedHidden = row.querySelector('.credit-amount-used-hidden');
                const available = parseFloat(useInput.dataset.available) || 0;

                if (cb.checked) {
                    totalSelectedCreditBalance += available;
                    selectedCreditsCount++;
                } else {
                    if (!isSetCreditAction) {
                        if(useInput) useInput.value = '0.00';
                        if (amountUsedHidden) amountUsedHidden.value = '0.00';
                        const remainingCell = row.querySelector('.remaining-balance-cell');
                        if (remainingCell) remainingCell.textContent = available.toLocaleString(undefined, {minimumFractionDigits: 2});
                    }
                }
            });
            
            let remainingCreditToAllocate = totalSelectedCreditBalance;

            if (selectedCreditsCount > 0) {
                if(appliedCreditsSummary) appliedCreditsSummary.classList.remove('d-none');
                if(appliedCreditsCount) appliedCreditsCount.textContent = selectedCreditsCount;
            } else {
                if(appliedCreditsSummary) appliedCreditsSummary.classList.add('d-none');
            }

            // 2. USER REQUIREMENT 1: Sum up "New Payment" values for checked rows to update header
            // This happens every time a "New Payment" input is updated OR a checkbox is toggled.
            // We use the current table values to drive the header amount.
            let sumNewPayments = 0;
            document.querySelectorAll('.bill-row').forEach(row => {
                const cb = row.querySelector('.bill-checkbox');
                const payInput = row.querySelector('.pay-input');
                if (cb.checked) {
                    sumNewPayments += parseFloat(payInput.value) || 0;
                }
            });

            // Re-read header amount to prevent crash in overpayment logic
            const rawAmount = displayAmountInput.value.replace(/,/g, '');
            let totalCashFunds = parseFloat(rawAmount) || 0;

            // 3. Allocation & Calculations
            document.querySelectorAll('.bill-row').forEach(row => {
                const cb = row.querySelector('.bill-checkbox');
                const payInput = row.querySelector('.pay-input'); 
                const amtDueCell = row.querySelector('.amt-due-cell');
                const origAmt = parseFloat(payInput.dataset.due) || 0;
                const creditHidden = row.querySelector('.credit-used-hidden');
                
                if (cb.checked) {
                    totalOrigDue += origAmt;

                    // A. Allocate Credit
                    let creditAllocated = parseFloat(creditHidden.value) || 0;
                    if (isSetCreditAction) {
                        creditAllocated = Math.min(origAmt, remainingCreditToAllocate);
                        remainingCreditToAllocate -= creditAllocated;
                        if(creditHidden) creditHidden.value = creditAllocated.toFixed(2);
                    }

                    // B. Determine Cash Part (New Payment - Credit)
                    let currentNewPayment = parseFloat(payInput.value) || 0;
                    if (currentNewPayment > origAmt) {
                        currentNewPayment = origAmt;
                        if(payInput) payInput.value = currentNewPayment.toFixed(2);
                    }
                    
                    // If credit allocated is more than total payment (can happen if user reduced total), reduce credit
                    if (creditAllocated > currentNewPayment) {
                        creditAllocated = currentNewPayment;
                        if(creditHidden) creditHidden.value = creditAllocated.toFixed(2);
                    }

                    let cashAllocated = currentNewPayment - creditAllocated;

                    totalCreditApplied += creditAllocated;
                    totalCashApplied += cashAllocated;

                    // C. Update Amt. Due cell (Remaining Balance after this payment)
                    let finalAmtDue = origAmt - currentNewPayment;
                    if(amtDueCell) amtDueCell.textContent = finalAmtDue.toLocaleString(undefined, {minimumFractionDigits: 2});
                    
                    if (finalAmtDue <= 0.01) {
                        if(amtDueCell) amtDueCell.classList.add('text-success', 'fw-bold');
                    } else {
                        if(amtDueCell) amtDueCell.classList.remove('text-success', 'fw-bold');
                    }
                } else {
                    // Reset display for unchecked rows without forcing the pay-input
                    // value — that is handled by distributeWaterfall() on checkbox events.
                    if(amtDueCell) amtDueCell.textContent = origAmt.toLocaleString(undefined, {minimumFractionDigits: 2});
                    if(amtDueCell) amtDueCell.classList.remove('text-success', 'fw-bold');
                    if(creditHidden) creditHidden.value = '0.00';
                }
            });

            // 4. Update Header Fields
            // The global Amount input (#displayAmount) is user-controlled and NOT
            // overwritten here. Only the readonly summary/display fields are synced.
            if(lkrTotalAmountInput) lkrTotalAmountInput.value = totalCashApplied.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            (() => { let el = document.getElementById('headerTotalAmount'); if(el) el.textContent = totalCashApplied.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); })();

            // CRITICAL: The hidden total_amount form field must carry the GLOBAL
            // amount the user typed (e.g., 16,000), NOT just the allocated portion
            // (e.g., 14,000). The difference (2,000) is the surplus that persists
            // on the PayBill record and is surfaced as a "Payment" credit on the
            // next getOutstandingBills() call.
            const rawGlobalForSubmit = displayAmountInput.value.replace(/,/g, '');
            const globalPaymentForSubmit = parseFloat(rawGlobalForSubmit) || 0;
            (() => { let el = document.getElementById('totalToPayInput'); if(el) el.value = globalPaymentForSubmit.toFixed(2); })();

            // 5. Summary Box Updates
            (() => { let el = document.getElementById('summaryAmountDue'); if(el) el.value = totalOrigDue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); })();
            (() => { let el = document.getElementById('summaryCredit'); if(el) el.value = totalCreditApplied.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); })();
            (() => { let el = document.getElementById('summaryPayment'); if(el) el.value = totalCashApplied.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); })();
            
            const summaryTotalPayment = totalCashApplied + totalCreditApplied;
            (() => { let el = document.getElementById('summaryTotalPayment'); if(el) el.value = summaryTotalPayment.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); })();

            // 5. Update Credit Record rows (Labels and Inputs)
            let totalCreditConsumedAcrossAllBills = totalCreditApplied;
            document.querySelectorAll('.credit-row').forEach(row => {
                const cb = row.querySelector('.credit-checkbox');
                if (cb.checked) {
                    const useInput = row.querySelector('.credit-use-input');
                    const amountUsedHidden = row.querySelector('.credit-amount-used-hidden');
                    const remainingCell = row.querySelector('.remaining-balance-cell');
                    const initial = parseFloat(remainingCell.dataset.initial) || 0;
                    
                    let consumedFromThisRecord = Math.min(initial, totalCreditConsumedAcrossAllBills);
                    if(useInput) useInput.value = consumedFromThisRecord.toFixed(2);
                    if (amountUsedHidden) amountUsedHidden.value = consumedFromThisRecord.toFixed(2);
                    
                    let remaining = initial - consumedFromThisRecord;
                    if(remainingCell) remainingCell.textContent = remaining.toLocaleString(undefined, {minimumFractionDigits: 2});
                    
                    totalCreditConsumedAcrossAllBills -= consumedFromThisRecord;
                }
            });

            // 6. Handle Overpayment
            const overPaymentAmount = Math.max(0, totalCashFunds - totalCashApplied);
            updateOverpaymentRow(overPaymentAmount);
        }

        function updateOverpaymentRow(amount) {
            let existingRow = document.getElementById('overpayment-row');
            
            if (amount <= 0.01) {
                if (existingRow) existingRow.remove();
                if (creditsTableBody.querySelectorAll('tr.credit-row, tr#overpayment-row').length === 0) {
                    if(creditsTableBody) creditsTableBody.innerHTML = '<tr><td colspan="7" class="py-3 bg-light text-muted small">No credits available</td></tr>';
                }
                return;
            }

            const date = new Date().toISOString().split('T')[0];
            const html = `
                <tr id="overpayment-row" class="bg-warning-subtle border-warning">
                    <td><input type="checkbox" class="form-check-input" checked disabled></td>
                    <td class="small">${date}</td>
                    <td class="small text-danger fw-bold">CURRENT OVERPAYMENT</td>
                    <td class="small">Will be saved as credit</td>
                    <td class="text-end small fw-bold">${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td class="text-end small fw-bold">${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm text-end bg-light fw-bold" readonly value="${amount.toFixed(2)}">
                    </td>
                </tr>`;

            if (existingRow) {
                existingRow.outerHTML = html;
            } else {
                const emptyRow = creditsTableBody.querySelector('td[colspan="7"]')?.closest('tr');
                if (emptyRow) emptyRow.remove();
                creditsTableBody.insertAdjacentHTML('afterbegin', html);
            }
        }
    });
</script>
@endpush
