<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{

    protected $table = 'medical_histroys';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'prescription_id',
        'chronic_conditions',
        'allergies',
        'previous_surgeries',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
