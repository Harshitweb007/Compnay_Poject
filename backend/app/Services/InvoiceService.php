<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceService
{
    /**
     * @param  array<string, mixed>  $payload  Validated request data (firm_id, items, tax fields, etc.)
     */
    public function create(array $payload): Invoice
    {
        $itemsPayload = $payload['items'];
        $subtotal = 0.0;
        $lines = [];

        foreach ($itemsPayload as $item) {
            $amount = (float) $item['quantity'] * (float) $item['rate'];
            $subtotal += $amount;
            $lines[] = [
                'description' => $item['description'],
                'quantity' => (int) $item['quantity'],
                'rate' => (float) $item['rate'],
                'amount' => round($amount, 2),
            ];
        }

        $taxMode = $payload['tax_mode'] ?? 'intra_state';
        $direction = $payload['direction'] ?? 'outbound';
        $rates = [
            'cgst_percent' => $payload['cgst_percent'] ?? null,
            'sgst_percent' => $payload['sgst_percent'] ?? null,
            'igst_percent' => $payload['igst_percent'] ?? null,
        ];

        $tax = GstCalculator::compute((float) $subtotal, $taxMode, $rates);

        $invoice = Invoice::query()->create([
            'firm_id' => $payload['firm_id'],
            'direction' => $direction,
            'tax_mode' => $taxMode,
            'cgst_percent' => $rates['cgst_percent'],
            'sgst_percent' => $rates['sgst_percent'],
            'igst_percent' => $rates['igst_percent'],
            'invoice_number' => $payload['invoice_number'],
            'date' => $payload['date'],
            'subtotal' => round($subtotal, 2),
            'cgst' => $tax['cgst'],
            'sgst' => $tax['sgst'],
            'igst' => $tax['igst'],
            'total_amount' => $tax['total_amount'],
        ]);

        foreach ($lines as $line) {
            $line['invoice_id'] = $invoice->id;
            InvoiceItem::query()->create($line);
        }

        return $invoice->fresh(['firm', 'items']);
    }

    /**
     * @param  array<string, mixed>  $payload  Validated request data
     */
    public function update(Invoice $invoice, array $payload): Invoice
    {
        $itemsPayload = $payload['items'];
        $subtotal = 0.0;
        $lines = [];

        foreach ($itemsPayload as $item) {
            $amount = (float) $item['quantity'] * (float) $item['rate'];
            $subtotal += $amount;
            $lines[] = [
                'description' => $item['description'],
                'quantity' => (int) $item['quantity'],
                'rate' => (float) $item['rate'],
                'amount' => round($amount, 2),
            ];
        }

        $taxMode = $payload['tax_mode'] ?? $invoice->tax_mode ?? 'intra_state';
        $direction = $payload['direction'] ?? $invoice->direction ?? 'outbound';
        $rates = [
            'cgst_percent' => $payload['cgst_percent'] ?? $invoice->cgst_percent,
            'sgst_percent' => $payload['sgst_percent'] ?? $invoice->sgst_percent,
            'igst_percent' => $payload['igst_percent'] ?? $invoice->igst_percent,
        ];

        $tax = GstCalculator::compute((float) $subtotal, $taxMode, $rates);

        $invoice->update([
            'firm_id' => $payload['firm_id'],
            'direction' => $direction,
            'tax_mode' => $taxMode,
            'cgst_percent' => $rates['cgst_percent'],
            'sgst_percent' => $rates['sgst_percent'],
            'igst_percent' => $rates['igst_percent'],
            'invoice_number' => $payload['invoice_number'],
            'date' => $payload['date'],
            'subtotal' => round($subtotal, 2),
            'cgst' => $tax['cgst'],
            'sgst' => $tax['sgst'],
            'igst' => $tax['igst'],
            'total_amount' => $tax['total_amount'],
        ]);

        $invoice->items()->delete();
        foreach ($lines as $line) {
            $line['invoice_id'] = $invoice->id;
            InvoiceItem::query()->create($line);
        }

        return $invoice->fresh(['firm', 'items']);
    }
}
