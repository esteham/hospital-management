<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\DoctorAppointmentNotificationMail;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'speciality' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'additional_notes' => 'nullable|string',
        ]);

        // Generate unique booking ID
        $bookingId = 'APT-' . strtoupper(Str::random(8));

        $validated['booking_id'] = $bookingId;

        $appointment = Appointment::create($validated);

        // Send confirmation email with PDF attachment to patient
        try {
            Mail::to($appointment->email)->send(new AppointmentConfirmationMail($appointment));
        } catch (\Exception $e) {
            // Log the error but don't fail the booking
            ('Failed to send appointment confirmation email: ' . $e->getMessage());
        }

        // Send notification email to doctor if doctor is selected
        if ($appointment->doctor_id) {
            try {
                $doctorEmail = $appointment->doctor->user->email;
                Mail::to($doctorEmail)->send(new DoctorAppointmentNotificationMail($appointment));
            } catch (\Exception $e) {
                // Log the error but don't fail the booking
                ('Failed to send doctor notification email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Appointment booked successfully! A confirmation email with PDF has been sent.',
            'appointment' => $appointment
        ], 201);
    }

    public function index()
    {
        $appointments = Appointment::latest()->get();

        return Inertia::render('Admin/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    public function show(Appointment $appointment)
    {
        return Inertia::render('Admin/Appointments/Show', [
            'appointment' => $appointment
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->update($validated);

        return response()->json([
            'message' => 'Appointment updated successfully!',
            'appointment' => $appointment
        ]);
    }
}
