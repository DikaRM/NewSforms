<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
 use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Ruangan;
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

    // 🚨 CEGAH DOUBLE SUBMIT
    if (session('login_in_progress')) {
        return back()->with(
            'error',
            'Permintaan login sedang diproses.'
        );
    }

    // set flag
    session(['login_in_progress' => true]);

    $login = $request->input('login');
    $password = $request->input('password');

    // Cari user berdasarkan nama atau username
    $user = User::where('nama', $login)
                ->orWhere('username', $login)
                ->first();

    // Jika user ditemukan
    if ($user && Auth::attempt([
        'username' => $user->username,
        'password' => $password
    ])) {

        // reset flag
        session()->forget('login_in_progress');

        $request->session()->regenerate();

        session([
            'user_role' => $user->role
        ]);

        switch ($user->role) {

            case 'admin':
                return redirect("/admin");

            case 'admin-ops':
                return redirect("/admin-ops");

            case 'siswa':
                return redirect("/siswa/sis");

            case 'guru':
                return redirect("/guru/siap");

            case 'pengawas':
                return redirect("/pengawas");

            default:
                Auth::logout();
                session()->flush();

                return redirect()
                    ->route("login")
                    ->with("error", "Role tidak dikenali");
        }
    }

    // reset flag kalau gagal
    session()->forget('login_in_progress');

    return back()->with(
        "error",
        "Login gagal. Periksa kembali username/nama dan password Anda."
    );
}
    public function logout(Request $request){
      Auth::logout();
      
      return redirect("/login");
    }
   

public function show($id)
{
    $ruangan = Ruangan::findOrFail($id);

    $qrText = "RUANG: {$ruangan->nama_ruang}\nKODE: {$ruangan->kode}";

    $qr = QrCode::size(200)->generate($qrText);

    return view('ruangan.qr', compact('ruangan', 'qr'));
}

    public function profil()
    {
        // Mengirim data user yang sedang login ke view
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // Logika GANTI PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:6|confirmed', // confirmed harus sama dengan password_confirmation
        ]);

        $user = Auth::user();

        // Cek apakah password lama cocok
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah!');
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // Logika RESET PASSWORD (Lupa Password)
   public function sendResetLink(Request $request)
{
    $user = Auth::user();

    $status = Password::sendResetLink([
        'email' => $user->email
    ]);

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link reset password telah dikirim ke email Anda!')
        : back()->with('error', 'Gagal mengirim link reset password.');
}

}
