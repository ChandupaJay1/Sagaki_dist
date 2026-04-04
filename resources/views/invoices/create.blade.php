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
                             <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                             <select name="location" class="form-select form-select-sm" required>
                                 <option value="">-- Select Location --</option>
                                 @foreach($locations as $location)
                                     <option value="{{ $location->name }}" {{ (old('location') == $location->name || $location->name == 'Main Stock') ? 'selected' : '' }}>{{ $location->name }}</option>
                                 @endforeach
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
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty" id="footer-qty" readonly></td>
                                    <td class="text-end fw-bold">Amount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-amount" id="footer-amount" readonly></td>
                                    <td class="text-end fw-bold">Discount</td>
                                    <td><input type="text" class="form-control form-control-sm text-end bg-white footer-discount" id="footer-discount" readonly></td>
                                    <td class="text-end fw-bold">Total</td>
                                    <td colspan="2"><input type="text" class="form-control form-control-sm text-end bg-white fw-bold footer-total" id="footer-total" readonly></td>
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
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold mb-1">LKR Total Amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">LKR</span>
                                        <input type="text" id="lkr-total" class="form-control text-end bg-light" readonly>
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
                                            <input type="number" id="final-discount-percent" class="form-control form-control-sm text-center" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount</label>
                                            <input type="number" id="final-discount-amount" class="form-control form-control-sm text-end" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" id="final-sub-total" class="form-control form-control-sm text-end w-50 bg-white" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold h6 text-primary mb-0">Total</span>
                                        <input type="text" id="final-grand-total" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary" readonly>
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

        setTimeout(() => {
            if (customerSelect.tomselect) {
                customerSelect.tomselect.on('change', function (value) {
                    fetchCustomerDetails(value);
                });
            }
        }, 500);

        // --- Table Controller (Data Source Level) --- //
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location"]');
            return locNode ? locNode.value : '';
        }

        const invoiceController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',

            init() {
                const firstRow = document.querySelector('.item-row');
                this.rowTemplateHTML = firstRow.innerHTML;
                firstRow.remove();

                // Start with two empty rows
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

            appendRow() {
                const currentLoc = getDefaultLocation();
                const newIdx = this.data.length;
                
                this.data.push({
                    rowId: newIdx,
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
                
                this.injectRowUI(currentLoc, newIdx);
                this.rowCount++;
            },

            injectRowUI(currentLoc, index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML;
                
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    if (input.classList.contains('qty-input')) input.value = '1';
                    if (input.classList.contains('location-input')) input.value = currentLoc;
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

            calculateGrandTotal() {
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
    
                document.getElementById('footer-qty').value = grandQty.toFixed(2);
                document.getElementById('footer-amount').value = grandAmount.toFixed(2);
                document.getElementById('footer-discount').value = grandDiscount.toFixed(2);
                document.getElementById('footer-total').value = grandTotal.toFixed(2);
                
                document.getElementById('final-sub-total').value = grandTotal.toFixed(2); // Invoices use table total as subtotal for bottom bill

                calculateFinalTotal();
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            if (!productId || !location) {
                onhandInput.value = '';
                invoiceController.updateRowData(rowIndex, 'onhand', '');
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
                    invoiceController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    invoiceController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');

            if (!qtyInput.value) qtyInput.value = '1';

            function handleProductChange(value) {
                invoiceController.updateRowData(rowIndex, 'product_id', value);
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';
                        const rate = parseFloat(selectedObj.max_sale_price) || parseFloat(selectedObj.cost) || 0; // Invoices use sale price

                        invoiceController.updateRowData(rowIndex, 'description', desc);
                        invoiceController.updateRowData(rowIndex, 'unit', unit);
                        invoiceController.updateRowData(rowIndex, 'rate', rate);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.unit-input').value = unit;
                        row.querySelector('.rate-input').value = rate;
                        
                        const currentLoc = row.querySelector('.location-input') ? row.querySelector('.location-input').value : '';
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        invoiceController.calculateRow(rowIndex, row);
                        invoiceController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                    invoiceController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">-- Select --</option>';
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
                        // Crucial: Update the data FIRST
                        invoiceController.updateRowData(rowIndex, 'product_id', value);
                        // Then trigger the row logic
                        handleProductChange(value);
                    }
                });
            } else if (window.jQuery && $(productSelect).select2) {
                $(productSelect).select2();
                $(productSelect).on('change', function() {
                    invoiceController.updateRowData(rowIndex, 'product_id', this.value);
                    handleProductChange(this.value);
                });
            } else {
                productSelect.addEventListener('change', function() {
                    invoiceController.updateRowData(rowIndex, 'product_id', this.value);
                    handleProductChange(this.value);
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
                    
                    invoiceController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    invoiceController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        invoiceController.init();

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
                            invoiceController.updateRowData(rowIndex, 'location', newLocation);
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

        document.getElementById('final-discount-percent').addEventListener('input', () => calculateFinalTotal('header_percent'));
        document.getElementById('final-discount-amount').addEventListener('input', () => calculateFinalTotal('header_amount'));

    });
</script>
