<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Favorite;
use App\Models\Prescription;
use App\Models\Insurance;
use App\Models\MedicalHistory;



class Patient extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'insurance_id',
        'date_of_birth',
        'gender',
        'blood_group',
    ];




    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    // علاقة مباشرة مع جدول Favorite
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteDoctors()
    {
        return $this->hasMany(favorite::class)->where('is_favorite', true);
    }

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }


    public function prescriptions()
    {
        return $this->hasManyThrough(
            Prescription::class,
            Appointment::class,
            'patient_id',
            'appointment_id',
            'id',
            'id'
        );
    }
    public function medicalHistories(): HasMany
    {
        return $this->hasMany(MedicalHistory::class);
    }
}
