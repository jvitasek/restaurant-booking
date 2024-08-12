<?php

declare(strict_types=1);

use App\Actions\Booking\FindAvailableTable;
use App\Models\Table;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it finds an available table', function () {
    // Arrange
    $table = Table::factory()->create(['capacity' => 4]);
    $timeFrom = CarbonImmutable::now()->addHours(2);
    $timeTo = $timeFrom->addHour();
    $action = app()->make(FindAvailableTable::class);

    // act
    $availableTable = $action->handle(2, $timeFrom, $timeTo);

    // assert
    expect($availableTable->id)->toBe($table->id);
});

test('it returns null when no table is available', function () {
    // arrange
    $table = Table::factory()->create(['capacity' => 4]);
    $timeFrom = CarbonImmutable::now()->addHours(2);
    $timeTo = $timeFrom->addHour();
    $table->bookings()->create([
        'user_id' => User::factory()->create()->id,
        'time_from' => $timeFrom,
        'time_to' => $timeTo,
        'capacity' => 2,
    ]);
    $action = app()->make(FindAvailableTable::class);

    // act
    $availableTable = $action->handle(2, $timeFrom, $timeTo);

    // assert
    expect($availableTable)->toBeNull();
});
