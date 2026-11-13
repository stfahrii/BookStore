<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Proteksi Hak Akses (Role-Based Access Control)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah terautentikasi (login)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Akses Ditolak: Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // 2. Cek apakah akun sedang berstatus terkunci oleh admin
        if ($user->status_akun === 'terkunci') {
            Auth::logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return redirect()->route('login')->with('error', 'Akses Ditolak: Akun Anda sedang terkunci (Off). Silakan hubungi petugas perpustakaan.');
        }

        // 3. Cek kesesuaian role
        if ($role === 'admin' && $user->role !== 'admin') {
            // User biasa mencoba membobol halaman Admin melalui URL -> Respon HTTP 403 Forbidden
            abort(403);
        }

        if (($role === 'anggota' || $role === 'user') && $user->role === 'admin') {
            // Admin membuka halaman khusus siswa
            return redirect()->route('admin.dashboard')->with('error', 'Pemberitahuan: Akun Administrator diarahkan ke Dashboard Manajemen.');
        }

        return $next($request);
    }
}
