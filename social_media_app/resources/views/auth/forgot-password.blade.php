<x-guest-layout>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-800" />
                <x-text-input id="email" class="block mt-1 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <div class="flex items-center justify-center mt-4">
    <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
        {{ __('Email Password Reset Link') }}
    </x-primary-button>
</div>
        </form>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                {{ __('Need an account? Register') }}
            </a>

            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                {{ __('Already registered? Log in') }}
            </a>
        </div>
</x-guest-layout>
