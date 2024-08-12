<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use Carbon\CarbonImmutable;

class GetAvailableTimeslots
{
    public function __construct(private readonly GetAvailableTimeslot $getAvailableTimeslot) {}

    public function handle(CarbonImmutable $date, int $capacity): array
    {
        return collect(range(config('booking.opening_hour'), config('booking.closing_hour')))
            ->flatMap(function (int $hour) use ($date, $capacity): array {
                return $this->getAvailableTimeslot->handle($date, $hour, $capacity);
            })
            ->toArray();
    }
}
