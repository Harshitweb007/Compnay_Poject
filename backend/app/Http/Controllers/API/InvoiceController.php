<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('firm')->orderBy('date', 'desc');

        if ($request->has('firm_id')) {
            $query->where('firm_id', $request->firm_id);
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $items = [];
            foreach ($request->items as $item) {
                $amount = $item['quantity'] * $item['rate'];
                $subtotal += $amount;
                $items[] = [
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'rate' => $item['rate'],
                    'amount' => $amount,
                ];
            }

            // Simple tax calculation (9% CGST, 9% SGST for intra-state)
            $cgst = $subtotal * 0.09;
            $sgst = $subtotal * 0.09;
            $total_amount = $subtotal + $cgst + $sgst;

            $invoice = Invoice::create([
                'firm_id' => $request->firm_id,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'subtotal' => $subtotal,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => 0,
                'total_amount' => $total_amount,
            ]);

            foreach ($items as $item) {
                $item['invoice_id'] = $invoice->id;
                InvoiceItem::create($item);
            }

            DB::commit();

            return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating invoice: ' . $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['firm', 'items'])->findOrFail($id);
        return response()->json($invoice);
    }

    public function sendEmail(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $invoice = Invoice::with(['firm', 'items'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);
        $pdfContent = $pdf->output();

        \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\InvoiceGeneratedMail($invoice, $pdfContent));

        return response()->json(['message' => 'Invoice email sent successfully']);
    }
}
