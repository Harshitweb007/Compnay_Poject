<?php

namespace App\Http\Requests;

use App\Models\Firm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firm_id' => ['required', 'string', Rule::exists(Firm::class, 'id')],
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'date' => 'required|date',
            'direction' => 'sometimes|in:outbound,inbound',
            'tax_mode' => 'sometimes|in:intra_state,inter_state',
            'cgst_percent' => 'nullable|numeric|min:0|max:100',
            'sgst_percent' => 'nullable|numeric|min:0|max:100',
            'igst_percent' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
        ];
    }
}
