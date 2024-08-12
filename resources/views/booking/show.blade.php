<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rezervace na') }} {{ $booking->time_from->format('j.n.Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Podrobnosti vaší rezervace') }}</h3>
                    <div class="mt-4">
                        <p class="text-lg">
                            <strong>{{ __('Jméno') }}:</strong> {{ $booking->user->name }}
                        </p>
                        <p class="text-lg">
                            <strong>{{ __('E-mail') }}:</strong> <a class="text-blue-700" href="mailto:{{ $booking->user->email }}">{{ $booking->user->email }}</a>
                        </p>
                        <p class="text-lg">
                            <strong>{{ __('Počet hostů') }}:</strong> {{ $booking->capacity }}
                        </p>
                        <p class="text-lg">
                            <strong>{{ __('Začátek rezervace') }}:</strong> {{ $booking->time_from->format('j.n.Y H:i') }}
                        </p>
                        <p class="text-lg">
                            <strong>{{ __('Konec rezervace') }}:</strong> {{ $booking->time_to->format('j.n.Y H:i') }}
                        </p>
                    </div>

                    <div class="mt-5 text-center">
                        @if (auth()->user()->can('destroy', $booking))
                            <form action="{{ route('booking.destroy', $booking) }}" method="POST" onsubmit="return confirm('{{ __('Opravdu chcete stornovat tuto rezervaci?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-6 py-3 bg-red-600 text-white text-xl font-bold rounded-lg hover:bg-red-700">
                                    {{ __('Stornovat rezervaci') }}
                                </button>
                            </form>
                        @else
                            <p class="text-lg text-gray-700">{{ __('Rezervaci již nelze stornovat.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
