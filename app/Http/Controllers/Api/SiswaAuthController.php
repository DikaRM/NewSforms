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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SiswaAuthController extends Controller
{
    /**
     * Login khusus siswa
     */
    public function login(Request $request)
{
    try {
        \Log::info('Login attempt', $request->all());
        
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nama', $request->nama)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nama atau password salah'
            ], 401);
        }

        if ($user->role !== 'siswa') {
            return response()->json([
                'status' => 'error', 
                'message' => 'Hanya siswa yang bisa login'
            ], 403);
        }

        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $token = $user->createToken('siswa-token', ['siswa'])->plainTextToken;

        // DATA DASHBOARD KOSONG DULU (JANGAN PAKAI getDashboardData)
        $dashboardData = [
            'ujian_hari_ini' => [],
            'statistik' => [
                'total_jadwal' => 0,
                'total_riwayat' => 0,
                'rata_rata_nilai' => 0,
            ]
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'role' => $user->role
                ],
                'siswa' => [
                    'id_siswa' => $siswa->id_siswa,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                    'kelas_id' => $siswa->kelas_id,
                ],
                'dashboard' => $dashboardData
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


private function getDashboardData($siswa)
{
    // AMBIL UJIAN HARI INI - PAKAI LIMIT DAN JOIN MANUAL
    $today = now()->format('Y-m-d');
    
    $ujianHariIni = DB::table('ujian')
        ->join('kelas_ujian', 'ujian.id', '=', 'kelas_ujian.ujian_id')
        ->join('jadwal', 'ujian.id', '=', 'jadwal.ujian_id')
        ->join('mapels', 'ujian.mapel_id', '=', 'mapels.id')
        ->leftJoin('peserta_ujian', function($join) use ($siswa) {
            $join->on('ujian.id', '=', 'peserta_ujian.ujian_id')
                 ->where('peserta_ujian.siswa_id', '=', $siswa->id_siswa);
        })
        ->where('kelas_ujian.kelas_id', $siswa->kelas_id)
        ->whereDate('jadwal.waktu_mulai', $today)
        ->select(
            'ujian.id',
            'ujian.nama_ujian',
            'ujian.durasi',
            'ujian.status',
            'mapels.nama_mapel as mapel',
            'jadwal.waktu_mulai',
            'peserta_ujian.status as peserta_status',
            'peserta_ujian.nilai'
        )
        ->limit(5)
        ->get()
        ->map(function($ujian) {
            return [
                'id' => $ujian->id,
                'nama_ujian' => $ujian->nama_ujian,
                'mapel' => $ujian->mapel ?? 'Unknown',
                'waktu_mulai' => $ujian->waktu_mulai,
                'durasi' => $ujian->durasi ?? 120,
                'status_ujian' => $ujian->peserta_status ?? 'belum_mulai',
                'nilai' => $ujian->nilai,
                'bisa_dimulai' => $ujian->status === 'ready' && (!$ujian->peserta_status || $ujian->peserta_status !== 'done'),
            ];
        });
    
    // STATISTIK - PAKAI COUNT LANGSUNG
    $totalJadwal = DB::table('ujian')
        ->join('kelas_ujian', 'ujian.id', '=', 'kelas_ujian.ujian_id')
        ->where('kelas_ujian.kelas_id', $siswa->kelas_id)
        ->where('ujian.status', 'ready')
        ->count();
    
    $totalRiwayat = DB::table('peserta_ujian')
        ->where('siswa_id', $siswa->id_siswa)
        ->whereNotNull('nilai')
        ->count();
    
    $rataNilai = DB::table('peserta_ujian')
        ->where('siswa_id', $siswa->id_siswa)
        ->whereNotNull('nilai')
        ->avg('nilai');
    
    return [
        'ujian_hari_ini' => $ujianHariIni,
        'statistik' => [
            'total_jadwal' => $totalJadwal,
            'total_riwayat' => $totalRiwayat,
            'rata_rata_nilai' => round($rataNilai ?: 0, 2),
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


public function dashboardSimple(Request $request)
{
    $user = $request->user();
    $siswa = Siswa::where('user_id', $user->id)->first();
    
    // Data statis minimal
    return response()->json([
        'status' => 'success',
        'data' => [
            'ujian_hari_ini' => [],
            'statistik' => [
                'total_jadwal' => 0,
                'total_riwayat' => 0,
                'rata_rata_nilai' => 0,
            ]
        ]
    ]);
}
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