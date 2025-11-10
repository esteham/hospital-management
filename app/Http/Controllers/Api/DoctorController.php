<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->get();

        // ensure photo field is a full URL when possible
        $doctorsTransformed = $doctors->map(function ($item) {
            if ($item->user->photo) {
                $img = $item->user->photo;
                if (!Str::startsWith($img, ['http://', 'https://'])) {
                    $item->user->photo = Storage::url($img);
                }
            }
            return $item;
        });

        return response()->json($doctorsTransformed);
    }
}