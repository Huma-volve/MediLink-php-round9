<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spelization extends Model
{
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
