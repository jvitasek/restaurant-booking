<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</label>
        </div>

        <div class="flex justify-between text-sm mt-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                {{ __('Register') }}
            </a>
        </div>

        <!-- Login Button -->
        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-400">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
