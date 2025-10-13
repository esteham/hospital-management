<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DoctorWelcomeMail;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    // List
    public function index(Request $request)
    {
        $rows = Doctor::with(['user:id,name,email'])
            ->orderByDesc('id')
            ->get()
            ->map(fn($d) => [
                'id'          => $d->id,
                'user_id'     => $d->user_id,
                'name'        => $d->user?->name,
                'email'       => $d->user?->email,
                'designation' => $d->designation,
                'speciality'  => $d->speciality,
                'phone'       => $d->phone,
                'about'       => $d->about,
            ]);

        return response()->json($rows, 200);
    }

    // Create + send email
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')],
            'password'     => ['required', Password::min(8)],
            'designation'  => ['nullable','string','max:255'],
            'speciality'   => ['nullable','string','max:255'],
            'phone'        => ['nullable','string','max:50'],
            'about'        => ['nullable','string','max:2000'],
        ]);

        $plain = $data['password'];

        $row = DB::transaction(function () use ($data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'role'              => 'doctor',
                'email_verified_at' => now(),
            ]);

            $doctor = Doctor::create([
                'user_id'     => $user->id,
                'designation' => $data['designation'] ?? null,
                'speciality'  => $data['speciality'] ?? null,
                'phone'       => $data['phone'] ?? null,
                'about'       => $data['about'] ?? null,
            ]);

            return [$user, $doctor];
        });

        [$user, $doctor] = $row;

        
        Mail::to($user->email)->send(new DoctorWelcomeMail(
            $user->name,
            $user->email,
            $plain
        ));

        return response()->json([
            'message' => 'Doctor created successfully',
            'doctor'  => [
                'id'          => $doctor->id,
                'user_id'     => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'designation' => $doctor->designation,
                'speciality'  => $doctor->speciality,
                'phone'       => $doctor->phone,
                'about'       => $doctor->about,
            ]
        ], 201);
    }

    
    public function update(Request $request, Doctor $doctor)
    {
        $user = $doctor->user;

        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($user?->id)],
            'password'     => ['nullable', Password::min(8)],
            'designation'  => ['nullable','string','max:255'],
            'speciality'   => ['nullable','string','max:255'],
            'phone'        => ['nullable','string','max:50'],
            'about'        => ['nullable','string','max:2000'],
        ]);

        DB::transaction(function () use ($data, $user, $doctor) {
            $user->name  = $data['name'];
            $user->email = $data['email'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            $doctor->update([
                'designation' => $data['designation'] ?? null,
                'speciality'  => $data['speciality'] ?? null,
                'phone'       => $data['phone'] ?? null,
                'about'       => $data['about'] ?? null,
            ]);
        });

        $doctor->load('user:id,name,email');

        return response()->json([
            'message' => 'Doctor updated',
            'doctor'  => [
                'id'          => $doctor->id,
                'user_id'     => $doctor->user_id,
                'name'        => $doctor->user?->name,
                'email'       => $doctor->user?->email,
                'designation' => $doctor->designation,
                'speciality'  => $doctor->speciality,
                'phone'       => $doctor->phone,
                'about'       => $doctor->about,
            ],
        ], 200);
    }

   
    public function destroy(Doctor $doctor)
    {
        DB::transaction(function () use ($doctor) {
            $user = $doctor->user;
            $doctor->delete();
            if ($user) $user->delete();
        });

        return response()->noContent();
    }
}
