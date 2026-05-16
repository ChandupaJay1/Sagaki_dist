@extends('layouts.admin')

@section('title', 'Sales Return - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Sales Return</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold">Credit Limit: <span id="customer-credit-limit">0.00</span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-arrow-go-back-line me-1"></i>Sales Return - Create</h5>
                <div class="float-end">
                    <button type="submit" form="createSalesReturnForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" form="createSalesReturnForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="reset" form="createSalesReturnForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createSalesReturnForm" action="{{ route('sales-returns.store') }}" method="POST">
                    @csrf

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Customer Name <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->company_name ?? $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" data-name="{{ $location->name }}" {{ (old('location_id') == $location->id || $location->name == 'Main Stock') ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Load <span class="text-muted fw-normal">(prior Invoice)</span></label>
                            <select id="loadDropdown" class="form-select form-select-sm">
                                <option value="">Select Invoice to copy</option>
                            </select>
                            <input type="hidden" name="load" id="invoiceLoadSourceField" value="">
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
                                <label class="form-label small fw-bold mb-0">SRTN No</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="00022" readonly>
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
                            <label class="form-label small fw-bold mb-1">Order By</label>
                            <select name="order_by" class="form-select form-select-sm">
                                <option value="">Select Order By</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Checked By</label>
                            <select name="checked_by" class="form-select form-select-sm">
                                <option value="">Select Checked By</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Rep</label>
                            <select name="rep" id="repSelect" class="form-select form-select-sm">
                                <option value="">Select Rep</option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->id }}" {{ old('rep') == $rep->id ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Ship Via</label>
                            <select name="ship_via" class="form-select form-select-sm">
                                <option value="">Select Ship Via</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Reference No</label>
                            <input type="text" name="reference_no" class="form-control form-control-sm" value="{{ old('reference_no') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Expected Date</label>
                            <input type="date" name="expected_date" class="form-control form-control-sm" value="{{ old('expected_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <!-- Header Row 4 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Attent</label>
                            <input type="text" name="attent" class="form-control form-control-sm" value="{{ old('attent') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Terms</label>
                            <select name="payment_term_id" id="termsSelect" class="form-select form-select-sm">
                                <option value="">Select Terms</option>
                                @foreach($terms as $term)
                                    @php $label = ($term->days == 0) ? 'Cash Only' : ($term->days.' Days Credit'); @endphp
                                    <option value="{{ $term->id }}" data-days="{{ $term->days }}" {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Due Date</label>
                            <input type="date" name="due_date" class="form-control form-control-sm" value="{{ old('due_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Create User</label>
                            <input type="text" name="create_user" class="form-control form-control-sm bg-light" value="Demo" readonly>
                        </div>
                    </div>

                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        /* Ensure critical columns don't vanish */
                        #itemsTable .location-input { min-width: 90px !important; }
                        #itemsTable .unit-input { min-width: 60px !important; }
                        #itemsTable .product-select { min-width: 120px !important; }
                        /* TomSelect Dropdown Custom Height */
                        .ts-dropdown .ts-dropdown-content {
                            max-height: 450px !important;
                        }
                    </style>
                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>OnHand</th>
                                    <th>Qty</th>
                                    <th>Rate(LKR)</th>
                                    <th>Amount</th>
                                    <th>Disc%</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Location</th>
                                    <th>Unit</th>
                                    <th style="width: 30px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select form-select-sm product-select border-0"><option></option></select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm onhand-input text-center bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-center qty-input" step="any"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end rate-input" step="any"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end amount-input bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-center disc-percent-input" step="any" placeholder="0"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end discount-input" step="any" placeholder="0.00"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end total-input bg-light fw-bold" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm location-input text-center bg-light" value="Main Stock" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" readonly></td>
                                    <td>
                                        <i class="ri-delete-bin-line text-slate-400 delete-row-btn" style="cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'"></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty" readonly></td>
                                    <td class="text-end fw-bold">Amount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-amount" readonly></td>
                                    <td class="text-end fw-bold">Discount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-discount" readonly></td>
                                    <td class="text-end fw-bold">Total</td>
                                    <td colspan="2"><input type="text" class="form-control form-control-sm text-end bg-white fw-bold footer-total" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Javascript Hydration Source -->
                    <script>
                        window.serverProductList = @json($products ?? []);
                    </script>

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
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold mb-1">Ex.Rate</label>
                                    <input type="text" class="form-control form-control-sm text-center" value="1.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">LKR</span>
                                        <input type="text" class="form-control text-end bg-light footer-grand-total" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                                    <select name="account_id" class="form-select form-select-sm border-danger" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                        @endforeach
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
                                            <input type="number" name="header_discount_percent" class="form-control form-control-sm text-center header-discount-percent" step="any" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount</label>
                                            <input type="number" name="header_discount_amount" class="form-control form-control-sm text-end header-discount-amount" step="any" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" name="subtotal" class="form-control form-control-sm text-end w-50 bg-white summary-subtotal" value="0.00" readonly>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">SSCL %</label>
                                            <input type="text" class="form-control form-control-sm text-center" value="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">SSCL</label>
                                            <input type="text" class="form-control form-control-sm text-end" value="0.00">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT %</label>
                                            <input type="text" class="form-control form-control-sm text-center" value="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT</label>
                                            <input type="text" class="form-control form-control-sm text-end" value="0.00">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold h6 text-primary mb-0">Total</span>
                                        <input type="text" name="total_amount" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary summary-total" readonly>
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
        const repSelect = document.getElementById('repSelect');
        const termsSelect = document.getElementById('termsSelect');
        const loadSelect = document.querySelector('select[name="load"]');
        const creditLimitSpan = document.getElementById('customer-credit-limit');
        const loadDropdown = document.getElementById('loadDropdown');

        function fetchCustomerInvoices(customerId) {
            if (!loadDropdown) return;

            const loadHidden = document.getElementById('invoiceLoadSourceField');
            if (loadHidden) loadHidden.value = '';

            // Clear current options
            if (loadDropdown.tomselect) {
                loadDropdown.tomselect.clear(true);
                loadDropdown.tomselect.clearOptions();
                loadDropdown.tomselect.addOption({ value: '', text: 'Select Invoice to copy' });
            } else {
                loadDropdown.innerHTML = '<option value="">Select Invoice to copy</option>';
            }

            if (!customerId) return;

            fetch(`/ajax/customers/${customerId}/invoices`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) throw new Error('Invoice list request failed: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    const rows = Array.isArray(data) ? data : [];
                    const options = rows.map(inv => ({
                        value: String(inv.id),
                        text: `${inv.invoice_no || 'INV'} - ${inv.date || ''} (Rs. ${parseFloat(inv.total_amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
                    }));

                    if (loadDropdown.tomselect) {
                        loadDropdown.tomselect.addOptions(options);
                    } else {
                        options.forEach(opt => {
                            const option = document.createElement('option');
                            option.value = opt.value;
                            option.textContent = opt.text;
                            loadDropdown.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error fetching customer Invoices:', error));
        }

        function normalizeInvoiceItemsPayload(res) {
            let items = res.items || res.invoice_items || (res.data && res.data.items) || (res.invoice && res.invoice.items) || [];
            if (!Array.isArray(items) && items && typeof items === 'object') {
                items = Object.values(items);
            }
            return Array.isArray(items) ? items : [];
        }

        function parseLoadedUnit(item) {
            if (item.unit != null && typeof item.unit === 'object') {
                return item.unit.short_name || item.unit.name || 'PCS';
            }
            if (item.unit) return String(item.unit);
            if (item.product && item.product.unit) {
                const u = item.product.unit;
                if (typeof u === 'object') return u.short_name || u.name || 'PCS';
                return String(u);
            }
            return 'PCS';
        }

        function setSelectByValue(selectEl, value) {
            if (!selectEl || value === undefined || value === null || value === '') return;
            const strVal = String(value);
            const hasOption = Array.from(selectEl.options).some(o => o.value === strVal);
            if (!hasOption) return;
            selectEl.value = strVal;
            if (selectEl.tomselect) {
                selectEl.tomselect.setValue(strVal, true);
            }
        }

        function applyLoadedInvoiceHeader(invoice) {
            if (!invoice || typeof invoice !== 'object') return;

            const loadHidden = document.getElementById('invoiceLoadSourceField');
            if (loadHidden && invoice.invoice_no) {
                loadHidden.value = invoice.invoice_no;
            }

            const setInput = (name, val) => {
                if (val === undefined || val === null) return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el || el.type === 'checkbox') return;
                el.value = val;
            };

            setInput('address', invoice.address);
            setInput('delivery_destination', invoice.delivery_destination);
            setInput('date', invoice.date);
            setInput('attent', invoice.attent);
            setInput('memo', invoice.memo);

            setSelectByValue(document.querySelector('select[name="location_id"]'), invoice.location_id);
            setSelectByValue(document.querySelector('select[name="rep_id"]'), invoice.rep_id);
            setSelectByValue(document.querySelector('select[name="account_id"]'), invoice.account_id);

            const numericPairs = [
                ['sscl_percent', invoice.sscl_percent],
                ['sscl_amount', invoice.sscl_amount],
                ['vat_percent', invoice.vat_percent],
                ['vat_amount', invoice.vat_amount],
                ['subtotal', invoice.subtotal],
                ['header_discount_percent', invoice.header_discount_percent],
                ['header_discount_amount', invoice.header_discount_amount],
                ['tax_amount', invoice.tax_amount],
                ['total_amount', invoice.total_amount],
            ];
            numericPairs.forEach(([name, val]) => {
                if (val === undefined || val === null || val === '') return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return;
                const n = parseFloat(val);
                el.value = Number.isFinite(n) ? n.toFixed(2) : String(val);
            });

            if (typeof salesReturnController !== 'undefined' && salesReturnController.calculateGrandTotal) {
                setTimeout(() => salesReturnController.calculateGrandTotal(), 0);
            }
        }

        function loadInvoiceDetails(invoiceId) {
            if (!invoiceId) return;

            const loadContainer = loadDropdown && loadDropdown.closest('.col-md-4');
            const labelEl = loadContainer && loadContainer.querySelector('label');
            const originalLabel = labelEl ? labelEl.innerHTML : '';

            if (labelEl) {
                labelEl.innerHTML = 'Load <span class="spinner-border spinner-border-sm text-primary" role="status"></span>';
            }

            fetch(`/ajax/invoices/${invoiceId}/details`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Invoice details request failed: ' + response.status);
                    }
                    return response.json();
                })
                .then(res => {
                    const selectedCustomerId = customerSelect && customerSelect.tomselect
                        ? customerSelect.tomselect.getValue()
                        : (customerSelect ? customerSelect.value : '');
                    if (res.invoice && res.invoice.customer_id != null && String(res.invoice.customer_id) !== String(selectedCustomerId || '')) {
                        alert('This Invoice belongs to a different customer. Select the correct customer first.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }
                    if (res.invoice) {
                        applyLoadedInvoiceHeader(res.invoice);
                    }
                    const items = normalizeInvoiceItemsPayload(res);
                    if (items.length === 0) {
                        alert('No items found in this Invoice.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    const tbody = document.querySelector('#itemsTable tbody');
                    if (!tbody) {
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    tbody.innerHTML = '';
                    salesReturnController.data = [];
                    salesReturnController.rowCount = 0;

                    salesReturnController._loadingInv = true;

                    const loadedRowsData = [];
                    let appended = 0;

                    items.forEach((item) => {
                        const pId = item.product_id || item.item_id || (item.product && item.product.id) || null;
                        if (!pId) return;

                        const qty = parseFloat(item.qty || item.quantity || 0) || 0;
                        const rate = parseFloat(item.rate || item.unit_price || (item.product && (item.product.cost != null ? item.product.cost : 0)) || 0) || 0;
                        let amount = parseFloat(item.amount);
                        if (isNaN(amount)) amount = qty * rate;
                        let discount = parseFloat(item.discount);
                        if (isNaN(discount)) discount = 0;
                        const discPercent = parseFloat(item.disc_percent || item.discount_percent || 0) || 0;
                        let total = parseFloat(item.total);
                        if (isNaN(total)) total = amount - discount;

                        const rowData = {
                            product_id: pId,
                            description: item.description || (item.product && item.product.name) || '',
                            onhand: item.onhand != null && item.onhand !== '' ? item.onhand : '',
                            qty: qty,
                            rate: rate,
                            amount: amount,
                            disc_percent: discPercent,
                            discount: discount,
                            total: total,
                            location: item.location || getDefaultLocation() || 'Main Stock',
                            unit: parseLoadedUnit(item)
                        };

                        if (rowData.amount === 0 && rowData.qty > 0 && rowData.rate > 0) {
                            rowData.amount = rowData.qty * rowData.rate;
                        }
                        if ((rowData.total === 0 || isNaN(rowData.total)) && rowData.amount > 0) {
                            rowData.total = rowData.amount - rowData.discount;
                        }

                        loadedRowsData.push(rowData);
                        salesReturnController.appendRow(rowData);
                        appended++;
                    });

                    if (appended === 0) {
                        salesReturnController._loadingInv = false;
                        alert('No valid line items (missing product) in this Invoice.');
                        salesReturnController.appendRow();
                        salesReturnController.appendRow();
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    setTimeout(() => {
                        const allRows = tbody.querySelectorAll('tr.item-row');
                        allRows.forEach((row, idx) => {
                            if (idx >= loadedRowsData.length) return;

                            const rd = loadedRowsData[idx];
                            const dataIdx = parseInt(row.dataset.rowIndex, 10);
                            if (isNaN(dataIdx) || !salesReturnController.data[dataIdx]) return;

                            const d = salesReturnController.data[dataIdx];
                            d.description = rd.description;
                            d.onhand = rd.onhand;
                            d.qty = rd.qty;
                            d.rate = rd.rate;
                            d.amount = rd.amount;
                            d.disc_percent = rd.disc_percent;
                            d.discount = rd.discount;
                            d.total = rd.total;
                            d.location = rd.location;
                            d.unit = rd.unit;
                            d.product_id = rd.product_id;

                            const descInput = row.querySelector('.description-input');
                            const qtyInput = row.querySelector('.qty-input');
                            const rateInput = row.querySelector('.rate-input');
                            const amountInput = row.querySelector('.amount-input');
                            const discPercentInput = row.querySelector('.disc-percent-input');
                            const discountInput = row.querySelector('.discount-input');
                            const totalInput = row.querySelector('.total-input');
                            const unitInput = row.querySelector('.unit-input');
                            const locInput = row.querySelector('.location-input');
                            const productSelect = row.querySelector('.product-select');

                            if (descInput) descInput.value = rd.description;
                            if (qtyInput) qtyInput.value = String(rd.qty);
                            if (rateInput) rateInput.value = String(rd.rate);
                            if (amountInput) amountInput.value = Number(rd.amount).toFixed(2);
                            if (discPercentInput) discPercentInput.value = rd.disc_percent ? String(rd.disc_percent) : '';
                            if (discountInput) discountInput.value = rd.discount > 0 ? Number(rd.discount).toFixed(2) : '';
                            if (totalInput) totalInput.value = Number(rd.total).toFixed(2);
                            if (unitInput) unitInput.value = rd.unit;
                            if (locInput) locInput.value = rd.location;

                            const pidStr = String(rd.product_id);
                            if (productSelect) {
                                if (productSelect.tomselect) {
                                    productSelect.tomselect.setValue(pidStr, true);
                                } else {
                                    productSelect.value = pidStr;
                                }
                            }

                            const rowCalcSource = parseFloat(rd.disc_percent) > 0 ? 'disc_percent' : 'discount';
                            salesReturnController.calculateRow(dataIdx, row, rowCalcSource);

                            if (productSelect && productSelect.value && rd.location) {
                                fetchItemStock(productSelect.value, rd.location, dataIdx, row);
                            }
                        });

                        salesReturnController.appendRow();
                        salesReturnController.calculateGrandTotal();

                        salesReturnController._loadingInv = false;

                        if (labelEl) labelEl.innerHTML = originalLabel;

                        const itemsTable = document.getElementById('itemsTable');
                        if (itemsTable) itemsTable.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 50);
                })
                .catch(error => {
                    console.error('CRITICAL LOAD ERROR:', error);
                    salesReturnController._loadingInv = false;
                    if (labelEl) labelEl.innerHTML = originalLabel;
                    alert('Error: Data could not be loaded. Check console for details.');
                });
        }

        function fetchCustomerDetails(customerId) {
            if (customerId) {
                const url = "{{ url('api/customers') }}/" + customerId;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea) deliveryDestinationTextarea.value = data.delivery_address || '';

                        if (creditLimitSpan) creditLimitSpan.innerText = parseFloat(data.credit_limit || 0).toLocaleString(undefined, {minimumFractionDigits: 2});

                        if (repSelect && data.rep_id) {
                            repSelect.value = data.rep_id;
                            if (repSelect.tomselect) {
                                repSelect.tomselect.setValue(data.rep_id);
                            }
                        }

                        if (termsSelect && data.terms) {
                            // Try to match by days first
                            let daysMatch = data.terms.match(/\d+/);
                            let matchedOption = null;

                            if (daysMatch) {
                                let days = parseInt(daysMatch[0]);
                                matchedOption = Array.from(termsSelect.options).find(opt => opt.dataset.days == days);
                            }

                            if (!matchedOption) {
                                // Try to match by text
                                matchedOption = Array.from(termsSelect.options).find(opt => opt.text.toLowerCase().includes(data.terms.toLowerCase()));
                            }

                            if (matchedOption) {
                                termsSelect.value = matchedOption.value;
                                if (termsSelect.tomselect) {
                                    termsSelect.tomselect.setValue(matchedOption.value);
                                }
                            }
                        }

                        // Fetch Invoices for Load dropdown
                        fetchCustomerInvoices(customerId);
                    })
                    .catch(error => console.error('Error fetching customer details:', error));
            } else {
                fetchCustomerInvoices(null);
            }
        }

        // Standard change event
        function attachCustomerListener() {
            if (customerSelect.tomselect) {
                customerSelect.tomselect.on('change', function(value) {
                    fetchCustomerDetails(value);
                });
                if (customerSelect.tomselect.getValue()) {
                    fetchCustomerDetails(customerSelect.tomselect.getValue());
                }
            } else {
                customerSelect.addEventListener('change', function () {
                    fetchCustomerDetails(this.value);
                });
                if (this.value) {
                    fetchCustomerDetails(this.value);
                }
            }
        }

        setTimeout(attachCustomerListener, 500);

        // Account dropdown default selection logic
        setTimeout(function() {
            let accSelect = document.getElementById('account_id') || document.querySelector('select[name="account_id"]');
            if (accSelect) {
                // More robust match for "Receivable" handling typos (ei/ie)
                let accOpt = Array.from(accSelect.options).find(opt => opt.text.toLowerCase().includes('receiv'));
                if (accOpt) {
                    if (accSelect.tomselect) {
                        accSelect.tomselect.setValue(accOpt.value);
                    } else {
                        $(accSelect).val(accOpt.value).trigger('change');
                    }
                }
            }
        }, 600);

        function initLoadDropdown() {
            if (loadDropdown && window.TomSelect) {
                if (loadDropdown.tomselect) return; // Already initialized

                new TomSelect(loadDropdown, {
                    create: false,
                    placeholder: "Select Invoice to copy",
                    allowEmptyOption: true,
                    onChange: function(value) {
                        if (value) {
                            loadInvoiceDetails(value);
                        }
                    }
                });
            }
        }

        // Initialize immediately if TomSelect is available, otherwise wait
        if (window.TomSelect) {
            initLoadDropdown();
        }

        setTimeout(() => {
            if (repSelect && window.TomSelect && !repSelect.tomselect) {
                new TomSelect(repSelect, { create: false });
            }
            if (termsSelect && window.TomSelect && !termsSelect.tomselect) {
                new TomSelect(termsSelect, { create: false });
            }
            initLoadDropdown(); // Fallback initialization
        }, 600);

        // --- Table Controller (Data Source Level) --- //
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                return locNode.options[locNode.selectedIndex].dataset.name || '';
            }
            return '';
        }

        const salesReturnController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',
            _loadingInv: false,

            init() {
                const firstRow = document.querySelector('.item-row');
                this.rowTemplateHTML = firstRow.innerHTML;
                firstRow.remove();

                // Sync header location to items
                const mainLocationSelect = document.querySelector('select[name="location_id"]');
                if (mainLocationSelect) {
                    mainLocationSelect.addEventListener('change', function(e) {
                        const selectedOption = this.options[this.selectedIndex];
                        const locationName = selectedOption ? selectedOption.dataset.name : '';

                        document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                            const rowLocationInput = row.querySelector('.location-input');
                            const rowIndex = parseInt(row.dataset.rowIndex);

                            if (rowLocationInput && rowLocationInput.value !== locationName) {
                                rowLocationInput.value = locationName;
                                if (!isNaN(rowIndex)) {
                                    salesReturnController.updateRowData(rowIndex, 'location', locationName);
                                    const productSelect = row.querySelector('.product-select');
                                    const productId = productSelect ? productSelect.value : '';
                                    if (productId) {
                                        fetchItemStock(productId, locationName, rowIndex, row);
                                    }
                                }
                            }
                        });
                    });
                }

                // Start with TWO empty rows
                this.appendRow();
                this.appendRow();
            },

            checkAndAppendRow(rowIndex) {
                if (rowIndex === this.data.length - 1) {
                    const currentRow = this.data[rowIndex];
                    if (currentRow.product_id) {
                        this.appendRow();
                    }
                }
            },

            appendRow(itemData = null) {
                const currentLoc = getDefaultLocation();
                const newIdx = this.data.length;

                const rowData = {
                    rowId: newIdx,
                    product_id: itemData ? itemData.product_id : '',
                    description: itemData ? itemData.description : '',
                    onhand: itemData ? itemData.onhand : '',
                    qty: itemData ? itemData.qty : 1,
                    rate: itemData ? itemData.rate : 0,
                    amount: itemData ? itemData.amount : 0,
                    disc_percent: itemData ? itemData.disc_percent : 0,
                    discount: itemData ? itemData.discount : 0,
                    total: itemData ? itemData.total : 0,
                    location: itemData ? itemData.location : currentLoc,
                    unit: itemData ? itemData.unit : ''
                };
                this.data.push(rowData);

                this.injectRowUI(rowData, newIdx);
                this.rowCount++;
            },

            injectRowUI(data, index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML;

                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    if (input.classList.contains('qty-input')) input.value = data.qty;
                    if (input.classList.contains('location-input')) input.value = data.location;
                    if (input.classList.contains('description-input')) input.value = data.description;
                    if (input.classList.contains('onhand-input')) input.value = data.onhand;
                    if (input.classList.contains('rate-input')) input.value = data.rate.toFixed(2);
                    if (input.classList.contains('amount-input')) input.value = data.amount.toFixed(2);
                    if (input.classList.contains('disc-percent-input')) input.value = data.disc_percent || '';
                    if (input.classList.contains('discount-input')) input.value = data.discount > 0 ? data.discount.toFixed(2) : '';
                    if (input.classList.contains('total-input')) input.value = data.total.toFixed(2);
                    if (input.classList.contains('unit-input')) input.value = data.unit;
                });

                newRow.querySelectorAll('.ts-wrapper').forEach(wrapper => wrapper.remove());
                newRow.querySelectorAll('select').forEach(select => {
                    select.classList.remove('tomselected', 'ts-hidden-accessible');
                    select.style.display = '';
                    if (select.hasAttribute('id')) select.removeAttribute('id');
                    select.value = '';
                });

                newRow.querySelectorAll('input, select').forEach(el => {
                    if (el.classList.contains('product-select')) el.name = `items[${index}][product_id]`;
                    if (el.classList.contains('description-input')) el.name = `items[${index}][description]`;
                    if (el.classList.contains('onhand-input')) el.name = `items[${index}][onhand]`;
                    if (el.classList.contains('qty-input')) el.name = `items[${index}][qty]`;
                    if (el.classList.contains('rate-input')) el.name = `items[${index}][rate]`;
                    if (el.classList.contains('amount-input')) el.name = `items[${index}][amount]`;
                    if (el.classList.contains('disc-percent-input')) el.name = `items[${index}][disc_percent]`;
                    if (el.classList.contains('discount-input')) el.name = `items[${index}][discount]`;
                    if (el.classList.contains('total-input')) el.name = `items[${index}][total]`;
                    if (el.classList.contains('location-input')) el.name = `items[${index}][location]`;
                    if (el.classList.contains('unit-input')) el.name = `items[${index}][unit]`;
                });

                document.querySelector('#itemsTable tbody').appendChild(newRow);
                initRowEvents(newRow);
            },

            deleteRow(rowElement) {
                const allRows = document.querySelectorAll('#itemsTable tbody tr.item-row');
                if (allRows.length <= 1) {
                    return; // Don't delete last row
                }

                const rowIndex = parseInt(rowElement.dataset.rowIndex);
                // Remove from data array
                this.data.splice(rowIndex, 1);
                // Remove from DOM
                rowElement.remove();

                // Re-index remaining rows in DOM and data
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach((row, newIdx) => {
                    row.dataset.rowIndex = newIdx;
                    // Update input names for form submission
                    row.querySelectorAll('input, select').forEach(el => {
                        if (el.name) {
                            el.name = el.name.replace(/items\[\d+\]/, `items[${newIdx}]`);
                        }
                    });
                });

                this.calculateGrandTotal();
            },

            updateRowData(rowIndex, field, value) {
                if (this.data[rowIndex]) {
                    this.data[rowIndex][field] = value;
                }
            },

            calculateRow(rowIndex, rowElement, sourceField = 'disc_percent') {
                if (!this.data[rowIndex]) return;

                const dataRow = this.data[rowIndex];
                dataRow.amount = dataRow.qty * dataRow.rate;

                if (sourceField === 'disc_percent') {
                    dataRow.discount = (dataRow.amount * dataRow.disc_percent) / 100;
                    rowElement.querySelector('.discount-input').value = dataRow.discount > 0 ? dataRow.discount.toFixed(2) : '';
                } else if (sourceField === 'discount') {
                    dataRow.disc_percent = 0;
                    rowElement.querySelector('.disc-percent-input').value = '';
                }

                dataRow.total = dataRow.amount - dataRow.discount;

                rowElement.querySelector('.amount-input').value = dataRow.amount.toFixed(2);
                rowElement.querySelector('.total-input').value = dataRow.total.toFixed(2);

                this.calculateGrandTotal();
            },

            calculateGrandTotal(sourceField = 'none') {
                let grandQty = 0;
                let grandAmount = 0;
                let grandDiscount = 0;
                let grandTotal = 0;

                this.data.forEach(row => {
                    grandQty += parseFloat(row.qty) || 0;
                    grandAmount += parseFloat(row.amount) || 0;
                    grandDiscount += parseFloat(row.discount) || 0;
                    grandTotal += parseFloat(row.total) || 0;
                });

                document.querySelector('.footer-qty').value = grandQty.toFixed(2);
                document.querySelector('.footer-amount').value = grandAmount.toFixed(2);
                document.querySelector('.footer-discount').value = grandDiscount.toFixed(2);
                document.querySelector('.footer-total').value = grandTotal.toFixed(2);

                const subTotal = grandTotal;
                const headerDiscPercentInput = document.querySelector('.header-discount-percent');
                const headerDiscAmountInput = document.querySelector('.header-discount-amount');

                let headerDiscPercent = parseFloat(headerDiscPercentInput.value) || 0;
                let headerDiscAmount = parseFloat(headerDiscAmountInput.value) || 0;

                if (sourceField === 'header_percent') {
                    headerDiscAmount = (subTotal * headerDiscPercent) / 100;
                    headerDiscAmountInput.value = headerDiscAmount > 0 ? headerDiscAmount.toFixed(2) : '';
                } else if (sourceField === 'header_amount') {
                    headerDiscPercent = 0;
                    headerDiscPercentInput.value = '';
                }

                const finalTotal = subTotal - headerDiscAmount;
                const lkrSummary = document.querySelector('.footer-grand-total');
                if(lkrSummary) lkrSummary.value = finalTotal.toFixed(2);

                const subTotalInput = document.querySelector('.summary-subtotal');
                if(subTotalInput) subTotalInput.value = subTotal.toFixed(2);

                const totalInput = document.querySelector('.summary-total');
                if(totalInput) totalInput.value = finalTotal.toFixed(2);
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            if (!productId || !location) {
                onhandInput.value = '';
                salesReturnController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            onhandInput.value = '...';
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0;
                    onhandInput.value = balance;
                    salesReturnController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    onhandInput.value = '0';
                    salesReturnController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');
            const discountInput = row.querySelector('.discount-input');
            const deleteBtn = row.querySelector('.delete-row-btn');

            if (!qtyInput.value) qtyInput.value = '1';

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    salesReturnController.deleteRow(row);
                });
            }

            function handleProductChange(value) {
                salesReturnController.updateRowData(rowIndex, 'product_id', value);

                if (salesReturnController._loadingInv) return;

                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';
                        const rate = parseFloat(selectedObj.max_sale_price) || parseFloat(selectedObj.cost) || 0;

                        salesReturnController.updateRowData(rowIndex, 'description', desc);
                        salesReturnController.updateRowData(rowIndex, 'unit', unit);
                        salesReturnController.updateRowData(rowIndex, 'rate', rate);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.unit-input').value = unit;
                        row.querySelector('.rate-input').value = rate;

                        const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        salesReturnController.calculateRow(rowIndex, row);
                        salesReturnController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                    salesReturnController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">Select Item</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = parseFloat(p.max_sale_price) || parseFloat(p.cost) || 0;
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-unit="${p.unit || ''}" data-rate="${rate}">${safeCode}</option>`;
                    });
                }
                productSelect.innerHTML = optionsHTML;
            }

            if (window.TomSelect) {
                new TomSelect(productSelect, {
                    create: false,
                    sortField: { field: "text", order: "asc" },
                    dropdownParent: 'body',
                    render: {
                        option: function(data, escape) {
                            return `<div class="px-2 py-1">
                                        <div class="fw-bold fs-12">${escape(data.text)}</div>
                                        <div class="text-muted fs-10">${escape(data.name)}</div>
                                    </div>`;
                        },
                        item: function(data, escape) {
                            return `<div title="${escape(data.name)}">${escape(data.text)}</div>`;
                        }
                    },
                    onChange: (val) => {
                        salesReturnController.updateRowData(rowIndex, 'product_id', val);
                        handleProductChange(val);
                    }
                });
            }

            [qtyInput, rateInput, discPercentInput, discountInput].forEach(input => {
                input.addEventListener('input', function() {
                    let fieldName = 'qty';
                    let sourceField = 'disc_percent';
                    if (this.classList.contains('rate-input')) fieldName = 'rate';
                    if (this.classList.contains('disc-percent-input')) { fieldName = 'disc_percent'; sourceField = 'disc_percent'; }
                    if (this.classList.contains('discount-input')) { fieldName = 'discount'; sourceField = 'discount'; }
                    salesReturnController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    salesReturnController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        salesReturnController.init();

        // Auto-select "Main Warehouse" on initial load
        function autoSelectMainLocation() {
            let locationSelect = document.querySelector('select[name="location_id"]');
            if (locationSelect) {
                let mainOption = Array.from(locationSelect.options).find(opt => opt.text.includes('Main'));
                if (mainOption) {
                    locationSelect.value = mainOption.value;
                    if (locationSelect.tomselect) {
                        locationSelect.tomselect.setValue(mainOption.value);
                    }
                    // Trigger change to sync with items
                    locationSelect.dispatchEvent(new Event('change'));
                }
            }
        }
        autoSelectMainLocation();

        // Auto-select "Accounts Receivable" on initial load
        function autoSelectDefaultAccount() {
            let accountSelect = document.getElementById('account_id') || document.querySelector('select[name="account_id"]');
            if (accountSelect) {
                if (accountSelect.tomselect) {
                    let val = Array.from(accountSelect.options).find(opt => opt.text.includes('Accounts Receivable'))?.value;
                    if (val) accountSelect.tomselect.setValue(val);
                } else {
                    let $acc = $('select[name="account_id"], #account_id');
                    let val = $acc.find('option:contains("Accounts Receivable")').val();
                    if (val) $acc.val(val).trigger('change');
                }
            }
        }
        autoSelectDefaultAccount();

        const mainLocationSelect = document.querySelector('select[name="location"]');
        if (mainLocationSelect) {
            mainLocationSelect.addEventListener('change', function(e) {
                if (e.detail && e.detail.isSyncTrigger) return;
                const newLocation = this.value;
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowLocationInput = row.querySelector('.location-input');
                    const rowIndex = parseInt(row.dataset.rowIndex);

                    if (rowLocationInput && rowLocationInput.value !== newLocation) {
                        rowLocationInput.value = newLocation;
                        if (!isNaN(rowIndex)) {
                            salesReturnController.updateRowData(rowIndex, 'location', newLocation);
                            const productSelect = row.querySelector('.product-select');
                            const productId = productSelect ? productSelect.value : '';
                            if (productId) {
                                fetchItemStock(productId, newLocation, rowIndex, row);
                            }
                        }
                    }
                });
            });
        }

        // Header Discount Events
        const headerDiscPercentInput = document.querySelector('.header-discount-percent');
        const headerDiscAmountInput = document.querySelector('.header-discount-amount');

        if (headerDiscPercentInput) {
            headerDiscPercentInput.addEventListener('input', () => {
                salesReturnController.calculateGrandTotal('header_percent');
            });
        }

        if (headerDiscAmountInput) {
            headerDiscAmountInput.addEventListener('input', () => {
                salesReturnController.calculateGrandTotal('header_amount');
            });
        }

        // --- Form Submission (fetch-based for proper 422 validation feedback) --- //
        const form = document.getElementById('createSalesReturnForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // ── Step 1: client-side re-index & basic guard ────────────────
                const rows = document.querySelectorAll('#itemsTable tbody tr.item-row');
                let validRowIndex = 0;
                let hasValidRow = false;

                rows.forEach((row) => {
                    const productSelect = row.querySelector('.product-select');
                    const productId = productSelect ? productSelect.value : '';

                    if (productId) {
                        hasValidRow = true;
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.classList.contains('product-select')) el.name = `items[${validRowIndex}][product_id]`;
                            if (el.classList.contains('description-input')) el.name = `items[${validRowIndex}][description]`;
                            if (el.classList.contains('onhand-input')) {
                                el.name = `items[${validRowIndex}][onhand]`;
                                if (el.value === '...' || isNaN(parseFloat(el.value))) el.value = '0';
                            }
                            if (el.classList.contains('qty-input')) {
                                el.name = `items[${validRowIndex}][qty]`;
                                if (isNaN(parseFloat(el.value))) el.value = '1';
                            }
                            if (el.classList.contains('rate-input')) {
                                el.name = `items[${validRowIndex}][rate]`;
                                if (isNaN(parseFloat(el.value))) el.value = '0';
                            }
                            if (el.classList.contains('amount-input')) el.name = `items[${validRowIndex}][amount]`;
                            if (el.classList.contains('disc-percent-input')) el.name = `items[${validRowIndex}][disc_percent]`;
                            if (el.classList.contains('discount-input')) el.name = `items[${validRowIndex}][discount]`;
                            if (el.classList.contains('total-input')) el.name = `items[${validRowIndex}][total]`;
                            if (el.classList.contains('location-input')) el.name = `items[${validRowIndex}][location]`;
                            if (el.classList.contains('unit-input')) el.name = `items[${validRowIndex}][unit]`;
                        });
                        validRowIndex++;
                    } else {
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.name) el.removeAttribute('name');
                        });
                    }
                });

                if (!hasValidRow) {
                    window.showAppToast('Please add at least one valid item to the sales return.', 'warning');
                    return;
                }

                // ── Step 2: capture which submit button was clicked ───────────
                const actionValue = (document.activeElement && document.activeElement.name === 'action')
                    ? document.activeElement.value
                    : null;

                // ── Step 3: serialise form to FormData, inject action ─────────
                const formData = new FormData(form);
                if (actionValue) formData.set('action', actionValue);

                // ── Step 4: disable submit buttons to prevent double-submit ──
                const submitBtns = form.querySelectorAll('[type="submit"]');
                submitBtns.forEach(btn => { btn.disabled = true; });

                // ── Step 5: POST via fetch ────────────────────────────────────
                // redirect:'manual' stops fetch from auto-following Laravel's 302.
                // The 302 itself becomes an opaque response (type='opaqueredirect',
                // status=0). We detect that as "store succeeded" and navigate to
                // the resource index ourselves.
                // getAttribute('action') returns the raw Blade-rendered route string
                // (e.g. "/sales-returns") exactly as written in the HTML — avoiding
                // the browser DOM property form.action which resolves to a fully-
                // qualified absolute URL and can be affected by base-URL normalisation.
                var storeBase = form.getAttribute('action'); // e.g. /sales-returns
                fetch(storeBase, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData,
                    credentials: 'same-origin',
                    redirect: 'manual',
                })
                .then(function (response) {
                    // Opaque redirect — Laravel issued 302 — store() succeeded.
                    if (response.type === 'opaqueredirect' || response.status === 0) {
                        window.location.href = storeBase;
                        return;
                    }

                    if (response.status === 422) {
                        return response.json().then(function (data) {
                            submitBtns.forEach(btn => { btn.disabled = false; });
                            const errors = data.errors || {};
                            if (typeof window.showValidationErrors === 'function') {
                                window.showValidationErrors(errors, '.card-body.p-3');
                            } else {
                                const msgs = Object.values(errors).flat().join('\n');
                                alert('Validation errors:\n' + msgs);
                            }
                        });
                    }

                    submitBtns.forEach(btn => { btn.disabled = false; });
                    window.showAppToast('An unexpected server error occurred (HTTP ' + response.status + '). Please try again.', 'error');
                })
                .catch(function (err) {
                    submitBtns.forEach(btn => { btn.disabled = false; });
                    window.showAppToast('Network error — could not reach the server. Please check your connection.', 'error');
                    console.error('Form submit fetch error:', err);
                });
            });
        }

    });
</script>
@endpush
