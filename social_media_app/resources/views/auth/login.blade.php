<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">{{ __('Welcome!') }}</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-text-input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('Email Address') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-text-input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" type="password" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <div class="mt-6 text-center">
            <x-primary-button class="w-full bg-blue-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg flex justify-center items-center">
                {{ __('Login In') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-between mb-6 mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <a href="{{ route('register') }}">
                <p class="text-sm text-gray-600">{{ __("Don't have an account?") }}</p>
            </a>
        </div>
    </form>
</x-guest-layout>