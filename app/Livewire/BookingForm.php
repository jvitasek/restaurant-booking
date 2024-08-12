<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Booking\FindAvailableTable;
use App\Actions\Booking\GetAvailableTimeslots;
use App\Models\Booking;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * @property-read Form $form
 */
class BookingForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    private GetAvailableTimeslots $getAvailableTimeslots;

    private FindAvailableTable $findAvailableTable;

    public function boot(GetAvailableTimeslots $getAvailableTimeslots, FindAvailableTable $findAvailableTable): void
    {
        $this->getAvailableTimeslots = $getAvailableTimeslots;
        $this->findAvailableTable = $findAvailableTable;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date_from')
                    ->label(__('Datum rezervace'))
                    ->native(false)
                    ->minDate(now()->startOfDay())
                    ->closeOnDateSelection()
                    ->live()
                    ->required(),
                TextInput::make('capacity')
                    ->label(__('Počet míst'))
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(config('booking.maximum_capacity'))
                    ->live()
                    ->required(),
                Select::make('time_from')
                    ->label(__('Čas rezervace'))
                    ->options(function (Get $get): array {
                        $dateFrom = new CarbonImmutable($get('date_from'));
                        $capacity = (int) $get('capacity');

                        return $this->getAvailableTimeslots->handle($dateFrom, $capacity);
                    })
                    ->hidden(fn (Get $get): bool => ! $get('date_from') || ! $get('capacity'))
                    ->required(),
            ])
            ->statePath('data')
            ->model(Booking::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $capacity = (int) $data['capacity'];

        $dateTimeString = sprintf('%s %s', $data['date_from'], $data['time_from']);
        $timeFrom = CarbonImmutable::createFromFormat('Y-m-d H:i', $dateTimeString);
        $timeTo = $timeFrom->addMinutes(config('booking.default_timeslot'));

        $availableTable = $this->findAvailableTable->handle($capacity, $timeFrom, $timeTo);

        if (! $availableTable) {
            Notification::make()
                ->title(__('V tomto termínu pro vás nemáme místo.'))
                ->danger()
                ->send();

            return;
        }

        $record = Booking::create([
            'user_id' => auth()->id(),
            'table_id' => $availableTable->id,
            'time_from' => $timeFrom,
            'time_to' => $timeTo,
            'capacity' => $capacity,
        ]);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title(__('Rezervace úspěšně vytvořena'))
            ->success()
            ->send();

        $this->redirect(route('booking.show', $record));
    }

    public function render(): View
    {
        return view('livewire.booking-form');
    }
}
