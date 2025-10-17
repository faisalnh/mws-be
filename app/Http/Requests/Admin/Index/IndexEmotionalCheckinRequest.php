<?php

namespace App\Http\Requests\Admin\Index;

use Illuminate\Foundation\Http\FormRequest;

class IndexEmotionalCheckinRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     * Di sini kita asumsikan user sudah login dan punya permission.
     */
    public function authorize(): bool
    {
        return true; // ubah ke false jika perlu validasi permission manual di middleware
    }

    /**
     * Rules untuk validasi input query/filter.
     * Request ini digunakan untuk fitur "index" (list/search).
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'], // untuk pencarian umum (mood, role, note)
            'user_id' => ['nullable', 'uuid'],             // filter berdasarkan user tertentu
            'role' => ['nullable', 'string', 'max:50'],    // filter berdasarkan peran
            'internal_weather' => ['nullable', 'string', 'max:50'],
            'mood' => ['nullable', 'string', 'max:50'],    // filter berdasarkan mood
            'energy_level' => ['nullable', 'in:low,medium,high'],
            'balance' => ['nullable', 'in:unbalanced,balanced,highly_balanced'],
            'load' => ['nullable', 'in:light,moderate,heavy'],
            'readiness' => ['nullable', 'in:not_ready,somewhat_ready,ready'],
            'contact_id' => ['nullable', 'uuid'],         
            'checked_in_at_from' => ['nullable', 'date'],  // rentang tanggal dari
            'checked_in_at_to' => ['nullable', 'date'],    // rentang tanggal ke
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'], // jumlah data per halaman
        ];
    }
}