<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// This class represents a form request for updating a user's profile. 
// It extends the base FormRequest class provided by Laravel and includes validation rules for the name and email fields. 
// The rules method defines the validation rules for the name and email fields, ensuring that the name is required, a string, and has a maximum length of 255 characters, while the email is required, a string, in lowercase, a valid email format, has a maximum length of 255 characters, and is unique in the users table except for the current user's email.
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
