<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMessage extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id','admin_id','schedule_id','message'];

    public function doctor() { return $this->belongsTo(Doctor::class);}
    public function schedule() { return $this->belongsTo(Schedule::class);}
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
}
