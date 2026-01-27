<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'is_verified'
    ];

    // المرضى اللي ضافوا الدكتور للمفضلة
    public function favoritedBy()
    {
        return $this->belongsToMany(Patient::class, 'favorites')
            ->withPivot('is_favorite')
            ->withTimestamps();
    }
}
