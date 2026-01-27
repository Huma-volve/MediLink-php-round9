<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spelization()
    {
        return $this->belongsTo(Spelization::class, 'speciality_id');
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
