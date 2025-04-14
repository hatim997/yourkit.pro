<?php

namespace App\Http\Requests\frontend\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'name' => ['required', 'string', 'regex:/^[A-Za-z\s\-\'\.]+$/'],
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'phone' => 'required|numeric|unique:users,phone'
    ];
}

public function messages(): array
{
    return [
        'name.regex' => 'The name must only contain letters, spaces, hyphens, apostrophes, and periods.',
        'password.confirmed' => 'Password and confirm password does not match',
    ];
}
}
