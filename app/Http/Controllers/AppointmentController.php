<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'speciality' => 'required|string|max:255',
            'additional_notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json([
            'message' => 'Appointment booked successfully!',
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
