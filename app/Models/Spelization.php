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
        return $this->hasMany(Doctor::class, 'speciality_id');
=======

        return $this->hasMany(Doctor::class, 'spelization_id');
>>>>>>> 620136d37187ae1b06387174217497df0dca6d12
    }
}
