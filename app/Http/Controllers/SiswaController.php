<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
      $paa = Hash::make($request->password);
      User::create([
        "nama" => $request->nama,
        "password" => $paa,
        "role" => "siswa",
        ]);
        $us = User::where("nama",$request->nama)->first();
      $sis = Siswa::create([
        "user_id" => $us->id,
        "nama" => $request->nama,
        "nisn" => $request->nisn,
        'kelas_id' => $request->kelas_id,
        ]);
        if($request->has("kelas_id")){
          $kels = Kelas::find($request->kelas_id);
          $sis->kelas()->associate($kels);
          $sis->save();
        }
        return redirect()->route("admin.siswa.index")->with("success","Berhasil Mantap!");
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
      $siswa = Siswa::findOrFail($id);
      $usd = User::findOrFail($siswa->user_id);
      $usd->nama = $request->nama;
      if($request->filled("password")){
      $usd->password = Hash::make($request->password);
      }
      $usd->save();
        $request->validate([
          "nama" => "required",
          "nisn" => "required",
          "password" => "required",
          
          ]);
          $siswa->nama = $request->nama;
          $siswa->nisn = $request->nisn;
        $siswa->save();
        return redirect()->route("admin.siswa.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $admin_siswa)
    {
        $admin_siswa->delete();
        return redirect()->route("admin.siswa.index");
    }
    public function Siswas()
    {
      
    $user = Auth::user();
    
    // Ambil data siswa dengan relasi
    $siswa = Siswa::with('kelas')
        ->where('nama', $user->nama)
        ->firstOrFail(); // Gunakan firstOrFail untuk keamanan
    
    // Ambil semua ujian untuk kelas siswa beserta status peserta
    $uji = Ujian::with(['kelas', 'jadwal'])
        ->whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
        })
        ->with(['peserta' => function($query) use ($siswa) {
            // Langsung filter peserta untuk siswa ini
            $query->where('siswa_id', $siswa->id_siswa);
        }])
        ->get();
    
    // Transform data untuk memudahkan di view
    foreach ($uji as $u) {
        // Set status berdasarkan data peserta
        $peserta = $u->peserta->first();
        $u->status_ujian = $peserta ? $peserta->status : 'belum_mulai';
        $u->nilai_siswa = $peserta ? $peserta->nilai : null;
        
        // Hapus data peserta dari relasi (sudah dipindah ke properti baru)
        unset($u->peserta);
    }
    $ire = $user;
    return view("siswa.index", compact("siswa", "uji","ire"));

    }
    public function Starts($id)
{
    $ire = Auth::user();
    $uji = Ujian::with("mapels","jadwal")->where("id", $id)->first();
    

    if(!$uji) {
        return redirect()->back()->with('error', 'Ujian tidak ditemukan');
    }
    

    $ujians = Ujian_soals::where("ujian_id", $uji->id)->pluck('bank_id')->toArray();
    
    // Ambil data siswa
    $sis = Siswa::with("kelas")->where("nama", $ire->nama)->first();
    

    $soal = banksoal::whereIn('id', $ujians)->get();
    

    if($soal->isEmpty()) {
        return redirect()->back()->with('error', 'Belum ada soal untuk ujian ini');
    }
    
    return view("siswa.ujian", compact("uji", "soal", "ire", "sis", "ujians"));
}
    
    public function Saved(Request $request)
    {
        $request->validate([
            "jawaban" => "required|array"
        ]);
        
        $jawabanSiswa = $request->jawaban;
        $soal_ids = array_keys($jawabanSiswa);
        $soals = banksoal::whereIn("id", $soal_ids)->get()->keyBy("id");
        
        $score = 0;
        $total_soal = count($jawabanSiswa);
        
        foreach($jawabanSiswa as $soal_id => $jawabans) {
            $soal = $soals[$soal_id] ?? null;
            if (!$soal) continue;
            
            $benar = 0;
            
            if($soal->opsi_a != null) {
                // SOAL PILIHAN GANDA
                $benar = (strtoupper(trim($jawabans)) == strtoupper(trim($soal->jawaban_benar))) ? 1 : 0;
            } else {
                // SOAL ESSAY - panggil method terpisah
                $nilai = $this->hitungNilaiEssay($jawabans, $soal->jawaban_benar);
                $benar = ($nilai >= 80) ? 1 : 0; // Anggap benar jika nilai >= 80
            }
            
            if($benar) {
                $score += 1;
            }
            
            Jawaban_Siswa::updateOrCreate([
                "ujian_id" => $request->ujian_id,
                "siswa_id" => $request->siswa_id,
                "bank_id" => $soal->id,
            ], [
                "jawaban" => $jawabans,
                "benar" => $benar,
            ]);
        }
        
        $nilai = ($total_soal > 0) ? ($score / $total_soal) * 100 : 0;
        
        Peserta_ujian::updateOrCreate([
            "ujian_id" => $request->ujian_id,
            "siswa_id" => $request->siswa_id,
        ], [
            "nilai" => $nilai,
            "status" => "done",
        ]);
        
        return redirect()->route("siswa.index")->with("success", "Ujian selesai!");
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
 
}
