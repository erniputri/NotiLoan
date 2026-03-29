<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_mitra' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'sektor' => ['required', 'string', 'max:50'],
            'tgl_peminjaman' => ['required', 'date'],
            'lama_angsuran_bulan' => ['required', 'integer', 'min:1'],
            'pokok_pinjaman_awal' => ['required', 'numeric', 'min:1'],
            'kualitas_kredit' => ['required', 'in:Lancar,Kurang Lancar,Ragu-ragu,Macet'],
        ];
    }
}
