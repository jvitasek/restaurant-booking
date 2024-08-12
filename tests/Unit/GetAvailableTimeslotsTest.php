<?php

declare(strict_types=1);

use App\Actions\Booking\FindAvailableTable;
use App\Actions\Booking\GetAvailableTimeslot;
use App\Actions\Booking\GetAvailableTimeslots;
use App\Models\Table;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it returns multiple available timeslots', function () {
    // arrange
    Table::factory()->create(['capacity' => 4]);
    $dateFrom = CarbonImmutable::now()->addDay();
    $capacity = 2;
    $getAvailableTimeslot = app()->make(GetAvailableTimeslot::class, [app()->make(FindAvailableTable::class)]);
    $action = app()->make(GetAvailableTimeslots::class, [$getAvailableTimeslot]);

    // act
    $timeslots = $action->handle($dateFrom, $capacity);

    // assert
    expect($timeslots)->not()->toBeEmpty();
});

test('it skips unavailable timeslots', function () {
    // arrange
    $table = Table::factory()->create(['capacity' => 4]);
    $dateFrom = CarbonImmutable::now()->addDay();
    $capacity = 2;
    $getAvailableTimeslot = app()->make(GetAvailableTimeslot::class, [app()->make(FindAvailableTable::class)]);
    $action = app()->make(GetAvailableTimeslots::class, [$getAvailableTimeslot]);
    $table->bookings()->create([
        'user_id' => User::factory()->create()->id,
        'time_from' => $dateFrom->setTime(8, 0),
        'time_to' => $dateFrom->setTime(9, 0),
        'capacity' => $capacity,
    ]);

    // act
    $timeslots = $action->handle($dateFrom, $capacity);

    // assert
    expect($timeslots)->not()->toHaveKey('08:00');
});
