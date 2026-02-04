<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalHistory extends Model
{
 use HasFactory;
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
