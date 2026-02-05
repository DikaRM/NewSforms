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
        "password" =>"required",
        
        ]);
        $pass = Hash::make($request->password);
        if(Auth::attempt($request->only("nama","password"))){
          $request->session()->regenerate();
          $user = Auth::user();
          switch ($user->role){
            case 'admin':
              return redirect("/admin");
            case 'siswa':
              return redirect("/siswa");
            case 'guru':
              return redirect("/guru");
            case 'pengawas':
              return redirect("/pengawas");
            default:
              Auth::logout;
              $request->session->invalidate();
              $request->session->regenerateToken();
              return redirect("/login");
          }
        }
    }
    public function logout(Request $request){
      Auth::logout();
      $request->session->invalidate();
      $request->session->regenerateToken();
      return redirect("/login");
    }
}
