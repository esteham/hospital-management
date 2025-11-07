<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DoctorMentionMail;
use App\Models\Schedule;
use App\Models\DoctorMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
    	$q = Schedule::query()->with(['doctor.user:id,name,email'])->orderByRaw("FIELD(day_of_week, 'sat','sun','mon','tue','wed','thu','fri')")->orderBy('start_time');

    	if($s = $request->get('search'))
    	{
    		$q->whereHas('doctor.user', function($qq) use ($s){

    			$qq->where('name','like',"%$s%")->orWhere('email','like',"%$s%");
    		});
    	}

    	$rows = $q->get()->map(fn($s) => [

    		'id' => $s->id,
    		'doctor_id' => $s->doctor_id,
    		'doctor' => $s->doctor?->user?->name,
    		'email' => $s->doctor?->user?->email,
    		'day' => strtoupper(implode(', ', $s->day_of_week)),
    		'time' => "{$s->start_time} - { $s->end_time}",
    		'slot' => $s->slot_minutes,
    		'max' => $s->max_patients_per_day,
    		'fee' => $s->fee,
    	]);

    	return response()->json($rows, 200);
    }

    public function mention(Request $request)
    {
    	$data = $request->validate([

    		'doctor_id' => ['required','exists:doctors,id'],
    		'schedule_id' => ['nullable','exists:schedules,id'],
    		'message' => ['required','string','max:5000'],
    	]);

    	$msg = DoctorMessage::create([

    		'doctor_id' => $data['doctor_id'],
    		'admin_id' => Auth::id(),
    		'schedule_id' => $data['schedule_id'] ?? null,
    		'message' => $data['message'],
    	]);

    	// Send email notification to the doctor
    	Mail::to($msg->doctor->user->email)->send(new DoctorMentionMail($msg));

    	return response()->json([

    		'message' => 'Message sent to doctor',
    		'id' => $msg->id,
    	], 201);
    }
}
