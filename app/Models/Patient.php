<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'insurance_id',
        'date_of_birth',
        'blood_group',
    ];

    // علاقة المريض بالدكاترة المفضلة (pivot table: favorites)
    public function favorites()
    {
        return $this->belongsToMany(Doctor::class, 'favorites')
            ->withPivot('is_favorite')
            ->withTimestamps();
    }

    // دكاترة مفضلة فقط
    public function favoriteDoctors()
    {
        return $this->favorites()->wherePivot('is_favorite', true);
    }
}
