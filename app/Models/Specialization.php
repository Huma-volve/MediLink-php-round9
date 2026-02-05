<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialization extends Model
{
    use HasFactory;
    use RefreshDatabase;
    protected $table = 'spelizations';

    protected $fillable = [
        'name',
        'description',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'spelization_id');
    }

    // public function doctors()
    // {
    //     return $this->hasMany(Doctor::class);
    // }
}
