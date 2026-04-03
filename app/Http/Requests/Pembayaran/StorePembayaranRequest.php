<?php

namespace App\Http\Requests\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peminjaman_id' => ['required', 'exists:peminjaman,id'],
            'tanggal_pembayaran' => ['required', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'force' => ['nullable', 'boolean'],
        ];
    }
}
