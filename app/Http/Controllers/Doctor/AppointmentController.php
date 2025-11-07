<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrescriptionMail;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'appointment' => $appointment->load('doctor.user', 'prescriptions')
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

    public function storePrescription(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'prescription_text' => 'required|string',
        ]);

        $prescription = Prescription::create([
            'appointment_id' => $appointment->id,
            'prescription_text' => $validated['prescription_text'],
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.prescription', compact('appointment', 'prescription'));

        // Send email with PDF attachment
        try {
            Mail::to($appointment->email)->send(new PrescriptionMail($appointment, $prescription, $pdf));
        } catch (\Exception $e) {
            // Log the error but don't fail the prescription creation
            ('Failed to send prescription email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Prescription added successfully! A PDF has been sent to the patient.',
            'prescription' => $prescription
        ], 201);
    }

    public function downloadPrescriptionPdf(Appointment $appointment, Prescription $prescription)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id || $prescription->appointment_id !== $appointment->id) {
            abort(403, 'Unauthorized');
        }

        // Load necessary relationships
        $appointment->load('doctor.user');

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.prescription', compact('appointment', 'prescription'));

        return $pdf->download('prescription_' . $appointment->booking_id . '.pdf');
    }
}
