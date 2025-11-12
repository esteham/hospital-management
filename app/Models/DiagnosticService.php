<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticService extends Model
{
    protected $fillable = [
        'name',
        'image',
        'price',
        'category',
        'sample_type',
        'description',
        'duration',
        'home_collection_available',
        'report_time',
    ];

    public function bookings()
    {
        return $this->hasMany(BookingDiagnostic::class);
    }
}
