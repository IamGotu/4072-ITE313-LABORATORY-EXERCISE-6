<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-center text-2xl font-bold mt-4 mb-6">
        {{ __('Create an Account') }}
    </h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="flex space-x-4">
            <!-- First Name Field -->
            <div class="w-1/2">
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus placeholder="{{ __('First Name') }}" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Name Field (Optional) -->
            <div class="w-1/2">
                <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" placeholder="{{ __('Middle Name (Optional)') }}" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>
        </div>

        <div class="flex space-x-4 mt-4">
            <!-- Last Name Field -->
            <div class="w-1/2">
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required placeholder="{{ __('Last Name') }}" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Suffix Field (Optional) -->
            <div class="w-1/2">
                <x-text-input id="suffix" class="block mt-1 w-full" type="text" name="suffix" :value="old('suffix')" placeholder="{{ __('Suffix (Optional)') }}" />
                <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
            </div>
        </div>

        <!-- Birthdate Fields -->
        <div class="mt-4">
            <x-input-label for="birthdate" :value="__('Birthdate')" />
            <div class="flex space-x-4 mt-2">
                <div class="w-1/3">
                    <select id="birth_month" name="birth_month" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="">{{ __('Month') }}</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ old('birth_month') == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 10)) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('birth_month')" class="mt-2" />
                </div>

                <div class="w-1/3">
                    <select id="birth_day" name="birth_day" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="">{{ __('Day') }}</option>
                        @foreach (range(1, 31) as $day)
                            <option value="{{ $day }}" {{ old('birth_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('birth_day')" class="mt-2" />
                </div>

                <div class="w-1/3">
                    <select id="birth_year" name="birth_year" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="">{{ __('Year') }}</option>
                        @foreach (range(now()->year, 1900, -1) as $year)
                            <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('birth_year')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Gender Dropdown -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="custom" {{ old('gender') == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
            </select>

            <!-- Pronouns Dropdown (shown when "Custom" is selected) -->
            <div class="mt-2" id="custom-pronouns" style="display: none;">
                <x-input-label for="pronouns" :value="__('Pronouns')" />
                <select id="pronouns" name="pronouns" class="block mt-1 w-full p-2 border border-gray-300 rounded-md">
                    <option value="she/her" {{ old('pronouns') == 'she/her' ? 'selected' : '' }}>{{ __('She/Her') }}</option>
                    <option value="he/his" {{ old('pronouns') == 'he/his' ? 'selected' : '' }}>{{ __('He/His') }}</option>
                    <option value="they/them" {{ old('pronouns') == 'they/them' ? 'selected' : '' }}>{{ __('They/Them') }}</option>
                </select>
            </div>

            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="{{ __('Email') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex space-x-4 mt-4">

            <!-- Password -->
            <div class="w-1/2">
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="w-1/2">
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mt-4 text-sm">
            <p>
                {{ __('People who use our service may have uploaded your contact information.') }}
            </p>
            <p class="mt-2">
                {{ __('By clicking Sign Up, you agree to our') }}
                <a href="#" class="text-blue-500">{{ __('Terms') }}</a>,
                <a href="#" class="text-blue-500">{{ __('Privacy Policy') }}</a>,
                {{ __('and') }}
                <a href="#" class="text-blue-500">{{ __('Cookies Policy') }}</a>.
            </p>
        </div>

        <!-- Register Button -->
        <div class="mt-4">
            <x-primary-button class="block w-full h-12 text-center flex items-center justify-center text-sm font-semibold">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="flex items-center justify-center mt-4 space-x-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>

    <script>
        // Show custom pronouns field only when gender is custom
        document.getElementById('gender').addEventListener('change', function () {
            const pronounsField = document.getElementById('pronouns');
            const customPronouns = document.getElementById('custom-pronouns');
            if (this.value === 'custom') {
                document.getElementById('custom-pronouns').style.display = 'block';
                customPronouns.style.display = 'block';
            } else {
                document.getElementById('custom-pronouns').style.display = 'none';
                customPronouns.style.display = 'none';
                pronounsField.value = ''; // Set pronouns to null if not custom
            }
        });

        // Validate password confirmation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            if (document.getElementById('password').value !== this.value) {
                this.setCustomValidity('Passwords do not match.');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</x-guest-layout>