<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spelization extends Model
{
    use HasFactory;

    public function doctors()
    {
<<<<<<< HEAD
        return $this->hasMany(Doctor::class, 'spelization_id');
=======
        return $this->hasMany(Doctor::class, 'speciality_id');
>>>>>>> 58ac349ae53998a7788c68106f05f06f14264766
    }
}
