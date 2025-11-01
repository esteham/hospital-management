<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\PackageBookingController;
use App\Http\Controllers\Api\EmailCheckController;
use App\Http\Controllers\Api\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\BookingController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/health-checks', [HealthCheckController::class, 'index']);

Route::middleware('auth:sanctum')->post('/bookings', [PackageBookingController::class, 'store']);

Route::post('/email-check', [EmailCheckController::class, 'check']);

Route::get('/news', [NewsController::class, 'index']);
