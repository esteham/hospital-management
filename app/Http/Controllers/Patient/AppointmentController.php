<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\PackageBooking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $appointments = Appointment::where('email', $user->email)->latest()->get();

        return Inertia::render('Patient/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Ensure user can only view their own appointments
        if ($appointment->email !== auth()->user()->email) {
            abort(403);
        }

        return Inertia::render('Patient/Appointments/Show', [
            'appointment' => $appointment
        ]);
    }

    /**
     * Download PDF for the appointment.
     */
    public function downloadPdf(Appointment $appointment)
    {
        // Ensure user can only download their own appointment PDFs
        if ($appointment->email !== auth()->user()->email) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdfs.appointment_details', ['appointment' => $appointment]);

        return $pdf->download('appointment_' . $appointment->booking_id . '.pdf');
    }

    /**
     * Download PDF for the package booking receipt.
     */
    public function downloadPackagePdf(PackageBooking $packageBooking)
    {
        // Ensure user can only download their own package booking PDFs
        if ($packageBooking->user_id !== auth()->id()) {
            abort(403);
        }

        $packageBooking->load(['user', 'healthCheck']);
        $pdf = Pdf::loadView('pdfs.package_booking_receipt', ['packageBooking' => $packageBooking]);

        return $pdf->download('package_booking_receipt_' . $packageBooking->id . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
