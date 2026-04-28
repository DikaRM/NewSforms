<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. CEK: Apakah User Sudah Login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. CEK: Apakah Role User Sama dengan Role yang Diminta Middleware?
        if (Auth::user()->role !== $role) {
            
            // Buat peta redirect agar tepat sasaran (Mencegah Loop)
            $dashboardMap = [
                'admin'     => '/admin',
                'admin-ops' => '/admin-ops',
                'siswa'     => '/siswa/sis', // PENTING: /siswa/sis bukan /siswa
                'guru'      => '/guru/siap',  // PENTING: /guru/siap bukan /guru
                'pengawas'  => '/pengawas',
            ];
            
            // Ambil role user yang sedang login
            $userRole = Auth::user()->role;
            
            // Cari URL dashboard yang sesuai dengan role user tersebut
            $targetUrl = $dashboardMap[$userRole] ?? '/login';

            // Redirect ke dashboard user tersebut dengan pesan error
            return redirect($targetUrl)->with('error', 'Anda tidak berhak mengakses halaman tersebut.');
        }

        // 3. Jika Role Cocok -> Lanjut ke Controller
        return $next($request);
    }
}