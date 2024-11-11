<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'gender' => ['required', 'string', 'in:male,female,custom'],
            'pronouns' => ['nullable', 'string', 'in:he/his,she/her,they/them'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_month' => ['required', 'integer', 'between:1,12'],
            'birth_day' => ['required', 'integer', 'between:1,31'],
            'birth_year' => ['required', 'integer', 'between:1900,' . now()->year],
        ]);
    
        // Format the birthdate from the provided month, day, and year
        $birth_date = \Carbon\Carbon::createFromDate(
            $request->birth_year,
            $request->birth_month,
            $request->birth_day
        )->toDateString();
    
        // Create the user
        $userData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'birth_date' => $birth_date,
        ];
    
        // Add pronouns only if gender is 'custom'
        if ($request->gender === 'custom') {
            $userData['pronouns'] = $request->pronouns;
        }
    
        // Create the user in the database
        $user = User::create($userData);
    
        // Fire the registered event
        event(new Registered($user));
    
        // Redirect the user to the dashboard
        return redirect(route('dashboard', absolute: false));
    }    
}
