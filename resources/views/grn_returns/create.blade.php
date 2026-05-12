@extends('layouts.admin')

@section('title', 'GRN Return - Create')

@push('css')
    <link href="{{ asset('assets/libs/tom-select/css/tom-select.bootstrap5.min.css') }}" rel="stylesheet">
    <style>
        .fs-12 { font-size: 12px; }
        .fs-10 { font-size: 10px; }
        .bg-soft-primary { background-color: rgba(64, 81, 137, 0.1); }
        .bg-soft-info { background-color: rgba(41, 156, 219, 0.1); }
        #itemsTable thead th {
            background-color: #405189;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 5px;
        }
        .item-row input, .item-row select {
            border-radius: 0;
            border: 1px solid #e9ebec;
        }
        .item-row input:focus, .item-row select:focus {
            box-shadow: none;
            border-color: #405189;
        }
        .footer-total-box {
            background: #f3f3f9;
            padding: 15px;
            border-radius: 5px;
        }
        .ts-wrapper.form-control-sm .ts-control {
            padding: 5px 10px !important;
            min-height: 31px !important;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">GRN Returns</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Purchasing</a></li>
                        <li class="breadcrumb-item active">Create GRN Return</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form id="createGrnReturnForm" action="{{ route('grn-returns.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1 text-uppercase"><i class="ri-refresh-line align-bottom me-1"></i> GRN Return - Create</h4>
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="save_new" class="btn btn-info btn-sm"><i class="ri-save-line align-bottom me-1"></i> Save & New</button>
                                <button type="submit" name="action" value="save_close" class="btn btn-success btn-sm"><i class="ri-checkbox-circle-line align-bottom me-1"></i> Save & Close</button>
                                <button type="button" class="btn btn-primary btn-sm disabled"><i class="ri-printer-line align-bottom me-1"></i> Save & Print</button>
                                <a href="{{ route('grn-returns.index') }}" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i> Reset</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">Vendor Name <span class="text-danger">*</span></label>
                                <select name="vendor_id" class="form-select form-select-sm" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                                <select name="location_id" class="form-select form-select-sm" required>
                                    <option value="">Select Site</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" data-name="{{ $loc->name }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">Load <span class="text-muted fw-normal">(prior GRN)</span></label>
                                <select id="loadDropdown" class="form-select form-select-sm">
                                    <option value="">Select GRN to copy</option>
                                </select>
                                <input type="hidden" name="load" id="grnReturnLoadSourceField" value="">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">Address</label>
                                <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="address">{{ old('address') }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">Delivery Destination</label>
                                <textarea name="delivery_destination" class="form-control form-control-sm" rows="2" placeholder="deliver destination">{{ old('delivery_destination') }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold mb-1">RTN No</label>
                                        <input type="text" name="rtn_no" class="form-control form-control-sm bg-light" value="{{ $nextRtnNo }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold mb-1">Date</label>
                                        <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold mb-1">Order By</label>
                                <select name="order_by" class="form-select form-select-sm">
                                    <option value="">Select Order By</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('order_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold mb-1">Checked By</label>
                                <select name="checked_by" class="form-select form-select-sm">
                                    <option value="">Select Checked By</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('checked_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold mb-1">Rep</label>
                                <select name="rep" class="form-select form-select-sm">
                                    <option value="">Select Rep</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('rep') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold mb-1">Reference No</label>
                                <input type="text" name="reference_no" class="form-control form-control-sm" value="{{ old('reference_no') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold mb-1">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control form-control-sm" value="{{ old('invoice_date', date('Y-m-d')) }}">
                            </div>

                            <div class="col-md-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label small fw-bold mb-1">Credit Limit</label>
                                    <span class="badge bg-danger-subtle text-danger" id="vendor-credit-limit">0.00</span>
                                </div>
                                <div class="mt-1 small text-muted"><i class="ri-error-warning-line me-1"></i>Date Control is inactive.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Attent</label>
                                <input type="text" name="attent" class="form-control form-control-sm" value="{{ old('attent') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Terms</label>
                                <select name="payment_term_id" id="termsSelect" class="form-select form-select-sm">
                                    <option value="">Select Terms</option>
                                    @foreach($paymentTerms as $term)
                                        <option value="{{ $term->id }}" data-days="{{ $term->days }}" {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Due Date</label>
                                <input type="date" name="due_date" class="form-control form-control-sm" value="{{ old('due_date', date('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Dispatch No</label>
                                <input type="text" name="dispatch_no" class="form-control form-control-sm" value="{{ old('dispatch_no') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Item Code</th>
                                        <th style="width: 25%;">Description</th>
                                        <th style="width: 10%;">Onhand</th>
                                        <th style="width: 8%;">Qty</th>
                                        <th style="width: 10%;">Rate(LKR)</th>
                                        <th style="width: 10%;">Amount</th>
                                        <th style="width: 5%;">Disc%</th>
                                        <th style="width: 8%;">Discount</th>
                                        <th style="width: 10%;">Total</th>
                                        <th style="width: 10%;">Location</th>
                                        <th style="width: 5%;">Unit</th>
                                        <th style="width: 4%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_id]" class="form-select form-select-sm product-select">
                                                <option value="">-- Select --</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-unit="{{ $p->unit }}" data-rate="{{ $p->cost }}" data-onhand="">{{ $p->code }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-control form-control-sm description-input bg-light" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][onhand]" class="form-control form-control-sm text-center onhand-input bg-light" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][qty]" class="form-control form-control-sm text-center qty-input" step="any">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][rate]" class="form-control form-control-sm text-end rate-input" step="any">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][amount]" class="form-control form-control-sm text-end amount-input bg-light" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][disc_percent]" class="form-control form-control-sm text-center disc-percent-input" step="any" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][discount]" class="form-control form-control-sm text-end discount-input" step="any" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][total]" class="form-control form-control-sm text-end total-input bg-light fw-bold" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][location]" class="form-control form-control-sm text-center location-input bg-light" value="Main Stock" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][unit]" class="form-control form-control-sm bg-light text-center unit-input" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-light-subtle">
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Total</td>
                                        <td><input type="text" class="form-control form-control-sm text-center footer-qty bg-light" readonly value="0.00"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control form-control-sm text-end footer-amount bg-light" readonly value="0.00"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control form-control-sm text-end footer-discount bg-light" readonly value="0.00"></td>
                                        <td><input type="text" class="form-control form-control-sm text-end footer-total bg-light" readonly value="0.00"></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Summary -->
                    <div class="card-footer bg-light-subtle">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold mb-1">Ex.Rate</label>
                                    <input type="number" name="exchange_rate" class="form-control form-control-sm" value="1.00" step="any">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                                    <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" value="LKR" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                                    <select name="account_id" class="form-select form-select-sm" required>
                                        <option value="">-- Select Account --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold mb-1">Memo</label>
                                    <textarea name="memo" class="form-control form-control-sm" rows="5" placeholder="memo">{{ old('memo') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="footer-total-box border">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold small">Sub Total</span>
                                        <input type="text" name="subtotal" class="form-control form-control-sm text-end summary-subtotal bg-light w-50" readonly value="0.00">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 gap-2">
                                        <div class="w-50">
                                            <span class="fw-bold small">Discount %</span>
                                            <input type="number" name="header_discount_percent" class="form-control form-control-sm text-end header-discount-percent" step="any" value="0">
                                        </div>
                                        <div class="w-50">
                                            <span class="fw-bold small">Discount</span>
                                            <input type="number" name="header_discount_amount" class="form-control form-control-sm text-end header-discount-amount" step="any" value="0.00">
                                        </div>
                                    </div>
                                    <div class="form-check form-switch form-switch-md mb-2">
                                        <input class="form-check-input" type="checkbox" id="svatSwitch" name="is_svat" value="1">
                                        <label class="form-check-label fw-bold small" for="svatSwitch">SVAT</label>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 gap-2">
                                        <div class="w-50">
                                            <span class="fw-bold small">SSCL %</span>
                                            <input type="number" name="sscl_percent" class="form-control form-control-sm text-end" step="any" value="0.00">
                                        </div>
                                        <div class="w-50">
                                            <span class="fw-bold small">SSCL</span>
                                            <input type="number" name="sscl_amount" class="form-control form-control-sm text-end bg-light" readonly value="0.00">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 gap-2">
                                        <div class="w-50">
                                            <span class="fw-bold small">VAT %</span>
                                            <input type="number" name="vat_percent" class="form-control form-control-sm text-end" step="any" value="0.00">
                                        </div>
                                        <div class="w-50">
                                            <span class="fw-bold small">VAT</span>
                                            <input type="number" name="vat_amount" class="form-control form-control-sm text-end bg-light" readonly value="0.00">
                                        </div>
                                    </div>
                                    <input type="hidden" name="tax_amount" class="summary-tax-total" value="0.00">
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="fw-bold text-primary fs-15">Total</span>
                                        <input type="text" name="total_amount" class="form-control form-control-sm text-end summary-total bg-light w-50 fw-bold text-primary" readonly value="0.00">
                                        <input type="hidden" class="footer-grand-total" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Blade generated Product List for Guaranteed Client-Side Usage (Safe JSON) -->
                    <script>
                        window.serverProductList = @json($products);
                        window.oldItems = @json(old('items'));
                    </script>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/tom-select/js/tom-select.base.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const vendorSelect = document.querySelector('select[name="vendor_id"]');
        const addressTextarea = document.querySelector('textarea[name="address"]');
        const deliveryDestinationTextarea = document.querySelector('textarea[name="delivery_destination"]');
        const termsSelect = document.getElementById('termsSelect');
        const creditLimitSpan = document.getElementById('vendor-credit-limit');
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const loadDropdown = document.getElementById('loadDropdown');

        function fetchVendorGrns(vendorId) {
            if (!loadDropdown) return;

            const loadHidden = document.getElementById('grnReturnLoadSourceField');
            if (loadHidden) loadHidden.value = '';

            // Clear current options
            if (loadDropdown.tomselect) {
                loadDropdown.tomselect.clear(true);
                loadDropdown.tomselect.clearOptions();
                loadDropdown.tomselect.addOption({ value: '', text: 'Select GRN to copy' });
            } else {
                loadDropdown.innerHTML = '<option value="">Select GRN to copy</option>';
            }

            if (!vendorId) return;

            fetch(`/ajax/vendors/${vendorId}/grns`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) throw new Error('GRN list request failed: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    const rows = Array.isArray(data) ? data : [];
                    const options = rows.map(r => ({
                        value: String(r.id),
                        text: `${r.grn_no || 'GRN'} - ${r.date || ''} (Rs. ${parseFloat(r.total_amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
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
                .catch(error => console.error('Error fetching vendor GRNs:', error));
        }

        function normalizeGrnItemsPayload(res) {
            let items = res.items || res.grn_items || (res.data && res.data.items) || (res.grn && res.grn.items) || [];
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

        function applyLoadedGrnHeader(r) {
            if (!r || typeof r !== 'object') return;

            const loadHidden = document.getElementById('grnReturnLoadSourceField');
            if (loadHidden && r.grn_no) {
                loadHidden.value = r.grn_no;
            }

            const setInput = (name, val) => {
                if (val === undefined || val === null) return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el || el.type === 'checkbox') return;
                el.value = val;
            };

            setInput('reference_no', r.reference_no);
            setInput('invoice_date', r.invoice_date);
            setInput('attent', r.attent);
            setInput('memo', r.memo);

            if (addressTextarea && r.address !== undefined) {
                addressTextarea.value = r.address || '';
            }
            if (deliveryDestinationTextarea && r.delivery_destination !== undefined) {
                deliveryDestinationTextarea.value = r.delivery_destination || '';
            }

            setSelectByValue(document.querySelector('select[name="location_id"]'), r.location_id);
            setSelectByValue(document.getElementById('termsSelect'), r.payment_term_id);
            setSelectByValue(document.querySelector('select[name="order_by"]'), r.order_by);
            setSelectByValue(document.querySelector('select[name="checked_by"]'), r.checked_by);
            setSelectByValue(document.querySelector('select[name="rep"]'), r.rep);
            setSelectByValue(document.querySelector('select[name="account_id"]'), r.account_id);

            const numericPairs = [
                ['sscl_percent', r.sscl_percent],
                ['sscl_amount', r.sscl_amount],
                ['vat_percent', r.vat_percent],
                ['vat_amount', r.vat_amount],
                ['subtotal', r.subtotal],
                ['header_discount_percent', r.header_discount_percent],
                ['header_discount_amount', r.header_discount_amount],
                ['tax_amount', r.tax_amount],
                ['total_amount', r.total_amount],
            ];
            numericPairs.forEach(([name, val]) => {
                if (val === undefined || val === null || val === '') return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return;
                const n = parseFloat(val);
                el.value = Number.isFinite(n) ? n.toFixed(2) : String(val);
            });

            if (typeof grnReturnController !== 'undefined' && grnReturnController.calculateGrandTotal) {
                setTimeout(() => grnReturnController.calculateGrandTotal(), 0);
            }
        }

        function loadGrnDetails(grnId) {
            if (!grnId) return;

            const loadContainer = loadDropdown && loadDropdown.closest('.col-md-4');
            const labelEl = loadContainer && loadContainer.querySelector('label');
            const originalLabel = labelEl ? labelEl.innerHTML : '';

            if (labelEl) {
                labelEl.innerHTML = 'Load <span class="spinner-border spinner-border-sm text-primary" role="status"></span>';
            }

            fetch(`/ajax/grns/${grnId}/details`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('GRN details request failed: ' + response.status);
                    }
                    return response.json();
                })
                .then(res => {
                    const selectedVendorId = vendorSelect && vendorSelect.tomselect
                        ? vendorSelect.tomselect.getValue()
                        : (vendorSelect ? vendorSelect.value : '');
                    
                    if (res.grn && res.grn.vendor_id != null && String(res.grn.vendor_id) !== String(selectedVendorId || '')) {
                        alert('This GRN belongs to a different vendor. Select the correct vendor first.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    if (res.grn) {
                        applyLoadedGrnHeader(res.grn);
                    }

                    const items = normalizeGrnItemsPayload(res);
                    if (items.length === 0) {
                        alert('No items found in this GRN.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    // 1. Clear Table
                    itemsTableBody.innerHTML = '';
                    grnReturnController.data = [];
                    grnReturnController.rowCount = 0;

                    grnReturnController._loadingGrn = true;

                    // 2. Sequential Row Appending
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

                        grnReturnController.appendRow(rowData);
                    });

                    // 3. Map Selectors & Trigger Calculations
                    setTimeout(() => {
                        const allRows = itemsTableBody.querySelectorAll('tr.item-row');
                        allRows.forEach((row) => {
                            const dataIdx = parseInt(row.dataset.rowIndex, 10);
                            const rd = grnReturnController.data[dataIdx];
                            if (!rd) return;

                            const productSelect = row.querySelector('.product-select');
                            const qtyInput = row.querySelector('.qty-input');
                            const rateInput = row.querySelector('.rate-input');
                            const discPercentInput = row.querySelector('.disc-percent-input');
                            const discountInput = row.querySelector('.discount-input');

                            if (productSelect) {
                                if (productSelect.tomselect) {
                                    productSelect.tomselect.setValue(String(rd.product_id), true);
                                } else {
                                    productSelect.value = String(rd.product_id);
                                }
                            }

                            if (qtyInput) qtyInput.value = rd.qty;
                            if (rateInput) rateInput.value = rd.rate.toFixed(2);
                            if (discPercentInput) discPercentInput.value = rd.disc_percent || '';
                            if (discountInput) discountInput.value = rd.discount > 0 ? rd.discount.toFixed(2) : '';

                            // Trigger individual row calculation
                            const rowCalcSource = parseFloat(rd.disc_percent) > 0 ? 'disc_percent' : 'discount';
                            grnReturnController.calculateRow(dataIdx, row, rowCalcSource);
                        });

                        // 4. Final Global Totals
                        grnReturnController.appendRow(); // Add one empty row at the end
                        grnReturnController.calculateGrandTotal();
                        grnReturnController._loadingGrn = false;

                        if (labelEl) labelEl.innerHTML = originalLabel;
                        
                        const itemsTable = document.getElementById('itemsTable');
                        if (itemsTable) itemsTable.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 300);
                })
                .catch(error => {
                    console.error('CRITICAL LOAD ERROR:', error);
                    grnReturnController._loadingGrn = false;
                    if (labelEl) labelEl.innerHTML = originalLabel;
                    alert('Error: Data could not be loaded. Check console for details.');
                });
        }

        function fetchVendorDetails(vendorId) {
            if (vendorId) {
                const url = "{{ url('api/vendors') }}/" + vendorId;
                fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea) deliveryDestinationTextarea.value = data.delivery_address || '';
                        
                        if (creditLimitSpan) creditLimitSpan.innerText = parseFloat(data.credit_limit || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                        
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

                        // Fetch GRNs for Load dropdown
                        fetchVendorGrns(vendorId);
                    })
                    .catch(error => console.error('Error fetching vendor details:', error));
            } else {
                fetchVendorGrns(null);
            }
        }

        function attachVendorListener() {
            if (vendorSelect.tomselect) {
                vendorSelect.tomselect.on('change', function(value) {
                    fetchVendorDetails(value);
                });
                if (vendorSelect.tomselect.getValue()) {
                    fetchVendorDetails(vendorSelect.tomselect.getValue());
                }
            } else {
                vendorSelect.addEventListener('change', function () {
                    fetchVendorDetails(this.value);
                });
                if (this.value) {
                    fetchVendorDetails(this.value);
                }
            }
        }

        setTimeout(attachVendorListener, 500);

        setTimeout(function() { 
            let accSelect = document.getElementById('account_id') || document.querySelector('select[name="account_id"]'); 
            if (accSelect) { 
                // More robust match for "Payable"
                let accOpt = Array.from(accSelect.options).find(opt => opt.text.toLowerCase().includes('payab')); 
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
                    placeholder: "Select GRN to copy",
                    allowEmptyOption: true,
                    onChange: function(value) {
                        if (value) {
                            loadGrnDetails(value);
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
            if (termsSelect && window.TomSelect && !termsSelect.tomselect) {
                new TomSelect(termsSelect, { create: false });
            }
            initLoadDropdown(); // Fallback initialization
        }, 600);

        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        // --- Table Controller ---
        const grnReturnController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',
            _loadingGrn: false,

            init() {
                const table = document.getElementById('itemsTable');
                if (!table) return;
                const tbody = table.querySelector('tbody');
                if (!tbody) return;

                const firstRow = tbody.querySelector('.item-row');
                if (!firstRow) return;
                
                this.rowTemplateHTML = firstRow.innerHTML;
                
                // Clear items
                tbody.innerHTML = '';
                this.data = [];
                this.rowCount = 0;

                if (window.oldItems && window.oldItems.length > 0) {
                    window.oldItems.forEach(item => {
                        this.appendRow(item);
                    });
                    this.appendRow();
                } else {
                    // Default TWO empty rows
                    this.appendRow();
                    this.appendRow();
                }
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

                newRow.querySelectorAll('[name]').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });

                newRow.dataset.rowIndex = index;
                const tbody = document.querySelector('#itemsTable tbody');
                if (tbody) {
                    tbody.appendChild(newRow);
                    initRowEvents(newRow);
                }
            },

            updateRowData(rowIndex, field, value) {
                if (this.data[rowIndex]) {
                    this.data[rowIndex][field] = value;
                }
            },

            calculateRow(rowIndex, rowElement, sourceField = 'disc_percent') {
                if (!this.data[rowIndex]) return;
                
                const dataRow = this.data[rowIndex];
                const qty = parseFloat(dataRow.qty) || 0;
                const rate = parseFloat(dataRow.rate) || 0;
                
                dataRow.amount = qty * rate;
                
                if (sourceField === 'disc_percent') {
                    const discPercent = parseFloat(dataRow.disc_percent) || 0;
                    dataRow.discount = (dataRow.amount * discPercent) / 100;
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
                let grandGrossAmount = 0;
                let grandRowDiscount = 0;
                let grandNetTotal = 0; 

                this.data.forEach(row => {
                    if (row.product_id) {
                        grandQty += parseFloat(row.qty) || 0;
                        grandGrossAmount += (parseFloat(row.qty) || 0) * (parseFloat(row.rate) || 0);
                        grandRowDiscount += parseFloat(row.discount) || 0;
                        grandNetTotal += parseFloat(row.total) || 0;
                    }
                });
    
                document.querySelector('.footer-qty').value = grandQty.toFixed(2);
                document.querySelector('.footer-amount').value = grandGrossAmount.toFixed(2);
                document.querySelector('.footer-discount').value = grandRowDiscount.toFixed(2);
                document.querySelector('.footer-total').value = grandNetTotal.toFixed(2);
                
                // Summary calculation
                const subTotal = grandNetTotal; 
                document.querySelector('.summary-subtotal').value = subTotal.toFixed(2);
                
                const headerDiscPercentInput = document.querySelector('.header-discount-percent');
                const headerDiscAmountInput = document.querySelector('.header-discount-amount');
                
                let headerDiscPercent = parseFloat(headerDiscPercentInput.value) || 0;
                let headerDiscAmount = parseFloat(headerDiscAmountInput.value) || 0;
                
                if (sourceField === 'header_percent') {
                    headerDiscAmount = (subTotal * headerDiscPercent) / 100;
                    headerDiscAmountInput.value = headerDiscAmount > 0 ? headerDiscAmount.toFixed(2) : '';
                } else if (sourceField === 'header_amount') {
                    if (subTotal > 0) {
                        headerDiscPercent = (headerDiscAmount / subTotal) * 100;
                        headerDiscPercentInput.value = headerDiscPercent > 0 ? headerDiscPercent.toFixed(2) : '';
                    } else {
                        headerDiscPercentInput.value = '';
                    }
                } else if (sourceField === 'none' && headerDiscPercent > 0) {
                    headerDiscAmount = (subTotal * headerDiscPercent) / 100;
                    headerDiscAmountInput.value = headerDiscAmount > 0 ? headerDiscAmount.toFixed(2) : '';
                }
                
                // SSCL and VAT
                const ssclPercentInput = document.querySelector('input[name="sscl_percent"]');
                const ssclAmountInput = document.querySelector('input[name="sscl_amount"]');
                const vatPercentInput = document.querySelector('input[name="vat_percent"]');
                const vatAmountInput = document.querySelector('input[name="vat_amount"]');
                const svatSwitch = document.getElementById('svatSwitch');

                let amountAfterHeaderDisc = subTotal - headerDiscAmount;
                
                let ssclPercent = parseFloat(ssclPercentInput.value) || 0;
                let ssclAmount = (amountAfterHeaderDisc * ssclPercent) / 100;
                ssclAmountInput.value = ssclAmount.toFixed(2);

                let amountAfterSSCL = amountAfterHeaderDisc + ssclAmount;
                let vatPercent = parseFloat(vatPercentInput.value) || 0;
                let vatAmount = 0;
                
                if (svatSwitch && !svatSwitch.checked) {
                    vatAmount = (amountAfterSSCL * vatPercent) / 100;
                }
                vatAmountInput.value = vatAmount.toFixed(2);

                const finalTotal = amountAfterSSCL + vatAmount;
                const taxTotal = ssclAmount + vatAmount;
                
                document.querySelector('.summary-tax-total').value = taxTotal.toFixed(2);
                document.querySelector('.summary-total').value = finalTotal.toFixed(2);
                document.querySelector('.footer-grand-total').value = finalTotal.toFixed(2);
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if (!productId || !location) {
                onhandInput.value = '';
                grnReturnController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            onhandInput.value = '...';
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    grnReturnController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    onhandInput.value = '0';
                    grnReturnController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');
            const discountInput = row.querySelector('.discount-input');

            if (!qtyInput.value) qtyInput.value = '1';

            function handleProductChange(selectedOption, value) {
                grnReturnController.updateRowData(rowIndex, 'product_id', value);
                
                if (grnReturnController._loadingGrn) return;

                if (value && selectedOption) {
                    const desc = selectedOption.dataset.name || '';
                    const unit = selectedOption.dataset.unit || '';
                    const rate = parseFloat(selectedOption.dataset.rate) || 0;

                    grnReturnController.updateRowData(rowIndex, 'description', desc);
                    grnReturnController.updateRowData(rowIndex, 'unit', unit);
                    grnReturnController.updateRowData(rowIndex, 'rate', rate);

                    row.querySelector('.description-input').value = desc;
                    row.querySelector('.unit-input').value = unit;
                    row.querySelector('.rate-input').value = rate;
                    
                    const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                    fetchItemStock(value, currentLoc, rowIndex, row);

                    grnReturnController.calculateRow(rowIndex, row);
                    grnReturnController.checkAndAppendRow(rowIndex);
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    row.querySelector('.onhand-input').value = '';
                    grnReturnController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                // Clear existing options
                productSelect.innerHTML = '<option value="">Select Item</option>';
            }

            if (window.TomSelect) {
                if (productSelect.tomselect) {
                    productSelect.tomselect.destroy();
                }

                // Prepare options for TomSelect
                const tsOptions = [];
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        tsOptions.push({
                            value: String(p.id),
                            text: p.code || '',
                            name: p.name || '',
                            unit: p.unit || '',
                            rate: p.cost || 0
                        });
                    });
                }

                new TomSelect(productSelect, {
                    options: tsOptions,
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
                    onChange: function(value) {
                        let selectedOption = null;
                        if (value) {
                            const data = this.options[value];
                            selectedOption = {
                                dataset: {
                                    name: data.name || '',
                                    unit: data.unit || '',
                                    rate: data.rate || 0
                                }
                            };
                        }
                        handleProductChange(selectedOption, value);
                    }
                });
            }

            [qtyInput, rateInput, discPercentInput, discountInput].forEach(input => {
                input.addEventListener('input', function() {
                    let fieldName = 'qty';
                    let sourceField = 'disc_percent';

                    if (this.classList.contains('rate-input')) fieldName = 'rate';
                    if (this.classList.contains('disc-percent-input')) {
                        fieldName = 'disc_percent';
                        sourceField = 'disc_percent';
                    }
                    if (this.classList.contains('discount-input')) {
                        fieldName = 'discount';
                        sourceField = 'discount';
                    }
                    
                    grnReturnController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    grnReturnController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        // Header Listeners
        const ssclPercentInput = document.querySelector('input[name="sscl_percent"]');
        const vatPercentInput = document.querySelector('input[name="vat_percent"]');
        const svatSwitch = document.getElementById('svatSwitch');
        const headerDiscPercentInput = document.querySelector('.header-discount-percent');
        const headerDiscAmountInput = document.querySelector('.header-discount-amount');

        if (ssclPercentInput) ssclPercentInput.addEventListener('input', () => grnReturnController.calculateGrandTotal());
        if (vatPercentInput) vatPercentInput.addEventListener('input', () => grnReturnController.calculateGrandTotal());
        if (svatSwitch) svatSwitch.addEventListener('change', () => grnReturnController.calculateGrandTotal());
        if (headerDiscPercentInput) headerDiscPercentInput.addEventListener('input', () => grnReturnController.calculateGrandTotal('header_percent'));
        if (headerDiscAmountInput) headerDiscAmountInput.addEventListener('input', () => grnReturnController.calculateGrandTotal('header_amount'));

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
                            grnReturnController.updateRowData(rowIndex, 'location', locationName);
                            const productSelect = row.querySelector('.product-select');
                            const productId = productSelect ? productSelect.value : '';
                            if (productId) fetchItemStock(productId, locationName, rowIndex, row);
                        }
                    }
                });
            });
        }

        vendorSelect.addEventListener('change', function () {
            fetchVendorDetails(this.value);
        });

        setTimeout(() => {
            if (vendorSelect.tomselect) {
                vendorSelect.tomselect.on('change', function (value) {
                    fetchVendorDetails(value);
                });
            }
            if (termsSelect && window.TomSelect) {
                new TomSelect(termsSelect, { create: false });
            }
        }, 500);

        // --- Form Submission Fix --- //
        const form = document.getElementById('createGrnReturnForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                const rows = document.querySelectorAll('#itemsTable tbody tr.item-row');
                let validRowIndex = 0;
                let hasValidRow = false;

                rows.forEach((row) => {
                    const productSelect = row.querySelector('.product-select');
                    const productId = productSelect ? productSelect.value : '';

                    if (productId) {
                        hasValidRow = true;
                        // Re-index the names to be sequential
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
                        // Remove names from empty rows so they are not submitted
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.name) el.removeAttribute('name');
                        });
                    }
                });

                if (!hasValidRow) {
                    e.preventDefault();
                    alert('Please add at least one valid item to the GRN return.');
                }
            });
        }

        // Initialize Table at the very end to ensure all functions are defined
        if (typeof grnReturnController !== 'undefined') {
            grnReturnController.init();
        }
    });
</script>
@endpush
