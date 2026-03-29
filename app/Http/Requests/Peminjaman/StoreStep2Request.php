<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class StoreStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pokok_pinjaman_awal' => ['required', 'numeric', 'min:1'],
            'tgl_peminjaman' => ['required', 'date'],
            'lama_angsuran_bulan' => ['required', 'integer', 'min:1'],
            'bunga_persen' => ['required', 'numeric', 'min:0'],
        ];
    }
}
