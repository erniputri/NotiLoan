<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'administrasi_awal' => ['nullable', 'numeric', 'min:0'],
            'no_surat_perjanjian' => ['required', 'string', 'max:100'],
            'jaminan' => ['required', 'string', 'max:255'],
        ];
    }
}
