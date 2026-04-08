<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function registerView()
    {
        return view('pages.auth.register');
    }
    public function index(){
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sap' => ['required', 'regex:/^\d{5,}$/', 'unique:users,email'],
            'password' => 'required|min:6|confirmed'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'sap.required' => 'SAP wajib diisi.',
            'sap.regex' => 'SAP harus terdiri dari minimal 5 angka dan hanya boleh angka.',
            'sap.unique' => 'SAP tersebut sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['sap'],
            'password' => Hash::make($validated['password'])
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat dan Anda sudah masuk ke sistem.');
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
