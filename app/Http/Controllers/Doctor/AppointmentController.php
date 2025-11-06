<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctor = $user->doctor; // Assuming user has doctor relationship

        if (!$doctor) {
            abort(403, 'Unauthorized');
        }

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with('doctor.user')
            ->latest()
            ->get();

        return Inertia::render('Doctor/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('Doctor/Appointments/Show', [
            'appointment' => $appointment->load('doctor.user')
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

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
