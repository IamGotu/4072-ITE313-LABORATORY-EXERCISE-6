<x-guest-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-10">
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">{{ __('Create Your Account') }}</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Full Name')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="name" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" 
                              type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email Address')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" 
                              type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
                              type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-lg font-medium text-gray-800" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <div class="flex-grow"></div> <!-- Spacer to push the button to the right -->

                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
