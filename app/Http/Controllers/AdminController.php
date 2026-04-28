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
use App\Models\Ruangan;
use App\Models\Ujian;
use App\Models\Jadwal;
use App\Models\GuruMapel;
use App\Models\Mapel;
use App\Models\Pelanggaran;
use App\Models\banksoal;
use Carbon\Carbon;
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
// Query tetap sama
 $pelanggaranPerBulan = Pelanggaran::selectRaw(
    "strftime('%m', created_at) as bulan, COUNT(*) as total"
)
->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
->groupBy('bulan')
->pluck('total', 'bulan')
->toArray();

// ✅ PERBAIKAN BAGIAN LOOP INI
 $chartData = [];
for ($i = 1; $i <= 12; $i++) {
    // Ubah integer (1) menjadi string '01', '02', dst
    $keyBulan = sprintf("%02d", $i);
    
    $chartData[] = $pelanggaranPerBulan[$keyBulan] ?? 0;
}
$pelanggaranPerBulan = $chartData;
// Contoh hasil: [0, 0, 0, 5, 0, 0, 0, 0, 0, 10, 0, 0]

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
        "ujianReady", "ujianDraft", "ujianDone","pelanggaranPerBulan"
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
          "password" => "required|min:6",
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
      $request->validate(["password" => "required|min:6"]);
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
      $dat = Kelas::with("ruangan")->get();
      $siswa = Ruangan::all();
      return view("admin.kelas",compact("dat","ire","siswa"));
    }
    public function KelasCreate(Request $request)
    {
      $request->validate([
        "nama_kelas" => "required","ruangan_id" => "required"]);
      Kelas::create($request->all());
      return redirect()->route("admin.kelas")->with("success","Berhasil Menambah Kelas");
    }
    public function KelasUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_kelas" => "required","ruangan_id" => "required"]);
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
      $us = Mapel::findOrFail($id);
      
      $request->validate([
        "nama_mapel" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.mapel")->with("success","Berhasil Update Mapel");
    }
    public function MapelDestroy($id)
    {
      $hi = Mapel::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.mapel")->with("success","Berhasil hapus Mapel");
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
      return redirect()->route("admin.mapel")->with("success","Berhasil Menambah Mapel");
    }
public function RuangIndex()
    {
      $ire = Auth::user();
      $dat = Ruangan::all();
      return view("admin.ruangan",compact("dat","ire"));
    }
    public function RuangCreate(Request $request)
{
    $request->validate([
        "nama_ruang" => "required"
    ]);

    Ruangan::create([
        "nama_ruang" => $request->nama_ruang,
        "kode" => strtoupper(Str::random(6)),
        "kode_expired_at" => Carbon::now()->addHour(),
    ]);

    return redirect()->route("admin.ruangan")
        ->with("success", "Berhasil Menambah Ruangan");
}
    public function RuangUpdate(Request $request, $id)
{
    $ruangan = Ruangan::findOrFail($id);

    $request->validate([
        "nama_ruang" => "required"
    ]);

    $ruangan->update([
        "nama_ruang" => $request->nama_ruang,

        // 🔥 OPTIONAL: kalau mau kode selalu berubah saat update
        "kode" => strtoupper(Str::random(6)),
        "kode_expired_at" => Carbon::now()->addHour(),
    ]);

    return redirect()->route("admin.ruangan")
        ->with("success", "Berhasil Update Ruangan");
}
    public function RuangDestroy($id)
    {
      $hi = Ruangan::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.ruangan")->with("success","Berhasil hapus Ruangan");
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
    
    // 1. Tentukan waktu mulai dan selesai
    $waktuMulai = \Carbon\Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
    $waktuSelesai = $waktuMulai->copy()->addMinutes($uji->durasi);

    // 2. Cari GURU ACAK yang sedang tidak bertugas di waktu tersebut
    // Kita filter guru yang TIDAK punya jadwal bentrok
    $guruAcak = Guru::whereDoesntHave('pengawas.jadwal', function($query) use ($request, $waktuMulai, $waktuSelesai) {
        $query->where('tanggal', $request->tanggal)
              ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                  // Cek apakah waktu ujian baru bersinggungan dengan jadwal yang sudah ada
                  $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                    ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                    ->orWhere(function($inner) use ($waktuMulai, $waktuSelesai) {
                        $inner->where('waktu_mulai', '<=', $waktuMulai)
                              ->where('waktu_selesai', '>=', $waktuSelesai);
                    });
              });
    })
    ->inRandomOrder() // Langkah krusial: Mengacak urutan database
    ->first();

    // 3. Validasi jika tidak ada guru yang tersedia
    if (!$guruAcak) {
        return redirect()->back()->withErrors([
            'error' => "Gagal mengacak: Tidak ada guru yang tersedia di jam tersebut (semua sedang mengawas)."
        ])->withInput();
    }

    // 4. Cek Bentrok Kelas (Sama seperti kodemu sebelumnya)
    $existingJadwal = Jadwal::where('kelas_id', $request->kelas_id)
        ->where('tanggal', $request->tanggal)
        ->where('jam_mapel', $request->jam_mapel)
        ->first();
    
    if ($existingJadwal) {
        return redirect()->back()->withErrors([
            'error' => "Kelas ini sudah memiliki jadwal pada jam tersebut."
        ])->withInput();
    }

    // 5. Eksekusi Simpan Data
    // Pastikan relasi ke kelas di tabel pivot ujian_kelas tetap ada
    if (!$uji->kelas()->where('kelas_id', $request->kelas_id)->exists()) {
        $uji->kelas()->attach($request->kelas_id);
    }

    // Setup data pengawas dari hasil acak
    $pengawas = Pengawas::firstOrCreate([
        "guru_id" => $guruAcak->id,
        "user_id" => $guruAcak->user_id,
    ]);

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
        "status" => "ready",
    ]);

    return redirect()->route("admin-ops.set", ["id" => $request->kelas_id])
                     ->with('success', "Jadwal berhasil diacak! Pengawas terpilih: {$guruAcak->nama}");
}
 

