<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Purchase Order - {{ $order->po_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
        }
        .info-box {
            width: 45%;
        }
        .info-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .totals-section {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }
        .totals-table th, .totals-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .totals-table th {
            background-color: #f8f9fa;
            text-align: left;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Purchase Order</h1>
        <p style="margin-top: 5px; color: #7f8c8d;">{{ $order->po_no }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h3>Vendor Details</h3>
            <p><strong>Name:</strong> {{ $order->vendor->company_name ?? $order->vendor->name ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $order->vendor->address ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $order->vendor->phone ?? 'N/A' }}</p>
        </div>
        <div class="info-box">
            <h3>Order Details</h3>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}</p>
            <p><strong>Expected Date:</strong> {{ $order->expected_date ? \Carbon\Carbon::parse($order->expected_date)->format('Y-m-d') : 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Reference:</strong> {{ $order->reference_no ?? 'N/A' }}</p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="45%">Product Description</th>
                <th width="15%" class="text-center">Quantity</th>
                <th width="15%" class="text-right">Unit Price</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product->name ?? $item->description ?? 'Unknown Product' }}
                        @if($item->product && $item->product->code)
                            <br><small style="color: #6c757d;">Code: {{ $item->product->code }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 2) }} {{ $item->unit ?? '' }}</td>
                    <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">No items found in this purchase order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <th>Subtotal</th>
                <td class="text-right">{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <th>Tax</th>
                <td class="text-right">{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->header_discount_amount > 0)
            <tr>
                <th>Discount</th>
                <td class="text-right">-{{ number_format($order->header_discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <th style="font-size: 16px;">Grand Total</th>
                <td class="text-right" style="font-size: 16px; font-weight: bold;">{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #7f8c8d;">
        <p>This is a computer generated document.</p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #0d6efd; color: white; border: none; border-radius: 4px;">Print Document</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
