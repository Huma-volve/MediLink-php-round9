<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'doctor_id',
        'amount',
        'status',
        'admin_notes',
        'processed_at',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
