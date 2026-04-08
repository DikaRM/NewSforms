<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController
{
    public function index()
    {
      return view("login");
    }
    public function login(Request $request)
{
    $request->validate([
        "nama" => "required",
        "password" => "required",
    ]);
    
    // Hapus baris Hash::make karena Auth::attempt akan menangani hashing otomatis
    // $pass = Hash::make($request->password); // <-- HAPUS BARIS INI
    
    if (Auth::attempt($request->only("nama", "password"))) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        // Simpan role ke session untuk keperluan lain jika diperlukan
        session(['user_role' => $user->role]);
        
        switch ($user->role) {
            case 'admin':
                return redirect("/admin");
            case 'admin-ops':
                return redirect("/admin-ops");
            case 'siswa':
                return redirect("/siswa");
            case 'guru':
                return redirect("/guru");
            case 'pengawas':
                return redirect("/pengawas");
            default:
                Auth::logout();
                session()->flush(); // Hapus semua session
                return redirect()->route("login")->with("error", "Role tidak dikenali");
        }
    }
    
    return redirect()->back()->with("error", "Login gagal. Periksa kembali username dan password Anda.");
}
    public function logout(Request $request){
      Auth::logout();
      
      return redirect("/login");
    }
}
