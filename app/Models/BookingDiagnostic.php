<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDiagnostic extends Model
{
    protected $table = 'booking_diagnostic';

    protected $fillable = [
        'user_id',
        'diagnostic_service_id',
        'doctor_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_status',
        'payment_method',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diagnosticService()
    {
        return $this->belongsTo(DiagnosticService::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
}
