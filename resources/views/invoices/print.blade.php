<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_no }}</title>
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
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
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
            body { padding: 0; margin: 0; font-size: 11px; }
            .container { border: none; width: 100%; max-width: 100%; padding: 10px; margin: 0; box-sizing: border-box; }
            .no-print { display: none !important; }
            table { width: 100%; page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            th, td { padding: 6px 8px; }
            .details-grid { gap: 20px; }
            .totals-section { width: 100%; }
            .footer { margin-top: 30px; }
            .signature-box { width: 150px; }
            .header h1 { font-size: 20px; }
            .invoice-title h2 { font-size: 18px; }
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
        <button onclick="window.print()" class="no-print-btn">Print Invoice</button>
        <button onclick="window.history.back()" class="no-print-btn" style="background-color: #6c757d;">Go Back</button>
    </div>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>Sagaki Distribution</h1>
                <p>High-Quality Products Distribution</p>
                <p>Phone: +94 11 234 5678 | Email: info@sagaki.lk</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p style="font-weight: bold; font-size: 16px; margin-top: 10px;">{{ $invoice->invoice_no }}</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="left-col">
                <div class="detail-item">
                    <span class="detail-label">Customer:</span>
                    <span class="detail-value">
                        <strong>{{ $invoice->customer->company_name ?? $invoice->customer->name }}</strong><br>
                        {{ $invoice->customer->address ?? '' }}<br>
                        {{ $invoice->customer->phone ?? '' }}
                    </span>
                </div>
                @if($invoice->rep)
                <div class="detail-item">
                    <span class="detail-label">Rep:</span>
                    <span class="detail-value">{{ $invoice->rep->name ?? '' }}</span>
                </div>
                @endif
                @if($invoice->address)
                <div class="detail-item">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $invoice->address }}</span>
                </div>
                @endif
            </div>
            <div class="right-col">
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $invoice->location ?? '' }}</span>
                </div>
                @if($invoice->payment_term_id)
                <div class="detail-item">
                    <span class="detail-label">Terms:</span>
                    <span class="detail-value">{{ $invoice->payment_term->days ?? 'N/A' }} days</span>
                </div>
                @endif
                @if($invoice->payment_method)
                <div class="detail-item">
                    <span class="detail-label">Payment:</span>
                    <span class="detail-value">{{ $invoice->payment_method }}</span>
                </div>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Rate</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product ? $item->product->code : '' }}</td>
                    <td>{{ $item->product ? $item->product->name : '' }}</td>
                    <td>{{ $item->description ?? '' }}</td>
                    <td class="text-end">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->header_discount_amount > 0)
            <div class="total-row">
                <span>Discount</span>
                <span>-{{ number_format($invoice->header_discount_amount, 2) }}</span>
            </div>
            @endif
            @if($invoice->tax_amount > 0)
            <div class="total-row">
                <span>Tax</span>
                <span>{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($invoice->sscl_amount > 0)
            <div class="total-row">
                <span>SSCL</span>
                <span>{{ number_format($invoice->sscl_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>{{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            @if(isset($outstanding))
            <div class="total-row" style="border-bottom: none; color: #dc3545; font-weight: bold; margin-top: 5px;">
                <span>REMAINING OUTSTANDING</span>
                <span>LKR {{ number_format($outstanding, 2) }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <div class="signature-box">Prepared By</div>
            <div class="signature-box">Authorized By</div>
        </div>

        <div style="margin-top: 40px; font-size: 10px; color: #999; text-align: center;">
            Printed on: {{ date('Y-m-d H:i:s') }} | System generated invoice
        </div>
    </div>

    <script>
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
