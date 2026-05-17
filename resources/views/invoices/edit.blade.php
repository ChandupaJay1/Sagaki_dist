@extends('layouts.admin')

@section('title', 'Invoice - Edit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Invoice</h4>
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
                <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-1"></i>Invoice - Edit</h5>
                <div class="float-end">
                    <button type="submit" form="editInvoiceForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Update Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-warning btn-sm"><i class="ri-close-line me-1"></i>Cancel</a>
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

                <form id="editInvoiceForm" action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Header Row 1 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Customer Name <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm" required>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $invoice->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->company_name ?? $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" data-name="{{ $loc->name }}" {{ old('location_id', $invoice->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Invoice No</label>
                            <input type="text" name="invoice_no" class="form-control form-control-sm" value="{{ old('invoice_no', $invoice->invoice_no) }}">
                        </div>
                    </div>

                    <!-- Header Row 2 -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Address</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $invoice->address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Delivery Destination</label>
                            <textarea name="delivery_destination" class="form-control form-control-sm" rows="2">{{ old('delivery_destination', $invoice->delivery_destination) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Date</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', $invoice->date) }}">
                        </div>
                    </div>

                    <!-- Additional Info Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Villa Type</label>
                            <input type="text" name="villa_type" class="form-control form-control-sm" value="{{ old('villa_type', $invoice->villa_type) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Meal Plan</label>
                            <input type="text" name="meal_plan" class="form-control form-control-sm" value="{{ old('meal_plan', $invoice->meal_plan) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">No of Pax</label>
                            <input type="number" name="no_of_pax" class="form-control form-control-sm" value="{{ old('no_of_pax', $invoice->no_of_pax) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Check In</label>
                            <input type="date" name="check_in_date" class="form-control form-control-sm" value="{{ old('check_in_date', $invoice->check_in_date) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Check Out</label>
                            <input type="date" name="check_out_date" class="form-control form-control-sm" value="{{ old('check_out_date', $invoice->check_out_date) }}">
                        </div>
                    </div>

                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                    </style>

                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="fw-bold py-2 text-uppercase">Item Code</th>
                                    <th class="fw-bold py-2 text-uppercase">Description</th>
                                    <th class="fw-bold py-2 text-uppercase">Qty</th>
                                    <th class="fw-bold py-2 text-uppercase">Rate</th>
                                    <th class="fw-bold py-2 text-uppercase">Amount</th>
                                    <th class="fw-bold py-2 text-uppercase">Disc%</th>
                                    <th class="fw-bold py-2 text-uppercase">Discount</th>
                                    <th class="fw-bold py-2 text-uppercase">Total</th>
                                    <th class="fw-bold py-2 text-uppercase">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row d-none" id="row-template">
                                    <td>
                                        <select name="items[idx][product_id]" class="form-select form-select-sm product-select">
                                            <option value="">-- Select --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-unit="{{ $p->unit }}" data-rate="{{ $p->max_sale_price ?? $p->cost }}">{{ $p->code }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[idx][description]" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="number" name="items[idx][qty]" class="form-control form-control-sm text-center qty-input" step="any"></td>
                                    <td><input type="number" name="items[idx][rate]" class="form-control form-control-sm text-end rate-input" step="any"></td>
                                    <td><input type="number" name="items[idx][amount]" class="form-control form-control-sm text-end amount-input bg-light" readonly></td>
                                    <td><input type="number" name="items[idx][disc_percent]" class="form-control form-control-sm text-center disc-percent-input" step="any"></td>
                                    <td><input type="number" name="items[idx][discount]" class="form-control form-control-sm text-end discount-input" step="any"></td>
                                    <td><input type="number" name="items[idx][total]" class="form-control form-control-sm text-end fw-bold total-input bg-light" readonly></td>
                                    <td><input type="text" name="items[idx][location]" class="form-control form-control-sm location-input bg-light" value="Main Warehouse" readonly></td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="2" class="text-end">Grand Total</td>
                                    <td><input type="text" class="form-control form-control-sm text-center footer-qty bg-white" readonly></td>
                                    <td colspan="4"></td>
                                    <td><input type="text" class="form-control form-control-sm text-end footer-total bg-white" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <script>
                        window.serverProductList = @json($products);
                        window.existingItems = @json($invoice->items);
                    </script>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Account <span class="text-danger">*</span></label>
                                <select name="account_id" class="form-select form-select-sm border-danger" required>
                                    <option value="">-- Select Account --</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id', $invoice->account_id) == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea name="memo" class="form-control form-control-sm" rows="3" placeholder="Memo">{{ old('memo', $invoice->memo) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body p-2">
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="small fw-bold">Discount %</label>
                                            <input type="number" name="header_discount_percent" class="form-control form-control-sm header-discount-percent" value="{{ old('header_discount_percent', $invoice->header_discount_percent) }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold">Discount Amt</label>
                                            <input type="number" name="header_discount_amount" class="form-control form-control-sm header-discount-amount" value="{{ old('header_discount_amount', $invoice->header_discount_amount) }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Sub Total</span>
                                        <input type="text" class="form-control form-control-sm text-end w-50 sub-total bg-white" value="{{ number_format($invoice->items->sum('total'), 2, '.', '') }}" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Net Total</span>
                                        <input type="text" name="total_amount" class="form-control form-control-sm text-end w-50 summary-total bg-white" value="{{ $invoice->total_amount }}" readonly>
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
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const templateRow = document.getElementById('row-template');

        const invoiceController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: templateRow.innerHTML,

            init() {
                if (window.existingItems && window.existingItems.length > 0) {
                    window.existingItems.forEach((item, idx) => {
                        this.data.push({
                            rowId: idx,
                            product_id: item.product_id,
                            description: item.description || '',
                            onhand: item.onhand || '',
                            qty: parseFloat(item.qty) || 1,
                            rate: parseFloat(item.rate) || 0,
                            amount: parseFloat(item.amount) || 0,
                            disc_percent: parseFloat(item.disc_percent) || 0,
                            discount: parseFloat(item.discount) || 0,
                            total: parseFloat(item.total) || 0,
                            location: item.location || 'Main Warehouse',
                            unit: item.unit || ''
                        });
                        this.injectRowUI(idx);
                        this.rowCount++;
                    });
                }
                // Always add at least one empty row at the end
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
                const idx = this.data.length;
                this.data.push({
                    rowId: idx,
                    product_id: '',
                    description: '',
                    onhand: '',
                    qty: 1,
                    rate: 0,
                    amount: 0,
                    disc_percent: 0,
                    discount: 0,
                    total: 0,
                    location: getDefaultLocation() || 'Main Warehouse',
                    unit: ''
                });
                this.injectRowUI(idx);
                this.rowCount++;
            },

            injectRowUI(index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML.replace(/idx/g, index);
                
                itemsTableBody.appendChild(newRow);

                const data = this.data[index];
                if (data.product_id) {
                    const sel = newRow.querySelector('.product-select');
                    sel.value = data.product_id;
                    newRow.querySelector('.description-input').value = data.description;
                    newRow.querySelector('.qty-input').value = data.qty;
                    newRow.querySelector('.rate-input').value = data.rate;
                    newRow.querySelector('.amount-input').value = data.amount.toFixed(2);
                    newRow.querySelector('.disc-percent-input').value = data.disc_percent || '0';
                    newRow.querySelector('.discount-input').value = data.discount.toFixed(2);
                    newRow.querySelector('.total-input').value = data.total.toFixed(2);
                    newRow.querySelector('.location-input').value = data.location;
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
                dataRow.amount = dataRow.qty * dataRow.rate;
                
                if (sourceField === 'disc_percent') {
                    dataRow.discount = (dataRow.amount * dataRow.disc_percent) / 100;
                    rowElement.querySelector('.discount-input').value = dataRow.discount > 0 ? dataRow.discount.toFixed(2) : '0.00';
                } else if (sourceField === 'discount') {
                    // Two-way discount not implemented here yet, but keeping structure
                }

                dataRow.total = dataRow.amount - dataRow.discount;

                rowElement.querySelector('.amount-input').value = dataRow.amount.toFixed(2);
                rowElement.querySelector('.total-input').value = dataRow.total.toFixed(2);

                this.calculateGrandTotal();
            },

            calculateGrandTotal() {
                let grandQty = 0;
                let grandTotal = 0;

                this.data.forEach(row => {
                    grandQty += parseFloat(row.qty) || 0;
                    grandTotal += parseFloat(row.total) || 0;
                });
    
                document.querySelector('.footer-qty').value = grandQty.toFixed(2);
                document.querySelector('.footer-total').value = grandTotal.toFixed(2);
                
                document.querySelector('.sub-total').value = grandTotal.toFixed(2);
                calculateFinalTotal();
            }
        };

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const discPercentInput = row.querySelector('.disc-percent-input');
            const discountInput = row.querySelector('.discount-input');

            function handleProductChange(value) {
                invoiceController.updateRowData(rowIndex, 'product_id', value);
                if (value) {
                    const opt = productSelect.querySelector(`option[value="${value}"]`);
                    if (opt) {
                        const desc = opt.dataset.name || '';
                        const rate = parseFloat(opt.dataset.rate) || 0;

                        invoiceController.updateRowData(rowIndex, 'description', desc);
                        invoiceController.updateRowData(rowIndex, 'rate', rate);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.rate-input').value = rate;

                        invoiceController.calculateRow(rowIndex, row);
                        invoiceController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    invoiceController.calculateRow(rowIndex, row);
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
                    onChange: (val) => handleProductChange(val)
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
                    
                    invoiceController.updateRowData(rowIndex, fieldName, parseFloat(this.value) || 0);
                    invoiceController.calculateRow(rowIndex, row, sourceField);
                });
            });
        }

        function calculateFinalTotal(source = 'none') {
            const subTotal = parseFloat(document.querySelector('.sub-total').value) || 0;
            const discPercentInput = document.querySelector('.header-discount-percent');
            const discAmountInput = document.querySelector('.header-discount-amount');
            
            let discPercent = parseFloat(discPercentInput.value) || 0;
            let discAmount = parseFloat(discAmountInput.value) || 0;

            if (source === 'header_percent' || (source === 'none' && discPercent > 0)) {
                discAmount = (subTotal * discPercent) / 100;
                discAmountInput.value = discAmount > 0 ? discAmount.toFixed(2) : '';
            } else if (source === 'header_amount') {
                discPercent = 0;
                discPercentInput.value = '';
            }

            document.querySelector('.summary-total').value = (subTotal - discAmount).toFixed(2);
        }

        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        invoiceController.init();
        invoiceController.calculateGrandTotal();

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
                            invoiceController.updateRowData(rowIndex, 'location', locationName);
                        }
                    }
                });
            });
        }
        
        document.querySelector('.header-discount-percent').addEventListener('input', () => calculateFinalTotal('header_percent'));
        document.querySelector('.header-discount-amount').addEventListener('input', () => calculateFinalTotal('header_amount'));
    });
</script>
@endpush
