<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-primary-button type="submit" class="mt-5">
            {{ __('Odeslat') }}
        </x-primary-button>
    </form>

    <x-filament-actions::modals />
</div>
