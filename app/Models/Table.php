<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $fillable = ['capacity'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public static function findAvailableTable(int $capacity, CarbonImmutable $timeFrom, CarbonImmutable $timeTo): ?Table
    {
        return self::where('capacity', '>=', $capacity)
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
