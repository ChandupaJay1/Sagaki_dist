@extends('layouts.admin')

@section('title', 'Purchase Order - Edit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Purchase Order</h4>
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
                <h5 class="card-title mb-0"><i class="ri-shopping-basket-2-line me-1"></i>Purchase Order - Edit</h5>
                <div class="float-end">
                    <button type="submit" form="editPurchaseOrderForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Update Order</button>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-warning btn-sm"><i class="ri-close-line me-1"></i>Cancel</a>
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

                <form id="editPurchaseOrderForm" action="{{ route('purchase-orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Vendor Name <span class="text-danger">*</span></label>
                            <select name="vendor_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}" {{ old('vendor_id', $order->vendor_id) == $v->id ? 'selected' : '' }}>{{ $v->company_name ?? $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                             <select name="location_id" class="form-select form-select-sm" required>
                                 <option value="">-- Select Location --</option>
                                 @foreach($locations as $location)
                                     <option value="{{ $location->id }}" data-name="{{ $location->name }}" {{ old('location_id', $order->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                 @endforeach
                             </select>
                         </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Load</label>
                            <input type="text" name="load" class="form-control form-control-sm" value="{{ old('load', $order->load) }}">
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
                            <div class="mb-1">
                                <label class="form-label small fw-bold mb-0">PO No</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $order->reference_no }}" readonly>
                            </div>
                            <div>
                                <label class="form-label small fw-bold mb-0">Date</label>
                                <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', $order->date) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Header Row 3 -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Order By</label>
                            <select name="order_by" class="form-select form-select-sm">
                                <option value=""></option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('order_by', $order->order_by) == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Checked By</label>
                            <select name="checked_by" class="form-select form-select-sm">
                                <option value=""></option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('checked_by', $order->checked_by) == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Rep</label>
                            <select name="rep" class="form-select form-select-sm">
                                <option value=""></option>
                                @foreach($reps as $rep)
                                    <option value="{{ $rep->name }}" {{ old('rep', $order->rep) == $rep->name ? 'selected' : '' }}>{{ $rep->name }}</option>
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
                            <select name="terms" class="form-select form-select-sm">
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
                                    <th class="fw-bold py-2 text-uppercase" style="width: 40px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row d-none" id="row-template">
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
                                    <td><input type="text" class="form-control form-control-sm location-input text-center bg-light" value="Main Warehouse" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
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

                    <script>
                        window.serverProductList = @json($products ?? []);
                        window.existingItems = @json($order->items);
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
                                <textarea name="memo" class="form-control form-control-sm" rows="4">{{ old('memo', $order->memo) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card bg-light border-0 shadow-none">
                                <div class="card-body p-2">
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
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <span class="small fw-bold">Sub Total</span>
                                        <input type="text" name="subtotal" class="form-control form-control-sm text-end w-50 bg-white summary-subtotal" value="{{ number_format($order->subtotal, 2, '.', '') }}" readonly>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">SSCL %</label>
                                            <input type="text" name="sscl_percent" class="form-control form-control-sm text-center" value="{{ old('sscl_percent', $order->sscl_percent) }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">SSCL</label>
                                            <input type="text" name="sscl_amount" class="form-control form-control-sm text-end" value="{{ old('sscl_amount', $order->sscl_amount) }}">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT %</label>
                                            <input type="text" name="vat_percent" class="form-control form-control-sm text-center" value="{{ old('vat_percent', $order->vat_percent) }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">VAT</label>
                                            <input type="text" name="vat_amount" class="form-control form-control-sm text-end" value="{{ old('vat_amount', $order->vat_amount) }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold h6 text-primary mb-0">Total</span>
                                        <input type="text" name="total_amount" class="form-control form-control-sm text-end w-50 bg-white fw-bold text-primary summary-total" value="{{ number_format($order->total_amount, 2, '.', '') }}" readonly>
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
        const termsSelect = document.querySelector('select[name="terms"]');
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const templateRow = document.getElementById('row-template');

        function fetchVendorDetails(vendorId) {
            if (vendorId) {
                fetch(`/api/vendors/${vendorId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (addressTextarea && !addressTextarea.value) addressTextarea.value = data.address || '';
                        if (deliveryDestinationTextarea && !deliveryDestinationTextarea.value) deliveryDestinationTextarea.value = data.delivery_address || '';
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

        const purchaseOrderController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: templateRow.innerHTML,

            init() {
                if (window.existingItems && window.existingItems.length > 0) {
                    window.existingItems.forEach((item, idx) => {
                        const newIdx = this.data.length;
                        this.data.push({
                            rowId: newIdx,
                            product_id: item.product_id,
                            description: item.description,
                            onhand: '',
                            qty: parseFloat(item.qty) || 0,
                            rate: parseFloat(item.rate) || 0,
                            amount: parseFloat(item.amount) || 0,
                            disc_percent: parseFloat(item.disc_percent) || 0,
                            discount: parseFloat(item.discount) || 0,
                            total: parseFloat(item.total) || 0,
                            location: item.location || getDefaultLocation(),
                            unit: item.unit || ''
                        });
                        this.injectRowUI(this.data[newIdx], newIdx);
                        this.rowCount++;
                    });
                }
                // Start with ONE empty row at the end if none exists, or just to be safe
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
                
                this.injectRowUI(this.data[newIdx], newIdx);
                this.rowCount++;
            },

            injectRowUI(data, index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML;
                
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

                itemsTableBody.appendChild(newRow);

                if (data.product_id) {
                    const sel = newRow.querySelector('.product-select');
                    this.populateSel(sel, data.product_id);
                    newRow.querySelector('.description-input').value = data.description || '';
                    newRow.querySelector('.qty-input').value = data.qty;
                    newRow.querySelector('.rate-input').value = data.rate;
                    newRow.querySelector('.amount-input').value = data.amount.toFixed(2);
                    newRow.querySelector('.disc-percent-input').value = data.disc_percent || '';
                    newRow.querySelector('.discount-input').value = data.discount || '';
                    newRow.querySelector('.total-input').value = data.total.toFixed(2);
                    newRow.querySelector('.location-input').value = data.location;
                    newRow.querySelector('.unit-input').value = data.unit || '';
                    
                    fetchItemStock(data.product_id, data.location, index, newRow);
                } else {
                    newRow.querySelector('.qty-input').value = '1';
                    newRow.querySelector('.location-input').value = data.location;
                }

                initRowEvents(newRow);
            },

            populateSel(sel, val) {
                let optionsHTML = '<option value="">-- Select --</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = parseFloat(p.cost) || parseFloat(p.max_sale_price) || 0;
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-unit="${p.unit || ''}" data-rate="${rate}" ${p.id == val ? 'selected' : ''}>${safeCode}</option>`;
                    });
                }
                sel.innerHTML = optionsHTML;
            },

            updateRowData(rowIndex, field, value) {
                if (this.data[rowIndex]) {
                    this.data[rowIndex][field] = value;
                }
            },

            calculateRow(rowIndex, rowElement, sourceField = 'disc_percent') {
                const dataRow = this.data[rowIndex];
                if (!dataRow) return;

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
                let grandQty = 0, grandAmount = 0, grandDiscount = 0, grandTotal = 0;

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
                
                if (sourceField === 'header_percent' || (sourceField === 'none' && headerDiscPercent > 0)) {
                    headerDiscAmount = (subTotal * headerDiscPercent) / 100;
                    headerDiscAmountInput.value = headerDiscAmount > 0 ? headerDiscAmount.toFixed(2) : '';
                } else if (sourceField === 'header_amount') {
                    headerDiscPercent = 0;
                    headerDiscPercentInput.value = '';
                }

                const finalTotal = subTotal - headerDiscAmount;
                
                // Update LKR Grand Total
                const lkrSummary = document.querySelector('.footer-grand-total');
                if(lkrSummary) lkrSummary.value = finalTotal.toFixed(2);

                // Update Summary Section
                const subTotalInput = document.querySelector('.summary-subtotal');
                if(subTotalInput) subTotalInput.value = subTotal.toFixed(2);

                // Update Summary SSCL and VAT
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
                    });
                    this.updateRowData(rowIndex, 'product_id', '');
                    this.updateRowData(rowIndex, 'qty', 1);
                    this.calculateGrandTotal();
                }
            },

            reindexRows() {
                const rows = document.querySelectorAll('#itemsTable tbody tr.item-row:not(.d-none)');
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
            
            if (productSelect.innerHTML == '<option></option>') {
                purchaseOrderController.populateSel(productSelect, '');
            }

            function handleProductChange(value) {
                purchaseOrderController.updateRowData(rowIndex, 'product_id', value);
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';
                        const rate = parseFloat(selectedObj.cost) || parseFloat(selectedObj.max_sale_price) || 0;

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
                        purchaseOrderController.updateRowData(rowIndex, 'product_id', val);
                        handleProductChange(val);
                    }
                });
            }

            [qtyInput, rateInput, discPercentInput, discountInput].forEach(el => {
                if (el) {
                    el.addEventListener('input', function() {
                        let fieldName = 'qty', sourceField = 'disc_percent';
                        if (this.classList.contains('rate-input')) fieldName = 'rate';
                        if (this.classList.contains('disc-percent-input')) { fieldName = 'disc_percent'; sourceField = 'disc_percent'; }
                        if (this.classList.contains('discount-input')) { fieldName = 'discount'; sourceField = 'discount'; }
                        
                        purchaseOrderController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                        purchaseOrderController.calculateRow(rowIndex, row, sourceField);
                    });
                }
            });

            const removeBtn = row.querySelector('.remove-row-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    purchaseOrderController.removeRow(rowIndex, row);
                });
            }
        }

        purchaseOrderController.init();
        purchaseOrderController.calculateGrandTotal();

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

        document.querySelector('.header-discount-percent').addEventListener('input', () => purchaseOrderController.calculateGrandTotal('header_percent'));
        document.querySelector('.header-discount-amount').addEventListener('input', () => purchaseOrderController.calculateGrandTotal('header_amount'));
        
        const ssclPercentInput = document.querySelector('input[name="sscl_percent"]');
        if (ssclPercentInput) ssclPercentInput.addEventListener('input', () => purchaseOrderController.calculateGrandTotal());
        
        const vatPercentInput = document.querySelector('input[name="vat_percent"]');
        if (vatPercentInput) vatPercentInput.addEventListener('input', () => purchaseOrderController.calculateGrandTotal());

        if (vendorSelect) vendorSelect.addEventListener('change', function () { fetchVendorDetails(this.value); });
    });
</script>
@endpush
