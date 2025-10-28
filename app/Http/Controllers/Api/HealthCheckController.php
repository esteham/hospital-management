<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    public function index()
    {
        return response()->json(HealthCheck::all());
    }
}
