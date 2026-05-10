<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['firm_id', 'invoice_number', 'date', 'subtotal', 'cgst', 'sgst', 'igst', 'total_amount', 'pdf_path'];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
