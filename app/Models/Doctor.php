<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Doctor extends Model
{
<<<<<<< HEAD



=======
>>>>>>> 58ac349ae53998a7788c68106f05f06f14264766
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

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

    public function medicalHistory()
    {
        return $this->hasMany(MedicalHistroy::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

<<<<<<< HEAD


=======
>>>>>>> 58ac349ae53998a7788c68106f05f06f14264766
    public function workingHours()
    {
        return $this->hasMany(DoctorWorking::class);
    }
}
