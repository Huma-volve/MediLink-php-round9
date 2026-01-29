<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Review;
use App\Models\Clinic;
use App\Models\Spelization;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Prescription;
use App\Models\Payment;


class Doctor extends Model
{
  
    protected $fillable = [
        'user_id',
        'license_number',
        'experience_years',
        'certification',
        'bio',
        'education',
        'consultation_fee_online',
        'consultation_fee_inperson',
        'spelization_id',
        'location',
        'is_verified',
    ];


    public function favorites()
    {
        return $this->hasMany(favorite::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->hasMany(Clinic::class);
    }

    public function spelization()
    {
        return $this->belongsTo(Spelization::class);   // specialization
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function workingHours()
    {
        return $this->hasMany(DoctorWorking::class);
    }
}
