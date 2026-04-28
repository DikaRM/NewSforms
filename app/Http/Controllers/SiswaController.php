<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\banksoal;
use App\Models\Ujian_soals;
use App\Models\Jawaban_Siswa;
use App\Models\Peserta_ujian;
use App\Models\Jadwal;
use App\Models\Pelanggaran;
class SiswaController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data = Siswa::with("kelas")->get();
      $ire = Auth::user();
      $kelas = Kelas::all();
      
      return view("admin.siswa.index",compact("data","ire","kelas"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'nisn' => 'required|digits:10',
        'password' => 'required|min:6',
        'kelas_id' => 'required'
    ]);
    $katapertama = Str::of($request->nama)->before(' ')->toString();
    $user = User::create([
        "nama" => $request->nama,
        "password" => Hash::make($request->password),
        "role" => "siswa",
        "username"=> $katapertama,
    ]);
    
    Siswa::create([
        "user_id" => $user->id,
        "nama" => $request->nama,
        "nisn" => $request->nisn,
        'kelas_id' => $request->kelas_id,
        "username" => $katapertama, 
    ]);
    
    return redirect()->route("admin.siswa.index")->with("success", "Berhasil Tambah Siswa!");
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{

    $request->validate([
        "nama" => "required",
        "nisn" => "required|digits:10",
        // Hapus 'required' dari password
        "password" => "nullable|min:6", // optional, dengan minimal 6 karakter
    ]);
    
    // 2. Proses update data
    $siswa = Siswa::findOrFail($id);
    $usd = User::findOrFail($siswa->user_id);
    $katapertama = Str::of($request->nama)->before(' ')->toString();
    $usd->nama = $request->nama;
    $usd->username = $katapertama;
    if($request->filled("password")) {
        $usd->password = Hash::make($request->password);
    }
    $usd->save();
    
    $siswa->nama = $request->nama;
    
    $siswa->username= $katapertama;
    $siswa->nisn = $request->nisn;
    $siswa->save();
    
    return redirect()->route("admin.siswa.index")->with("success", "Berhasil Update Siswa!");
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)  // Parameter harus $siswa, bukan $admin_siswa
{
    // Hapus user terkait
    if($siswa->user_id) {
        User::destroy($siswa->user_id);
    }
    
    $siswa->delete();
    return redirect()->route("admin.siswa.index")->with('success', 'Data berhasil dihapus');
}
    public function Siswas()
{
    $user = Auth::user();
    
    // Ambil data siswa dengan relasi
    $siswa = Siswa::with('kelas')
        ->where('nama', $user->nama)
        ->first();
    
    if(!$siswa) {
        return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan');
    }
    
    // Ambil ujian HARI INI saja (filter by jadwal)
    $today = now()->format('Y-m-d');
    
    $uji = Ujian::with(['mapels', 'jadwal', 'kelas'])
        ->whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('waktu_mulai', $today);
        })
        ->whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
        })
        ->with(['peserta' => function($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id_siswa);
        }])
        ->get();
    
    // Transform data untuk memudahkan di view
    foreach ($uji as $u) {
        $peserta = $u->peserta->first();
        
        if(!$peserta) {
            $u->status_ujian = 'belum';
            $u->nilai_siswa = null;
            $u->peserta_id = null;
            $u->selesai_pada = null;
        } else {
            $u->status_ujian = $peserta->status;
            $u->nilai_siswa = $peserta->nilai;
            $u->peserta_id = $peserta->id;
            $u->selesai_pada = $peserta->updated_at;
        }
        
        unset($u->peserta);
    }
    
    // Statistik untuk resume
    $totalUjian = Peserta_ujian::where('siswa_id', $siswa->id_siswa)->count();
    $ujianSelesai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
        ->where('status', 'selesai')
        ->count();
    $rataNilai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
        ->where('status', 'selesai')
        ->avg('nilai') ?? 0;
    
    // Jadwal mendatang (3 hari ke depan)
    $jadwalMendatang = Jadwal::with('ujian')
        ->where('kelas_id', $siswa->kelas_id)
        ->whereDate('waktu_mulai', '>', $today)
        ->orderBy('waktu_mulai', 'asc')
        ->limit(3)
        ->get();
    
    $ire = $user;
    
    return view("siswa.index", compact(
        "siswa", 
        "uji", 
        "ire", 
        "totalUjian", 
        "ujianSelesai", 
        "rataNilai",
        "jadwalMendatang",
        "today"
    ));
}
    public function Starts($id)
{
    $ire = Auth::user();
    $uji = Ujian::with("mapels","jadwal")->where("id", $id)->first();
    
    if(!$uji) {
        return redirect()->back()->with('error', 'Ujian tidak ditemukan');
    }
    
    // ========== TAMBAHKAN VALIDASI INI ==========
    // Ambil data siswa
    $sis = Siswa::with("kelas")->where("nama", $ire->nama)->first();
    
    if(!$sis) {
        return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
    }
    
    // CEK STATUS DI TABEL PESERTA_UJIAN
    $peserta = Peserta_ujian::where('ujian_id', $uji->id)
        ->where('siswa_id', $sis->id_siswa)
        ->first();
    
    // Jika sudah selesai, tolak akses
    if($peserta && $peserta->status == 'selesai') {
        return redirect()->route('siswa.index')->with('error', 'Anda sudah menyelesaikan ujian ini! Nilai: ' . $peserta->nilai);
    }
    
    // Jika status 'mulai' (sedang mengerjakan), boleh lanjutkan
    // Jika belum ada record (null), buat baru dengan status 'mulai'
    if(!$peserta) {
        $peserta = Peserta_ujian::create([
            'siswa_id' => $sis->id_siswa,
            'ujian_id' => $uji->id,
            'status' => 'mulai',
            'nilai'=> 0,
        ]);
    }
    
    $ujians = Ujian_soals::where("ujian_id", $uji->id)->pluck('bank_id')->toArray();
    $soal = banksoal::whereIn('id', $ujians)->get();
    
    if($soal->isEmpty()) {
        return redirect()->back()->with('error', 'Belum ada soal untuk ujian ini');
    }
    
    return view("siswa.ujian", compact("uji", "soal", "ire", "sis", "ujians", "peserta"));
}
    
    public function Saved(Request $request)
{
    // 1. VALIDASI
    $request->validate([
        "ujian_id" => "required|integer|exists:ujian,id",
        "siswa_id" => "required|integer|exists:siswa,id_siswa", // Pastikan siswa valid
        "jawaban" => "required|array",
    ]);

    // 2. AMBIL DATA SISWA (Untuk redirect aman)
    $siswa = Siswa::findOrFail($request->siswa_id);

    $ujianId = $request->ujian_id;
    $jawabanSiswa = $request->jawaban;
    $soal_ids = array_keys($jawabanSiswa);
    
    // 3. AMBIL DATA SOAL
    $soals = banksoal::whereIn("id", $soal_ids)->get()->keyBy("id");
    
    $score = 0;
    $total_soal = count($jawabanSiswa);
    
    $jawabanData = []; // Siapkan array untuk batch insert (opsional tapi disarankan)
    
    foreach($jawabanSiswa as $soal_id => $jawabans) {
        $soal = $soals[$soal_id] ?? null;
        if (!$soal) continue;
        
        $benar = 0;
        
        if($soal->opsi_a != null) {
            // PILIHAN GANDA
            $benar = (strtoupper(trim($jawabans)) == strtoupper(trim($soal->jawaban_benar))) ? 1 : 0;
        } else {
            // ESSAY
            $nilai = $this->hitungNilaiEssay($jawabans, $soal->jawaban_benar);
            $benar = ($nilai >= 80) ? 1 : 0; 
        }
        
        if($benar) {
            $score += 1;
        }
        
        // SIMPAN DATA KE ARRAY (Lebih aman dari error query per loop)
        $jawabanData[] = [
            "ujian_id" => $ujianId,
            "siswa_id" => $siswa->id_siswa,
            "bank_id" => $soal->id,
            "jawaban" => $jawabans,
            "benar" => $benar,
            "updated_at" => now(),
        ];
    }

    // 4. MASS INSERT (Upsert) agar tidak lambat
    // Pastikan di migration ada: $table->unique(['ujian_id', 'siswa_id', 'bank_id']);
    if (!empty($jawabanData)) {
        Jawaban_Siswa::upsert(
            $jawabanData,
            ['ujian_id', 'siswa_id', 'bank_id'], 
            ['jawaban', 'benar', 'updated_at']   
        );
    }
    
    // 5. HITUNG NILAI AKHIR
    $nilai = ($total_soal > 0) ? round(($score / $total_soal) * 100, 2) : 0;
    
    // 6. UPDATE PESERTA UJIAN
    Peserta_ujian::updateOrCreate([
        "ujian_id" => $ujianId,
        "siswa_id" => $siswa->id_siswa,
    ], [
        "nilai" => $nilai,
        "status" => "done", // Pastikan konsisten dengan database (done/selesai)
        "selesai_pengerjaan" => now(),
    ]);
    
    // 7. UPDATE STATUS SISWA (HANYA JIKA KOLOMNYA ADA)
    // Kode ini "Aman" karena mengecek dulu kolomnya ada atau tidak.
    // Jika tabel siswa tidak punya kolom status, maka baris ini tidak dijalankan.
    if (Schema::hasColumn('siswa', 'status')) {
        $siswa->update(["status" => "unready"]);
    }

    // 8. REDIRECT KE HOME / HASIL
    // Menggunakan route 'siswa.index' atau route dashboard
    return redirect()->route("siswa.index")->with("success", "Ujian selesai! Nilai: $nilai");
}
    
    /**
     * Fungsi terpisah untuk menghitung nilai essay
     */
    private function hitungNilaiEssay($jawaban_siswa, $jawaban_benar)
    {
        $jawaban_siswa = strtolower(trim($jawaban_siswa));
        $jawaban_benar = strtolower(trim($jawaban_benar));
        
        // 1. Cek exact match dulu
        if ($jawaban_siswa === $jawaban_benar) {
            return 100;
        }
        
        // 2. Cek dengan menghapus tanda baca
        $clean_siswa = preg_replace('/[^\w\s]/', '', $jawaban_siswa);
        $clean_benar = preg_replace('/[^\w\s]/', '', $jawaban_benar);
        $clean_siswa = preg_replace('/\s+/', ' ', $clean_siswa);
        $clean_benar = preg_replace('/\s+/', ' ', $clean_benar);
        
        if ($clean_siswa === $clean_benar) {
            return 95;
        }
        
        // 3. Hitung similarity
        similar_text($clean_siswa, $clean_benar, $percent);
        
        // 4. Hitung Levenshtein distance
        $distance = levenshtein($clean_siswa, $clean_benar);
        $max_distance = strlen($clean_benar) * 0.3;
        
        if ($distance <= $max_distance) {
            return round($percent);
        }
        
        // 5. Cek kata kunci
        $kata_kunci = explode(' ', $jawaban_benar);
        $kata_terpenuhi = 0;
        
        foreach ($kata_kunci as $kata) {
            if (strlen($kata) > 3) {
                if (strpos($jawaban_siswa, $kata) !== false) {
                    $kata_terpenuhi++;
                }
            }
        }
        
        if (count($kata_kunci) > 0) {
            $nilai_kata_kunci = ($kata_terpenuhi / count($kata_kunci)) * 100;
            return round($nilai_kata_kunci);
        }
        
        return 0;
    }

    
 public function Pelanggaran(Request $request)
 {
   Pelanggaran::create([
     "ujian_id" => $request->ujian_id,
     "siswa_id" => $request->siswa_id,
     "jenis_pelanggaran" => $request->jenis_pelanggaran,
     ]);
     return response()->json([
       "redirect" => route("siswa.index")
       ]);
 }
 public function riwayat(){
   $ire = Auth::user();
   $sis = Siswa::with("kelas")->where("nama",$ire->nama)->first();
   $data = Peserta_ujian::with("ujian","siswa")->where("siswa_id",$sis->id_siswa)->get();
   
   return view("siswa.riwayat",compact("data","sis","ire"));
 }
 public function jadwal(){
   $ire = Auth::user();
   $sis = Siswa::with("kelas")->where("nama",$ire->nama)->first();
   $data = Jadwal::with("ujian")->where("kelas_id",$sis->kelas_id)->get();
   return view("siswa.jadwal",compact("data","sis","ire"));
 }
 public function resume($id)
{
    $ire = Auth::user();
    $sis = Siswa::where("nama", $ire->nama)->first();
    
    // Cek apakah ada ujian dengan status 'mulai'
    $peserta = Peserta_ujian::where('ujian_id', $id)
        ->where('siswa_id', $sis->id_siswa)
        ->where('status', 'mulai')
        ->first();
    
    if(!$peserta) {
        return redirect()->route('siswa.index')->with('error', 'Tidak ada ujian yang sedang berjalan');
    }
    
    $uji = Ujian::with("mapels","jadwal")->find($id);
    $ujians = Ujian_soals::where("ujian_id", $uji->id)->pluck('bank_id')->toArray();
    $soal = banksoal::whereIn('id', $ujians)->get();
    
    // Kirim data peserta (berisi status 'mulai')
    return view("siswa.ujian", compact("id","uji", "soal", "ire", "sis", "ujians", "peserta"));
}
 public function detail($id)
{
    $ire = Auth::user();
    $siswa = Siswa::where('nama', $ire->nama)->first();
    
    // Ambil data peserta ujian beserta relasinya
    $peserta = Peserta_ujian::with(['ujian.mapels', 'siswa'])
        ->where('id', $id)
        ->where('siswa_id', $siswa->id_siswa)
        ->firstOrFail();
    

    $jawaban = Jawaban_Siswa::with(['bank', 'ujian'])
        ->where('siswa_id', $siswa->id_siswa)
        ->where('ujian_id', $peserta->ujian_id)
        ->get();
    
    // Hitung statistik
    $totalSoal = $jawaban->count();
    $jawabanBenar = $jawaban->where('benar', true)->count();
    $jawabanSalah = $totalSoal - $jawabanBenar;
    
    return view("siswa.detail", compact(
        "jawaban", 
        "peserta", 
        "totalSoal", 
        "jawabanBenar", 
        "jawabanSalah",
        "ire"
    ));
}
 public function dashboard()
{
    $user = Auth::user();
    
    // Ambil data siswa dengan relasi
    $siswa = Siswa::with('kelas')
        ->where('nama', $user->nama)
        ->first();
    
    if(!$siswa) {
        return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan');
    }
    
    // Ambil ujian HARI INI saja untuk dashboard
    $today = now()->format('Y-m-d');
    
    $uji = Ujian::with(['mapels', 'jadwal', 'kelas'])
        ->whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('waktu_mulai', $today);
        })
        ->whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
        })
        ->with(['peserta' => function($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id_siswa);
        }])
        ->get();
    
    // Tambahan: Statistik untuk resume di dashboard
    $totalUjian = Peserta_ujian::where('siswa_id', $siswa->id_siswa)->count();
    $ujianSelesai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
        ->where('status', 'selesai')
        ->count();
    
    // Hitung rata-rata nilai
    $rataNilai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
        ->where('status', 'selesai')
        ->avg('nilai') ?? 0;
    
    // Jadwal mendatang (3 hari ke depan)
    $jadwalMendatang = Jadwal::with('ujian')
        ->where('kelas_id', $siswa->kelas_id)
        ->whereDate('waktu_mulai', '>', $today)
        ->orderBy('waktu_mulai', 'asc')
        ->limit(3)
        ->get();
    
    $ire = $user;
    
    return view("siswa.dashboard", compact(
        "siswa", 
        "uji", 
        "ire", 
        "totalUjian", 
        "ujianSelesai", 
        "rataNilai",
        "jadwalMendatang",
        "today"
    ));
}
 public function reportViolation(Request $request)
{
    $validated = $request->validate([
        'ujian_id' => 'required|exists:ujian,id',
        'siswa_id' => 'required|exists:siswa,id_siswa',
        'jenis_pelanggaran' => 'required|string',
        'detail' => 'nullable|string',
        'user_agent' => 'nullable|string',
        'screen_resolution' => 'nullable|string',
        'timestamp' => 'nullable|string'
    ]);
    
    // Simpan ke database
    $violation = Pelanggaran::create([
        'ujian_id' => $validated['ujian_id'],
        'siswa_id' => $validated['siswa_id'],
        'jenis_pelanggaran' => $validated['jenis_pelanggaran'],
        'timestamp' => now(),
        
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Pelanggaran tercatat',
        'violation_id' => $violation->id
    ]);
}
 
 
 
}

