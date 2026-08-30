@extends('layouts.admin')

@section('title', 'Issue Note - Create')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Issue Note</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary"><i class="ri-information-line me-1"></i>Create a new inventory issue note.</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-secondary d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-1"></i>Issue Note - Create</h5>
                    <span class="badge bg-warning-subtle text-warning ms-2 rounded-pill">Status: Pending</span>
                </div>
                <div class="float-end">
                    <button type="submit" form="createIssueForm" class="btn btn-success btn-sm me-1"><i class="ri-check-line me-1"></i>Save Issue Note</button>
                    <a href="{{ route('inventory-issues.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ri-close-line me-1"></i>Cancel</a>
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

                <form id="createIssueForm" action="{{ route('inventory-issues.store') }}" method="POST">
                    @csrf

                    <!-- Header Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Issue No</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $issueNo }}" readonly>
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
                            <label class="form-label small fw-bold mb-1">Account (Optional)</label>
                            <select name="account_id" class="form-select form-select-sm">
                                <option value="">Select Account</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold mb-1">Memo</label>
                            <textarea name="memo" class="form-control form-control-sm" rows="2" placeholder="Reason for issuing stock (e.g., Damaged, Internal Use)">{{ old('memo') }}</textarea>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <style>
                        #itemsTable th, #itemsTable td { padding: 0.15rem !important; font-size: 0.7rem !important; white-space: nowrap; }
                        #itemsTable .form-control-sm, #itemsTable .form-select-sm { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable .ts-wrapper .ts-control { padding: 0.1rem 0.2rem !important; font-size: 0.7rem !important; min-height: 22px !important; border-radius: 0.15rem; }
                        #itemsTable { width: 100% !important; table-layout: auto !important; }
                        #itemsTable .product-select { min-width: 150px !important; }
                        #itemsTable .unit-input { min-width: 80px !important; }
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
                                    <th class="fw-bold py-2 text-uppercase">OnHand</th>
                                    <th class="fw-bold py-2 text-uppercase">Qty</th>
                                    <th class="fw-bold py-2 text-uppercase">Unit</th>
                                    <th class="fw-bold py-2 text-uppercase" style="width: 40px"></th>
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
                                    <td>
                                        <button type="button" class="btn btn-link text-danger p-0 remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light text-center">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Qty</td>
                                    <td><input type="text" class="form-control form-control-sm text-center bg-white footer-qty fw-bold" id="footer-qty" readonly></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
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
        
        function getDefaultLocation() {
            const locNode = document.querySelector('select[name="location_id"]');
            if (locNode && locNode.selectedIndex >= 0) {
                const selectedOption = locNode.options[locNode.selectedIndex];
                return selectedOption ? selectedOption.dataset.name || '' : '';
            }
            return '';
        }

        const issueController = {
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
                        let rows = document.querySelectorAll('#itemsTable tbody tr');
                        if (rows.length > 0) {
                            let lastRowQty = rows[rows.length - 1].querySelector('.qty-input');
                            if (lastRowQty) { lastRowQty.value = '0'; }
                        }
                    }
                }
            },

            appendRow() {
                const newIdx = this.data.length;
                this.data.push({
                    rowId: newIdx,
                    product_id: '',
                    description: '',
                    onhand: '',
                    qty: 1,
                    unit: ''
                });
                
                this.injectRowUI(newIdx);
                this.rowCount++;
            },

            injectRowUI(index) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.rowIndex = index;
                newRow.innerHTML = this.rowTemplateHTML;
                
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    if (input.classList.contains('qty-input')) input.value = '1';
                });
                
                newRow.querySelectorAll('.ts-wrapper, .select2-container').forEach(wrapper => wrapper.remove());
                newRow.querySelectorAll('[data-select2-id]').forEach(el => el.removeAttribute('data-select2-id'));
                newRow.querySelectorAll('select').forEach(select => {
                    select.classList.remove('tomselected', 'ts-hidden-accessible', 'select2-hidden-accessible');
                    select.style.display = '';
                    if (select.hasAttribute('id')) select.removeAttribute('id');
                    select.value = '';
                });

                newRow.querySelectorAll('input, select').forEach(el => {
                    if (el.classList.contains('product-select')) el.name = `items[${index}][product_id]`;
                    if (el.classList.contains('qty-input')) el.name = `items[${index}][qty]`;
                });

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
                document.getElementById('footer-qty').value = grandQty.toFixed(2);
            },

            removeRow(rowIndex, rowElement) {
                if (this.data.length > 1) {
                    this.data.splice(rowIndex, 1);
                    rowElement.remove();
                    // Re-index remaining rows
                    document.querySelectorAll('#itemsTable tbody tr.item-row').forEach((row, idx) => {
                        row.dataset.rowIndex = idx;
                    });
                    this.calculateGrandTotal();
                } else {
                    // Just clear the row if it's the last one
                    const productSelect = rowElement.querySelector('.product-select');
                    if (productSelect.tomselect) productSelect.tomselect.clear();
                }
            }
        };

        function fetchItemStock(productId, location, rowIndex, row) {
            const onhandInput = row.querySelector('.onhand-input');
            if(!onhandInput) return;

            if (!productId || !location) {
                onhandInput.value = '';
                issueController.updateRowData(rowIndex, 'onhand', '');
                return;
            }
            
            onhandInput.value = '...';
            
            fetch(`/api/products/${productId}/stock?location=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    const balance = data.stock || 0; 
                    onhandInput.value = balance;
                    issueController.updateRowData(rowIndex, 'onhand', balance);
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    onhandInput.value = '0';
                    issueController.updateRowData(rowIndex, 'onhand', 0);
                });
        }

        function initRowEvents(row) {
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            if (!qtyInput.value) qtyInput.value = '1';

            function handleProductChange(value) {
                const currentIndex = parseInt(row.dataset.rowIndex);
                issueController.updateRowData(currentIndex, 'product_id', value);
                
                if (value) {
                    const selectedObj = window.serverProductList && Array.isArray(window.serverProductList) ? window.serverProductList.find(opt => opt.id == value) : null;
                    if (selectedObj) {
                        const desc = selectedObj.name || '';
                        const unit = selectedObj.unit || '';

                        issueController.updateRowData(currentIndex, 'description', desc);
                        issueController.updateRowData(currentIndex, 'unit', unit);

                        row.querySelector('.description-input').value = desc;
                        row.querySelector('.unit-input').value = unit;
                        
                        const currentLoc = getDefaultLocation();
                        fetchItemStock(value, currentLoc, currentIndex, row);

                        issueController.checkAndAppendRow(currentIndex);
                    }
                } else {
                    issueController.updateRowData(currentIndex, 'description', '');
                    issueController.updateRowData(currentIndex, 'unit', '');
                    issueController.updateRowData(currentIndex, 'onhand', 0);

                    row.querySelector('.description-input').value = '';
                    row.querySelector('.unit-input').value = '';
                    if(row.querySelector('.onhand-input')) row.querySelector('.onhand-input').value = '';
                }
                issueController.calculateGrandTotal();
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
                        handleProductChange(value);
                    }
                });
            }

            qtyInput.addEventListener('input', function() {
                const currentIndex = parseInt(row.dataset.rowIndex);
                issueController.updateRowData(currentIndex, 'qty', parseFloat(this.value) || 0);
                issueController.calculateGrandTotal();
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    issueController.removeRow(parseInt(row.dataset.rowIndex), row);
                });
            }
        }

        issueController.init();

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

        // --- Form Submission Fix --- //
        const form = document.getElementById('createIssueForm');
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
                            if (el.classList.contains('qty-input')) el.name = `items[${validRowIndex}][qty]`;
                        });
                        validRowIndex++;
                    } else {
                        // Remove names from empty rows
                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.name) el.removeAttribute('name');
                        });
                    }
                });

                if (!hasValidRow) {
                    e.preventDefault();
                    alert('Please add at least one valid item.');
                }
            });
        }

    });
</script>
@endpush

