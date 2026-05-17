@extends('layouts.admin')

@section('title', 'Stock Adjustment - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Stock Adjustment</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary"><i class="ri-information-line me-1"></i>Create a new stock adjustment.</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="ri-equalizer-line me-1"></i>Stock Adjustment - Create</h5>
                    <span class="badge bg-warning-subtle text-warning ms-2 rounded-pill">Status: Pending</span>
                </div>
                <div class="float-end">
                    <button type="submit" form="createAdjForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save Adjustment</button>
                    <a href="{{ route('stock-adjustments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ri-close-line me-1"></i>Cancel</a>
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

                <form id="createAdjForm" action="{{ route('stock-adjustments.store') }}" method="POST">
                    @csrf

                    <!-- Header Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Adjustment No</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $adjustmentNo }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select form-select-sm" required>
                                <option value="">Select Location</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" data-name="{{ $loc->name }}" {{ (old('location_id') == $loc->id || $loc->name == 'Main Warehouse') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <input type="text" name="memo" class="form-control form-control-sm" value="{{ old('memo') }}" placeholder="Reason for adjustment">
                        </div>
                    </div>

                    <!-- Items Table -->
                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        #itemsTable .product-select { min-width: 150px !important; }
                        #itemsTable .qty-input { min-width: 80px !important; }
                        #itemsTable .onhand-input { min-width: 80px !important; }
                        .ts-dropdown .ts-dropdown-content { max-height: 400px !important; }
                    </style>

                    <div class="table-responsive mb-3 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center" id="itemsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="fw-bold py-2 text-uppercase">Item Code</th>
                                    <th class="fw-bold py-2 text-uppercase">Description</th>
                                    <th class="fw-bold py-2 text-uppercase">Current Qty (System)</th>
                                    <th class="fw-bold py-2 text-uppercase">New Qty (Physical)</th>
                                    <th class="fw-bold py-2 text-uppercase">Adjustment Qty</th>
                                    <th class="fw-bold py-2 text-uppercase" style="width: 40px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Initial rows will be added by JS -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Javascript Hydration Source -->
