<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    
// Karena Anda tahu pakai SQLite, langsung gunakan SQLite syntax
$pelanggaranPerBulan = Pelanggaran::selectRaw(
    "strftime('%m', created_at) as bulan, COUNT(*) as total"
)
->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
->groupBy('bulan')
->pluck('total', 'bulan')
->toArray();

// Format untuk chart (12 bulan)
$chartData = [];
for($i = 1; $i <= 12; $i++) {
    $chartData[] = $pelanggaranPerBulan[$i] ?? 0;
}

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
        "ujianReady", "ujianDraft", "ujianDone","chartData"
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
          $kata = Str::of($validated["nama"])->before(" ")->toString();
          $usrr = [
            "nama" => $validated["nama"],
            "password" => $ps,
            "role" => $validated["role"],
            "username"=>$kata,
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
          return redirect()->route("admin.index")->with("success","Berhasil Menambahkan User");
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
      $ps = Hash::make($request->password);
      $kata = Str::of($request->nama)->before(" ")->toString();
        $usef->update([
        "nama" =>$request->nama,
        "role" => $request->role,
        "username"=>$kata,
        
        ]);
        return redirect()->route("admin.index")->with("success","Berhasil Update User");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($admin)
    {
      $user = User::findOrFail($admin);
        $user->delete();
        return redirect()->route("admin.index")->with("success","berhasil delete user");
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
      return redirect()->route("admin.kelas")->with("success","Berhasil Menambah Kelas");
    }
    public function KelasUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_kelas" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.kelas")->with("success","Berhasil Update Kelas");
    }
    public function KelasDestroy($id)
    {
      $hi = Kelas::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.kelas")->with("success","Berhasil hapus Kelas");
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
      $hi = Mapel::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.mapel")->with("sip","Berhasil hapus Mapel");
    }
    public function AddGuru(Request $request)
{
    // Validasi input
    $request->validate([
        'guru_id' => 'required|exists:guru,id',
        'mapel_id' => 'required|exists:mapel,id'
    ]);
    
    // Cari guru beserta relasi mapelnya
    $guru = Guru::with('mapel')->find($request->guru_id);
    
    // Cek apakah guru sudah memiliki mapel ini
    if ($guru->mapel()->where('mapel_id', $request->mapel_id)->exists()) {
        return redirect()
            ->route('admin.mapel')
            ->with('error', 'Guru sudah terdaftar di mapel ini!');
    }
    
    // Jika belum ada, tambahkan relasi
    $guru->mapel()->attach($request->mapel_id);
    
    return redirect()
        ->route('admin.mapel')
        ->with('success', 'Berhasil menambahkan guru ke mapel');
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
    

    $existingJadwal = Jadwal::where('kelas_id', $request->kelas_id)
        ->where('tanggal', $request->tanggal)
        ->where('jam_mapel', $request->jam_mapel)
        ->first();
    
    if ($existingJadwal) {
        return redirect()->back()->withErrors([
            'error' => "Kelas ini sudah memiliki jadwal ujian pada tanggal {$request->tanggal} jam ke-{$request->jam_mapel}"
        ])->withInput();
    }
    

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
    

    $pengawas = Pengawas::where('guru_id', $request->guru_id)
        ->where('user_id', $guru->user_id)
        ->first();
    
    if (!$pengawas) {
        $pengawas = Pengawas::create([
            "guru_id" => $request->guru_id,
            "user_id" => $guru->user_id,
        ]);
    }
    

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
    $kelas = Kelas::with("siswa")->get();
    
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
        "status"=> "ready",
    ]);
    
    return redirect()->route("admin-ops.set", ["id" => $request->kelas_id])
                     ->with('success', 'Jadwal berhasil dibuat');
}
    
}
