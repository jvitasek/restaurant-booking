<?php

namespace App\Livewire;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListBookings extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $isSuperadmin = auth()->user()->isSuperadmin();
        $bookings = Booking::query()->orderBy('time_from', 'desc');

        if (! $isSuperadmin) {
            $bookings->where('user_id', auth()->id());
        }

        return $table
            ->searchable($isSuperadmin)
            ->query($bookings)
            ->recordUrl(fn (Booking $booking) => route('booking.show', $booking))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Jméno'))
                    ->hidden(! $isSuperadmin)
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label(__('E-mail'))
                    ->hidden(! $isSuperadmin)
                    ->searchable(),
                TextColumn::make('time_from')
                    ->label(__('Datum'))
                    ->formatStateUsing(fn (Booking $booking): string => $booking->getFormattedDateRange())
                    ->sortable(),
                TextColumn::make('capacity')
                    ->label(__('Počet hostů'))
                    ->numeric(),
            ])
            ->filters([
                Filter::make('only_upcoming')
                    ->label(__('Pouze nadcházející'))
                    ->query(fn (Builder $query): Builder => $query->where('time_from', '>=', Carbon::now())),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label(__('Smazat'))
                    ->modalHeading(__('Smazat rezervaci'))
                    ->hidden(! $isSuperadmin),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.list-bookings');
    }
}
