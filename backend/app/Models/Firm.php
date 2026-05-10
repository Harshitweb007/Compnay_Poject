<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = ['name', 'gstin', 'address', 'phone'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
