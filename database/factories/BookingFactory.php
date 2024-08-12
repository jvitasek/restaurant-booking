<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'table_id' => Table::inRandomOrder()->first()->id,
            'time_from' => $this->faker->dateTimeBetween('now', '+1 week'),
            'time_to' => $this->faker->dateTimeBetween('+1 week', '+2 week'),
            'capacity' => $this->faker->numberBetween(1, config('booking.maximum_capacity')),
        ];
    }
}
