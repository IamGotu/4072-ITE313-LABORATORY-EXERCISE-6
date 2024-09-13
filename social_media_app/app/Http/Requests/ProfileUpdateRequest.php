<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [];

        // Apply rules based on the action
        if ($this->routeIs('profile.updateEmail')) {
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)];
        }

        if ($this->routeIs('profile.update')) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
