<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStep3Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'administrasi_awal' => ['required', 'numeric', 'min:0'],
            'kualitas_kredit' => ['required', 'in:Lancar,Kurang Lancar,Ragu-ragu,Macet'],
            'jaminan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
