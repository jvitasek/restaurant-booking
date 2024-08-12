<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('booking.index');
    }

    public function show(Booking $booking): View
    {
        if (! auth()->user()->can('view', $booking)) {
            abort(403);
        }

        return view('booking.show', [
            'booking' => $booking,
        ]);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        if (! auth()->user()->can('destroy', $booking)) {
            abort(403);
        }

        $booking->delete();

        return redirect()->route('booking.index');
    }
}
