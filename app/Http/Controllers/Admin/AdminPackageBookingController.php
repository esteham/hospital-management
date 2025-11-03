<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CancellationNotificationMail;
use App\Models\PackageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminPackageBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packageBookings = PackageBooking::with(['user', 'healthCheck'])
            ->orderBy('updated_at', 'desc')
            ->get();
        return Inertia::render('Admin/PackageBookings/Index', [
            'packageBookings' => $packageBookings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If needed, but for now, bookings might be created elsewhere
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // If needed
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $packageBooking = PackageBooking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'cancellation_reason' => 'nullable|string|max:1000',
        ]);

        $packageBooking->update($request->only(['status', 'cancellation_reason']));

        // Send email notification if status is cancelled
        if ($packageBooking->status === 'cancelled' && $packageBooking->cancellation_reason) {
            Mail::to($packageBooking->user->email)->send(new CancellationNotificationMail($packageBooking));
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Package booking updated successfully.']);
        }

        return redirect()->route('admin.package-bookings.index')->with('success', 'Package booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $packageBooking = PackageBooking::findOrFail($id);
        $packageBooking->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Package booking deleted successfully.']);
        }

        return redirect()->route('admin.package-bookings.index')->with('success', 'Package booking deleted successfully.');
    }
}
