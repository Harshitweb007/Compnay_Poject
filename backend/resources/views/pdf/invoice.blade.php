<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
        .brand { text-align: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #2563eb; }
        .brand h1 { margin: 0; font-size: 18px; color: #1e40af; }
        .meta { margin-bottom: 12px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; background: #e0e7ff; color: #3730a3; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f8fafc; }
        .totals { margin-top: 16px; text-align: right; }
        .totals p { margin: 4px 0; }
        .total-big { font-size: 14px; font-weight: bold; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="brand">
        <h1>{{ config('app.name') }}</h1>
        <p style="margin:4px 0;">GST Invoice</p>
    </div>

    <div class="meta">
        <p><strong>{{ $invoice->firm->name }}</strong></p>
        <p>GSTIN: {{ $invoice->firm->gstin ?? 'N/A' }}</p>
        @if($invoice->firm->address)
            <p>{{ $invoice->firm->address }}</p>
        @endif
    </div>

    <p>
        <strong>Invoice:</strong> {{ $invoice->invoice_number }}
        <span class="badge">{{ strtoupper($invoice->direction ?? 'outbound') }}</span>
    </p>
    <p><strong>Date:</strong> {{ $invoice->date }}</p>
    <p><strong>Tax:</strong> {{ str_replace('_', ' ', $invoice->tax_mode ?? 'intra_state') }}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->rate, 2) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: ₹{{ number_format($invoice->subtotal, 2) }}</p>
        <p>CGST: ₹{{ number_format($invoice->cgst, 2) }}</p>
        <p>SGST: ₹{{ number_format($invoice->sgst, 2) }}</p>
        <p>IGST: ₹{{ number_format($invoice->igst, 2) }}</p>
        <p class="total-big">Total: ₹{{ number_format($invoice->total_amount, 2) }}</p>
    </div>
</body>
</html>
