<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    protected $fillable = [
        'user_id',
        'health_check_id',
        'payment_type',
        'amount_paid',
        'total_amount',
        'status',
        'cancellation_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthCheck()
    {
        return $this->belongsTo(HealthCheck::class);
    }
}
