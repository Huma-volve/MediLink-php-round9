<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'is_visible',
        'is_active',
        'data_sharing',
        'two_factor_auth',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
