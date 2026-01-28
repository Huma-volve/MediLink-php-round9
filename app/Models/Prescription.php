<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'prescription_number',
        'medications',
        'frequency',
        'duration_days',
        'additional_notes',
        'diagnosis',
        'patient_conditions',
        'prescription_date',
        'expiry_date',
    ];

    protected $casts = [
        'medications' => 'array',
        'prescription_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->appointment->patient();
    }

    public function doctor()
    {
        return $this->appointment->doctor();
    }
}
