<?php

namespace App\Policies\CRM;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Booking $booking)
    {
        //echo '<pre>'; print_r(!$user->employee->companies->contains($booking->company_id)); echo '</pre>'; //exit;
        //return true;
        // Condition 3: Admin or Super Admin can edit all bookings
        if ($user->role === 1 || $user->role === 2) {
            return true;
        }

        // Condition 2: Manager can edit bookings of assigned company
        if ($user->role === 3 && $user->employee->companies->contains($booking->company_id)) {
            return true;
        }
        // Condition 2: Manager can edit bookings of assigned company
        if ($user->role === 4 && $user->employee->companies->contains($booking->company_id)) {
            return true;
        }

        // Condition 1: User can edit own bookings
        if ($user->role === 5 && $user->id == $booking->created_by) {
            return true;
        }

        return false;

    }
}
