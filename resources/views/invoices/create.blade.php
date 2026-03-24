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
<<<<<<< HEAD
                            <label class="form-label small fw-bold mb-1">Site <span class="text-danger">*</span></label>
                            <select name="site" class="form-select form-select-sm" required>
                                <option value="Main Stock">Main Stock</option>
=======
                            <label class="form-label small fw-bold mb-1">Location <span class="text-danger">*</span></label>
                            <select name="site" class="form-select form-select-sm">
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->name }}" {{ old('site') == $location->name ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
>>>>>>> ad8733eccfa2c587183a585c273c03b6324de5b2
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
                    <div class="table-responsive mb-2 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center small">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 12%;">Item Code</th>
                                    <th style="width: 18%;">Description</th>
                                    <th style="width: 7%;">OnHand</th>
                                    <th style="width: 7%;">Qty</th>
                                    <th style="width: 9%;">Rate(LKR)</th>
                                    <th style="width: 9%;">Amount</th>
                                    <th style="width: 7%;">Disc %</th>
                                    <th style="width: 9%;">Discount</th>
                                    <th style="width: 9%;">Total</th>
                                    <th style="width: 10%;">Site</th>
                                    <th style="width: 4%;">Unit</th>
                                    <th style="width: 4%;"></th>
                                </tr>
                            </thead>
                            <tbody id="invoice-item-table-body">
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm border-0 item-select">
                                            <option value="">Select Item</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm border-0" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center qty-input"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end rate-input"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end amount-input" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center disc-percent-input"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end discount-input"></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-end fw-bold total-input" readonly></td>
                                    <td>
                                        <select class="form-select form-select-sm border-0 site-select">
                                            <option value="Main Stock">Main Stock</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center unit-field" readonly></td>
                                    <td>
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 border-0 delete-row-btn" tabindex="-1">
                                            <i class="ri-delete-bin-line fs-18"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <template id="row-template">
                        <tr>
                            <td>
                                <select class="form-select form-select-sm border-0 item-select">
                                    <option value="">Select Item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm border-0" readonly></td>
                            <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-center qty-input"></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-end rate-input"></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-end amount-input" readonly></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-center disc-percent-input"></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-end discount-input"></td>
                            <td><input type="number" class="form-control form-control-sm border-0 text-end fw-bold total-input" readonly></td>
                            <td>
                                <select class="form-select form-select-sm border-0 site-select">
                                    <option value="Main Stock">Main Stock</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm border-0 text-center unit-field" readonly></td>
                            <td>
                                <button type="button" class="btn btn-link btn-sm text-danger p-0 border-0 delete-row-btn" tabindex="-1">
                                    <i class="ri-delete-bin-line fs-18"></i>
                                </button>
                            </td>
                        </tr>
                    </template>

                    <!-- Table Footer Row -->
                    <div class="row g-2 mb-3 justify-content-end align-items-center">
                        <div class="col-md-1 text-end small fw-bold">Qty</div>
                        <div class="col-md-1">
                            <input type="text" id="footer-qty" class="form-control form-control-sm text-center bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Amount</div>
                        <div class="col-md-2">
                            <input type="text" id="footer-amount" class="form-control form-control-sm text-end bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Discount</div>
                        <div class="col-md-1">
                            <input type="text" id="footer-discount" class="form-control form-control-sm text-end bg-light" readonly>
                        </div>
                        <div class="col-md-1 text-end small fw-bold">Total Amount</div>
                        <div class="col-md-2">
                            <input type="text" id="footer-total" class="form-control form-control-sm text-end bg-light fw-bold" readonly>
                        </div>
                    </div>

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
                                            <input type="text" id="final-discount-percent" class="form-control form-control-sm text-center">
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold mb-0">Discount</label>
                                            <input type="text" id="final-discount-amount" class="form-control form-control-sm text-end">
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

        // For TomSelect support
        setTimeout(() => {
            if (customerSelect.tomselect) {
                customerSelect.tomselect.on('change', function (value) {
                    fetchCustomerDetails(value);
                });
            }
        }, 500);

        const itemTableBody = document.getElementById('invoice-item-table-body');

        function initItemSelect(selectEl) {
            if (selectEl.tomselect) return;
            
            const ts = new TomSelect(selectEl, {
                create: false,
                allowEmptyOption: true,
                plugins: ['clear_button'],
                maxOptions: 1000,
                searchField: ['text'],
                selectOnTab: true,
                closeAfterSelect: true,
                onItemAdd: function(value) {
                    const row = this.input.closest('tr');
                    // Add new row if this is the last row
                    if (!row.nextElementSibling) {
                        addNewRow();
                    }
                },
                onChange: function(productId) {
                    const row = selectEl.closest('tr');
                    
                    if (productId) {
                        fetch(`/api/products/${productId}`)
                            .then(response => response.json())
                            .then(data => {
                                row.querySelector('td:nth-child(2) input').value = data.description || data.name || '';
                                row.querySelector('.unit-field').value = data.unit || '';
                                
                                const rateInput = row.querySelector('.rate-input');
                                rateInput.value = data.max_sale_price || data.cost || '';
                                rateInput.dispatchEvent(new Event('input', { bubbles: true }));
                            })
                            .catch(error => console.error('Error fetching product details:', error));
                    } else {
                        // Clear row
                        row.querySelectorAll('input').forEach(input => input.value = '');
                        calculateGrandTotals();
                    }
                }
            });
        }

        // Handle delete row
        itemTableBody.addEventListener('click', function(event) {
            if (event.target.closest('.delete-row-btn')) {
                const row = event.target.closest('tr');
                const allRows = itemTableBody.querySelectorAll('tr');
                
                // Don't delete if it's the only row
                if (allRows.length > 1) {
                    // Destroy TomSelect instance if it exists to prevent memory leaks
                    const select = row.querySelector('.item-select');
                    if (select && select.tomselect) {
                        select.tomselect.destroy();
                    }
                    row.remove();
                    calculateGrandTotals();
                } else {
                    // Just clear the first row if it's the only one
                    const select = row.querySelector('.item-select');
                    if (select && select.tomselect) {
                        select.tomselect.clear();
                    }
                    row.querySelectorAll('input').forEach(input => input.value = '');
                    calculateGrandTotals();
                }
            }
        });

        function initSiteSelect(selectEl) {
            if (selectEl.tomselect) return;
            new TomSelect(selectEl, {
                create: false,
                allowEmptyOption: false,
            });
        }

        function addNewRow() {
            const template = document.getElementById('row-template');
            const newRow = template.content.cloneNode(true).querySelector('tr');
            itemTableBody.appendChild(newRow);
            
            const newSelect = newRow.querySelector('.item-select');
            const newSiteSelect = newRow.querySelector('.site-select');
            
            initItemSelect(newSelect);
            initSiteSelect(newSiteSelect);
        }

        // Initialize existing rows
        document.querySelectorAll('.item-select').forEach(initItemSelect);
        document.querySelectorAll('.site-select').forEach(initSiteSelect);

        function calculateGrandTotals() {
            let totalQty = 0;
            let totalAmount = 0;
            let totalDiscount = 0;
            let netTotal = 0;

            itemTableBody.querySelectorAll('tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const amount = parseFloat(row.querySelector('.amount-input').value) || 0;
                const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
                const total = parseFloat(row.querySelector('.total-input').value) || 0;

                totalQty += qty;
                totalAmount += amount;
                totalDiscount += discount;
                netTotal += total;
            });

            document.getElementById('footer-qty').value = totalQty.toFixed(2);
            document.getElementById('footer-amount').value = totalAmount.toFixed(2);
            document.getElementById('footer-discount').value = totalDiscount.toFixed(2);
            document.getElementById('footer-total').value = netTotal.toFixed(2);
            
            document.getElementById('final-sub-total').value = netTotal.toFixed(2);
            
            calculateFinalTotal();
        }

        function calculateFinalTotal() {
            const subTotal = parseFloat(document.getElementById('final-sub-total').value) || 0;
            const finalDiscPercent = parseFloat(document.getElementById('final-discount-percent').value) || 0;
            let finalDiscAmount = parseFloat(document.getElementById('final-discount-amount').value) || 0;
            
            if (document.activeElement === document.getElementById('final-discount-percent')) {
                finalDiscAmount = subTotal * (finalDiscPercent / 100);
                document.getElementById('final-discount-amount').value = finalDiscAmount.toFixed(2);
            } else if (document.activeElement === document.getElementById('final-discount-amount')) {
                if (subTotal > 0) {
                    const percent = (finalDiscAmount / subTotal) * 100;
                    document.getElementById('final-discount-percent').value = percent.toFixed(2);
                }
            } else {
                finalDiscAmount = subTotal * (finalDiscPercent / 100);
                document.getElementById('final-discount-amount').value = finalDiscAmount.toFixed(2);
            }

            const grandTotal = subTotal - finalDiscAmount;
            document.getElementById('final-grand-total').value = grandTotal.toFixed(2);
            document.getElementById('lkr-total').value = grandTotal.toFixed(2);
        }

        document.getElementById('final-discount-percent').addEventListener('input', calculateFinalTotal);
        document.getElementById('final-discount-amount').addEventListener('input', calculateFinalTotal);

        itemTableBody.addEventListener('input', function(event) {
            if (event.target.matches('input[type="number"]')) {
                const row = event.target.closest('tr');
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
                const discPercent = parseFloat(row.querySelector('.disc-percent-input').value) || 0;

                const amount = qty * rate;
                const discount = amount * (discPercent / 100);
                const total = amount - discount;

                row.querySelector('.amount-input').value = amount.toFixed(2);
                row.querySelector('.discount-input').value = discount.toFixed(2);
                row.querySelector('.total-input').value = total.toFixed(2);
                
                calculateGrandTotals();
            }
        });
    });
</script>
@endpush
