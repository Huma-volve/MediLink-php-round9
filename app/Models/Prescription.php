<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Appointment;
use App\Models\PrescriptionItem;

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
        return $this->hasOneThrough(
            Doctor::class,
            Appointment::class,
            'id',
            'id',
            'appointment_id',
            'doctor_id'
        );
    }
    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
