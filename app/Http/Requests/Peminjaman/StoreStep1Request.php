<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class StoreStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_mitra' => ['nullable', 'string', 'max:50'],
            'virtual_account' => ['nullable', 'string', 'max:50'],
            'nama_mitra' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'sektor' => ['nullable', 'string', 'max:50'],
        ];
    }
}
