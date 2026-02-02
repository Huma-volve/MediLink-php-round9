<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Doctor extends Model
{

    use Searchable;

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


    public function searchableWith()
    {
        return ['user', 'spelization', 'workingHours' , 'workingHoursOnline'];
    }

    public function toSearchableArray()
    {
        return [
            'doctor_id' => $this->id,
            'name' => optional($this->user)->name,
            'email' => optional($this->user)->email,
            'specialization' => optional($this->spelization)->name,
            'location' => $this->location,
            'working_days' => $this->workingHours
                ->where('is_closed', false)
                ->pluck('day_of_week')
                ->unique()
                ->values()
                ->toArray(),

            'working_days_online' => $this->workingHoursOnline
                ->where('is_closed', false)
                ->pluck('day_of_week')
                ->unique()
                ->values()
                ->toArray(),
        ];
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

    public function workingHours()
    {
        return $this->hasMany(DoctorWorking::class);
    }

    public function workingHoursOnline()
    {
        return $this->hasMany(DoctorWorkingHoursOnline::class);
    }
}
