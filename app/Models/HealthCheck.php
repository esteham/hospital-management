<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    protected $fillable = ['name', 'price', 'features', 'popular'];

    protected $casts = [
        'features' => 'array',
        'popular' => 'boolean',
    ];
}
