<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email:filter', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required',
        ], [
            'email.required'    => 'Email akun wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.regex'       => 'Alamat email wajib menyertakan domain lengkap (contoh: nama@gmail.com).',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if ($user->status_akun === 'terkunci') {
                return back()->with('error', 'Akun Anda saat ini TERKUNCI (Off) oleh Admin. Silakan hubungi petugas.');
            }

            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                return $user->role === 'admin' 
                    ? redirect()->route('admin.dashboard') 
                    : redirect()->route('user.dashboard');
            }
        }

        return back()->with('error', 'Email atau password yang Anda masukkan tidak sesuai.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi ramah seperti aplikasi umum dengan pesan peringatan jelas
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nis'      => 'required|string|max:30|unique:users,nis',
            'telepon'  => 'required|string|max:20',
            'email'    => ['required', 'string', 'email:filter', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/[0-9]/', // Wajib ada angka
            ],
        ], [
            'nis.unique'         => 'Nomor Identitas (NIS/NIK) ini sudah terdaftar sebelumnya.',
            'email.unique'       => 'Alamat email ini sudah terdaftar sebelumnya.',
            'email.email'        => 'Format email tidak valid.',
            'email.regex'        => 'Alamat email wajib menyertakan domain lengkap (contoh: nama@gmail.com).',
            'password.min'       => 'Password minimal harus 6 karakter.',
            'password.regex'     => 'Password harus mengandung minimal satu angka (0-9).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        User::create([
            'nama'        => $request->nama,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'anggota',
            'nis'         => $request->nis,
            'kelas'       => 'Umum',
            'telepon'     => $request->telepon,
            'status_akun' => 'aktif',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
