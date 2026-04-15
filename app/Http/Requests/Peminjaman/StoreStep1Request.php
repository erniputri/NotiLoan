<?php

namespace App\Http\Requests\Peminjaman;

use App\Models\Peminjaman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'virtual_account_bank' => ['nullable', 'string', Rule::in(array_keys(Peminjaman::virtualAccountBankOptions()))],
            'virtual_account' => ['nullable', 'string', 'max:50', 'required_with:virtual_account_bank'],
            'nama_mitra' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'sektor' => ['nullable', 'string', 'max:50'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $virtualAccount = $this->input('virtual_account');
        $virtualAccountBank = $this->input('virtual_account_bank');

        $this->merge([
            'virtual_account' => is_string($virtualAccount) ? trim($virtualAccount) : $virtualAccount,
            'virtual_account_bank' => is_string($virtualAccountBank) ? trim($virtualAccountBank) : $virtualAccountBank,
        ]);

        if ($this->filled('virtual_account') && ! $this->filled('virtual_account_bank')) {
            $this->merge([
                'virtual_account_bank' => 'Bank BRI',
            ]);
        }
    }
}
