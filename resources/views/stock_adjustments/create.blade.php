@extends('layouts.admin')

@section('title', 'Stock Adjustment - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Adjustment</h4>
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
                <h5 class="card-title mb-0"><i class="ri-equalizer-line me-1"></i>Stock Adjustment - Create</h5>
                <div class="float-end">
                    <button type="submit" form="createAdjustmentForm" class="btn btn-info btn-sm me-1"><i class="ri-save-line me-1"></i>Save & New</button>
                    <button type="submit" form="createAdjustmentForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save & Close</button>
                    <button type="reset" form="createAdjustmentForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createAdjustmentForm" action="{{ route('stock-adjustments.store') }}" method="POST">
                    @csrf

                    <!-- Header Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Account</label>
                            <select name="account_id" class="form-select form-select-sm">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Location</label>
                            <select name="location" class="form-select form-select-sm">
                                <option value="">Select Location</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('location') == $loc->name || $loc->name == 'Main Warehouse') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Adjustment Amount</label>
                            <input type="text" class="form-control form-control-sm text-end" value="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Stock Adjust No</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="STKA/00007" readonly>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <textarea name="memo" class="form-control form-control-sm" rows="2">{{ old('memo') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Date</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        /* Ensure critical columns don't vanish */
                        #itemsTable .product-select { min-width: 120px !important; }
                    </style>
                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Onhand</th>
                                    <th>Rate(LKR)</th>
                                    <th>Value</th>
                                    <th>New Qty</th>
                                    <th>Qty Diff</th>
                                    <th>New Value</th>
                                    <th>Value Diff</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select form-select-sm product-select border-0"><option></option></select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm description-input bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm onhand-input text-center bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-end rate-input bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-end current-value-input bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-center new-qty-input" step="any"></td>
                                    <td><input type="number" class="form-control form-control-sm text-center qty-diff-input bg-light fw-bold" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-end new-value-input bg-light" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm text-end value-diff-input bg-light fw-bold" readonly></td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="7" class="text-end fw-bold">Total Adjustment Amount</td>
                                    <td colspan="2"><input type="text" class="form-control form-control-sm text-end bg-white footer-total fw-bold text-primary" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Javascript Hydration Source -->
                    <script>
                        window.serverProductList = @json($products ?? []);
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Table Controller (Data Source Level) --- //
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location"]');
            return locNode ? locNode.value : '';
        }

        const adjustmentController = {
            data: [],
            rowCount: 0,
            rowTemplateHTML: '',

            init() {
                const firstRow = document.querySelector('.item-row');
                this.rowTemplateHTML = firstRow.innerHTML;
                firstRow.remove();

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
                this.data.push({
                    rowId: this.rowCount,
                    product_id: '',
                    description: '',
                    onhand: 0,
                    rate: 0,
                    current_value: 0,
                    new_qty: 0,
                    qty_diff: 0,
                    new_value: 0,
                    value_diff: 0
                });
                
                this.injectRowUI();
                this.rowCount++;
            },

            injectRowUI() {
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
                newRow.querySelectorAll('input, select').forEach(el => {
                    if (el.classList.contains('product-select')) el.name = `items[${newIndex}][product_id]`;
                    if (el.classList.contains('description-input')) el.name = `items[${newIndex}][description]`;
                    if (el.classList.contains('onhand-input')) el.name = `items[${newIndex}][onhand]`;
                    if (el.classList.contains('rate-input')) el.name = `items[${newIndex}][rate]`;
                    if (el.classList.contains('current-value-input')) el.name = `items[${newIndex}][current_value]`;
                    if (el.classList.contains('new-qty-input')) el.name = `items[${newIndex}][new_qty]`;
                    if (el.classList.contains('qty-diff-input')) el.name = `items[${newIndex}][qty_diff]`;
                    if (el.classList.contains('new-value-input')) el.name = `items[${newIndex}][new_value]`;
                    if (el.classList.contains('value-diff-input')) el.name = `items[${newIndex}][value_diff]`;
                });

                newRow.dataset.rowIndex = this.data.length - 1;
                document.querySelector('#itemsTable tbody').appendChild(newRow);
                
                initRowEvents(newRow);
            },

            updateRowData(rowIndex, field, value) {
                if (this.data[rowIndex]) {
                    this.data[rowIndex][field] = value;
                }
            },

            calculateRow(rowIndex, rowElement) {
                if (!this.data[rowIndex]) return;
                
                const dataRow = this.data[rowIndex];
                dataRow.current_value = dataRow.onhand * dataRow.rate;
                dataRow.qty_diff = dataRow.new_qty - dataRow.onhand;
                dataRow.new_value = dataRow.new_qty * dataRow.rate;
                dataRow.value_diff = dataRow.new_value - dataRow.current_value;

                rowElement.querySelector('.current-value-input').value = dataRow.current_value.toFixed(2);
                rowElement.querySelector('.qty-diff-input').value = dataRow.qty_diff.toFixed(2);
                rowElement.querySelector('.new-value-input').value = dataRow.new_value.toFixed(2);
                
                const valDiffInput = rowElement.querySelector('.value-diff-input');
                valDiffInput.value = dataRow.value_diff.toFixed(2);
                
                if (dataRow.value_diff > 0) {
                    valDiffInput.classList.remove('text-danger');
                    valDiffInput.classList.add('text-success');
                } else if (dataRow.value_diff < 0) {
                    valDiffInput.classList.remove('text-success');
                    valDiffInput.classList.add('text-danger');
                } else {
                    valDiffInput.classList.remove('text-success', 'text-danger');
                }

                this.calculateGrandTotal();
            },

            calculateGrandTotal() {
                let grandValueDiff = 0;

                this.data.forEach(row => {
                    grandValueDiff += parseFloat(row.value_diff) || 0;
                });
    
                document.querySelector('.footer-total').value = grandValueDiff.toFixed(2);
                
                const headerAdjInput = document.querySelector('input[value="0.00"]'); 
                if (headerAdjInput && headerAdjInput.parentElement.querySelector('label').textContent.includes('Adjustment Amount')) {
                    headerAdjInput.value = grandValueDiff.toFixed(2);
                }
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            if (!productId || !location) {
                adjustmentController.updateRowData(rowIndex, 'onhand', 0);
                adjustmentController.calculateRow(rowIndex, row);
                row.querySelector('.onhand-input').value = '0';
                return;
            }
            
            const onhandInput = row.querySelector('.onhand-input');
            onhandInput.value = '...';
            
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('Network response error');
                })
                .then(data => {
                    const balance = parseFloat(data.stock) || 0; 
                    onhandInput.value = balance;
                    adjustmentController.updateRowData(rowIndex, 'onhand', balance);
                    
                    const newQtyInput = row.querySelector('.new-qty-input');
                    if (!newQtyInput.value) {
                         newQtyInput.value = balance;
                         adjustmentController.updateRowData(rowIndex, 'new_qty', balance);
                    }
                    adjustmentController.calculateRow(rowIndex, row);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    adjustmentController.updateRowData(rowIndex, 'onhand', 0);
                    adjustmentController.calculateRow(rowIndex, row);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const newQtyInput = row.querySelector('.new-qty-input');

            function handleProductChange(value) {
                adjustmentController.updateRowData(rowIndex, 'product_id', value);
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const rate = parseFloat(selectedObj.cost) || parseFloat(selectedObj.max_sale_price) || 0; // Adjustments affect cost valuation mostly

                        adjustmentController.updateRowData(rowIndex, 'description', desc);
                        adjustmentController.updateRowData(rowIndex, 'rate', rate);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.rate-input').value = rate;
                        
                        const currentLoc = getDefaultLocation();
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        adjustmentController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.rate-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                    adjustmentController.updateRowData(rowIndex, 'onhand', 0);
                    adjustmentController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">Select Item</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let rate = parseFloat(p.cost) || parseFloat(p.max_sale_price) || 0;
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-rate="${rate}">${safeCode}</option>`;
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
                    onChange: function(value) {
                        handleProductChange(value);
                    }
                });
            } else if (window.jQuery && $(productSelect).select2) {
                $(productSelect).select2();
                $(productSelect).on('change', function() {
                    handleProductChange(this.value);
                });
            } else {
                productSelect.addEventListener('change', function() {
                    handleProductChange(this.value);
                });
            }

            [newQtyInput].forEach(input => {
                input.addEventListener('input', function() {
                    adjustmentController.updateRowData(rowIndex, 'new_qty', parseFloat(this.value) || 0);
                    adjustmentController.calculateRow(rowIndex, row);
                });
            });
        }

        adjustmentController.init();

        const mainLocationSelect = document.querySelector('select[name="location"]');
        if (mainLocationSelect) {
            mainLocationSelect.addEventListener('change', function(e) {
                if (e.detail && e.detail.isSyncTrigger) return; 
                const newLocation = this.value;
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowIndex = parseInt(row.dataset.rowIndex);
                    if (!isNaN(rowIndex)) {
                        const productSelect = row.querySelector('.product-select');
                        const productId = productSelect ? productSelect.value : '';
                        if (productId) {
                            fetchItemStock(productId, newLocation, rowIndex, row);
                        }
                    }
                });
            });
        }

    });
</script>
@endpush
