<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'booking_id',
        'file_path',
        'delivered_at',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingDiagnostic::class);
    }
}
