<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCartRequest extends FormRequest
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
            'file' => 'required_unless:is_logo_later,1|array',
            'file.*' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'is_logo_later' => 'nullable|boolean',
            'comment' => 'nullable',
            // 'comment' => 'required_unless:is_logo_later,1',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required_unless' => 'The file field is required unless you select "is logo later".',
            'file.array' => 'The file field must be an array.',
            'file.*.mimes' => 'Each file must be a file of type: jpg, jpeg, png, pdf.',
            'file.*.max' => 'Each file may not be greater than 2MB.',
            'is_logo_later.boolean' => 'The is_logo_later field must be true or false.',
            // 'comment.required_unless'=> 'The comment field is required required unless you select "is logo later".'
        ];
    }
}
