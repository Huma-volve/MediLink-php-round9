<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
<<<<<<< HEAD
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
=======
    use HasFactory;
>>>>>>> 6c3f0275a6361a8b234c4b98936287fe9e00b92a
}
