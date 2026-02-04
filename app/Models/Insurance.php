<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
