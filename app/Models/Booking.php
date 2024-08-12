<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Carbon $time_from
 * @property-read Carbon $time_to
 */
class Booking extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $fillable = ['user_id', 'table_id', 'time_from', 'time_to', 'capacity'];

    protected function casts(): array
    {
        return [
            'time_from' => 'datetime',
            'time_to' => 'datetime',
        ];
    }

    public function getFormattedDateRange(): string
    {
        return sprintf(
            '%s %s - %s',
            $this->time_from->format('d.m.Y'),
            $this->time_from->format('H:i'),
            $this->time_to->format('H:i')
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
