<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
    
        // Split birth date into year, month, and day if it's set
        $birthDate = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date) : null;
        $birthYear = $birthDate ? $birthDate->year : null;
        $birthMonth = $birthDate ? $birthDate->month : null;
        $birthDay = $birthDate ? $birthDate->day : null;
    
        return view('profile.profile', [
            'user' => $user,
            'birthYear' => $birthYear,
            'birthMonth' => $birthMonth,
            'birthDay' => $birthDay,
        ]);
    }
    
    /**
     * Update the user's email address.
     */
    public function updateEmail(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->email = $request->email;

        // If email changes, set email_verified_at to null
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.profile')->with('status', 'email-updated');
    }

    /**
     * Update the user's profile information, including birthdate.
     */public function update(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:'.date('Y'),
            'gender' => 'required|string|in:female,male,custom',
            'pronouns' => 'nullable|string|in:she/her,he/his,they/them',
        ]);

        // Handle the birth_date update and ensure it is in the correct format
        $birthDate = Carbon::createFromDate(
            $validatedData['birth_year'],
            $validatedData['birth_month'],
            $validatedData['birth_day']
        )->format('Y-m-d');

        // Update the user's profile
        $user = auth()->user();
        $user->update([
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'],
            'last_name' => $validatedData['last_name'],
            'suffix' => $validatedData['suffix'],
            'birth_date' => $birthDate, // Make sure to store the formatted birth date
            'gender' => $validatedData['gender'],
            'pronouns' => $validatedData['gender'] === 'custom' ? $validatedData['pronouns'] : null,
        ]);

        // Redirect back to the edit profile page
        return redirect()->route('profile.profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}