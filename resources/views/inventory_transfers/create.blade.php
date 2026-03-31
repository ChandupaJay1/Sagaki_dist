@extends('layouts.admin')

@section('title', 'Inventory Transfer - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Inventory Transfer</h4>
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
                <h5 class="card-title mb-0"><i class="ri-exchange-line me-1"></i>Inventory Transfer - Create</h5>
                <div class="float-end">
                    <button type="submit" form="createTransferForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Transfer</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
                    <button type="reset" form="createTransferForm" class="btn btn-warning btn-sm"><i class="ri-refresh-line me-1"></i>Reset</button>
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

                <form id="createTransferForm" action="{{ route('inventory-transfers.store') }}" method="POST">
                    @csrf

                    <!-- Header Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Site From</label>
                            <select name="site_from" class="form-select form-select-sm">
                                <option value="">-- Select --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('site_from') == $loc->name || $loc->name == 'Main Stock') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Site To</label>
                            <select name="site_to" class="form-select form-select-sm">
                                <option value="">-- Select --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('site_to') == $loc->name || $loc->name == 'Main Stock') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Inventory Transfer No</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="00014" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <textarea name="memo" class="form-control form-control-sm" rows="2">{{ old('memo') }}</textarea>
                        </div>
                    </div>

                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        /* Ensure critical columns don't vanish */
                        #itemsTable .product-select { min-width: 120px !important; }
                        #itemsTable .unit-input { min-width: 70px !important; }
                    </style>
                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>OnHand (From Site)</th>
                                    <th>Qty</th>
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
                                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" readonly></td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty" readonly></td>
                                    <td></td>
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
        function getSiteFrom() {
            const locNode = document.querySelector('select[name="site_from"]');
            return locNode ? locNode.value : '';
        }

        const transferNoteController = {
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
                    onhand: '',
                    qty: 1,
                    unit: ''
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
                    if (input.classList.contains('qty-input')) input.value = '1';
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
                    if (el.classList.contains('qty-input')) el.name = `items[${newIndex}][qty]`;
                    if (el.classList.contains('unit-input')) el.name = `items[${newIndex}][unit]`;
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
                this.calculateGrandTotal();
            },

            calculateGrandTotal() {
                let grandQty = 0;

                this.data.forEach(row => {
                    grandQty += parseFloat(row.qty) || 0;
                });
    
                document.querySelector('.footer-qty').value = grandQty.toFixed(2);
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            if (!productId || !location) {
                onhandInput.value = '';
                transferNoteController.updateRowData(rowIndex, 'onhand', '');
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
                    transferNoteController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    transferNoteController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');

            if (!qtyInput.value) qtyInput.value = '1';

            function handleProductChange(value) {
                transferNoteController.updateRowData(rowIndex, 'product_id', value);
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';

                        transferNoteController.updateRowData(rowIndex, 'description', desc);
                        transferNoteController.updateRowData(rowIndex, 'unit', unit);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.unit-input').value = unit;
                        
                        const currentLoc = getSiteFrom();
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        transferNoteController.calculateRow(rowIndex, row);
                        transferNoteController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                    transferNoteController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">-- Select --</option>';
                if (window.serverProductList && Array.isArray(window.serverProductList)) {
                    window.serverProductList.forEach(p => {
                        let safeName = (p.name || '').replace(/"/g, '&quot;');
                        let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        optionsHTML += `<option value="${p.id}" data-name="${safeName}" data-unit="${p.unit || ''}">${safeCode}</option>`;
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

            [qtyInput].forEach(input => {
                input.addEventListener('input', function() {
                    transferNoteController.updateRowData(rowIndex, 'qty', parseFloat(this.value) || 0);
                    transferNoteController.calculateRow(rowIndex, row);
                });
            });
        }

        transferNoteController.init();

        const siteFromSelect = document.querySelector('select[name="site_from"]');
        if (siteFromSelect) {
            siteFromSelect.addEventListener('change', function(e) {
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
