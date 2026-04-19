<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sap' => ['required', 'regex:/^\d{5,}$/', 'max:20', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'sap.regex' => 'SAP harus terdiri dari minimal 5 angka dan hanya boleh angka.',
            'sap.unique' => 'SAP tersebut sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $sap = $this->input('sap');

        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'sap' => is_string($sap) ? preg_replace('/\D+/', '', trim($sap)) : $sap,
        ]);
    }
}
