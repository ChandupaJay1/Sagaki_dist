@extends('layouts.admin')

@section('title', 'Sales Order - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Job Order</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger"><i class="ri-error-warning-line me-1"></i>Date Control is Inactive.</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-1"></i>Job Order - Create</h5>
                <div class="float-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="submit" form="createSalesOrderForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" form="createSalesOrderForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="reset" form="createSalesOrderForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createSalesOrderForm" action="{{ route('sales-orders.store') }}" method="POST">
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
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="location" class="form-select form-select-sm">
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('location') == $loc->name || $loc->name == 'Main Stock') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">JO No</label>
                            <input type="text" class="form-control form-select-sm bg-light" value="JO00037" readonly>
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
                            <label class="form-label small fw-bold mb-1">Date</label>
                            <input type="date" name="order_date" class="form-control form-control-sm" value="{{ old('order_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <!-- Header Row 3 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Order By</label>
                            <select name="order_by" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Checked By</label>
                            <select name="checked_by" class="form-select form-select-sm">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Rep</label>
                            <select name="rep" id="repSelect" class="form-select form-select-sm">
                                <option value="">-- Select Rep --</option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->id }}" {{ old('rep') == $rep->id ? 'selected' : '' }}>{{ $rep->name }}</option>
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
                            <select name="terms" id="termsSelect" class="form-select form-select-sm">
                                <option value="">-- Select Terms --</option>
                                @foreach($terms as $term)
                                    @php $label = ($term->days == 0) ? 'Cash Only' : ($term->days.' Days Credit'); @endphp
                                    <option value="{{ $term->days }}" {{ old('terms') == $term->days ? 'selected' : '' }}>{{ $label }}</option>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-sm product-select">
                                            <option value="">-- Select --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-unit="{{ $p->unit }}" data-rate="{{ $p->max_sale_price ?? $p->cost }}" data-onhand="">{{ $p->code }}</option>
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
                                    <select class="form-select form-select-sm border-danger">
                                        <option value=""></option>
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
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" class="form-control form-control-sm text-end w-50 bg-white summary-subtotal" readonly placeholder="0.00">
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
                                    <div class="d-flex justify-content-between">
                                        <span class="small fw-bold h6 text-primary">Total</span>
                                        <input type="text" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary summary-total" readonly placeholder="0.00">
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
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const repSelect = document.getElementById('repSelect');
        const termsSelect = document.getElementById('termsSelect');
        function fetchCustomerDetails(customerId) {
            if (customerId) {
                fetch(`/api/customers/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea) deliveryDestinationTextarea.value = data.delivery_address || '';
                        
                        if (repSelect && data.rep_id) {
                            repSelect.value = data.rep_id;
                            if (repSelect.tomselect) {
                                repSelect.tomselect.setValue(data.rep_id);
                            }
                        }
                        
                        if (termsSelect && data.terms) {
                            // Try to match exact value first
                            let matchedOption = Array.from(termsSelect.options).find(opt => opt.value === data.terms);
                            
                            // If not found, try to extract the number of days or match by text
                            if (!matchedOption && data.terms) {
                                let daysMatch = data.terms.match(/\d+/);
                                if (daysMatch) {
                                    let parsedDays = daysMatch[0];
                                    matchedOption = Array.from(termsSelect.options).find(opt => opt.value === parsedDays);
                                }
                                
                                // Alternatively, check if the option text includes the term
                                if (!matchedOption) {
                                    matchedOption = Array.from(termsSelect.options).find(opt => opt.text && opt.text.includes(data.terms));
                                }
                            }
                            
                            if (matchedOption) {
                                termsSelect.value = matchedOption.value;
                                if (termsSelect.tomselect) {
                                    termsSelect.tomselect.setValue(matchedOption.value);
                                }
                            } else {
                                termsSelect.value = data.terms;
                                if (termsSelect.tomselect) {
                                    termsSelect.tomselect.setValue(data.terms);
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching customer details:', error));
            }
        }

        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location"]');
            return locNode ? locNode.value : '';
        }

        // --- Table Controller (Data Source Level) ---
        const salesOrderController = {
            data: [
                {
                    rowId: 0,
                    product_id: '',
                    description: '',
                    onhand: '',
                    qty: 1,
                    rate: 0,
                    amount: 0,
                    disc_percent: 0,
                    discount: 0,
                    total: 0,
                    location: getDefaultLocation(),
                    unit: ''
                }
            ],
            rowCount: 1,
            rowTemplateHTML: '',

            checkAndAppendRow(rowIndex) {
                // If it is the last row and an item is selected, push a new empty object
                if (rowIndex === this.data.length - 1) {
                    const currentRow = this.data[rowIndex];
                    if (currentRow.product_id) {
                        this.appendRow();
                    }
                }
            },

            appendRow() {
                const currentLoc = getDefaultLocation();
                
                // Push a new empty object (Blank Row) into the table's data array
                this.data.push({
                    rowId: this.rowCount,
                    product_id: '',
                    description: '',
                    onhand: '',
                    qty: 1,
                    rate: 0,
                    amount: 0,
                    disc_percent: 0,
                    discount: 0,
                    total: 0,
                    location: currentLoc,
                    unit: ''
                });
                
                // Inject Row into UI
                this.injectRowUI(currentLoc);
                this.rowCount++;
            },

            injectRowUI(currentLoc) {
                // Use a deeply primitive DOM string replacement to bypass ALL plugin bugs
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = this.rowTemplateHTML;
                
                // Clear UI values just in case
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    if (input.classList.contains('qty-input')) input.value = '1';
                    if (input.classList.contains('location-input')) input.value = currentLoc;
                });
                
                // Full Select Cleanup just to be absolutely safe
                newRow.querySelectorAll('.ts-wrapper').forEach(wrapper => wrapper.remove());
                newRow.querySelectorAll('select').forEach(select => {
                    select.classList.remove('tomselected', 'ts-hidden-accessible');
                    select.style.display = '';
                    if (select.hasAttribute('id')) select.removeAttribute('id');
                    select.value = '';
                });

                // Update input names for form submission
                const newIndex = this.rowCount;
                newRow.querySelectorAll('[name]').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, `[${newIndex}]`));
                    }
                });

                // Set row index on DOM element
                newRow.dataset.rowIndex = this.data.length - 1;
                itemsTableBody.appendChild(newRow);
                
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
                dataRow.amount = dataRow.qty * dataRow.rate;
                
                if (sourceField === 'disc_percent') {
                    dataRow.discount = (dataRow.amount * dataRow.disc_percent) / 100;
                    rowElement.querySelector('.discount-input').value = dataRow.discount > 0 ? dataRow.discount.toFixed(2) : '';
                } else if (sourceField === 'discount') {
                    dataRow.disc_percent = 0;
                    rowElement.querySelector('.disc-percent-input').value = '';
                }

                dataRow.total = dataRow.amount - dataRow.discount;

                // Sync UI
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
                
                // Summary calculation
                const subTotal = grandTotal; // Sum of row net totals
                document.querySelector('.summary-subtotal').value = subTotal.toFixed(2);
                
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
                
                document.querySelector('.summary-total').value = finalTotal.toFixed(2);
                document.querySelector('.footer-grand-total').value = finalTotal.toFixed(2);
            }
        };

        // Save raw string template from the first row BEFORE any scripts run
        const firstRow = document.querySelector('.item-row');
        salesOrderController.rowTemplateHTML = firstRow.innerHTML;
        firstRow.dataset.rowIndex = 0;

        // Fetch stock from backend
        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if (!productId || !location) {
                onhandInput.value = '';
                salesOrderController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            
            onhandInput.value = '...';
            
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('Network response error');
                })
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    salesOrderController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    salesOrderController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');

            // Set default value for Qty if empty
            if (!qtyInput.value) qtyInput.value = '1';

            function handleProductChange(selectedOption, value) {
                salesOrderController.updateRowData(rowIndex, 'product_id', value);
                
                if (value && selectedOption) {
                    const desc = selectedOption.dataset.name || '';
                    const unit = selectedOption.dataset.unit || '';
                    const rate = parseFloat(selectedOption.dataset.rate) || 0;

                    salesOrderController.updateRowData(rowIndex, 'description', desc);
                    salesOrderController.updateRowData(rowIndex, 'unit', unit);
                    salesOrderController.updateRowData(rowIndex, 'rate', rate);

                    row.querySelector('.description-input').value = desc;
                    row.querySelector('.unit-input').value = unit;
                    row.querySelector('.rate-input').value = rate;
                    
                    const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                    fetchItemStock(value, currentLoc, rowIndex, row);

                    salesOrderController.calculateRow(rowIndex, row);
                    salesOrderController.checkAndAppendRow(rowIndex);
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    row.querySelector('.onhand-input').value = '';
                    salesOrderController.calculateRow(rowIndex, row);
                }
            }

            // Absolutely Force Options natively onto the select BEFORE any plugins initialize
            if (productSelect) {
                let optionsHTML = '<option value="">-- Select --</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = p.max_sale_price !== null && p.max_sale_price !== undefined ? p.max_sale_price : (p.cost || 0);
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
            } else if (window.jQuery && $(productSelect).select2) {
                $(productSelect).select2();
                $(productSelect).on('change', function() {
                    let selectedOption = this.options[this.selectedIndex];
                    handleProductChange(selectedOption, this.value);
                });
            } else {
                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    handleProductChange(selectedOption, this.value);
                });
            }

            const discountInput = row.querySelector('.discount-input');

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
                    
                    salesOrderController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    salesOrderController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        initRowEvents(firstRow);

        // Initial State: The table must always start with 2 default empty rows when the page loads.
        salesOrderController.appendRow();

        // Header events
        const mainLocationSelect = document.querySelector('select[name="location"]');
        
        // --- 1. Header to Table Sync ---
        if (mainLocationSelect) {
            mainLocationSelect.addEventListener('change', function(e) {
                // e.detail.isSyncTrigger prevents infinite loops if this change was triggered by the row
                if (e.detail && e.detail.isSyncTrigger) return; 

                const newLocation = this.value;
                
                // Loop through all existing rows in the items table
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowLocationInput = row.querySelector('.location-input');
                    const rowIndex = parseInt(row.dataset.rowIndex);
                    
                    if (rowLocationInput && rowLocationInput.value !== newLocation) {
                        rowLocationInput.value = newLocation;
                        
                        if (!isNaN(rowIndex)) {
                            salesOrderController.updateRowData(rowIndex, 'location', newLocation);
                            
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

        // Handled completely by header dropdown now.

        // Header Discount Events
        const headerDiscPercentInput = document.querySelector('.header-discount-percent');
        const headerDiscAmountInput = document.querySelector('.header-discount-amount');
        
        if (headerDiscPercentInput) {
            headerDiscPercentInput.addEventListener('input', () => {
                salesOrderController.calculateGrandTotal('header_percent');
            });
        }
        
        if (headerDiscAmountInput) {
            headerDiscAmountInput.addEventListener('input', () => {
                salesOrderController.calculateGrandTotal('header_amount');
            });
        }

        customerSelect.addEventListener('change', function () {
            fetchCustomerDetails(this.value);
        });

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
