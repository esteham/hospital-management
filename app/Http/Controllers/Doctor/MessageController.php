<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->doctor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = DoctorMessage::where('doctor_id', $user->doctor->id)
            ->with(['admin:id,name,email', 'schedule:id,day_of_week,start_time,end_time'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'admin_name' => $msg->admin?->name ?? 'Admin',
                    'schedule_info' => $msg->schedule ? "Schedule: {$msg->schedule->day_of_week} {$msg->schedule->start_time}-{$msg->schedule->end_time}" : null,
                    'created_at' => $msg->created_at->format('M d, Y H:i'),
                ];
            });

        return response()->json($messages, 200);
    }
}
