@extends('layouts.admin')

@section('title', 'Purchase Order - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Purchase Order</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold">Credit Limit: <span id="vendor-credit-limit">0.00</span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-shopping-basket-2-line me-1"></i>Purchase Order - Create</h5>
                <div class="float-end">
                    <button type="submit" name="action" value="save_and_new" form="createPurchaseOrderForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" name="action" value="save_and_close" form="createPurchaseOrderForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="reset" form="createPurchaseOrderForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createPurchaseOrderForm" action="{{ route('purchase-orders.store') }}" method="POST">
                    @csrf

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Vendor Name <span class="text-danger">*</span></label>
                            <select name="vendor_id" class="form-select form-select-sm" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->company_name ?? $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                             <select name="location_id" class="form-select form-select-sm" required>
                                 <option value="">Select Location</option>
                                 @foreach($locations as $location)
                                     <option value="{{ $location->id }}" data-name="{{ $location->name }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                 @endforeach
                             </select>
                         </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Load <span class="text-muted fw-normal">(prior Purchase Order)</span></label>
                            <select id="loadDropdown" class="form-select form-select-sm">
                                <option value="">Select Purchase Order to copy</option>
                            </select>
                            <input type="hidden" name="load" id="poLoadSourceField" value="">
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
                                <label class="form-label small fw-bold mb-0">PO No</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $nextPoNo }}" readonly>
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
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('order_by') == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Checked By</label>
                            <select name="checked_by" class="form-select form-select-sm">
                                <option value="">Select Checked By</option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('checked_by') == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Rep</label>
                            <select name="rep" class="form-select form-select-sm">
                                <option value="">Select Rep</option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('rep') == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
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
                                    <th class="fw-bold py-2 text-uppercase">Item Code</th>
                                    <th class="fw-bold py-2 text-uppercase">Description</th>
                                    <th class="fw-bold py-2 text-uppercase">OnHand</th>
                                    <th class="fw-bold py-2 text-uppercase">Qty</th>
                                    <th class="fw-bold py-2 text-uppercase">Rate(LKR)</th>
                                    <th class="fw-bold py-2 text-uppercase">Amount</th>
                                    <th class="fw-bold py-2 text-uppercase">Disc%</th>
                                    <th class="fw-bold py-2 text-uppercase">Discount</th>
                                    <th class="fw-bold py-2 text-uppercase">Total</th>
                                    <th class="fw-bold py-2 text-uppercase">Location</th>
                                    <th class="fw-bold py-2 text-uppercase">Unit</th>
                                    <th class="fw-bold py-2 text-uppercase" style="width: 40px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select form-select-sm product-select border-0"><option value="">Select Item</option></select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm onhand-input text-center bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-center qty-input" step="any" value="1"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end rate-input" step="any" value="0.00"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end amount-input bg-light" value="0.00" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-center disc-percent-input" step="any" placeholder="0"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end discount-input" step="any" placeholder="0.00"></td>
                                    <td><input type="number" class="form-control form-control-sm text-end total-input bg-light fw-bold" value="0.00" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm location-input text-center bg-light" value="Main Stock" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty fw-bold" readonly></td>
                                    <td class="text-end fw-bold">Amount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-amount fw-bold" readonly></td>
                                    <td class="text-end fw-bold">Discount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-discount fw-bold" readonly></td>
                                    <td class="text-end fw-bold">Total</td>
                                    <td colspan="3"><input type="text" class="form-control form-control-sm text-end bg-white fw-bold footer-total" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Javascript Hydration Source -->
                    <script>
                        window.serverProductList = @json($products ?? []);
                    </script>

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
                                            <input type="text" name="sscl_percent" class="form-control form-control-sm text-center" value="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">SSCL</label>
                                            <input type="text" name="sscl_amount" class="form-control form-control-sm text-end" value="0.00">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT %</label>
                                            <input type="text" name="vat_percent" class="form-control form-control-sm text-center" value="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT</label>
                                            <input type="text" name="vat_amount" class="form-control form-control-sm text-end" value="0.00">
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
        const vendorSelect = document.querySelector('select[name="vendor_id"]');
        const addressTextarea = document.querySelector('textarea[name="address"]');
        const deliveryDestinationTextarea = document.querySelector('textarea[name="delivery_destination"]');
        const termsSelect = document.getElementById('termsSelect');
        const creditLimitSpan = document.getElementById('vendor-credit-limit');
        const loadDropdown = document.getElementById('loadDropdown');

        function fetchVendorPurchaseOrders(vendorId) {
            if (!loadDropdown) return;

            const loadHidden = document.getElementById('poLoadSourceField');
            if (loadHidden) loadHidden.value = '';

            // Clear current options
            if (loadDropdown.tomselect) {
                loadDropdown.tomselect.clear(true);
                loadDropdown.tomselect.clearOptions();
                loadDropdown.tomselect.addOption({ value: '', text: 'Select PO to copy' });
            } else {
                loadDropdown.innerHTML = '<option value="">Select PO to copy</option>';
            }

            if (!vendorId) return;

            fetch(`/ajax/vendors/${vendorId}/purchase-orders`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) throw new Error('PO list request failed: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    const rows = Array.isArray(data) ? data : [];
                    const options = rows.map(po => ({
                        value: String(po.id),
                        text: `${po.po_no || 'PO'} - ${po.date || ''} (Rs. ${parseFloat(po.total_amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
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
                .catch(error => console.error('Error fetching vendor POs:', error));
        }

        function normalizePoItemsPayload(res) {
            let items = res.items || res.po_items || (res.data && res.data.items) || (res.po && res.po.items) || [];
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

        function applyLoadedPurchaseOrderHeader(po) {
            if (!po || typeof po !== 'object') return;

            const loadHidden = document.getElementById('poLoadSourceField');
            if (loadHidden && po.po_no) {
                loadHidden.value = po.po_no;
            }

            const setInput = (name, val) => {
                if (val === undefined || val === null) return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el || el.type === 'checkbox') return;
                el.value = val;
            };

            setInput('reference_no', po.reference_no);
            setInput('date', po.date);
            setInput('expected_date', po.expected_date);
            setInput('due_date', po.due_date);
            setInput('attent', po.attent);
            setInput('memo', po.memo);

            if (addressTextarea && po.address !== undefined) {
                addressTextarea.value = po.address || '';
            }
            if (deliveryDestinationTextarea && po.delivery_destination !== undefined) {
                deliveryDestinationTextarea.value = po.delivery_destination || '';
            }

            setSelectByValue(document.querySelector('select[name="location_id"]'), po.location_id);
            setSelectByValue(document.getElementById('termsSelect'), po.payment_term_id);
            setSelectByValue(document.querySelector('select[name="order_by"]'), po.order_by);
            setSelectByValue(document.querySelector('select[name="checked_by"]'), po.checked_by);
            setSelectByValue(document.querySelector('select[name="rep"]'), po.rep);
            setSelectByValue(document.querySelector('select[name="account_id"]'), po.account_id);

            const numericPairs = [
                ['sscl_percent', po.sscl_percent],
                ['sscl_amount', po.sscl_amount],
                ['vat_percent', po.vat_percent],
                ['vat_amount', po.vat_amount],
                ['subtotal', po.subtotal],
                ['header_discount_percent', po.header_discount_percent],
                ['header_discount_amount', po.header_discount_amount],
                ['tax_amount', po.tax_amount],
                ['total_amount', po.total_amount],
            ];
            numericPairs.forEach(([name, val]) => {
                if (val === undefined || val === null || val === '') return;
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return;
                const n = parseFloat(val);
                el.value = Number.isFinite(n) ? n.toFixed(2) : String(val);
            });

            if (typeof purchaseOrderController !== 'undefined' && purchaseOrderController.calculateGrandTotal) {
                setTimeout(() => purchaseOrderController.calculateGrandTotal(), 0);
            }
        }

        function loadPurchaseOrderDetails(poId) {
            if (!poId) return;

            const loadContainer = loadDropdown && loadDropdown.closest('.col-md-4');
            const labelEl = loadContainer && loadContainer.querySelector('label');
            const originalLabel = labelEl ? labelEl.innerHTML : '';

            if (labelEl) {
                labelEl.innerHTML = 'Load <span class="spinner-border spinner-border-sm text-primary" role="status"></span>';
            }

            fetch(`/ajax/purchase-orders/${poId}/details`, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('PO details request failed: ' + response.status);
                    }
                    return response.json();
                })
                .then(res => {
                    const selectedVendorId = vendorSelect && vendorSelect.tomselect
                        ? vendorSelect.tomselect.getValue()
                        : (vendorSelect ? vendorSelect.value : '');
                    if (res.po && res.po.vendor_id != null && String(res.po.vendor_id) !== String(selectedVendorId || '')) {
                        alert('This Purchase Order belongs to a different vendor. Select the correct vendor first.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }
                    if (res.po) {
                        applyLoadedPurchaseOrderHeader(res.po);
                    }
                    const items = normalizePoItemsPayload(res);
                    if (items.length === 0) {
                        alert('No items found in this Purchase Order.');
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    const tbody = document.querySelector('#itemsTable tbody');
                    if (!tbody) {
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    tbody.innerHTML = '';
                    purchaseOrderController.data = [];
                    purchaseOrderController.rowCount = 0;

                    purchaseOrderController._loadingPo = true;

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
                        purchaseOrderController.appendRow(rowData);
                        appended++;
                    });

                    if (appended === 0) {
                        purchaseOrderController._loadingPo = false;
                        alert('No valid line items (missing product) in this Purchase Order.');
                        purchaseOrderController.appendRow();
                        purchaseOrderController.appendRow();
                        if (labelEl) labelEl.innerHTML = originalLabel;
                        return;
                    }

                    setTimeout(() => {
                        const allRows = tbody.querySelectorAll('tr.item-row');
                        allRows.forEach((row, idx) => {
                            if (idx >= loadedRowsData.length) return;

                            const rd = loadedRowsData[idx];
                            const dataIdx = parseInt(row.dataset.rowIndex, 10);
                            if (isNaN(dataIdx) || !purchaseOrderController.data[dataIdx]) return;

                            const d = purchaseOrderController.data[dataIdx];
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
                            purchaseOrderController.calculateRow(dataIdx, row, rowCalcSource);

                            if (productSelect && productSelect.value && rd.location) {
                                fetchItemStock(productSelect.value, rd.location, dataIdx, row);
                            }
                        });

                        purchaseOrderController.appendRow();
                        purchaseOrderController.calculateGrandTotal();

                        purchaseOrderController._loadingPo = false;

                        if (labelEl) labelEl.innerHTML = originalLabel;

                        const itemsTable = document.getElementById('itemsTable');
                        if (itemsTable) itemsTable.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 50);
                })
                .catch(error => {
                    console.error('CRITICAL LOAD ERROR:', error);
                    purchaseOrderController._loadingPo = false;
                    if (labelEl) labelEl.innerHTML = originalLabel;
                    alert('Error: Data could not be loaded. Check console for details.');
                });
        }

        function fetchVendorDetails(vendorId) {
            if (vendorId) {
                const url = "{{ url('api/vendors') }}/" + vendorId;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea) deliveryDestinationTextarea.value = data.delivery_address || '';
                        
                        if (creditLimitSpan) creditLimitSpan.innerText = parseFloat(data.credit_limit || 0).toLocaleString(undefined, {minimumFractionDigits: 2});

                        if (termsSelect && data.terms) {
                            let matchedOption = Array.from(termsSelect.options).find(opt => opt.value == data.terms);
                            
                            if (!matchedOption && data.terms) {
                                let daysMatch = data.terms.match(/\d+/);
                                if (daysMatch) {
                                    let days = parseInt(daysMatch[0]);
                                    matchedOption = Array.from(termsSelect.options).find(opt => opt.dataset.days == days);
                                }
                                
                                if (!matchedOption) {
                                    matchedOption = Array.from(termsSelect.options).find(opt => opt.text && opt.text.toLowerCase().includes(data.terms.toLowerCase()));
                                }
                            }
                            
                            if (matchedOption) {
                                termsSelect.value = matchedOption.value;
                                if (termsSelect.tomselect) {
                                    termsSelect.tomselect.setValue(matchedOption.value);
                                }
                            }
                        }

                        // Fetch POs for Load dropdown
                        fetchVendorPurchaseOrders(vendorId);
                    })
                    .catch(error => console.error('Error fetching vendor details:', error));
            } else {
                fetchVendorPurchaseOrders(null);
            }
        }

        // Standard change event
        vendorSelect.addEventListener('change', function () {
            fetchVendorDetails(this.value);
        });

        setTimeout(() => {
            if (vendorSelect.tomselect) {
                vendorSelect.tomselect.on('change', function (value) {
                    fetchVendorDetails(value);
                });
            }
        }, 500);

        // --- Table Controller (Data Source Level) --- //
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        const purchaseOrderController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',

            init() {
                const firstRow = document.querySelector('.item-row');
                this.rowTemplateHTML = firstRow.innerHTML;
                firstRow.remove();

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
                
                // Construct clean HTML with unique IDs if needed, but classes are enough
                newRow.innerHTML = `
                    <td>
                        <select class="form-select form-select-sm product-select border-0" name="items[${index}][product_id]">
                            <option value="">Select Item</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm description-input bg-light" name="items[${index}][description]" value="${data.description}" readonly></td>
                    <td><input type="text" class="form-control form-control-sm onhand-input text-center bg-light" name="items[${index}][onhand]" value="${data.onhand}" readonly></td>
                    <td><input type="number" class="form-control form-control-sm text-center qty-input" name="items[${index}][qty]" step="any" value="${data.qty}"></td>
                    <td><input type="number" class="form-control form-control-sm text-end rate-input" name="items[${index}][rate]" step="any" value="${data.rate.toFixed(2)}"></td>
                    <td><input type="number" class="form-control form-control-sm text-end amount-input bg-light" name="items[${index}][amount]" value="${data.amount.toFixed(2)}" readonly></td>
                    <td><input type="number" class="form-control form-control-sm text-center disc-percent-input" name="items[${index}][disc_percent]" step="any" value="${data.disc_percent || ''}" placeholder="0"></td>
                    <td><input type="number" class="form-control form-control-sm text-end discount-input" name="items[${index}][discount]" step="any" value="${data.discount > 0 ? data.discount.toFixed(2) : ''}" placeholder="0.00"></td>
                    <td><input type="number" class="form-control form-control-sm text-end total-input bg-light fw-bold" name="items[${index}][total]" value="${data.total.toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control form-control-sm location-input text-center bg-light" name="items[${index}][location]" value="${data.location}" readonly></td>
                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" name="items[${index}][unit]" value="${data.unit}" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                    </td>
                `;

                document.querySelector('#itemsTable tbody').appendChild(newRow);
                
                // Ensure no existing TomSelect instance on this new element (though it shouldn't exist)
                const productSelect = newRow.querySelector('.product-select');
                if (productSelect && productSelect.tomselect) {
                    productSelect.tomselect.destroy();
                }
                
                initRowEvents(newRow);
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
                let grandAmount = 0;
                let grandDiscount = 0;
                let grandTotal = 0;

                this.data.forEach(row => {
                    if (row.product_id) {
                        grandQty += parseFloat(row.qty) || 0;
                        grandAmount += parseFloat(row.amount) || 0;
                        grandDiscount += parseFloat(row.discount) || 0;
                        grandTotal += parseFloat(row.total) || 0;
                    }
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

                const finalTotal = subTotal - headerDiscAmount;
                
                // Update LKR Grand Total
                const lkrSummary = document.querySelector('.footer-grand-total');
                if(lkrSummary) lkrSummary.value = finalTotal.toFixed(2);

                // Update Summary Section
                const subTotalInput = document.querySelector('.summary-subtotal');
                if(subTotalInput) subTotalInput.value = subTotal.toFixed(2);

                // Update Summary SSCL and VAT (Assuming 0 for now as per current view logic)
                const ssclPercentInput = document.querySelector('input[name="sscl_percent"]');
                const ssclAmountInput = document.querySelector('input[name="sscl_amount"]');
                const vatPercentInput = document.querySelector('input[name="vat_percent"]');
                const vatAmountInput = document.querySelector('input[name="vat_amount"]');

                let amountAfterHeaderDisc = subTotal - headerDiscAmount;
                
                let ssclPercent = parseFloat(ssclPercentInput ? ssclPercentInput.value : 0) || 0;
                let ssclAmount = (amountAfterHeaderDisc * ssclPercent) / 100;
                if(ssclAmountInput) ssclAmountInput.value = ssclAmount.toFixed(2);

                let amountAfterSSCL = amountAfterHeaderDisc + ssclAmount;
                let vatPercent = parseFloat(vatPercentInput ? vatPercentInput.value : 0) || 0;
                let vatAmount = (amountAfterSSCL * vatPercent) / 100;
                if(vatAmountInput) vatAmountInput.value = vatAmount.toFixed(2);

                const finalTotalWithTax = amountAfterSSCL + vatAmount;

                const totalInput = document.querySelector('.summary-total');
                if(totalInput) totalInput.value = finalTotalWithTax.toFixed(2);
                if(lkrSummary) lkrSummary.value = finalTotalWithTax.toFixed(2);
            },

            removeRow(rowIndex, rowElement) {
                if (this.data.length > 2) {
                    this.data.splice(rowIndex, 1);
                    
                    const select = rowElement.querySelector('.product-select');
                    if (select && select.tomselect) {
                        select.tomselect.destroy();
                    }
                    
                    rowElement.remove();
                    this.reindexRows();
                    this.calculateGrandTotal();
                } else {
                    const productSelect = rowElement.querySelector('.product-select');
                    if (productSelect && productSelect.tomselect) {
                        productSelect.tomselect.clear();
                    }
                    
                    rowElement.querySelectorAll('input').forEach(input => {
                        input.value = '';
                        if (input.classList.contains('qty-input')) input.value = '1';
                        if (input.classList.contains('rate-input')) input.value = '0.00';
                        if (input.classList.contains('amount-input')) input.value = '0.00';
                        if (input.classList.contains('total-input')) input.value = '0.00';
                    });
                    this.updateRowData(rowIndex, 'product_id', '');
                    this.updateRowData(rowIndex, 'description', '');
                    this.updateRowData(rowIndex, 'onhand', '');
                    this.updateRowData(rowIndex, 'qty', 1);
                    this.updateRowData(rowIndex, 'rate', 0);
                    this.updateRowData(rowIndex, 'amount', 0);
                    this.updateRowData(rowIndex, 'disc_percent', 0);
                    this.updateRowData(rowIndex, 'discount', 0);
                    this.updateRowData(rowIndex, 'total', 0);
                    this.calculateGrandTotal();
                }
            },

            reindexRows() {
                const rows = document.querySelectorAll('#itemsTable tbody tr.item-row');
                rows.forEach((row, idx) => {
                    row.dataset.rowIndex = idx;
                    row.querySelectorAll('input, select').forEach(el => {
                        if (el.name) {
                            el.name = el.name.replace(/items\[\d+\]/, `items[${idx}]`);
                        }
                    });
                });
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            if (!productId || !location) {
                onhandInput.value = '';
                purchaseOrderController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            onhandInput.value = '...';
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    purchaseOrderController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    onhandInput.value = '0';
                    purchaseOrderController.updateRowData(rowIndex, 'onhand', 0);
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

            function handleProductChange(value) {
                purchaseOrderController.updateRowData(rowIndex, 'product_id', value);

                if (purchaseOrderController._loadingPo) return;
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';
                        const rate = parseFloat(selectedObj.cost) || 0;

                        purchaseOrderController.updateRowData(rowIndex, 'description', desc);
                        purchaseOrderController.updateRowData(rowIndex, 'unit', unit);
                        purchaseOrderController.updateRowData(rowIndex, 'rate', rate);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.unit-input').value = unit;
                        row.querySelector('.rate-input').value = rate;
                        
                        const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        purchaseOrderController.calculateRow(rowIndex, row);
                        purchaseOrderController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                    purchaseOrderController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">Select Item</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = parseFloat(p.cost) || 0;
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-unit="${p.unit || ''}" data-rate="${rate}">${safeCode}</option>`;
                    });
                }
                productSelect.innerHTML = optionsHTML;
            }

            // TomSelect initialization pattern used in Invoice/GRN
            if (window.TomSelect) {
                if (productSelect.tomselect) {
                    productSelect.tomselect.destroy();
                }

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
                        purchaseOrderController.updateRowData(rowIndex, 'product_id', val);
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
                    purchaseOrderController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    purchaseOrderController.calculateRow(rowIndex, row, sourceField);
                });
            });

            const removeBtn = row.querySelector('.remove-row-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    purchaseOrderController.removeRow(rowIndex, row);
                });
            }
        }

        purchaseOrderController.init();

        const mainLocationSelect = document.querySelector('select[name="location_id"]');
        if (mainLocationSelect) {
            mainLocationSelect.addEventListener('change', function(e) {
                if (e.detail && e.detail.isSyncTrigger) return; 
                const selectedOption = this.options[this.selectedIndex];
                const locationName = selectedOption ? selectedOption.dataset.name : '';
                
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowLocationInput = row.querySelector('.location-input');
                    const rowIndex = parseInt(row.dataset.rowIndex);
                    
                    if (rowLocationInput && rowLocationInput.value !== locationName) {
                        rowLocationInput.value = locationName;
                        if (!isNaN(rowIndex)) {
                            purchaseOrderController.updateRowData(rowIndex, 'location', locationName);
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
        // Header Discount Events
        const headerDiscPercentInput = document.querySelector('.header-discount-percent');
        const headerDiscAmountInput = document.querySelector('.header-discount-amount');
        
        if (headerDiscPercentInput) {
            headerDiscPercentInput.addEventListener('input', () => {
                purchaseOrderController.calculateGrandTotal('header_percent');
            });
        }
        
        if (headerDiscAmountInput) {
            headerDiscAmountInput.addEventListener('input', () => {
                purchaseOrderController.calculateGrandTotal('header_amount');
            });
        }

        // Vendor Select Change
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

        setTimeout(function() { 
            let locSelect = document.getElementById('location_id') || document.querySelector('select[name="location_id"]'); 
            if (locSelect) { 
                // Find the valid element option index containing "main" 
                let mainOpt = Array.from(locSelect.options).find(opt => opt.text.toLowerCase().includes('main')); 
                if (mainOpt) { 
                    if (locSelect.tomselect) { 
                        locSelect.tomselect.setValue(mainOpt.value); 
                    } else { 
                        $(locSelect).val(mainOpt.value).trigger('change'); 
                    } 
                } 
            } 
        }, 650);

        function initLoadDropdown() {
            if (loadDropdown && window.TomSelect) {
                if (loadDropdown.tomselect) return; // Already initialized

                new TomSelect(loadDropdown, {
                    create: false,
                    placeholder: "Select PO to copy",
                    allowEmptyOption: true,
                    onChange: function(value) {
                        if (value) {
                            loadPurchaseOrderDetails(value);
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

        // --- Form Submission Fix --- //
        const form = document.getElementById('createPurchaseOrderForm');
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
                    alert('Please add at least one valid item to the purchase order.');
                }
            });
        }

    });
</script>
@endpush
