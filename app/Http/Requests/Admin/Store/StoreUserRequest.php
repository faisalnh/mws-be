<?php

namespace App\Http\Requests\Admin\Store;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)->mixedCase()->letters()->numbers()],
            'class_id' => [
                'nullable',
                Rule::requiredIf(fn() => $this->role === 'student'),
                'exists:classes,id',
            ]
        ];
    }
}
