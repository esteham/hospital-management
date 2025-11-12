<?php

namespace App\Http\Controllers\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticService;
use App\Models\BookingDiagnostic;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Helper function to calculate percentage change
        $calculatePercentage = function ($current, $lastMonth) {
            return $lastMonth > 0 ? round((($current - $lastMonth) / $lastMonth) * 100, 1) : 0;
        };

        // Diagnostic Services
        $servicesCurrent = DiagnosticService::count();
        $servicesLastMonth = DiagnosticService::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $servicesPercentage = $calculatePercentage($servicesCurrent, $servicesLastMonth);

        // Bookings today
        $bookingsToday = BookingDiagnostic::where('booking_date', $now->toDateString())->count();

        // Pending test results
        $pendingResults = TestResult::whereNull('delivered_at')->count();

        // Recent Bookings
        $recentBookings = BookingDiagnostic::with('diagnosticService', 'user')
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->take(3)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'service_name' => $booking->diagnosticService->name,
                    'patient_name' => $booking->user->name,
                    'booking_date' => $booking->booking_date,
                    'booking_time' => $booking->booking_time,
                    'status' => $booking->status,
                ];
            });

        $summaries = [
            'services' => $servicesCurrent,
            'services_percentage' => $servicesPercentage,
            'bookings' => BookingDiagnostic::count(),
            'bookings_today' => $bookingsToday,
            'pending_results' => $pendingResults,
            'total_results' => TestResult::count(),
        ];

        return inertia('Diagnostic/Dashboard', [
            'summaries' => $summaries,
            'recentBookings' => $recentBookings,
        ]);
    }
}
