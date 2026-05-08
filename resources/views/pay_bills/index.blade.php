@extends('layouts.admin')

@section('title', $type . ' Bills History')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 text-white">{{ $type }} Bills History</h4>
            <div class="page-title-right">
                <a href="{{ $type === 'Supplier' ? route('pay-bills.supplier.create') : route('pay-bills.customer.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i>New {{ $type === 'Supplier' ? 'Payment' : 'Collection' }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-lg" style="background-color: #1a1d21; border: 1px solid #2d3238;">
            <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #212529 !important; border-bottom: 1px solid #2d3238;">
                <h5 class="card-title mb-0 text-white" style="letter-spacing: 0.5px;"><i class="ri-history-line me-2 text-info"></i>{{ strtoupper($type) }} BILL TRANSACTIONS</h5>
                <div class="btn-group">
                    <a href="{{ route('pay-bills.index', ['type' => 'Supplier']) }}" class="btn btn-sm {{ $type === 'Supplier' ? 'btn-info' : 'btn-outline-info' }}" style="font-size: 0.75rem; font-weight: 600;">Supplier Mode</a>
                    <a href="{{ route('pay-bills.index', ['type' => 'Customer']) }}" class="btn btn-sm {{ $type === 'Customer' ? 'btn-info' : 'btn-outline-info' }}" style="font-size: 0.75rem; font-weight: 600;">Customer Mode</a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success border-0 bg-success-subtle text-success mx-4 mt-4 rounded-3 d-flex align-items-center" role="alert">
                        <i class="ri-check-line me-2 fs-18"></i>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" id="historyTable">
                        <thead>
                            <tr class="text-uppercase small fw-bold" style="background-color: #4b38b3;">
                                <th class="py-3 ps-4">Voucher No</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">{{ $type === 'Supplier' ? 'Vendor' : 'Customer' }}</th>
                                <th class="py-3">Method</th>
                                <th class="py-3 text-end">Total Amount</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-soft-info text-info border border-info-subtle px-2 fs-12">{{ $p->voucher_no }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->date)->format('Y-m-d') }}</td>
                                <td>
                                    @if($type === 'Supplier')
                                        <div class="fw-bold text-white">{{ $p->vendor->company_name ?? $p->vendor->name }}</div>
                                        <small class="text-muted extra-small text-uppercase">{{ $p->vendor->code ?? 'V-00' }}</small>
                                    @else
                                        <div class="fw-bold text-white">{{ $p->customer->company_name ?? $p->customer->name }}</div>
                                        <small class="text-muted extra-small text-uppercase">{{ $p->customer->code ?? 'C-00' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small"><i class="ri-bank-card-line me-1 text-info"></i>{{ $p->payment_method }}</span>
                                </td>
                                <td class="text-end fw-bold text-success">{{ number_format($p->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2">Paid</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('pay-bills.show', $p->id) }}" class="btn btn-soft-info btn-sm icon-btn" title="View Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('pay-bills.print', $p->id) }}" class="btn btn-soft-secondary btn-sm icon-btn" title="Print">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                        <form action="{{ route('pay-bills.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm icon-btn" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ri-file-list-3-line fs-48 mb-2 d-block text-secondary"></i>
                                        <p class="fs-15">No {{ strtolower($type) }} transactions found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top border-secondary">
                    {{ $payments->appends(['type' => $type])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-title { color: #fff !important; }
    #historyTable thead th { 
        background-color: #2d3238 !important; 
        color: #adb5bd !important; 
        border-bottom: 1px solid #3e444a;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    #historyTable tbody td { border-color: #2d3238; color: #ced4da; font-size: 0.85rem; }
    #historyTable tr:hover td { background-color: #212529; }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .icon-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #2d3238;
        border: 1px solid #3e444a;
        color: #adb5bd;
    }
    .icon-btn:hover { background-color: #3e444a; color: #fff; }
    .extra-small { font-size: 10px; }
    .btn-outline-info { color: #0dcaf0; border-color: #0dcaf0; }
    .btn-outline-info:hover { background-color: #0dcaf0; color: #fff; }
    .btn-info { background-color: #0dcaf0 !important; border-color: #0dcaf0 !important; color: #fff !important; }
</style>
@endsection
