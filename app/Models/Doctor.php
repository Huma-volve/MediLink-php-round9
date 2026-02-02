<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Review;
use App\Models\Clinic;

use App\Models\Specialization;

use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Prescription;
use App\Models\Payment;
use App\Models\Favorite;
use App\Models\DoctorWorking;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'license_number',
        'experience_years',
        'certification',
        'bio',
        'education',
        'consultation_fee_online',
        'consultation_fee_inperson',
        'specialization_id',
        'location',
        'is_verified',
        'current_balance'
    ];

    protected $appends = ['is_favorite'];
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->hasMany(Clinic::class);
    }


    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function workingHours()
    {
        return $this->hasMany(DoctorWorking::class);
    }

    public function scopeFilter($query, $request)
    {
        return $query
            ->when(

                $request->specialization_id,

                fn($q) =>
                $q->where('specialization_id', $request->specialization_id)
            )
            ->when(
                $request->location,
                fn($q) =>
                $q->where('location', $request->location)
            )
            ->when(
                $request->experience_years,
                fn($q) =>
                $q->where('experience_years', $request->experience_years)
            )
            ->when(
                $request->is_verified !== null,
                fn($q) =>
                $q->where('is_verified', $request->is_verified)
            );
    }

    public function getIsFavoriteAttribute()
    {
        if (!isset($this->relations['favorites'])) {
            return false;
        }

        return $this->favorites->contains(fn($fav) => $fav->is_favorite);
    }
 
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);

    }
}
