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
        'date_of_birth',
        'blood_group',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
}
