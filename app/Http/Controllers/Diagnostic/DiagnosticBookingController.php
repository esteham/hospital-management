<?php

namespace App\Http\Controllers\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\BookingDiagnostic;
use App\Models\DiagnosticService;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class DiagnosticBookingController extends Controller
{
    /**
     * Display the diagnostic booking form.
     */
    public function create($serviceId)
    {
        $service = DiagnosticService::findOrFail($serviceId);
        $doctors = Doctor::with('user')->get();

        return Inertia::render('DiagnosticBooking', [
            'canLogin' => true,
            'canRegister' => true,
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
            'service' => $service,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Store a newly created diagnostic booking.
     */
    public function store(Request $request)
    {
        // Manual validation for better error handling
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:99',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'address' => 'required|string|max:500',
            'diagnostic_service_id' => 'required|exists:diagnostic_services,id',
            'doctor_id' => 'nullable|exists:users,id',
            'payment_method' => 'required|in:cash,card,online',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();

            // Handle empty doctor_id
            if (isset($validated['doctor_id']) && $validated['doctor_id'] === '') {
                $validated['doctor_id'] = null;
            }

            // Convert booking_time from 'H:i A' to 'H:i:s' format
            if (isset($validated['booking_time'])) {
                $validated['booking_time'] = date('H:i:s', strtotime($validated['booking_time']));
            }

            // Set user_id if authenticated
            if (Auth::check()) {
                $validated['user_id'] = Auth::id();
            }

            // Set default status
            $validated['status'] = 'pending';
            $validated['payment_status'] = 'pending';

            $booking = BookingDiagnostic::create($validated);

            // Load relationships for response
            $booking->load(['diagnosticService', 'doctor.doctor']);

            return response()->json([
                'message' => 'Diagnostic test booked successfully!',
                'booking' => $booking,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Booking Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download booking PDF.
     */
    public function downloadPdf($bookingId)
    {
        try {
            $booking = BookingDiagnostic::with(['diagnosticService', 'doctor', 'user'])
                        ->findOrFail($bookingId);

            // For anonymous bookings (no user_id), allow download without authentication
            // For authenticated bookings, check ownership or admin/diagnostic role
            if ($booking->user_id) {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->role !== 'admin' && $user->role !== 'diagnostic' && $booking->user_id !== $user->id) {
                        return response()->json([
                            'message' => 'Unauthorized access'
                        ], 403);
                    }
                } else {
                    return response()->json([
                        'message' => 'Authentication required'
                    ], 403);
                }
            }
            // If booking has no user_id (anonymous), allow download

            $pdf = Pdf::loadView('pdfs.diagnostic-booking', compact('booking'));

            return $pdf->download('diagnostic_booking_' . $booking->id . '.pdf');

        } catch (\Exception $e) {
            \Log::error('PDF Download Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}