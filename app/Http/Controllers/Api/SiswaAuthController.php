<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Peserta_ujian;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SiswaAuthController extends Controller
{
    /**
     * Login khusus siswa
     */
    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan nama
        $user = User::where('nama', $request->nama)->first();

        // Cek apakah user ada, password benar, dan role-nya siswa
        if (!$user || !Hash::check($request->password, $user->password) || $user->role !== 'siswa') {
            throw ValidationException::withMessages([
                'nama' => ['Login gagal. Hanya siswa yang dapat login.'],
            ]);
        }

        // Cari data siswa terkait
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        if (!$siswa) {
            throw ValidationException::withMessages([
                'nama' => ['Data siswa tidak ditemukan.'],
            ]);
        }

        // Hapus token lama (opsional)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('siswa-token', ['siswa'])->plainTextToken;

        // Ambil data dashboard
        $dashboardData = $this->getDashboardData($siswa);

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'role' => $user->role,
                ],
                'siswa' => [
                    'id_siswa' => $siswa->id_siswa,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                    'kelas_id' => $siswa->kelas_id,
                ],
                'token' => $token,
                'dashboard' => $dashboardData
            ]
        ], 200);
    }

    /**
     * Ambil data dashboard siswa
     */
    private function getDashboardData($siswa)
    {
        // Ambil ujian hari ini
        $today = now()->format('Y-m-d');
        
        $ujianHariIni = Ujian::with(['mapels', 'jadwal', 'kelas'])
            ->whereHas('kelas', function($query) use ($siswa) {
                $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
            })
            ->whereHas('jadwal', function($query) use ($today) {
                $query->whereDate('waktu_mulai', $today);
            })
            ->with(['peserta' => function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id_siswa);
            }])
            ->get()
            ->map(function($ujian) use ($siswa) {
                $peserta = $ujian->peserta->first();
                return [
                    'id' => $ujian->id,
                    'nama_ujian' => $ujian->nama_ujian,
                    'mapel' => $ujian->mapels->nama_mapel ?? 'Unknown',
                    'waktu_mulai' => $ujian->jadwal ? $ujian->jadwal->waktu_mulai : null,
                    'durasi' => $ujian->durasi ?? 120,
                    'status_ujian' => $peserta ? $peserta->status : 'belum_mulai',
                    'nilai' => $peserta ? $peserta->nilai : null,
                    'bisa_dimulai' => $ujian->status === 'ready' && (!$peserta || $peserta->status !== 'done'),
                ];
            });

        // Hitung statistik
        $pesertaUjian = Peserta_ujian::where('siswa_id', $siswa->id_siswa)->get();
        
        $totalJadwal = Ujian::whereHas('kelas', function($query) use ($siswa) {
                $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
            })
            ->where('status', 'ready')
            ->count();

        $totalRiwayat = $pesertaUjian->whereNotNull('nilai')->count();
        $rataNilai = $pesertaUjian->whereNotNull('nilai')->avg('nilai');

        return [
            'ujian_hari_ini' => $ujianHariIni,
            'statistik' => [
                'total_jadwal' => $totalJadwal,
                'total_riwayat' => $totalRiwayat,
                'rata_rata_nilai' => round($rataNilai, 2) ?: 0,
            ]
        ];
    }

    /**
     * Logout siswa
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Get profile siswa
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'role' => $user->role,
                ],
                'siswa' => [
                    'id_siswa' => $siswa->id_siswa,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                ]
            ]
        ], 200);
    }

    /**
     * Get dashboard data
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $dashboardData = $this->getDashboardData($siswa);

        return response()->json([
            'status' => 'success',
            'data' => $dashboardData
        ], 200);
    }
}