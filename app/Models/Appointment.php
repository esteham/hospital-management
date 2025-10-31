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
        'additional_notes',
        'status',
    ];
}
