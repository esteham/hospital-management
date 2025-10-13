<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $req)
{
    $user = \Illuminate\Support\Facades\Auth::user();

    
    if (!$user) 
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

   
    $doctor = $user->doctor;
    if (!$doctor)
     {
       
        return response()->json(['message' => 'No doctor profile found for this user. Please create doctor profile first.'], 422);    }

   
    $items = $doctor->schedules()
        ->orderByRaw("FIELD(day_of_week,'sat','sun','mon','tue','wed','thu','fri')")
        ->get();

    return response()->json($items, 200);
}


    public function store(Request $req)
    {
        $doctor = Auth::user()->doctor;

        $data = $req->validate([
            'day_of_week'=>['required', Rule::in(['sat','sun','mon','tue','wed','thu','fri'])],
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
            'slot_minutes'=>'required|integer|min:5|max:240',
            'max_patients_per_day'=>'required|integer|min:1|max:500',
            'fee'=>'required|integer|min:0|max:1000000',
        ]);
       
        $duration = Carbon::parse($data['start_time'])->diffInMinutes(Carbon::parse($data['end_time']));
        if ($duration < $data['slot_minutes']) {
            return response()->json(['message'=>'Slot minutes must be <= total duration'], 422);
        }

        $schedule = Schedule::create(array_merge(
            ['doctor_id' => $doctor->id],
            $data
        ));


        return $schedule;
    }

    public function update(Request $req, $id)
{
    $doctor = \Illuminate\Support\Facades\Auth::user()->doctor;
    if (!$doctor) 
    {
        return response()->json(['message' => 'No doctor profile found'], 422);
    }
  
    $schedule = \App\Models\Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

    $data = $req->validate([
        'day_of_week' => ['required', \Illuminate\Validation\Rule::in(['sat','sun','mon','tue','wed','thu','fri'])],
        'start_time'  => 'required|date_format:H:i',
        'end_time'    => 'required|date_format:H:i|after:start_time',
        'slot_minutes'=> 'required|integer|min:5|max:240',
        'max_patients_per_day' => 'required|integer|min:1|max:500',
        'fee'         => 'required|integer|min:0|max:1000000',
    ]);

    $duration = \Carbon\Carbon::parse($data['start_time'])
        ->diffInMinutes(\Carbon\Carbon::parse($data['end_time']));
    if ($duration < $data['slot_minutes']) {
        return response()->json(['message' => 'Slot minutes must be <= total duration'], 422);
    }

    $schedule->update($data);
    return $schedule->refresh();
}

    public function destroy($id)
	{
    $doctor = \Illuminate\Support\Facades\Auth::user()->doctor;
    if (!$doctor) {
        return response()->json(['message' => 'No doctor profile found'], 422);
    }
    
    $schedule = \App\Models\Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

    $schedule->delete();
    return response()->noContent();
	}
}
