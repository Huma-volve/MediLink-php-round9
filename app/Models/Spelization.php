<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spelization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
    use RefreshDatabase;

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'spelization_id');
    }
}
