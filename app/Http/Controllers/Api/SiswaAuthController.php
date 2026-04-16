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
    $today = now()->format('Y-m-d');
    
    // ==========================================
    // 1. UJIAN HARI INI (Pakai Model)
    // ==========================================
    $jadwalHariIni = Jadwal::with(['ujian', 'ujian.mapels'])
        ->where('kelas_id', $siswa->kelas_id)
        ->where('tanggal', $today)
        ->get();
    
    $ujianHariIniFormatted = [];
    foreach ($jadwalHariIni as $jadwal) {
        $ujian = $jadwal->ujian;
        
        if (!$ujian) continue;
        
        // Cek peserta ujian
        $peserta = Peserta_ujian::where('ujian_id', $ujian->id)
            ->where('siswa_id', $siswa->id_siswa)
            ->first();
        
        $ujianHariIniFormatted[] = [
            'id' => $ujian->id,
            'nama_ujian' => $ujian->nama_ujian,
            'mapel' => $ujian->mapels->nama_mapel ?? 'Unknown',
            'waktu_mulai' => $jadwal->waktu_mulai,
            'durasi' => $ujian->durasi,
            'status_ujian' => $peserta ? ($peserta->status ?? 'belum') : 'belum',
            'nilai' => $peserta->nilai ?? null,
        ];
    }
    
    // ==========================================
    // 2. TOTAL UJIAN YANG SUDAH DIKERJAKAN
    // ==========================================
    $totalUjian = Peserta_ujian::where('siswa_id', $siswa->id_siswa)->count();
    
    // ==========================================
    // 3. UJIAN SELESAI
    // ==========================================
    $ujianSelesai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
        ->where('status', 'selesai')
        ->count();
    
    // ==========================================
    // 4. RATA-RATA NILAI
    // ==========================================
    $rataNilai = 80;
    
    // ==========================================
    // 5. JADWAL MENDATANG (Pakai Model)
    // ==========================================
    $jadwalMendatang = Jadwal::with('ujian')
        ->where('kelas_id', $siswa->kelas_id)
        ->where('tanggal', '>', $today)
        ->orderBy('tanggal', 'asc')
        ->limit(5)
        ->get()
        ->map(function($jadwal) {
            return [
                'id' => $jadwal->id,
                'ujian_id' => $jadwal->ujian_id,
                'nama_ujian' => $jadwal->ujian->nama_ujian ?? 'Unknown',
                'tanggal' => $jadwal->tanggal,
                'waktu_mulai' => $jadwal->waktu_mulai,
                'durasi' => $jadwal->ujian->durasi ?? 0,
            ];
        });
    
    return [
        'siswa' => [
            'nama' => $siswa->nama,
            'nisn' => $siswa->nisn,
            'kelas' => $siswa->kelas->nama_kelas ?? 'Unknown',
        ],
        'statistik' => [
            'total_ujian' => $totalUjian,
            'ujian_selesai' => $ujianSelesai,
            'rata_rata_nilai' => round($rataNilai, 2),
        ],
        'ujian_hari_ini' => $ujianHariIniFormatted,
        'jadwal_mendatang' => $jadwalMendatang,
        'tanggal' => now()->format('l, d F Y'),
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
    $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
    
    if (!$siswa) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data siswa tidak ditemukan'
        ], 404);
    }
    
    $dashboardData = $this->getDashboardData($siswa);
    
    // Tambah data siswa (untuk ditampilkan di Flutter)
    $dashboardData['siswa'] = [
        'nama' => $siswa->nama,
        'nisn' => $siswa->nisn,
        'kelas' => $siswa->kelas->nama_kelas ?? 'Unknown',
        'username' => $siswa->username ?? $user->nama,
    ];
    
    // Tambah info tanggal
    $dashboardData['tanggal'] = now()->format('l, d F Y');
    $dashboardData['waktu'] = now()->format('H:i:s');
    
    return response()->json([
        'status' => 'success',
        'data' => $dashboardData
    ]);
}
    public function dashboard(Request $request)
{
    $user = $request->user();
    $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
    
    if (!$siswa) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data siswa tidak ditemukan'
        ], 404);
    }
    
    $dashboardData = $this->getDashboardData($siswa);
    
    // Tambah data siswa (yang dibutuhkan Flutter)
    $dashboardData['siswa'] = [
        'nama' => $siswa->nama,
        'nisn' => $siswa->nisn,
        'kelas' => $siswa->kelas->nama_kelas ?? 'Unknown',
        'id_siswa' => $siswa->id_siswa,
    ];
    
    $dashboardData['info_waktu'] = [
        'tanggal' => now()->format('Y-m-d'),
        'waktu' => now()->format('H:i:s'),
    ];
    
    return response()->json([
        'status' => 'success',
        'data' => $dashboardData
    ]);
}
}