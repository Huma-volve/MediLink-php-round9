<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Builder;

class Appointment extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_UPCOMING = 'upcoming';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'status',
        'reason_for_visit',
        'consultation_type',
        'patient_name',
        'patient_email',
        'patient_phone'
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
        return $this->hasOne(Prescription::class);
    }


    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }


    public function scopeForDoctor(Builder $query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
