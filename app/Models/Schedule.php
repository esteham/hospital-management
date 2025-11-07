<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

     protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_minutes',
        'max_patients_per_day',
        'fee',
    ];

    protected $casts = [
        'day_of_week' => 'array',
    ];
    public function doctor(){ return $this->belongsTo(Doctor::class); }
}
