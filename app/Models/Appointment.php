<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
