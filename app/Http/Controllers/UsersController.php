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
        'login' => 'required',
        'password' => 'required',
    ]);
    
    $login = $request->input('login');
    $password = $request->input('password');
    
    // Cari user berdasarkan nama atau username
    $user = User::where('nama', $login)
                ->orWhere('username', $login)
                ->first();
    
    // Jika user ditemukan, coba login dengan 'nama' (atau 'username')
    if ($user && Auth::attempt(['nama' => $user->nama, 'password' => $password])) {
        $request->session()->regenerate();
        session(['user_role' => $user->role]);
        
        switch ($user->role) {
            case 'admin': return redirect("/admin");
            case 'admin-ops': return redirect("/admin-ops");
            case 'siswa': return redirect("/siswa/sis");
            case 'guru': return redirect("/guru/siap");
            case 'pengawas': return redirect("/pengawas");
            default:
                Auth::logout();
                session()->flush();
                return redirect()->route("login")->with("error", "Role tidak dikenali");
        }
    }
    
    return redirect()->back()->with("error", "Login gagal. Periksa kembali username/nama dan password Anda.");
}
    public function logout(Request $request){
      Auth::logout();
      
      return redirect("/login");
    }
}