<script>
    window.serverProductList = @json($products ?? []);
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        const adjController = {
            data: [],
            rowCount: 0,

            init() {
                // We use the exact same initialization pattern as Invoice/GRN
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
                const newIdx = this.data.length;
                this.data.push({
                    rowId: newIdx,
                    product_id: '',
                    description: '',
                    current_qty: 0,
                    new_qty: 0,
                    adjustment_qty: 0
                });
                
                this.injectRowUI(newIdx);
                this.rowCount++;
            },

            injectRowUI(index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                
                // Construct clean HTML exactly as in Invoice/GRN
                newRow.innerHTML = `
                    <td>
                        <select class="form-select form-select-sm product-select border-0" name="items[${index}][product_id]">
                            <option value="">Select Item</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm description-input bg-light" name="items[${index}][description]" readonly></td>
                    <td><input type="text" class="form-control form-control-sm onhand-input text-center bg-light" name="items[${index}][current_qty]" readonly></td>
                    <td><input type="number" class="form-control form-control-sm text-center new-qty-input" name="items[${index}][new_qty]" step="any"></td>
                    <td><input type="text" class="form-control form-control-sm adj-qty-input text-center bg-light fw-bold" name="items[${index}][adjustment_qty]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                    </td>
                `;

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
                const newQtyInput = rowElement.querySelector('.new-qty-input');
                const adjInput = rowElement.querySelector('.adj-qty-input');
                
                const current = parseFloat(dataRow.current_qty) || 0;
                
                if (newQtyInput.value === '') {
                    dataRow.new_qty = 0;
                    dataRow.adjustment_qty = 0;
                    adjInput.value = '0.00';
                    adjInput.classList.remove('text-success', 'text-danger');
                    return;
                }

                const physical = parseFloat(newQtyInput.value) || 0;
                const diff = physical - current;

                dataRow.new_qty = physical;
                dataRow.adjustment_qty = diff;
                adjInput.value = diff.toFixed(2);
                
                if (diff > 0) {
                    adjInput.classList.remove('text-danger');
                    adjInput.classList.add('text-success');
                } else if (diff < 0) {
                    adjInput.classList.remove('text-success');
                    adjInput.classList.add('text-danger');
                } else {
                    adjInput.classList.remove('text-success', 'text-danger');
                }
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
                } else {
                    const productSelect = rowElement.querySelector('.product-select');
                    if (productSelect && productSelect.tomselect) {
                        productSelect.tomselect.clear();
                    }
                    
                    rowElement.querySelectorAll('input').forEach(input => input.value = '');
                    this.updateRowData(rowIndex, 'product_id', '');
                    this.updateRowData(rowIndex, 'description', '');
                    this.updateRowData(rowIndex, 'current_qty', 0);
                    this.updateRowData(rowIndex, 'new_qty', 0);
                    this.updateRowData(rowIndex, 'adjustment_qty', 0);
                    this.calculateRow(rowIndex, rowElement);
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
                adjController.updateRowData(rowIndex, 'current_qty', 0);
                adjController.calculateRow(rowIndex, row);
                return;
            }
            
            onhandInput.value = '...';
            
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    adjController.updateRowData(rowIndex, 'current_qty', balance);
                    adjController.calculateRow(rowIndex, row);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    adjController.updateRowData(rowIndex, 'current_qty', 0);
                    adjController.calculateRow(rowIndex, row);
                });
        }

        function initRowEvents(row) {
            const rowIndex = parseInt(row.dataset.rowIndex);
            const productSelect = row.querySelector('.product-select');
            const newQtyInput = row.querySelector('.new-qty-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            function handleProductChange(value) {
                adjController.updateRowData(rowIndex, 'product_id', value);
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        adjController.updateRowData(rowIndex, 'description', desc);
                        row.querySelector('.description-input').value = desc;
                        
                        const currentLoc = getDefaultLocation();
                        fetchItemStock(value, currentLoc, rowIndex, row);

                        adjController.checkAndAppendRow(rowIndex);
                    }
                } else {
                    adjController.updateRowData(rowIndex, 'description', '');
                    adjController.updateRowData(rowIndex, 'current_qty', 0);
                    adjController.updateRowData(rowIndex, 'new_qty', 0);
                    adjController.updateRowData(rowIndex, 'adjustment_qty', 0);

                    row.querySelector('.description-input').value = '';
                    row.querySelector('.onhand-input').value = '';
                    row.querySelector('.new-qty-input').value = '';
                    row.querySelector('.adj-qty-input').value = '';
                    adjController.calculateRow(rowIndex, row);
                }
            }

            // --- STANDARD SYSTEM PATTERN START ---
            // 1. Populate Options
            let optionsHTML = '<option value="">Select Item</option>';
            if (window.serverProductList && Array.isArray(window.serverProductList)) {
                window.serverProductList.forEach(p => {
                    let safeName = (p.name || '').replace(/"/g, '&quot;');
                    let safeCode = (p.code || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    optionsHTML += `<option value="${p.id}" data-name="${safeName}">${safeCode}</option>`;
                });
            }
            productSelect.innerHTML = optionsHTML;

            // 2. Initialize TomSelect using the exact Invoice/GRN pattern
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
                        // Crucial: Update data and trigger handlers exactly like working forms
                        adjController.updateRowData(rowIndex, 'product_id', value);
                        handleProductChange(value);
                    }
                });
            }
            // --- STANDARD SYSTEM PATTERN END ---

            newQtyInput.addEventListener('input', function() {
                adjController.updateRowData(rowIndex, 'new_qty', parseFloat(this.value) || 0);
                adjController.calculateRow(rowIndex, row);
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    adjController.removeRow(parseInt(row.dataset.rowIndex), row);
                });
            }
        }

        // Initialize controller
        adjController.init();

        const mainLocationSelect = document.querySelector('select[name="location_id"]');
        if (mainLocationSelect) {
            mainLocationSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const locationName = selectedOption ? selectedOption.dataset.name : '';
                
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowIndex = parseInt(row.dataset.rowIndex);
                    const productSelect = row.querySelector('.product-select');
                    const productId = productSelect ? productSelect.value : '';
                    if (productId) {
                        fetchItemStock(productId, locationName, rowIndex, row);
                    }
                });
            });
        }

        const form = document.getElementById('createAdjForm');
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
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.name) {
                                el.name = el.name.replace(/items\[\d+\]/, `items[${validRowIndex}]`);
                            }
                        });
                        validRowIndex++;
                    } else {
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.name) el.removeAttribute('name');
                        });
                    }
                });

                if (!hasValidRow) {
                    e.preventDefault();
                    alert('Please add at least one valid item for adjustment.');
                }
            });
        }

    });
</script>
@endpush
