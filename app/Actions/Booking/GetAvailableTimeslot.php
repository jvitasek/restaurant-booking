<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use Carbon\CarbonImmutable;

class GetAvailableTimeslot
{
    public function __construct(private readonly FindAvailableTable $findAvailableTable) {}

    public function handle(CarbonImmutable $dateFrom, int $hour, int $capacity): array
    {
        $timeStart = sprintf('%02d:00', $hour);
        $timeEnd = sprintf('%02d:00', $hour + 1);

        $timeFrom = $dateFrom->copy()->setTimeFromTimeString($timeStart);
        $timeTo = $dateFrom->copy()->setTimeFromTimeString($timeEnd);

        // if the selected date is today, only list times at least 1 hour after now
        if ($dateFrom->isToday() && $timeFrom->lessThanOrEqualTo(now()->addMinutes(config('booking.time_padding_before_booking')))) {
            return []; // skip this time slot
        }

        $availableTable = $this->findAvailableTable->handle($capacity, $timeFrom, $timeTo);

        if ($availableTable) {
            return [
                $timeStart => $timeStart,
                $timeEnd => $timeEnd,
            ];
        }

        return []; // no available table at this time
    }
}
