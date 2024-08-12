<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use App\Models\Table;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class FindAvailableTable
{
    public function handle(int $capacity, CarbonImmutable $timeFrom, CarbonImmutable $timeTo): ?Table
    {
        return Table::where('capacity', '>=', $capacity)
            ->whereDoesntHave('bookings', function (Builder $query) use ($timeFrom, $timeTo): void {
                $query->where(function (Builder $q) use ($timeFrom, $timeTo): void {
                    $q->whereBetween('time_from', [$timeFrom, $timeTo])
                        ->orWhereBetween('time_to', [$timeFrom, $timeTo])
                        ->orWhere(function (Builder $q) use ($timeFrom, $timeTo): void {
                            $q->where('time_from', '<', $timeFrom)
                                ->where('time_to', '>', $timeTo);
                        });
                });
            })
            ->orderBy('capacity')
            ->first();
    }
}
