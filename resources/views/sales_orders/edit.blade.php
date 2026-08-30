@extends('layouts.admin')

@section('title', 'Sales Order - Edit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Job Order</h4>
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
                <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-1"></i>Job Order - Edit</h5>
                <div class="float-end">
                    <button type="submit" form="editSalesOrderForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Update Order</button>
                    <a href="{{ route('sales-orders.index') }}" class="btn btn-warning btn-sm"><i class="ri-close-line me-1"></i>Cancel</a>
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

                <form id="editSalesOrderForm" action="{{ route('sales-orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Customer Name <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $order->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->company_name ?? $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" data-name="{{ $loc->name }}" {{ (old('location_id', $order->location_id) == $loc->id) ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">JO No</label>
                            <input type="text" class="form-control form-select-sm bg-light" value="{{ $order->reference_no }}" readonly>
                        </div>
                    </div>

                    <!-- Header Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Address</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="address">{{ old('address', $order->address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Delivery Destination</label>
                            <textarea name="delivery_destination" class="form-control form-control-sm" rows="2" placeholder="deliver destination">{{ old('delivery_destination', $order->delivery_destination) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Date</label>
                            <input type="date" name="order_date" class="form-control form-control-sm" value="{{ old('order_date', $order->order_date) }}">
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
                                    <option value="{{ $rep->id }}" {{ old('rep', $order->rep_id) == $rep->id ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Reference No</label>
                            <input type="text" name="reference_no" class="form-control form-control-sm" value="{{ old('reference_no', $order->reference_no) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Expected Date</label>
                            <input type="date" name="expected_date" class="form-control form-control-sm" value="{{ old('expected_date', $order->expected_date) }}">
                        </div>
                    </div>

                    <!-- Header Row 4 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Attent</label>
                            <input type="text" name="attent" class="form-control form-control-sm" value="{{ old('attent', $order->attent) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Terms</label>
                            <select name="terms" id="termsSelect" class="form-select form-select-sm">
                                <option value="">-- Select Terms --</option>
                                @foreach($terms as $term)
                                    @php $label = ($term->days == 0) ? 'Cash Only' : ($term->days.' Days Credit'); @endphp
                                    <option value="{{ $term->days }}" {{ old('terms', $order->terms) == $term->days ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Due Date</label>
                            <input type="date" name="due_date" class="form-control form-control-sm" value="{{ old('due_date', $order->due_date) }}">
                        </div>
                    </div>

                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        #itemsTable .location-input { min-width: 90px !important; }
                        #itemsTable .unit-input { min-width: 60px !important; }
                        #itemsTable .product-select { min-width: 120px !important; }
                        .ts-dropdown .ts-dropdown-content { max-height: 450px !important; }
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
                                <!-- Existing Items Row Template (to be used by JS) -->
                                <tr class="item-row d-none" id="row-template">
                                    <td>
                                        <select name="items[idx][product_id]" class="form-select form-select-sm product-select">
                                            <option value="">-- Select --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-unit="{{ $p->unit }}" data-rate="{{ $p->max_sale_price ?? $p->cost }}" data-onhand="">{{ $p->code }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[idx][description]" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="text" name="items[idx][onhand]" class="form-control form-control-sm text-center onhand-input bg-light" readonly></td>
                                    <td><input type="number" name="items[idx][qty]" class="form-control form-control-sm text-center qty-input" step="any"></td>
                                    <td><input type="number" name="items[idx][rate]" class="form-control form-control-sm text-end rate-input" step="any"></td>
                                    <td><input type="number" name="items[idx][amount]" class="form-control form-control-sm text-end amount-input bg-light" readonly></td>
                                    <td><input type="number" name="items[idx][disc_percent]" class="form-control form-control-sm text-center disc-percent-input" step="any" placeholder="0"></td>
                                    <td><input type="number" name="items[idx][discount]" class="form-control form-control-sm text-end discount-input" step="any" placeholder="0.00"></td>
                                    <td><input type="number" name="items[idx][total]" class="form-control form-control-sm text-end fw-bold total-input bg-light" readonly></td>
                                    <td>
                                        <input type="text" name="items[idx][location]" class="form-control form-control-sm text-center location-input bg-light" value="Main Warehouse" readonly>
                                    </td>
                                    <td><input type="text" name="items[idx][unit]" class="form-control form-control-sm unit-input bg-light" readonly></td>
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
                    
                    <script>
                        window.serverProductList = @json($products);
                        window.existingItems = @json($order->items);
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
                                        <input type="text" class="form-control text-end bg-light footer-grand-total" value="{{ number_format($order->total_amount, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                                    <select name="account_id" class="form-select form-select-sm border-danger" required>
                                        <option value="">-- Select Account --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account_id', $order->account_id) == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label small fw-bold mb-1">Memo</label>
                                <textarea name="memo" class="form-control form-control-sm" rows="3">{{ old('memo', $order->memo) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-none">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" name="subtotal" class="form-control form-control-sm text-end w-50 bg-white summary-subtotal" readonly placeholder="0.00">
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount %</label>
                                            <input type="number" name="header_discount_percent" class="form-control form-control-sm text-center header-discount-percent" step="any" value="{{ old('header_discount_percent', $order->header_discount_percent) }}" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount</label>
                                            <input type="number" name="header_discount_amount" class="form-control form-control-sm text-end header-discount-amount" step="any" value="{{ old('header_discount_amount', $order->header_discount_amount) }}" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold h6 text-primary mb-0">Total</span>
                                        <input type="text" name="total_amount" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary summary-total" value="{{ number_format($order->total_amount, 2, '.', '') }}" readonly placeholder="0.00">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        let initialRows = document.querySelectorAll('#itemsTable tbody tr.item-row');
        initialRows.forEach((row, index) => {
            if(index > 0) {
                let qtyInput = row.querySelector('.qty-input');
                if(qtyInput) qtyInput.value = '0';
            }
        });
    }, 200);
});
</script>
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
        const templateRow = document.getElementById('row-template');

        function fetchCustomerDetails(customerId) {
            if (customerId) {
                fetch(`/api/customers/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea && !addressTextarea.value) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea && !deliveryDestinationTextarea.value) deliveryDestinationTextarea.value = data.delivery_address || '';
                    })
                    .catch(error => console.error('Error fetching customer details:', error));
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

        const salesOrderController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: templateRow.innerHTML,

            init() {
                if (window.existingItems && window.existingItems.length > 0) {
                    window.existingItems.forEach((item, idx) => {
                        this.data.push({
                            rowId: idx,
                            product_id: item.product_id,
                            description: item.description,
                            onhand: '',
                            qty: parseFloat(item.qty),
                            rate: parseFloat(item.rate),
                            amount: parseFloat(item.amount),
                            disc_percent: parseFloat(item.disc_percent),
                            discount: parseFloat(item.discount),
                            total: parseFloat(item.total),
                            location: item.location || getDefaultLocation(),
                            unit: item.unit
                        });
                        this.injectRowUI(this.data[idx], idx);
                        this.rowCount++;
                    });
                }
                // Always add at least one empty row at the end
                this.appendRow();
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
                
                this.injectRowUI(this.data[newIdx], newIdx);
                this.rowCount++;
            },

            injectRowUI(data, index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML.replace(/idx/g, index);
                
                itemsTableBody.appendChild(newRow);
                
                // Populate fields
                if (data.product_id) {
                    const productSelect = newRow.querySelector('.product-select');
                    productSelect.value = data.product_id;
                    newRow.querySelector('.description-input').value = data.description;
                    newRow.querySelector('.qty-input').value = data.qty;
                    newRow.querySelector('.rate-input').value = data.rate;
                    newRow.querySelector('.amount-input').value = data.amount.toFixed(2);
                    newRow.querySelector('.disc-percent-input').value = data.disc_percent || '';
                    newRow.querySelector('.discount-input').value = data.discount || '';
                    newRow.querySelector('.total-input').value = data.total.toFixed(2);
                    newRow.querySelector('.location-input').value = data.location;
                    newRow.querySelector('.unit-input').value = data.unit;
                    
                    this.fetchStock(data.product_id, data.location, index, newRow);
                }

                initRowEvents(newRow);
                if (window.jQuery && $.fn.select2) {
                    $(newRow).find('.select2, .product-select').select2();
                }
            },

            updateRowData(rowIndex, field, value) {
                if (this.data[rowIndex]) {
                    this.data[rowIndex][field] = value;
                }
            },

            checkAndAppendRow(rowIndex) {
                if (rowIndex === this.data.length - 1) {
                    const currentRow = this.data[rowIndex];
                    if (currentRow.product_id) {
                        this.appendRow();
                        let rows = document.querySelectorAll('#itemsTable tbody tr');
                        if (rows.length > 0) {
                            let lastRowQty = rows[rows.length - 1].querySelector('.qty-input');
                            if (lastRowQty) { lastRowQty.value = '0'; }
                        }
                    }
                }
            },

            fetchStock(productId, location, rowIndex, row) {
                const onhandInput = row.querySelector('.onhand-input');
                if (!productId || !location) return;
                
                onhandInput.value = '...';
                fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                    .then(response => response.json())
                    .then(data => {
                        onhandInput.value = data.stock || 0;
                    });
            },

            calculateRow(rowIndex, rowElement, sourceField = 'disc_percent') {
                const dataRow = this.data[rowIndex];
                if (!dataRow) return;

                dataRow.amount = dataRow.qty * dataRow.rate;
                
                if (sourceField === 'disc_percent') {
                  dataRow.discount = (dataRow.amount * dataRow.disc_percent) / 100;
                  rowElement.querySelector('.discount-input').value = dataRow.discount > 0 ? dataRow.discount.toFixed(2) : '';
                } else if (sourceField === 'discount') {
                  // If manual discount entered, we don't recalculate disc_percent for simplicity or we could.
                }

                dataRow.total = dataRow.amount - dataRow.discount;
                rowElement.querySelector('.amount-input').value = dataRow.amount.toFixed(2);
                rowElement.querySelector('.total-input').value = dataRow.total.toFixed(2);

                this.calculateGrandTotal();
            },

            calculateGrandTotal(sourceField = 'none') {
                let grandQty = 0, grandAmount = 0, grandDiscount = 0, grandTotal = 0;

                const domRows = document.querySelectorAll('#itemsTable tbody tr.item-row');
                domRows.forEach(domRow => {
                    const productSelect = domRow.querySelector('.product-select');
                    if (!productSelect || !productSelect.value || productSelect.value.trim() === '') return;
                    
                    const qtyInput = domRow.querySelector('.qty-input');
                    const domQty = qtyInput ? (parseFloat(qtyInput.value) || 0) : 0;
                    if (domQty <= 0) return; // Completely ignore rows where visible DOM qty is 0 or empty

                    const amountInput = domRow.querySelector('.amount-input');
                    const domAmount = amountInput ? (parseFloat(amountInput.value) || 0) : 0;

                    const discountInput = domRow.querySelector('.discount-input');
                    const domDiscount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;

                    const totalInput = domRow.querySelector('.total-input');
                    const domTotal = totalInput ? (parseFloat(totalInput.value) || 0) : 0;

                    const valueDiffInput = domRow.querySelector('.value-diff-input');
                    const domValueDiff = valueDiffInput ? (parseFloat(valueDiffInput.value) || 0) : 0;

                    // Proxy row object using DOM values
                    const row = {
                        qty: domQty,
                        amount: domAmount,
                        discount: domDiscount,
                        total: domTotal,
                        value_diff: domValueDiff
                    };

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
                document.querySelector('.summary-subtotal').value = subTotal.toFixed(2);
                
                const headerDiscPercentInput = document.querySelector('.header-discount-percent');
                const headerDiscAmountInput = document.querySelector('.header-discount-amount');
                
                let headerDiscPercent = parseFloat(headerDiscPercentInput.value) || 0;
                let headerDiscAmount = parseFloat(headerDiscAmountInput.value) || 0;
                
                if (sourceField === 'header_percent' || (sourceField === 'none' && headerDiscPercent > 0)) {
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

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');
            const discountInput = row.querySelector('.discount-input');

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
                    onChange: function(value) {
                        const opt = productSelect.querySelector(`option[value="${value}"]`);
                        if (value && opt) {
                            const desc = opt.dataset.name, unit = opt.dataset.unit, rate = opt.dataset.rate;
                            salesOrderController.updateRowData(rowIndex, 'product_id', value);
                            salesOrderController.updateRowData(rowIndex, 'description', desc);
                            salesOrderController.updateRowData(rowIndex, 'unit', unit);
                            salesOrderController.updateRowData(rowIndex, 'rate', rate);
                            
                            row.querySelector('.description-input').value = desc;
                            row.querySelector('.unit-input').value = unit;
                            row.querySelector('.rate-input').value = rate;
                            
                            salesOrderController.fetchStock(value, row.querySelector('.location-input').value, rowIndex, row);
                            salesOrderController.calculateRow(rowIndex, row);
                            salesOrderController.checkAndAppendRow(rowIndex);
                        }
                    }
                });
            }

            [qtyInput, rateInput, discPercentInput, discountInput].forEach(input => {
                input.addEventListener('input', function() {
                    let field = 'qty', source = 'disc_percent';
                    if (this.classList.contains('rate-input')) field = 'rate';
                    if (this.classList.contains('disc-percent-input')) { field = 'disc_percent'; source = 'disc_percent'; }
                    if (this.classList.contains('discount-input')) { field = 'discount'; source = 'discount'; }
                    
                    salesOrderController.updateRowData(rowIndex, field, parseFloat(this.value) || 0);
                    salesOrderController.calculateRow(rowIndex, row, source);
                });
            });
        }

        salesOrderController.init();
        salesOrderController.calculateGrandTotal();

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
                            salesOrderController.updateRowData(rowIndex, 'location', locationName);
                            const productSelect = row.querySelector('.product-select');
                            const productId = productSelect ? productSelect.value : '';
                            if (productId) {
                                salesOrderController.fetchStock(productId, locationName, rowIndex, row);
                            }
                        }
                    }
                });
            });
        }

        document.querySelector('.header-discount-percent').addEventListener('input', () => salesOrderController.calculateGrandTotal('header_percent'));
        document.querySelector('.header-discount-amount').addEventListener('input', () => salesOrderController.calculateGrandTotal('header_amount'));
        
        customerSelect.addEventListener('change', function () { fetchCustomerDetails(this.value); });
        
        if (customerSelect.tomselect) {
            customerSelect.tomselect.on('change', (val) => fetchCustomerDetails(val));
        }
    });
</script>
@endpush
