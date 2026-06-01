<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = ['name', 'gstin', 'address', 'phone'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
