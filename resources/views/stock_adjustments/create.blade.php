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
                                <option value="">-- Select Account --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Site</label>
                            <select name="site" class="form-select form-select-sm">
                                <option value="">-- Select Site --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}" {{ (old('site') == $loc->name || $loc->name == 'Main Stock') ? 'selected' : '' }}>{{ $loc->name }}</option>
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

                    <!-- Items Table -->
                    <div class="table-responsive mb-2 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center small">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 15%;">Item</th>
                                    <th style="width: 20%;">Description</th>
                                    <th style="width: 8%;">Onhand</th>
                                    <th style="width: 8%;">AVG</th>
                                    <th style="width: 10%;">Value</th>
                                    <th style="width: 8%;">New Qty</th>
                                    <th style="width: 8%;">Qty Different</th>
                                    <th style="width: 10%;">New Value</th>
                                    <th style="width: 10%;">Value Different</th>
                                    <th style="width: 3%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-sm border-0">
                                            <option value="">-- Select Item --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm border-0" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-end" readonly></td>
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center"></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-center" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-end" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm border-0 text-end" readonly></td>
                                    <td><button type="button" class="btn btn-link text-danger p-0"><i class="ri-delete-bin-line fs-18"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
