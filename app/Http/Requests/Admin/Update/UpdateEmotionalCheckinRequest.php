<?php

namespace App\Http\Requests\Admin\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmotionalCheckinRequest extends FormRequest
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
            'internal_weather' => 'nullable|string|max:50',
            'mood' => 'nullable|string|max:50',
            'presence_level' => 'nullable|integer|min:1|max:10',
            'capasity_level' => 'nullable|integer|min:1|max:10',
            'energy_level' => 'nullable|in:low,medium,high',
            'balance' => 'nullable|in:unbalanced,balanced,highly_balanced',
            'load' => 'nullable|in:light,moderate,heavy',
            'readiness' => 'nullable|in:not_ready,somewhat_ready,ready',
            'note' => 'nullable|string|max:500',
            'contact_id' => 'nullable|integer|exists:users,id',
            'ai_analysis' => 'nullable|string|max:255',
            'checked_in_at' => 'nullable|date',
        ];
    }
}
