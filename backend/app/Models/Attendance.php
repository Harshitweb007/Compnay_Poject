<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'status', 'overtime_hours'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
