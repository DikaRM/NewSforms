<?php

namespace App\Http\Controllers;
use App\Models\User;
use Ilumminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        "no_id" => "required"
        ]);
        if(Auth::attempt($request->only("nama","password","no_id"))){
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
}