public function operateDestroy($id)
{
    return DB::transaction(function () use ($id) {
        // Cari Jadwal
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            // Throw exception agar Rollback otomatis jika ingin, atau return JSON 404
            throw new \Exception('Jadwal tidak ditemukan');
        }

        // Ambil Ujian
        $ujian = $jadwal->ujian;

        // Update Status Ujian terlebih dahulu
        if ($ujian) {
            $ujian->jadwal_id = null;
            $ujian->status = 'draft';
            $ujian->save();
        }

        // Hapus Jadwal
        $jadwal->delete();

        // Jika sampai sini, semua sukses. 
        // Kembalikan JSON success: true
        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    });
}

public function operateUpdate(Request $request, $id)
{
    $jadwal = Jadwal::find($id);

    if (!$jadwal) {
        return response()->json(['success' => false, 'message' => 'Data jadwal tidak ditemukan'], 404);
    }

    // 1. Ambil Data Ujian Baru
    $uji = Ujian::find($request->ujian_id);
    if (!$uji) {
        return response()->json(['success' => false, 'message' => 'Ujian tidak ditemukan'], 404);
    }

    // 2. Hitung Waktu Berdasarkan Durasi Ujian Baru
    $waktuMulai = \Carbon\Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
    $waktuSelesai = $waktuMulai->copy()->addMinutes($uji->durasi);

    // 3. Cek Bentrok Kelas (Period/Jam Mapel) - Kecuali diri sendiri
    $existingJadwal = Jadwal::where('kelas_id', $request->kelas_id)
        ->where('tanggal', $request->tanggal)
        ->where('jam_mapel', $request->jam_mapel)
        ->where('id', '!=', $jadwal->id) // PENTING: Abaikan jadwal yang sedang diedit
        ->first();

    if ($existingJadwal) {
        return response()->json(['success' => false, 'message' => 'Kelas ini sudah memiliki jadwal lain pada jam tersebut.']);
    }

    // 4. LOGIKA PENENTUAN GURU PENGAWAS (MENGIKUTI REFERENCE CREATE)
    // Pertama, cek apakah Guru pengawas lama masih tersedia di jam baru
    $currentGuru = $jadwal->pengawas->guru;
    
    // Cek ketersediaan guru lama (exclude jadwal sendiri)
    $isGuruLamaAvailable = !$currentGuru->whereDoesntHave('pengawas.jadwal', function($query) use ($request, $waktuMulai, $waktuSelesai, $id) {
        $query->where('tanggal', $request->tanggal)
              ->where('id', '!=', $id) // Abaikan jadwal sendiri
              ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                  $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                    ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                    ->orWhere(function($inner) use ($waktuMulai, $waktuSelesai) {
                        $inner->where('waktu_mulai', '<=', $waktuMulai)
                              ->where('waktu_selesai', '>=', $waktuSelesai);
                    });
              });
    })->exists();

    if ($isGuruLamaAvailable) {
        $guruAcak = $currentGuru;
    } else {
        // Jika guru lama sibuk di jam baru, cari guru baru acak
        $guruAcak = Guru::whereDoesntHave('pengawas.jadwal', function($query) use ($request, $waktuMulai, $waktuSelesai) {
            $query->where('tanggal', $request->tanggal)
                  ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                      $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                        ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                        ->orWhere(function($inner) use ($waktuMulai, $waktuSelesai) {
                            $inner->where('waktu_mulai', '<=', $waktuMulai)
                                  ->where('waktu_selesai', '>=', $waktuSelesai);
                        });
                  });
        })
        ->inRandomOrder() // Langkah krusial: Mengacak urutan database
        ->first();
    }

    // 5. Validasi jika tidak ada guru yang tersedia
    if (!$guruAcak) {
        return response()->json(['success' => false, 'message' => 'Tidak ada guru yang tersedia di jam tersebut (Semua sedang bertugas).']);
    }

    // 6. Update Pengawas jika berubah
    if ($guruAcak->id !== $jadwal->pengawas->guru_id) {
        $pengawas = Pengawas::firstOrCreate([
            "guru_id" => $guruAcak->id,
            "user_id" => $guruAcak->user_id,
        ]);
        $jadwal->update(['pengawas_id' => $pengawas->id]);
    }

    // 7. Update Data Jadwal
    $jadwal->update([
        "jam_mapel"   => $request->jam_mapel,
        "tanggal"     => $request->tanggal,
        "ujian_id"    => $request->ujian_id,
        "waktu_mulai" => $waktuMulai,
        "waktu_selesai"=> $waktuSelesai,
    ]);

    // 8. Update Status Ujian
    $uji->update([
        "jadwal_id" => $jadwal->id,
        "status"    => "ready",
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Jadwal berhasil diperbarui!',
        'teacher_name' => $guruAcak->nama
    ]);
}




}
