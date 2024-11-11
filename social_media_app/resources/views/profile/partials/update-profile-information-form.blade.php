<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-800">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- First Name and Middle Name -->
        <div class="flex space-x-4">
            <div class="w-1/2">
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div class="w-1/2">
                <x-input-label for="middle_name" :value="__('Middle Name (Optional)')" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name', $user->middle_name)" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>
        </div>

        <!-- Last Name and Suffix -->
        <div class="flex space-x-4 mt-4">
            <div class="w-1/2">
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <div class="w-1/2">
                <x-input-label for="suffix" :value="__('Suffix (Optional)')" />
                <x-text-input id="suffix" name="suffix" type="text" class="mt-1 block w-full" :value="old('suffix', $user->suffix)" />
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
                            <option value="{{ $month }}" {{ old('birth_month', $birthMonth) == $month ? 'selected' : '' }}>
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
                            <option value="{{ $day }}" {{ old('birth_day', $birthDay) == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('birth_day')" class="mt-2" />
                </div>

                <div class="w-1/3">
                    <select id="birth_year" name="birth_year" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="">{{ __('Year') }}</option>
                        @foreach (range(now()->year, 1900, -1) as $year)
                            <option value="{{ $year }}" {{ old('birth_year', $birthYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('birth_year')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Gender Dropdown and Pronouns -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="custom" {{ old('gender', $user->gender) == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
            </select>

            <div class="mt-2" id="custom-pronouns" style="{{ old('gender', $user->gender) == 'custom' ? 'display:block;' : 'display:none;' }}">
                <x-input-label for="pronouns" :value="__('Pronouns')" />
                <select id="pronouns" name="pronouns" class="block mt-1 w-full p-2 border border-gray-300 rounded-md">
                    <option value="she/her" {{ old('pronouns', $user->pronouns) == 'she/her' ? 'selected' : '' }}>{{ __('She/Her') }}</option>
                    <option value="he/his" {{ old('pronouns', $user->pronouns) == 'he/his' ? 'selected' : '' }}>{{ __('He/His') }}</option>
                    <option value="they/them" {{ old('pronouns', $user->pronouns) == 'they/them' ? 'selected' : '' }}>{{ __('They/Them') }}</option>
                </select>
            </div>

            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 mt-6">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('gender').addEventListener('change', function () {
            const pronounsField = document.getElementById('pronouns');
            const customPronouns = document.getElementById('custom-pronouns');
            if (this.value === 'custom') {
                customPronouns.style.display = 'block';
            } else {
                customPronouns.style.display = 'none';
                pronounsField.value = '';
            }
        });
    </script>
</section>