<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengawas;
use App\Models\Absensi;
use App\Models\Susulan;
use App\Models\Ruangan;
use App\Models\BlockSiswa;
use App\Models\Jadwal;
use App\Models\Berita;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class PengawasController
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
      $id = $id;
      $data = Pengawas::with("user","guru")->where("guru_id",$id)->get();
      
      if(!$data){
        return redirect()->route("guru.index");
      }
      $datas = $data->pluck("id");
      $jads = Jadwal::with("ujian")
    ->whereIn("pengawas_id", $datas)
    ->where("waktu_selesai", ">", now())
    ->orderBy("waktu_selesai", 'asc')
    ->get();
      $sis = Siswa::with("kelas")->get();
      $ire = Auth::user();
   $dt = Guru::where("nama",$ire->nama)->first();
    

if(!$dt){
    abort(403, 'Guru tidak ditemukan');
}
     $jadwalMengawas = Jadwal::with('ujian','kelas.ruangan')
    ->where('pengawas_id', $dt->id)
    
    ->whereHas('ujian', function($q){
        $q->where('status', 'ready');
    })
    
    ->orderBy('waktu_mulai', 'asc')
    ->take(3)
    ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'kelas' => optional($item->kelas)->nama_kelas . ' - ' . optional($item->kelas->ruangan)->nama_ruang,
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->locale("id")->translatedformat('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
        return view("pengawas.index",compact('data','jads','sis',"id","ire","dt","notifData"));
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
    // 1. Validasi Input (Optional tapi disarankan)
    $request->validate([
        'ujian_id' => 'required',
        'kelas_id' => 'required',
        'catatan' => 'required'
    ]);

    // 2. Cari Jadwal yang sesuai dengan ujian_id dan kelas_id
    // Kita tidak perlu cari Ujian dulu, langsung cari Jadwal saja biar cepat
    $js = Jadwal::where('ujian_id', $request->ujian_id)
                 ->where('kelas_id', $request->kelas_id)
                 ->first();

    // Jika jadwal tidak ditemukan
    if (!$js) {
        return response()->json([
            'success' => false,
            'message' => 'Jadwal tidak ditemukan untuk ujian dan kelas tersebut.'
        ], 404);
    }

    // 3. Simpan Data ke Database
    Berita::create([
        "kelas_id"     => $request->kelas_id,
        "ujian_id"     => $request->ujian_id,
        "pengawas_id"  => $js->pengawas_id, // Ambil pengawas dari tabel jadwal
        "catatan"      => $request->catatan,
    ]);
    
    // 4. W A J I B: Kembalikan Response JSON (Bukan Redirect)
    // Karena Frontend menggunakan Javascript (Fetch), dia hanya mengerti JSON
    return response()->json([
        'success' => true, 
        'message' => 'Berita acara berhasil disimpan!'
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $jadk = Jadwal::findOrFail($id);

    $data = Siswa::with("kelas")
        ->where("kelas_id", $jadk->kelas_id)
        ->orderBy('nomor_absen', 'asc')
        ->get();

    $pelan = Pelanggaran::with("siswa")->get();

    $da = Pengawas::with("user","guru")
        ->where('id', $jadk->pengawas_id)
        ->first();

    $berita = Berita::where('ujian_id', $jadk->ujian_id)
        ->where('kelas_id', $jadk->kelas_id)
        ->first();

    $ruanganValid = session('ruangan_valid_'. $jadk->id);
    $ire = Auth::user();
    $dt = Guru::where("nama",$ire->nama)->first();
      $jadwalMengawas = Jadwal::with('ujian')
                            ->where('pengawas_id', $dt->id) // Pastikan kolom ini ada di tabel jadwal
                            ->orderBy('waktu_mulai', 'desc')
                            ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->locale("id")->translatedformat('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
    return view("pengawas.main", compact(
        "jadk","data","pelan","da","berita","ruanganValid","dt","ire","notifData"
    ));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
  public function abcent(Request $request)
{
    foreach ($request->siswa_id as $siswaId) {
    $statusKehadiran = $request->status[$siswaId] ?? null;

    Absensi::updateOrCreate(
        [
            'ujian_id' => $request->ujian_id,
            'siswa_id' => $siswaId,
        ],
        [
            'kelas_id' => $request->kelas_id,
            'status_kehadiran' => $statusKehadiran,
            'waktu_absen' => now(),
        ]
    );

    if ($statusKehadiran == 'hadir') {
        Siswa::where('id_siswa', $siswaId)->update(['status' => 'ready']);
    } else {
        Susulan::updateOrCreate(
            [
                'siswa_id' => $siswaId,
                'ujian_id' => $request->ujian_id,
            ],
            [
                'kelas_id' => $request->kelas_id,
                'alasan' => $statusKehadiran,
            ]
        );
    }
}
 return response()->json([
        'success' => true,
        'message' => 'Absensi berhasil disimpan'
    ]);
}

public function checkRuangan(Request $request, $id)
{
    $jadk = Jadwal::findOrFail($id);

    $ruangan = Ruangan::where('kode', $request->kode_ruangan)->first();

    if(!$ruangan){
        return response()->json([
            'success' => false,
            'message' => 'Kode ruangan tidak ditemukan'
        ]);
    }

    $valid = Kelas::where('id', $jadk->kelas_id)
        ->where('ruangan_id', $ruangan->id)
        ->exists();

    if(!$valid){
        return response()->json([
            'success' => false,
            'message' => 'Ruangan tidak sesuai kelas ini'
        ]);
    }

    session(['ruangan_valid_'.$id => true]);

    return response()->json([
        'success' => true
    ]);
}
// app/Http/Controllers/PengawasController.php

public function unblockSiswa($siswa_id, $ujian_id)
{
    try {
        // Cari siswa berdasarkan id_siswa (bukan id)
        $siswa = \App\Models\Siswa::where('id_siswa', $siswa_id)->first();
        
        if (!$siswa) {
            return response()->json([
                'success' => false, 
                'message' => 'Siswa tidak ditemukan'
            ]);
        }
        
        // Cari data block
        $block = \App\Models\BlockSiswa::where('siswa_id', $siswa->id_siswa)
            ->where('ujian_id', $ujian_id)
            ->first();
        
        if ($block) {
            $block->update([
                'violation_count' => 0,
                'blocked_at' => null,
                'expires_at' => null,
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Siswa berhasil diunblock. Siswa dapat melanjutkan ujian.'
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Data block tidak ditemukan'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

}
