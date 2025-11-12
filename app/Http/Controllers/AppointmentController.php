<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use App\Mail\AppointmentConfirmationMail;
use App\Mail\DoctorAppointmentNotificationMail;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{   
    public function create(Request $request)
    {
        $doctors = Doctor::with('user', 'schedules')->get();

        $startDate = now()->addDay()->toDateString();
        $endDate = now()->addDays(7)->toDateString();

        $appointments = Appointment::whereBetween('preferred_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->select('doctor_id', 'preferred_date', DB::raw('count(*) as count'))
            ->groupBy('doctor_id', 'preferred_date')
            ->get()
            ->keyBy(function ($item) {
                return $item->doctor_id . '-' . $item->preferred_date;
            });

        $selectedDoctor = null;
        if ($request->has('doctor')) {
            $selectedDoctor = Doctor::with('user', 'schedules')->find($request->doctor);
        }

        return Inertia::render('AppointmentBooking', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
            'doctors' => $doctors,
            'appointments' => $appointments,
            'selectedDoctor' => $selectedDoctor,
        ]);
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:99',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'speciality' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:doctors,id',
            'additional_notes' => 'nullable|string',
        ]);

        // Check if doctor is fully booked for the selected date
        if ($validated['doctor_id']) {
            $date = $validated['preferred_date'];
            $doctorId = $validated['doctor_id'];

            // Get day of week (e.g., 'mon', 'tue', etc.)
            $dayOfWeek = strtolower(date('D', strtotime($date)));

            // Find the schedule for this doctor and day
            $schedule = Schedule::where('doctor_id', $doctorId)
                ->whereJsonContains('day_of_week', $dayOfWeek)
                ->first();

            if ($schedule) {
                $maxPatients = $schedule->max_patients_per_day;

                // Count existing appointments for this doctor on this date (excluding cancelled)
                $existingAppointments = Appointment::where('doctor_id', $doctorId)
                    ->where('preferred_date', $date)
                    ->where('status', '!=', 'cancelled')
                    ->count();

                if ($existingAppointments >= $maxPatients) {
                    return response()->json([
                        'message' => 'This doctor is fully booked for the selected date. Please choose another date or a different doctor.'
                    ], 422);
                }
            }
        }

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
        $groupedAppointments = Appointment::where('status', '!=', 'cancelled')
            ->select('doctor_id', 'preferred_date', DB::raw('count(*) as booked'))
            ->groupBy('doctor_id', 'preferred_date')
            ->with('doctor.user', 'doctor.schedules')
            ->get()
            ->map(function ($item) {
                $dayOfWeek = strtolower(date('D', strtotime($item->preferred_date)));
                $schedule = $item->doctor->schedules->first(function ($s) use ($dayOfWeek) {
                    return in_array($dayOfWeek, $s->day_of_week);
                });
                $max = $schedule ? $schedule->max_patients_per_day : 0;
                return [
                    'doctor_id' => $item->doctor_id,
                    'date' => $item->preferred_date,
                    'doctor_name' => $item->doctor->user->name,
                    'speciality' => $item->doctor->speciality,
                    'booked' => $item->booked,
                    'max' => $max,
                ];
            });

        return Inertia::render('Admin/Appointments/Index', [
            'groupedAppointments' => $groupedAppointments
        ]);
    }

    public function show(Appointment $appointment)
    {
        return Inertia::render('Admin/Appointments/Show', [
            'appointment' => $appointment
        ]);
    }

    public function showByDoctorDate($doctorId, $date)
    {
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->where('preferred_date', $date)
            ->with('doctor.user')
            ->get();

        return Inertia::render('Admin/Appointments/ShowByDoctorDate', [
            'appointments' => $appointments,
            'doctor' => $appointments->first()->doctor ?? null,
            'date' => $date
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->update($validated);

        return redirect()->back()->with('success', 'Appointment updated successfully!');
    }

    public function downloadPdf(Appointment $appointment)
    {
        $pdf = Pdf::loadView('pdfs.appointment_details', ['appointment' => $appointment]);

        return $pdf->download('appointment_' . $appointment->booking_id . '.pdf');
    }
}
