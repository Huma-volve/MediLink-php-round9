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

    // علاقة مباشرة مع جدول Favorite
    public function favorites()
    {
        return $this->hasMany(favorite::class);
    }

    // دكاترة المفضلة فقط
    public function favoriteDoctors()
    {
        return $this->hasMany(favorite::class)->where('is_favorite', true);
    }
}
