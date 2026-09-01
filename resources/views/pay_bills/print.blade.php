<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher - {{ $payment->voucher_no }}</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 13px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #3577f1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #3577f1;
            font-size: 24px;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        .voucher-title {
            text-align: right;
        }
        .voucher-title h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 8px;
            display: flex;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .detail-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            font-size: 11px;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .text-end {
            text-align: right;
        }
        .totals-section {
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.grand-total {
            border-bottom: 2px double #333;
            font-weight: bold;
            font-size: 16px;
            color: #3577f1;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 10px;
            font-weight: bold;
        }
        @media print {
            @page { margin: 10mm; size: A4 portrait; }
            body {
                padding: 0;
                margin: 0;
            }
            .container {
                border: none;
                width: 100%;
                max-width: 210mm;
                margin: 0 auto;
                box-sizing: border-box;
            }
            .no-print {
                display: none;
            }
        }
        .no-print-btn {
            background-color: #3577f1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button onclick="window.print()" class="no-print-btn">Print Voucher</button>
        <a href="{{ url()->previous() }}" class="no-print-btn" style="background-color: #6c757d; text-decoration: none; display: inline-block;">Go Back</a>
    </div>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>Sagaki Distribution</h1>
                <p>High-Quality Products Distribution</p>
                <p>Phone: +94 11 234 5678 | Email: info@sagaki.lk</p>
            </div>
            <div class="voucher-title">
                <h2>{{ strtoupper($payment->type) }} PAYMENT VOUCHER</h2>
                <p style="font-weight: bold; font-size: 16px; margin-top: 10px;">{{ $payment->voucher_no }}</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="left-col">
                <div class="detail-item">
                    <span class="detail-label">{{ $payment->type === 'Supplier' ? 'Vendor' : 'Customer' }}:</span>
                    <span class="detail-value">
                        @if($payment->type === 'Supplier')
                            <strong>{{ $payment->vendor->company_name ?? $payment->vendor->name }}</strong><br>
                            {{ $payment->vendor->address }}<br>
                            {{ $payment->vendor->phone }}
                        @else
                            <strong>{{ $payment->customer->company_name ?? $payment->customer->name }}</strong><br>
                            {{ $payment->customer->address }}<br>
                            {{ $payment->customer->phone }}
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Memo:</span>
                    <span class="detail-value">{{ $payment->memo ?? '—' }}</span>
                </div>
            </div>
            <div class="right-col">
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Method:</span>
                    <span class="detail-value">{{ $payment->payment_method }}</span>
                </div>
                @if($payment->cheque_no)
                <div class="detail-item">
                    <span class="detail-label">Cheque No:</span>
                    <span class="detail-value">{{ $payment->cheque_no }}</span>
                </div>
                @endif
                @if($payment->pd_cheque_date)
                <div class="detail-item">
                    <span class="detail-label">PD Date:</span>
                    <span class="detail-value">{{ $payment->pd_cheque_date }}</span>
                </div>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Bill No / Ref No</th>
                    <th>Bill Date</th>
                    <th class="text-end">Bill Amount</th>
                    <th class="text-end">Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->bill_no }} @if($item->grn && $item->grn->reference_no) / {{ $item->grn->reference_no }} @endif</td>
                    <td>{{ $item->bill_date }}</td>
                    <td class="text-end">{{ number_format($item->bill_amount, 2) }}</td>
                    <td class="text-end">{{ number_format($item->amount_to_pay, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <div class="total-row">
                <span>Total Items</span>
                <span>{{ $payment->items->count() }}</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL PAID (LKR)</span>
                <span>{{ number_format($payment->total_amount, 2) }}</span>
            </div>
            @if(isset($outstanding))
            <div class="total-row" style="border-bottom: none; color: #dc3545; font-weight: bold; margin-top: 5px;">
                <span>REMAINING OUTSTANDING</span>
                <span>LKR {{ number_format($outstanding, 2) }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <div class="signature-box">
                Prepared By
            </div>
            <div class="signature-box">
                Authorized By
            </div>
            <div class="signature-box">
                Vendor Signature
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 10px; color: #999; text-align: center;">
            Printed on: {{ date('Y-m-d H:i:s') }} | System generated voucher
        </div>
    </div>

    <script>
        window.onload = function() {
            // Optional: Automatically open print dialog
            // window.print();
        }
    </script>
</body>
</html>

<script> window.onload = function() { window.print(); } </script>
