<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Prescription;


class PrescriptionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'prescription_id',
        'medicine_name',
        'dosage',
        'frequency',
        'duration_days',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
