<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'sap' => ['required', 'regex:/^\d{5,}$/'],
            'password' => 'required'
        ], [
            'sap.required' => 'SAP wajib diisi.',
            'sap.regex' => 'SAP harus terdiri dari minimal 5 angka dan hanya boleh angka.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt([
            'email' => $validated['sap'],
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login berhasil. Selamat datang kembali, '.Auth::user()->name.'.');
        }

        return back()->withErrors([
            'sap' => 'SAP atau password salah'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('info', 'Anda telah logout dari sistem.');
    }
}
