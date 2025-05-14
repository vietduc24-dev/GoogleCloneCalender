<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the calendar.
     */
    public function view(User $user, Calendar $calendar)
    {
        return $user->id === $calendar->user_id || 
               $calendar->sharedUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the calendar.
     */
    public function update(User $user, Calendar $calendar)
    {
        return $user->id === $calendar->user_id || 
               $calendar->sharedUsers()
                   ->where('user_id', $user->id)
                   ->where('permission', 'write')
                   ->exists();
    }

    /**
     * Determine whether the user can delete the calendar.
     */
    public function delete(User $user, Calendar $calendar)
    {
        return $user->id === $calendar->user_id;
    }
} 