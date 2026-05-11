@extends('layouts.admin')

@section('title', 'GRN - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">GRN</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
                <span class="text-muted small fw-bold">Rs: 0.00</span>
                <span class="text-muted small fw-bold">Credit Limit: <span id="vendor-credit-limit">0.00</span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-file-download-line me-1"></i>GRN - Create</h5>
                <div class="float-end">
                    <button type="submit" name="action" value="save_and_new" form="createGrnForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" name="action" value="save_and_close" form="createGrnForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="reset" form="createGrnForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createGrnForm" action="{{ route('grns.store') }}" method="POST">
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
                                    <option value="{{ $location->id }}" data-name="{{ $location->name }}" {{ (old('location_id') == $location->id || $location->name == 'Main Stock') ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Load</label>
                            <select name="load" class="form-select form-select-sm">
                                <option value="">Select Order</option>
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
                                <label class="form-label small fw-bold mb-0">GRN No</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $nextGrnNo }}" readonly>
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
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control form-control-sm" value="{{ old('invoice_date', date('Y-m-d')) }}">
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
                            <label class="form-label small fw-bold mb-1">Manual No</label>
                            <input type="text" name="manual_no" class="form-control form-control-sm" value="{{ old('manual_no') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Dispatch No</label>
                            <input type="text" name="dispatch_no" class="form-control form-control-sm" value="{{ old('dispatch_no') }}">
                        </div>
                    </div>

                    <!-- Items Table -->
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
                        /* Ensure dropdown is above everything */
                        .ts-dropdown {
                            z-index: 9999 !important;
                            position: absolute !important;
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
                                    <th style="width: 30px;"></th>
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
                                    <td><input type="text" name="items[0][description]" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="text" name="items[0][onhand]" class="form-control form-control-sm text-center onhand-input bg-light" readonly></td>
                                    <td><input type="number" name="items[0][qty]" class="form-control form-control-sm text-center qty-input" step="any"></td>
                                    <td><input type="number" name="items[0][rate]" class="form-control form-control-sm text-end rate-input" step="any"></td>
                                    <td><input type="number" name="items[0][amount]" class="form-control form-control-sm text-end amount-input bg-light" readonly></td>
                                    <td><input type="number" name="items[0][disc_percent]" class="form-control form-control-sm text-center disc-percent-input" step="any" placeholder="0"></td>
                                    <td><input type="number" name="items[0][discount]" class="form-control form-control-sm text-end discount-input" step="any" placeholder="0.00"></td>
                                    <td><input type="number" name="items[0][total]" class="form-control form-control-sm text-end fw-bold total-input bg-light" readonly></td>
                                    <td>
                                        <input type="text" name="items[0][location]" class="form-control form-control-sm text-center location-input bg-light" value="Main Stock" readonly>
                                    </td>
                                    <td><input type="text" name="items[0][unit]" class="form-control form-control-sm unit-input bg-light" readonly></td>
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

                    <!-- Blade generated Product List for Guaranteed Client-Side Usage (Safe JSON) -->
                    <script>
                        window.oldItems = @json(old('items', []));
                        window.serverProductList = @json($products);
                    </script>

                    <!-- Footer Section -->
                    <div class="row g-3">
                        <div class="col-md-8">
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
                                <textarea name="memo" class="form-control form-control-sm" rows="3">{{ old('memo') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-none">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" name="subtotal" class="form-control form-control-sm text-end w-50 bg-white summary-subtotal" value="0.00" readonly>
                                    </div>
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
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="svatSwitch">
                                        <label class="form-check-label" for="svatSwitch">SVAT</label>
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
                                    <input type="hidden" name="tax_amount" class="summary-tax-total" value="0.00">
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
        const itemsTableBody = document.querySelector('#itemsTable tbody');

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
                    })
                    .catch(error => console.error('Error fetching vendor details:', error));
            }
        }

        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        // --- Table Controller ---
        const grnController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',

            init() {
                const firstRow = document.querySelector('.item-row');
                this.rowTemplateHTML = firstRow.innerHTML;
                firstRow.remove();

                if (window.oldItems && window.oldItems.length > 0) {
                    window.oldItems.forEach(item => {
                        this.appendRow(item);
                    });
                    this.appendRow();
                } else {
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
                const rowData = {
                    rowId: this.rowCount,
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
                this.injectRowUI(rowData);
                this.rowCount++;
            },

            injectRowUI(data) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = this.rowTemplateHTML;
                
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });

                newRow.querySelectorAll('.ts-wrapper').forEach(wrapper => wrapper.remove());
                newRow.querySelectorAll('select').forEach(select => {
                    select.classList.remove('tomselected', 'ts-hidden-accessible');
                    select.style.display = '';
                    if (select.hasAttribute('id')) select.removeAttribute('id');
                    select.value = '';
                });

                const newIndex = this.rowCount;
                const productSelect = newRow.querySelector('.product-select');
                if (productSelect) {
                    productSelect.name = `items[${newIndex}][product_id]`;
                    productSelect.value = data.product_id;
                }

                const descInput = newRow.querySelector('.description-input');
                if (descInput) {
                    descInput.name = `items[${newIndex}][description]`;
                    descInput.value = data.description;
                }

                const onhandInput = newRow.querySelector('.onhand-input');
                if (onhandInput) {
                    onhandInput.name = `items[${newIndex}][onhand]`;
                    onhandInput.value = data.onhand;
                }

                const qtyInput = newRow.querySelector('.qty-input');
                if (qtyInput) {
                    qtyInput.name = `items[${newIndex}][qty]`;
                    qtyInput.value = data.qty;
                }

                const rateInput = newRow.querySelector('.rate-input');
                if (rateInput) {
                    rateInput.name = `items[${newIndex}][rate]`;
                    rateInput.value = data.rate;
                }

                const amountInput = newRow.querySelector('.amount-input');
                if (amountInput) {
                    amountInput.name = `items[${newIndex}][amount]`;
                    amountInput.value = data.amount;
                }

                const discPercentInput = newRow.querySelector('.disc-percent-input');
                if (discPercentInput) {
                    discPercentInput.name = `items[${newIndex}][disc_percent]`;
                    discPercentInput.value = data.disc_percent;
                }

                const discountInput = newRow.querySelector('.discount-input');
                if (discountInput) {
                    discountInput.name = `items[${newIndex}][discount]`;
                    discountInput.value = data.discount;
                }

                const totalInput = newRow.querySelector('.total-input');
                if (totalInput) {
                    totalInput.name = `items[${newIndex}][total]`;
                    totalInput.value = data.total;
                }

                const locationInput = newRow.querySelector('.location-input');
                if (locationInput) {
                    locationInput.name = `items[${newIndex}][location]`;
                    locationInput.value = data.location;
                }

                const unitInput = newRow.querySelector('.unit-input');
                if (unitInput) {
                    unitInput.name = `items[${newIndex}][unit]`;
                    unitInput.value = data.unit;
                }

                newRow.dataset.rowIndex = this.data.length - 1;
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
                grnController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            onhandInput.value = '...';
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    grnController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    onhandInput.value = '0';
                    grnController.updateRowData(rowIndex, 'onhand', 0);
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
                    grnController.deleteRow(row);
                });
            }

            function handleProductChange(selectedOption, value) {
                grnController.updateRowData(rowIndex, 'product_id', value);
                
                if (value && selectedOption) {
                    const desc = selectedOption.dataset.name || '';
                    const unit = selectedOption.dataset.unit || '';
                    const rate = parseFloat(selectedOption.dataset.rate) || 0;

                    grnController.updateRowData(rowIndex, 'description', desc);
                    grnController.updateRowData(rowIndex, 'unit', unit);
                    grnController.updateRowData(rowIndex, 'rate', rate);

                    row.querySelector('.description-input').value = desc;
                    row.querySelector('.unit-input').value = unit;
                    row.querySelector('.rate-input').value = rate;
                    
                    const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                    fetchItemStock(value, currentLoc, rowIndex, row);

                    grnController.calculateRow(rowIndex, row);
                    grnController.checkAndAppendRow(rowIndex);
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    row.querySelector('.onhand-input').value = '';
                    grnController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">Select Item</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = p.cost || 0;
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-unit="${p.unit || ''}" data-rate="${rate}">${safeCode}</option>`;
                    });
                }
                productSelect.innerHTML = optionsHTML;
            }

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
                    onChange: function(value) {
                        let selectedOption = null;
                        if (value) {
                            selectedOption = productSelect.querySelector(`option[value="${value}"]`);
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
                    
                    grnController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    grnController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        grnController.init();

        // Header Listeners
        const ssclPercentInput = document.querySelector('input[name="sscl_percent"]');
        const vatPercentInput = document.querySelector('input[name="vat_percent"]');
        const svatSwitch = document.getElementById('svatSwitch');
        const headerDiscPercentInput = document.querySelector('.header-discount-percent');
        const headerDiscAmountInput = document.querySelector('.header-discount-amount');

        if (ssclPercentInput) ssclPercentInput.addEventListener('input', () => grnController.calculateGrandTotal());
        if (vatPercentInput) vatPercentInput.addEventListener('input', () => grnController.calculateGrandTotal());
        if (svatSwitch) svatSwitch.addEventListener('change', () => grnController.calculateGrandTotal());
        if (headerDiscPercentInput) headerDiscPercentInput.addEventListener('input', () => grnController.calculateGrandTotal('header_percent'));
        if (headerDiscAmountInput) headerDiscAmountInput.addEventListener('input', () => grnController.calculateGrandTotal('header_amount'));

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
                            grnController.updateRowData(rowIndex, 'location', locationName);
                            const productSelect = row.querySelector('.product-select');
                            const productId = productSelect ? productSelect.value : '';
                            if (productId) fetchItemStock(productId, locationName, rowIndex, row);
                        }
                    }
                });
            });
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

        setTimeout(() => {
            if (termsSelect && window.TomSelect && !termsSelect.tomselect) {
                new TomSelect(termsSelect, { create: false });
            }
        }, 600);

        // --- Form Submission Fix --- //
        const form = document.getElementById('createGrnForm');
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
                    alert('Please add at least one valid item to the GRN.');
                }
            });
        }
    });
</script>
@endpush
