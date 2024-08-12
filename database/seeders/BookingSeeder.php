<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Table;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Table::all() as $table) {
            Booking::factory()->create([
                'table_id' => $table->id,
                'time_from' => now()->setTime(20, 0),
                'time_to' => now()->setTime(20, 59),
            ]);
        }
    }
}
