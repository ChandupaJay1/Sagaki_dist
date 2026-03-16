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
                                    <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Site To</label>
                            <select name="site_to" class="form-select form-select-sm">
                                <option value="">-- Select --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}">{{ $loc->name }}</option>
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

                    <!-- Items Table -->
                    <div class="table-responsive mb-2 border rounded">
                        <table class="table table-sm table-bordered mb-0 align-middle text-center small">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 20%;">Item</th>
                                    <th style="width: 35%;">Description</th>
                                    <th style="width: 15%;">OnHand</th>
                                    <th style="width: 15%;">Qty</th>
                                    <th style="width: 15%;">Unit</th>
                                    <th style="width: 5%;">Action</th>
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
                                    <td><input type="number" class="form-control form-control-sm border-0 text-center" value="1"></td>
                                    <td>
                                        <select name="items[0][unit_id]" class="form-select form-select-sm border-0">
                                            @foreach($units as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><select class="form-select form-select-sm border-0"><option></option></select></td>
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
