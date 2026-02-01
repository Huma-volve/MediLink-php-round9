<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $fillable = [
        'id',
        'code',
        'name',
        'native_name',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
