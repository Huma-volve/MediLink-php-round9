<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorWorkingHoursOnline extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_closed',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
