<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Schedule $schedule)
    {
    return $user->role==='doctor' && $schedule->doctor->user_id === $user->id;
    }
    public function delete(User $user, Schedule $schedule)
    {
    return $user->role==='doctor' && $schedule->doctor->user_id === $user->id;
    }

}
