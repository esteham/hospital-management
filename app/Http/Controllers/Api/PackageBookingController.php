<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackageBooking;
use App\Models\HealthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PackageBookingConfirmationMail as PkgMail;

class PackageBookingController extends Controller
{
    public function index()
    {
        $bookings = PackageBooking::where('user_id', Auth::id())
            ->with('healthCheck')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookings);
    }

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

        // Send confirmation email with PDF attachment
        try {
            Mail::to($booking->user->email)->send(new PkgMail($booking));
        } catch (\Exception $e) {
            // Log the error but don't fail the booking
            \Log::error('Failed to send package booking confirmation email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Booking created successfully! A confirmation email with PDF receipt has been sent.',
            'booking' => $booking->load('healthCheck'),
        ], 201);
    }
}
