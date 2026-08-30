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
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="ri-exchange-line me-1"></i>Inventory Transfer - Create</h5>
                    <span class="badge bg-warning-subtle text-warning ms-2 rounded-pill">Status: Pending</span>
                </div>
                <div class="float-end">
                    <button type="submit" form="createTransferForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Transfer</button>
                    <button type="submit" form="createTransferForm" name="action" value="save_and_print" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
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
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Site From</label>
                            <select name="site_from" id="site_from_select" class="form-select form-select-sm">
                                <option value="" data-id="">Select Site From</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" data-id="{{ $loc->id }}" {{ (old('site_from') == $loc->name || $loc->name == 'Main Warehouse') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Site To</label>
                            <select name="site_to" class="form-select form-select-sm">
                                <option value="">Select Site To</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('site_to') == $loc->name || $loc->name == 'Main Warehouse') ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Rep Agent</label>
                            <select name="rep_agent_id" id="rep_agent_select" class="form-select form-select-sm" disabled>
                                <option value="">Select Rep Agent</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Inventory Transfer No</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="(Auto Generated)" readonly>
                        </div>
                        <div class="col-md-2">
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
                                    <th>Assigned Rep</th>
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
                                    <td class="assigned-rep-name bg-light align-middle text-start px-2" style="font-size: 0.7rem; min-width: 120px;">—</td>
                                    <td><input type="number" class="form-control form-control-sm text-center qty-input" step="any"></td>
                                    <td><input type="text" class="form-control form-control-sm unit-input bg-light text-center" readonly></td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Javascript Hydration Source -->
                    <script>
                        window.oldItems = @json(old('items', []));
                        window.serverProductList = @json($products ?? []);
                    </script>
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

                if (window.oldItems && window.oldItems.length > 0) {
                    window.oldItems.forEach(item => {
                        this.appendRow(item);
                    });
                    // Always ensure there's at least one empty row or one more row to add items
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
                        let rows = document.querySelectorAll('#itemsTable tbody tr');
                        if (rows.length > 0) {
                            let lastRowQty = rows[rows.length - 1].querySelector('.qty-input');
                            if (lastRowQty) { lastRowQty.value = '0'; }
                        }
                    }
                }
            },

            appendRow(itemData = null) {
                const rowData = {
                    rowId: this.rowCount,
                    product_id: itemData ? itemData.product_id : '',
                    description: itemData ? itemData.description : '',
                    onhand: itemData ? itemData.onhand : '',
                    qty: itemData ? itemData.qty : 1,
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
                
                newRow.querySelectorAll('.ts-wrapper, .select2-container').forEach(wrapper => wrapper.remove());
                newRow.querySelectorAll('[data-select2-id]').forEach(el => el.removeAttribute('data-select2-id'));
                newRow.querySelectorAll('select').forEach(select => {
                    select.classList.remove('tomselected', 'ts-hidden-accessible', 'select2-hidden-accessible');
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

                const unitInput = newRow.querySelector('.unit-input');
                if (unitInput) {
                    unitInput.name = `items[${newIndex}][unit]`;
                    unitInput.value = data.unit;
                }

                newRow.dataset.rowIndex = this.data.length - 1;
                document.querySelector('#itemsTable tbody').appendChild(newRow);
                
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

            calculateRow(rowIndex, rowElement) {
                this.calculateGrandTotal();
            },

            calculateGrandTotal() {
                let grandQty = 0;

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
                });
    
                document.querySelector('.footer-qty').value = grandQty.toFixed(2);
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            const repCell = row.querySelector('.assigned-rep-name');

            if (!productId || !location) {
                onhandInput.value = '';
                transferNoteController.updateRowData(rowIndex, 'onhand', '');
                if (repCell) repCell.textContent = '—';
                return;
            }
            
            onhandInput.value = '...';
            if (repCell) repCell.textContent = 'Loading...';
            
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('Network response error');
                })
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    transferNoteController.updateRowData(rowIndex, 'onhand', balance);
                    if (repCell) {
                        repCell.textContent = data.assigned_rep_name ? data.assigned_rep_name : 'No Rep Assigned';
                    }
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    transferNoteController.updateRowData(rowIndex, 'onhand', 0);
                    if (repCell) repCell.textContent = 'No Rep Assigned';
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
                    const repCell = row.querySelector('.assigned-rep-name');
                    if (repCell) repCell.textContent = '—';
                    transferNoteController.calculateRow(rowIndex, row);
                }
            }

            if (productSelect) {
                let optionsHTML = '<option value="">Select Item</option>';
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

        // --- Unified Dependent Dropdown handling (TomSelect / Select2 / Native) --- //
        const siteFromSelector = 'select[name="site_from"], #site_from_select, #site_from';
        const repAgentSelector = 'select[name="rep_agent_id"], select[name="rep_agent"], #rep_agent_select, #rep_agent';

        function fetchRepAgents(locationId) {
            const repAgentEl = document.querySelector(repAgentSelector);
            if (!repAgentEl) return;

            // FIX: Completely empty out any pre-existing server-rendered Blade options first!
            repAgentEl.innerHTML = '';

            // Get or initialize TomSelect instance if TomSelect is loaded on page
            let repAgentTS = repAgentEl.tomselect;
            if (!repAgentTS && window.TomSelect) {
                try {
                    repAgentTS = new TomSelect(repAgentEl, {
                        create: false,
                        placeholder: "Select Rep Agent"
                    });
                } catch (e) {
                    repAgentTS = repAgentEl.tomselect;
                }
            }

            if (!locationId) {
                repAgentEl.innerHTML = '<option value="">Select Rep Agent</option>';
                repAgentEl.disabled = true;
                if (repAgentTS) {
                    repAgentTS.clearOptions();
                    repAgentTS.sync();
                    repAgentTS.disable();
                } else if (window.jQuery && $(repAgentEl).data('select2')) {
                    $(repAgentEl).trigger('change.select2');
                }
                return;
            }

            repAgentEl.innerHTML = '<option value="">Loading Reps...</option>';
            repAgentEl.disabled = true;
            if (repAgentTS) {
                repAgentTS.clearOptions();
                repAgentTS.sync();
                repAgentTS.disable();
            } else if (window.jQuery && $(repAgentEl).data('select2')) {
                $(repAgentEl).trigger('change.select2');
            }

            fetch(`/api/locations/${locationId}/reps`)
                .then(response => response.json())
                .then(data => {
                    // 1. Clear native select options
                    repAgentEl.innerHTML = '<option value="">Select Rep Agent</option>';
                    
                    if (data && data.length > 0) {
                        data.forEach(rep => {
                            const option = document.createElement('option');
                            option.value = rep.id;
                            option.textContent = rep.name;
                            repAgentEl.appendChild(option);
                        });
                        repAgentEl.disabled = false;
                    } else {
                        repAgentEl.innerHTML = '<option value="">No Reps Found</option>';
                        repAgentEl.disabled = true;
                    }

                    // CRITICAL FIX: Explicitly notify and sync the UI dropdown component framework
                    if (repAgentTS) {
                        // If it's a TomSelect instance, clear cache and sync
                        repAgentTS.clearOptions();
                        repAgentTS.sync();
                        if (data && data.length > 0) {
                            repAgentTS.enable();
                        } else {
                            repAgentTS.disable();
                        }
                    } else if (window.jQuery && $(repAgentEl).data('select2')) {
                        // If it's a Select2 instance, force update
                        $(repAgentEl).trigger('change.select2');
                    } else if (window.jQuery) {
                        // Safe fallback for generic jQuery custom dropdown setups
                        $(repAgentEl).trigger('change');
                    }
                })
                .catch(error => {
                    console.error('Error fetching reps:', error);
                    repAgentEl.innerHTML = '<option value="">Error Loading Reps</option>';
                    repAgentEl.disabled = true;
                    if (repAgentTS) {
                        repAgentTS.clearOptions();
                        repAgentTS.sync();
                        repAgentTS.disable();
                    } else if (window.jQuery && $(repAgentEl).data('select2')) {
                        $(repAgentEl).trigger('change.select2');
                    }
                });
        }

        // Setup event listeners using hybrid approach
        if (window.jQuery) {
            // Use jQuery delegation to capture Select2 / TomSelect events perfectly
            $(document).on('change', siteFromSelector, function() {
                const locationId = $(this).val();
                fetchRepAgents(locationId);

                // Run existing stock fetching loop
                document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                    const rowIndex = parseInt(row.dataset.rowIndex);
                    if (!isNaN(rowIndex)) {
                        const productSelect = row.querySelector('.product-select');
                        const productId = productSelect ? productSelect.value : '';
                        if (productId) {
                            fetchItemStock(productId, locationId, rowIndex, row);
                        }
                    }
                });
            });

            // Initial execution on ready
            $(function() {
                const initialLoc = $(siteFromSelector).val();
                const $repAgent = $(repAgentSelector);
                if (initialLoc) {
                    if ($repAgent.length > 0) {
                        $repAgent.html('<option value="">Select Rep Agent</option>');
                    }
                    fetchRepAgents(initialLoc);
                }
            });
        } else {
            // Native fallback
            const nativeSiteFrom = document.querySelector(siteFromSelector);
            if (nativeSiteFrom) {
                nativeSiteFrom.addEventListener('change', function() {
                    const locationId = this.value;
                    fetchRepAgents(locationId);

                    document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
                        const rowIndex = parseInt(row.dataset.rowIndex);
                        if (!isNaN(rowIndex)) {
                            const productSelect = row.querySelector('.product-select');
                            const productId = productSelect ? productSelect.value : '';
                            if (productId) {
                                fetchItemStock(productId, locationId, rowIndex, row);
                            }
                        }
                    });
                });

                // Initial native load
                const initialLoc = nativeSiteFrom.value;
                const nativeRepAgent = document.querySelector(repAgentSelector);
                if (initialLoc) {
                    if (nativeRepAgent) {
                        nativeRepAgent.innerHTML = '<option value="">Select Rep Agent</option>';
                    }
                    fetchRepAgents(initialLoc);
                }
            }
        }

        // --- Form Submission Fix --- //
        const form = document.getElementById('createTransferForm');
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
                    alert('Please add at least one valid item to the transfer.');
                }
            });
        }

    });
</script>
@endpush
