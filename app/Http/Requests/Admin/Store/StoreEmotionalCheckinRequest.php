<?php

namespace App\Http\Requests\Admin\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmotionalCheckinRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id', // user yang melakukan check-in
            'role' => 'required|string|max:50',
            'internal_weather' => 'nullable|string|max:255',
            'mood' => 'required|string|max:50',
            'energy_level' => 'nullable|in:low,medium,high',
            'balance' => 'nullable|in:unbalanced,balanced,highly_balanced',
            'load' => 'nullable|in:light,moderate,heavy',
            'readiness' => 'nullable|in:not_ready,somewhat_ready,ready',
            'presence_level' => 'required|integer|min:1|max:10',
            'capasity_level' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string|max:500',
            'contact_id' => 'nullable|integer|exists:users,id',
            'checked_in_at' => 'required|date',
        ];
    }
}
