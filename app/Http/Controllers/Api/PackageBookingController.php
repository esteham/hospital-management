<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackageBooking;
use App\Models\HealthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageBookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'health_check_id' => 'required|exists:health_checks,id',
            'payment_type' => 'required|in:50%,100%',
        ]);

        $healthCheck = HealthCheck::findOrFail($request->health_check_id);
        $price = (float) preg_replace('/[^0-9.]/', '', $healthCheck->price);

        $paymentType = $request->payment_type;
        $amountPaid = $paymentType === '50%' ? $price * 0.5 : $price;
        $totalAmount = $price;

        // Add error handling for price conversion
        if (!is_numeric($price) || $price <= 0) {
            return response()->json([
                'message' => 'Invalid price format in health check',
            ], 500);
        }

        $booking = PackageBooking::create([
            'user_id' => Auth::id(),
            'health_check_id' => $request->health_check_id,
            'payment_type' => $paymentType,
            'amount_paid' => $amountPaid,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking->load('healthCheck'),
        ], 201);
    }
}
