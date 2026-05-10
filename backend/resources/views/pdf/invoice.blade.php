<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .totals { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $invoice->firm->name }}</h2>
        <p>GSTIN: {{ $invoice->firm->gstin ?? 'N/A' }}</p>
    </div>
    
    <h3>Invoice Number: {{ $invoice->invoice_number }}</h3>
    <p>Date: {{ $invoice->date }}</p>
    
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
        <p>Subtotal: {{ number_format($invoice->subtotal, 2) }}</p>
        <p>CGST: {{ number_format($invoice->cgst, 2) }}</p>
        <p>SGST: {{ number_format($invoice->sgst, 2) }}</p>
        <p>IGST: {{ number_format($invoice->igst, 2) }}</p>
        <h4>Total Amount: {{ number_format($invoice->total_amount, 2) }}</h4>
    </div>
</body>
</html>
