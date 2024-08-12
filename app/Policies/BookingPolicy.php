<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $booking->user_id === $user->id;
    }

    public function destroy(User $user, Booking $booking): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($booking->user_id !== $user->id) {
            return false;
        }

        return $booking->time_from->isAfter(now()->addMinutes(config('booking.minimum_time_before_cancel')));
    }
}
