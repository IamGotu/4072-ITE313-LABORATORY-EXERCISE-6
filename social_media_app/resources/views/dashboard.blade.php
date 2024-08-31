<x-app-layout>
    <x-slot name="header">
        <div class="bg-blue-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-gray-900 text-center">
                    <h3 class="text-xl font-semibold mb-4">{{ __("You're logged in!") }}</h3>
                    <p class="text-gray-600">{{ __('Welcome to your dashboard. Here you can manage your account and view important information.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
