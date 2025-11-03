<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'preferred_date',
        'preferred_time',
        'speciality',
        'doctor_id',
        'additional_notes',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getDoctorNameAttribute()
    {
        return $this->doctor ? $this->doctor->user->name : null;
    }
}
