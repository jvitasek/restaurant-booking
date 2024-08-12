<?php

declare(strict_types=1);

use App\Actions\Booking\FindAvailableTable;
use App\Actions\Booking\GetAvailableTimeslot;
use App\Models\Table;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it returns available timeslot', function () {
    // arrange
    Table::factory()->create(['capacity' => 4]);
    $dateFrom = CarbonImmutable::now()->addDay();
    $hour = 12;
    $findAvailableTable = app()->make(FindAvailableTable::class);
    $action = app()->make(GetAvailableTimeslot::class, [$findAvailableTable]);

    // act
    $timeslot = $action->handle($dateFrom, $hour, 2);

    // assert
    expect($timeslot)->toHaveKey('12:00');
});

test('it skips timeslot when date is today and within an hour', function () {
    // arrange
    Table::factory()->create(['capacity' => 4]);
    $dateFrom = CarbonImmutable::now();
    $hour = CarbonImmutable::now()->hour;
    $findAvailableTable = app()->make(FindAvailableTable::class);
    $action = app()->make(GetAvailableTimeslot::class, [$findAvailableTable]);

    // act
    $timeslot = $action->handle($dateFrom, $hour, 2);

    // assert
    expect($timeslot)->toBeEmpty();
});
