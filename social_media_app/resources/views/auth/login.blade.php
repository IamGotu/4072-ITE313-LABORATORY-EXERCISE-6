<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-10">
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">{{ __('Welcome Back!') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email Address')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Password -->
            <div class="mb-6">
                <x-input-label for="password" :value="__('Your Password')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-400" name="remember">
                    <span class="ms-2">{{ __('Remember Me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div>
                <x-primary-button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg">
                    {{ __('Sign In') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">{{ __("Don't have an account?") }}</p>
            <a href="{{ route('register') }}">
                <button class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-lg mt-3">
                    {{ __('Create an Account') }}
                </button>
            </a>
        </div>
    </div>
</x-guest-layout>
