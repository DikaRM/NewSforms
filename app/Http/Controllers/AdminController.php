<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Pengawas;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\Jadwal;
use App\Models\GuruMapel;
use App\Models\Mapel;
use App\Models\Pelanggaran;
use App\Models\banksoal;

class AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $use = User::query();
    if($request->has("role") && $request->role != ''){
        $use->where("role",$request->role);
    }
    $sort = $request->get("sort","name");
    $order = $request->get("order","asc");
    $data = $use->orderBy($sort,$order,)->get();
    $ire = Auth::user();
    
    // ========== TAMBAHKAN INI ==========
    // Total counts
    $totalSiswa = User::where('role', 'siswa')->count();
    $totalGuru = User::where('role', 'guru')->count();
    $totalKelas = Kelas::count();
    $totalMapel = Mapel::count();
    $totalPelanggaran = Pelanggaran::count();
    $totalBankSoal = banksoal::count();
    
    // Status Ujian
    $ujianReady = Ujian::where('status', 'ready')->count();  // Ujian siap
    $ujianDraft = Ujian::where('status', 'draft')->count(); // Ujian draft
    $ujianDone = Ujian::where('status', 'done')->count();    // Ujian selesai
    // ==================================
    
    $user = User::all();
    $kelas = Kelas::all();
    $ujian = Ujian::all();
    $bank = banksoal::all();
    $pelanggaran = Pelanggaran::all();
    $mapel = Mapel::all();
    
    return view("admin.index", compact(
        "data", "ire", "kelas", "ujian", "bank", 
        "pelanggaran", "mapel",
        "totalSiswa", "totalGuru", "totalKelas", 
        "totalMapel", "totalPelanggaran", "totalBankSoal",
        "ujianReady", "ujianDraft", "ujianDone"
    ));
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
        $validated = $request->validate([
            "nama" => "required",
          "password" => "required",
          "role" => "required",
          "nip" => "nullable",
          ]);
          $ps = Hash::make($validated["password"]);
          $usrr = [
            "nama" => $validated["nama"],
            "password" => $ps,
            "role" => $validated["role"],
            ];
            User::create($usrr);
          if($validated["role"] === "siswa"){
            $on = 1;
            $su = [
              "user_id" => $on++,
              "nisn" => $request->nisn,
              "kelas" => $request->kelas,
              ];
            Siswa::create($su);
          }else if($validated["role"] === "guru"){
            $i = 1;
            $su = [
              "user_id" => $i++,
              "nama" => $validated["nama"],
              "nip" => $validated["nip"],
              ];
            Guru::create($su);
          }
          return redirect()->route("admin.index");
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
    public function update(Request $request, $admin)
    {
      $usef = User::findOrFail($admin);
        $usef->update($request->all());
        return redirect()->route("admin.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($admin)
    {
      $user = User::findOrFail($admin);
        $user->delete();
        return redirect()->route("admin.index");
    }
    public function KelasIndex()
    {
      $ire = Auth::user();
      $dat = Kelas::all();
      $siswa = Siswa::all();
      return view("admin.kelas",compact("dat","ire","siswa"));
    }
    public function KelasCreate(Request $request)
    {
      $request->validate([
        "nama_kelas" => "required"]);
      Kelas::create($request->all());
      return redirect()->route("admin.kelas")->with("sip","Berhasil Menambah Kelas");
    }
    public function KelasUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_kelas" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.kelas")->with("sip","Berhasil Update Kelas");
    }
    public function KelasDestroy($id)
    {
      $hi = Kelas::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.kelas")->with("sip","Berhasil hapus Kelas");
    }
    public function AddSiswa(Request $request ,$id)
    {
      $sis = Siswa::where("nama",$request->nama)->first();
      $kela = Kelas::findOrFail($id);
      SiswaKelas::create([
        "siswa_id" => $request->ids,
        "kelas_id" => $id,
        ]);
      return redirect()->route("admin.kelas")->with("sip","Sip Menambah Siswa Pada Kelas");
    }
    public function MapelIndex()
    {
      $ire = Auth::user();
      $guru = Mapel::with("guru")->get();
      $guruList = Guru::with("mapel")->get();
      return view("admin.mapel",compact("ire","guru","guruList"));
    }
    public function MapelUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_mapel" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.mapel")->with("sip","Berhasil Update Mapel");
    }
    public function MapelDestroy($id)
    {
      $hi = Kelas::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.mapel")->with("sip","Berhasil hapus Mapel");
    }
    public function AddGuru(Request $request)
    {
      $sis = Guru::where("nama",$request->nama)->first();
      $gu = Guru::with("mapel")->find($request->guru_id);
      $gu->mapel()->attach($request->mapel_id);
      return redirect()->route("admin.mapel")->with("sip","Sip Menambahkan Guru Ke Mapel");
    }
    public function Made(Request $request)
    {
      $request->validate([
        "nama_mapel" => "required"]);
      Mapel::create($request->all());
      return redirect()->route("admin.mapel")->with("sip","Berhasil Menambah Mapel");
    }
    public function ops(){
      $uji = Ujian::with("kelas","mapels")->get();
      $sis = Siswa::all();
      $kla = Kelas::all();
      $jad = Jadwal::with("pengawas","ujian","kelas")->get();
      return view("admin-sp.index",compact("uji","sis","kla","jad"));
    }
    public function SetUji($id)
    {
      $jad = Jadwal::with("ujian","pengawas")->where("kelas_id",$id)->get();
      $klas = Kelas::find($id);
      $uji = Ujian::all();
      $penh = Pengawas::with("user","guru")->get();
      $gur = Guru::all();
      return view("admin-sp.jadwal",compact("uji","klas","jad",'gur','penh'));
      
    }
    public function operateCreate(Request $request)
{
    $uji = Ujian::find($request->ujian_id);
    
    // Cek dulu apakah ujian sudah memiliki kelas ini
    if (!$uji->kelas()->where('kelas_id', $request->kelas_id)->exists()) {
        $uji->kelas()->attach($request->kelas_id);
    }
    
    $guru = Guru::find($request->guru_id);
    
    // Gabungkan tanggal dan waktu
    $waktuMulai = \Carbon\Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
    $waktuSelesai = $waktuMulai->copy()->addMinutes($uji->durasi);
    
    // ============ CEK APAKAH SUDAH ADA JADWAL ============
    // Cek apakah sudah ada jadwal untuk kelas ini di tanggal dan jam yang sama
    $existingJadwal = Jadwal::where('kelas_id', $request->kelas_id)
        ->where('tanggal', $request->tanggal)
        ->where('jam_mapel', $request->jam_mapel)
        ->first();
    
    if ($existingJadwal) {
        return redirect()->back()->withErrors([
            'error' => "Kelas ini sudah memiliki jadwal ujian pada tanggal {$request->tanggal} jam ke-{$request->jam_mapel}"
        ])->withInput();
    }
    
    // ============ CEK BENTURAN WAKTU ============
    // Cek apakah ada jadwal lain di kelas yang sama yang waktunya bentrok
    $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
        ->where('tanggal', $request->tanggal)
        ->where(function($query) use ($waktuMulai, $waktuSelesai) {
            $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                  ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                  ->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                      $q->where('waktu_mulai', '<=', $waktuMulai)
                        ->where('waktu_selesai', '>=', $waktuSelesai);
                  });
        })
        ->first();
    
    if ($bentrok) {
        return redirect()->back()->withErrors([
            'error' => "Waktu ujian bentrok dengan jadwal lain di kelas ini"
        ])->withInput();
    }
    
    // ============ CEK PENGAWAS ============
    $pengawas = Pengawas::where('guru_id', $request->guru_id)
        ->where('user_id', $guru->user_id)
        ->first();
    
    if (!$pengawas) {
        $pengawas = Pengawas::create([
            "guru_id" => $request->guru_id,
            "user_id" => $guru->user_id,
        ]);
    }
    
    // ============ CEK JADWAL PENGAWAS ============
    // Cek apakah pengawas sudah memiliki jadwal di waktu yang sama
    $pengawasBentrok = Jadwal::where('pengawas_id', $pengawas->id)
        ->where('tanggal', $request->tanggal)
        ->where(function($query) use ($waktuMulai, $waktuSelesai) {
            $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                  ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai]);
        })
        ->first();
    
    if ($pengawasBentrok) {
        return redirect()->back()->withErrors([
            'error' => "Pengawas sudah memiliki jadwal ujian di waktu yang sama"
        ])->withInput();
    }
    
    // Buat jadwal baru
    $jads = Jadwal::create([
        "jam_mapel" => $request->jam_mapel,
        "tanggal" => $request->tanggal,
        "ujian_id" => $request->ujian_id,
        "pengawas_id" => $pengawas->id,
        "kelas_id" => $request->kelas_id,
        "waktu_mulai" => $waktuMulai,
        "waktu_selesai" => $waktuSelesai,
    ]);
    
    $uji->update([
        "jadwal_id" => $jads->id, 
    ]);
    
    return redirect()->route("admin-ops.set", ["id" => $request->kelas_id])
                     ->with('success', 'Jadwal berhasil dibuat');
}
    
}
