<?php

namespace App\Http\Requests\frontend\auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'user_email' => 'required|string|exists:users,email',
            'user_password' => 'required|string'
        ];
    }
    
    public function messages(): array
    {
        return [
            'user_email.exists' => 'No account found with this email address.',
        ];
    }
}
