<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'description', 'quantity', 'rate', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rate' => 'float',
            'amount' => 'float',
        ];
    }
}
