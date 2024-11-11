<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Update Profile Information Form -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Profile Information Form -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="max-w-xl">
                    @include('profile.partials.update-user-email-form')
                </div>
            </div>

            <!-- Update Password Form -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Form -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
