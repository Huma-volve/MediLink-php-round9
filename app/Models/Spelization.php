<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spelization extends Model
{
    use HasFactory;
    // public function doctors()
    // {
    //     return $this->hasMany(Doctor::class);
    // }


    public function doctors()
    {
<<<<<<< HEAD
        return $this->hasMany(Doctor::class, 'spelization_id');
=======
        return $this->hasMany(Doctor::class, 'speciality_id');
>>>>>>> c6edc56db38aca6de5988c6e63a408e6dc090dee
    }
}
