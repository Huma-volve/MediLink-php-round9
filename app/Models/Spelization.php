<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spelization extends Model
{
<<<<<<< HEAD
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
=======
    use HasFactory;
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'speciality_id');
>>>>>>> 6c3f0275a6361a8b234c4b98936287fe9e00b92a
    }
}
    