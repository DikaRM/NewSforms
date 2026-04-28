<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Imports\BankSoalImport;
use App\Imports\BankSoalImportPreview;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Guru;
use App\Models\Pengawas;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Susulan;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Berita;
use App\Models\Nilai;
use App\Models\Peserta_ujian;
use App\Models\Pelanggaran;
use App\Models\banksoal ;
use App\Models\GuruMapel;
use App\Models\Ujian_soals;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $ire = Auth::user();
      $data = Guru::all();
        return view("admin.guru.index",compact("data","ire"));
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
        $request->validate(["password" => "required|min:6","nip" => "required|digits:18"]);
      $pass = Hash::make($request->password);
      $kata = Str::of($request->username)->before(" ")->toString();
      User::create([
        "nama" => $request->username,
        "password" => $pass,
        "role" => "guru",
        "username"=>$kata,
        ]);
      $whe = User::where("nama",$request->username)->first();
        Guru::create([
        "user_id" => $whe->id,
        "nama" => $request->username,
        "nip" => $request->nip,
        ]);
      return redirect()->route("admin.guru.index")->with("success","Berhasil Menambah Data");
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
    // Cari data Guru berdasarkan ID
    $guru = Guru::findOrFail($id);
    $request->validate(["password" => "required|min:6","nip" => "required|digits:18"]);
    // Cari User berdasarkan user_id dari tabel Guru
    $user = User::findOrFail($guru->user_id);
    $kata = Str::of($request->username)->before(" ")->toString();
    // Update data User
    $userData = [
        "nama" => $request->username,
        "username"=>$kata,
    ];
    
    // Only update password if provided
    if ($request->filled('password')) {
        $userData["password"] = Hash::make($request->password);
    }
    
    $user->update($userData);
    
    // Update data Guru
    $guru->update([
        "nama" => $request->username,
        "nip" => $request->nip,
    ]);
    
    // Optional: Add success message
    return redirect()->back()->with('success', 'Data berhasil diupdate');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $gu = Guru::findOrFail($id);
        
         $gu->delete();
        return redirect()->route("admin.guru.index")->with("success","Berhasil Dihapus");
    }
    public function TeachIndex()
{
    $ire = Auth::user();
    $dt = Guru::where("nama",$ire->nama)->first();
    
    // Data Ujian (Milik sendiri)
    $uji = Ujian::with("jadwal")->where("guru_id",$dt->id)->get();
    
    // Data Referensi
    $klas = Kelas::all();
    $sd = Guru::with("mapel")->where("id",$dt->id)->get();
    $guruMapel = GuruMapel::with("mapel")->where("guru_id",$dt->id)->get();
    $map = Mapel::all();
    $kelasList = Kelas::all();

    // --- KODE BARU: MENGAMBIL DATA NOTIFIKASI MENGAWAS ---
    // Ambil jadwal di mana guru ini bertindak sebagai pengawas

    $jadwalMengawas = Jadwal::with('ujian')
                            ->where('pengawas_id', $dt->id) // Pastikan kolom ini ada di tabel jadwal
                            ->orderBy('waktu_mulai', 'desc')
                            ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
    // -----------------------------------------------------

    return view("guru.index", compact("ire","uji","dt","klas","kelasList","sd","guruMapel", "notifData"));
}
    public function CreateUjian(Request $request)
{
    // Validasi
    $request->validate([
        'mapel_id' => 'required|exists:mapel,id',
        'nama' => 'required|exists:guru,nama',
        'nama_ujian' => 'required|string',
        'durasi' => 'required|integer|min:1',
        'grade' => 'nullable|string', // grade tetap string/text
        'kelas_id' => 'required|array', // tambahkan validasi untuk kelas_id
        'kelas_id.*' => 'exists:kelas,id',
        'catatan' => 'nullable|string'
    ]);

    $es = Guru::where("nama", $request->nama)->first();
    
    // Simpan data ujian
    $noub = Ujian::create([
        "mapel" => $request->mapel_id,
        "guru_id" => $es->id,
        "nama_ujian" => $request->nama_ujian,
        "durasi" => $request->durasi,
        "grade" => $request->grade, // grade tetap seperti semula
        "catatan" => $request->catatan,
        "status" => "draft",
    ]);
    
    // Simpan relasi many-to-many dengan kelas
    if ($request->has('kelas_id')) {
        $noub->kelas()->attach($request->kelas_id);
    }
    
    return redirect()->route("guru.create", ["id" => $noub->id])->with("success", "Berhasil Buat Ujian");
}
    public function Uji()
    {
      
      return view("guru.test",compact("ire","dt","uji","qs"));
    }
    public function CreateSoal(Request $request,$id)
    {
      $uji = Ujian::with("jadwal")->find($id);
      $fu = Ujian_soals::where("ujian_id",$uji->id)->get();
      $gurus = Guru::find($uji->guru_id);
      $mapel = Mapel::find($uji->mapel);
      $bak = banksoal::where("guru_id",$uji->guru_id)->get();
      return view("guru.create",compact("uji","gurus","bak","mapel","fu"));
    }
    public function rheina(Request $request)
    {
      $request->validate([
        "soal" => "required",
        "guru_id" => "required",
        "mapel_id" => "required",
        "jawaban_benar" => "required",
        "opsi_a" => "nullable",
        "opsi_b" => "nullable",
        "opsi_c" => "nullable",
        "opsi_d" => "nullable",
        ]);
        $bn = banksoal::where("guru_id",$request->guru_id)->latest();
        $soal = banksoal::create($request->all());
        $idea = $soal->id;
        Ujian_soals::create([
          "ujian_id" => $request->ujian_id,
          "bank_id" => $idea,
          ]);
          return redirect()->route("guru.create",['id' => $request->ujian_id]);
          return redirect()->route("guru.create",['id' => $request->ujian_id]);
    }
    public function bowl($id)
    {
      $ujian = Ujian::find($id);
      $ju = Ujian_soals::find($id2); 
      ujian->delete();
      if($ju){
      $ju->delete();
     
      }
      return redirect()->route("guru.create",['id' => $id]);
    }
   public function def(Request $request, $id)
{
   
    // 1. Validasi Dasar
    $rules = [
        'guru_id' => 'required|exists:guru,id',
        'mapel_id' => 'required|exists:mapel,id',
        'soal' => 'required|array|min:1',
        'soal.*.soal' => 'required|string',
        'soal.*.tipe' => 'required|in:pg,essay',
        'soal.*.gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
    


    // 2. Validasi Dinamis untuk Jawaban & Opsi
    foreach ($request->soal as $index => $val) {
        $tipe = $val['tipe'] ?? 'pg';

        // Jawaban Benar Wajib ada (baik PG maupun Essay)
        $rules['soal.'.$index.'.jawaban_benar'] = 'required|string';

        if ($tipe === 'pg') {
            // Jika PG: Opsi A, B, C WAJIB
            $rules['soal.'.$index.'.opsi_a'] = 'required|string';
            $rules['soal.'.$index.'.opsi_b'] = 'required|string';
            $rules['soal.'.$index.'.opsi_c'] = 'required|string';
            // Opsi D, E Opsional (nullable)
            $rules['soal.'.$index.'.opsi_d'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_e'] = 'nullable|string';
        } else {
            // Jika ESSAY: SEMUA Opsi boleh kosong
            $rules['soal.'.$index.'.opsi_a'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_b'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_c'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_d'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_e'] = 'nullable|string';
        }
    }

    $request->validate($rules);

    try {
        DB::beginTransaction();
        
        $ujian = Ujian::findOrFail($id);

        foreach ($request->soal as $index => $soalData) {
            $tipe = $soalData['tipe'] ?? 'pg';

            // Handle Gambar
            $gambarPath = null;
            if (isset($soalData['gambar']) && $soalData['gambar']->isValid()) {
                $gambarPath = $soalData['gambar']->store('soal_images', 'public');
            }

            // Persiapan Data Soal
            $insertData = [
                'guru_id' => $request->guru_id,
                'mapel_id' => $request->mapel_id,
                'soal' => $soalData['soal'],
                'gambar' => $gambarPath,
                'tipe' => $tipe,
                'jawaban_benar' => $soalData['jawaban_benar'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Handle Opsi berdasarkan Tipe
            if ($tipe === 'pg') {
                // Ambil semua opsi (D & E boleh kosong/tidak ada)
                $insertData['opsi_a'] = $soalData['opsi_a'] ?? '';
                $insertData['opsi_b'] = $soalData['opsi_b'] ?? '';
                $insertData['opsi_c'] = $soalData['opsi_c'] ?? '';
                $insertData['opsi_d'] = $soalData['opsi_d'] ?? '';
                $insertData['opsi_e'] = $soalData['opsi_e'] ?? '';
            } else {
                // Kosongkan semua opsi jika Essay
                $insertData['opsi_a'] = null;
                $insertData['opsi_b'] = null;
                $insertData['opsi_c'] = null;
                $insertData['opsi_d'] = null;
                $insertData['opsi_e'] = null;
            }

            // Simpan Soal & Dapat ID
            $newSoal = banksoal::create($insertData);

            // Simpan Relasi (Pivot)
            Ujian_soals::create([
                'ujian_id' => $ujian->id,
                'bank_id' => $newSoal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update Status Ujian
        $ujian->update(['status' => 'ready']);

        DB::commit();

        return redirect()->route('guru.index')
            ->with('success', "✅ Berhasil menyimpan " . count($request->soal) . " soal.");

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Jika ada gambar yang sudah terupload tapi rollback, hapus file-nya
        // (Opsional, perlu logic yang lebih kompleks untuk tracking file per loop)
        
        return redirect()->back()
            ->with('error', '❌ Gagal menyimpan soal: ' . $e->getMessage())
            ->withInput();
    }
}
    
    // Method untuk update soal dengan gambar
    public function updateSoal(Request $request, $id)
    {
        $request->validate([
            'soal' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $bankSoal = banksoal::findOrFail($id);
            
            // Upload gambar baru jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($bankSoal->gambar) {
                    Storage::disk('public')->delete($bankSoal->gambar);
                }
                
                $gambarPath = $request->file('gambar')->store('soal_images', 'public');
                $bankSoal->gambar = $gambarPath;
            }
            
            // Update data
            $bankSoal->update([
                'soal' => $request->soal,
                'opsi_a' => $request->opsi_a,
                'opsi_b' => $request->opsi_b,
                'opsi_c' => $request->opsi_c,
                'opsi_d' => $request->opsi_d,
                'jawaban_benar' => $request->jawaban_benar,
            ]);

            return redirect()->back()->with('success', 'Soal berhasil diupdate');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update soal: ' . $e->getMessage());
        }
    }
    
    // Method untuk hapus soal dengan gambar
    public function hapus($id)
    {
        try {
            $bankSoal = banksoal::findOrFail($id);
            
            // Hapus gambar dari storage
            if ($bankSoal->gambar) {
                Storage::disk('public')->delete($bankSoal->gambar);
            }
            
            // Hapus relasi di ujian_soals
            Ujian_soals::where('bank_id', $id)->delete();
            
            // Hapus soal
            $bankSoal->delete();
            
            return redirect()->back()->with('success', 'Soal berhasil dihapus');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus soal: ' . $e->getMessage());
        }
    }

    public function result(){
      $ire = Auth::user();
       $dt = Guru::where("nama",$ire->nama)->first();
      $data = Ujian::where("guru_id",$dt->id)->get();
      $val = Peserta_ujian::with("siswa");
     
      $jadwalMengawas = Jadwal::with('ujian')
                            ->where('pengawas_id', $dt->id) // Pastikan kolom ini ada di tabel jadwal
                            ->orderBy('waktu_mulai', 'desc')
                            ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
      return view("guru.hasil",compact("data","ire","val","dt","notifData"));
    }
    public function hasil($id)
{
    $ujian = Ujian::with('jadwal')->findOrFail($id);
    $ire = Auth::user();
    $dt = Guru::where("nama",$ire->nama)->first();
      $data = Ujian::where("guru_id",$dt->id)->get();
      $val = Peserta_ujian::with("siswa");
      
      $jadwalMengawas = Jadwal::with('ujian')
                            ->where('pengawas_id', $dt->id) // Pastikan kolom ini ada di tabel jadwal
                            ->orderBy('waktu_mulai', 'desc')
                            ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
    // 1. Data peserta yang sudah mengerjakan (nilai sudah ada)
    $pesertaUjian = Peserta_ujian::with([
        'siswa.kelas',
        'siswa.pelanggaran' => function($q) use ($id) {
            $q->where('ujian_id', $id); // Filter kecurangan per ujian
        }
    ])->where('ujian_id', $id)->get();
    
    // 2. Data siswa susulan
    $siswaSusulan = Susulan::with('siswa.kelas')
        ->where('ujian_id', $id)
      ->get();
    
    // 3. Data absensi (sakit/izin/alfa)
    $absensi = Absensi::where('ujian_id', $id)
        ->get()
        ->keyBy('siswa_id');
    
    // 4. Semua siswa di kelas ini
    $kelasId = $ujian->jadwal?->kelas_id;

$siswa = Siswa::with('kelas')
    ->when($kelasId, function($q) use ($kelasId) {
        $q->where("kelas_id", $kelasId);
    })
    ->get();
    
    // 5. Gabungkan semua data ke dalam satu collection
    $hasilUjian = $siswa->map(function($siswa) use ($pesertaUjian, $siswaSusulan, $absensi, $id) {
        $peserta = $pesertaUjian->firstWhere('siswa_id', $siswa->id_siswa);
        $susulan = $siswaSusulan->firstWhere('siswa_id', $siswa->id_siswa);
        $absen = $absensi->get($siswa->id_siswa);
        
        // Tentukan status kehadiran
        if($peserta) {
            $status_kehadiran = 'hadir';
            $nilai = $peserta->nilai;
            $pelanggaran = $peserta->siswa->pelanggaran;
        } elseif($susulan) {
            $status_kehadiran = 'susulan';
            $nilai = null;
            $pelanggaran = collect();
        } elseif($absen) {
            $status_kehadiran = $absen->status_kehadiran; // sakit/izin/alfa
            $nilai = null;
            $pelanggaran = collect();
        } else {
            $status_kehadiran = 'alfa';
            $nilai = null;
            $pelanggaran = collect();
        }
        
        return (object) [
            'siswa' => $siswa,
            'status_kehadiran' => $status_kehadiran,
            'nilai' => $nilai,
            'pelanggaran' => $pelanggaran,
            'waktu_absen' => $absen?->waktu_absen,
        ];
    });
    
    $berita = Berita::where('ujian_id', $id)->get();
    
    return view('guru.result', compact(
        'pesertaUjian',  // Ganti pesertaUjian dengan ini
        'ujian', 
        'berita', 
        'siswaSusulan',
        'siswa',
        'absensi',
        'dt',
        'ire',
        'notifData'
    ));
}

// Method untuk a kecurangan
public function catatKecurangan(Request $request)
{
    

    // 1. Simpan Data Pelanggaran
    \App\Models\Pelanggaran::create([
        'ujian_id' => $request->ujian_id,
        'siswa_id' => $request->siswa_id,
        'jenis_pelanggaran' => $request->jenis_pelanggaran,
        'waktu_kejadian' => \Carbon\Carbon::now(),
    ]);

    // 2. LOGIKA SKORS NILAI (Updated)
    // Jika ada nilai skors, kurangi nilai siswa
    if ($request->has('skors_nilai') && $request->skors_nilai != null) {
        $skorsNilai = $request->skors_nilai;
        
        // Ambil nilai sekarang
        $peserta = \App\Models\Peserta_ujian::where('ujian_id', $request->ujian_id)
                                     ->where('siswa_id', $request->siswa_id)
                                     ->first();
        
        if ($peserta) {
            // Kurangi nilai (tidak langsung ke 0)
            $nilaiBaru = max(0, $peserta->nilai - $skorsNilai);
            
            // Update nilai
            $peserta->update(['nilai' => $nilaiBaru]);
        }
    }

    return redirect()->back()->with('success', 'Pelanggaran berhasil dicatat.');
}
    
    public function riwayat(){
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
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
      $data = Ujian::with("jadwal")->where("guru_id",$dt->id)->get();
      return view("guru.riwayat",compact("data","ire","dt","notifData"));
    }
   public function jadwal()
{
    $ire = Auth::user();
    $dt = Guru::where("nama", $ire->nama)->first();
    $jadwalMengawas = Jadwal::with('ujian')
                            ->where('pengawas_id', $dt->id) // Pastikan kolom ini ada di tabel jadwal
                            ->orderBy('waktu_mulai', 'desc')
                            ->get();
    // Format data menjadi array rapi untuk JavaScript
    $notifData = $jadwalMengawas->map(function($item) {
        return [
            'id'        => $item->id,
            'title'     => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'time'      => \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i'),
            'unread'    => true // Default true agar muncul badge merah
        ];
    });
    // Jika data guru tidak ditemukan, abort biar aman
    if (!$dt) {
        abort(403, 'Data profil guru tidak ditemukan di database.');
    }

    // AMBIL JADWAL HANYA YANG DIAWASI OLEH GURU INI
    // Tambahkan relasi "pengawas.guru" agar tidak error di view & tidak query berulang kali (N+1)
    $data = Jadwal::with("kelas", "ujian.mapels", "pengawas.guru")
                ->whereHas('pengawas', function ($query) use ($dt) {
                    // Filter berdasarkan ID guru yang sedang login
                    $query->where('guru_id', $dt->id);
                })
                ->orderBy('tanggal', 'desc') // Urutkan dari jadwal terbaru
                ->get();

    return view("guru.jadwal", compact("data", "ire", "dt","notifData"));
}
    public function sed(Request $request){
      
      return redirect()->route("guru.index");
    }
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
            'mapel_id' => 'required|exists:mapel,id'
        ]);

        $guru_id = $request->guru_id; // sesuaikan dengan auth Anda

        Excel::import(
            new BankSoalImport($guru_id, $request->mapel_id),
            $request->file('file_excel')
        );

        return redirect()->back()->with('success', 'Soal berhasil diimport!');
    }
    
public function preview(Request $request)
{
    try {
        \Log::info('=== PREVIEW START ===');
        
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
            'uji_id' => 'required'
        ]);
        
        $file = $request->file('file_excel');
        
        // Baca file jadi array
        $data = Excel::toArray([], $file);
        
        \Log::info('Total sheets: ' . count($data));
        \Log::info('Sheet pertama jumlah baris: ' . count($data[0] ?? []));
        
        if (empty($data) || empty($data[0])) {
            return response()->json([
                'success' => false,
                'message' => 'File kosong atau tidak dapat dibaca'
            ]);
        }
        
        $rows = $data[0];
        
        // LOG: Lihat 5 baris pertama (termasuk header)
        \Log::info('5 Baris pertama Excel (termasuk header):', array_slice($rows, 0, 5));
        
        // Hapus header (baris pertama)
        $header = array_shift($rows);
        \Log::info('Header yang dihapus:', $header);
        
        $soalList = [];
        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;
            
            $soal = [
                'soal' => $row[0] ?? null,
                'opsi_a' => $row[1] ?? null,
                'opsi_b' => $row[2] ?? null,
                'opsi_c' => $row[3] ?? null,
                'opsi_d' => $row[4] ?? null,
                'opsi_e' => $row[5] ?? null,
                'jawaban_benar' => $row[6] ?? null,
                'tipe' => $row[7] ?? 'pg',
            ];
            
            // LOG setiap soal
            \Log::info("Soal baris " . ($index + 2) . ":", $soal);
            
            $soalList[] = $soal;
        }
        
        // Filter soal kosong
        $soalList = array_values(array_filter($soalList, fn($s) => !empty($s['soal'])));
        
        \Log::info('Total soal valid: ' . count($soalList));
        
        if (empty($soalList)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data soal yang valid'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'rows' => $soalList,
                'total' => count($soalList)
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Preview Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

public function confirm(Request $request)
{
    try {
        \Log::info('=== CONFIRM START ===');
        \Log::info('Data received:', $request->all());
        
        $request->validate([
            'uji_id' => 'required|exists:ujian,id',
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
            'soal_data' => 'required|array|min:1',
        ]);
        
        // LOG: Cek isi soal_data
        \Log::info('Total soal diterima: ' . count($request->soal_data));
        \Log::info('Contoh soal pertama:', $request->soal_data[0] ?? []);
        \Log::info('Opsi A dari soal pertama: ' . ($request->soal_data[0]['opsi_a'] ?? 'KOSONG'));
        \Log::info('Opsi B dari soal pertama: ' . ($request->soal_data[0]['opsi_b'] ?? 'KOSONG'));
        
        DB::beginTransaction();
        
        // Cari ujian
        $ujian = Ujian::findOrFail($request->uji_id);
        
        // ============================================
        // BATCH INSERT BANK SOAL
        // ============================================
        $bankData = [];
        $soalList = $request->soal_data;
        
        foreach ($soalList as $index => $soal) {
            // Validasi per soal
            if (empty($soal['soal'])) {
                throw new \Exception('Soal nomor ' . ($index + 1) . ' tidak boleh kosong');
            }
            
            // LOG: Data yang akan disimpan
            \Log::info("Menyimpan soal ke-$index:", [
                'opsi_a' => $soal['opsi_a'] ?? null,
                'opsi_b' => $soal['opsi_b'] ?? null,
                'opsi_c' => $soal['opsi_c'] ?? null,
                'opsi_d' => $soal['opsi_d'] ?? null,
                'opsi_e' => $soal['opsi_e'] ?? null,
            ]);
            
            $bankData[] = [
                'guru_id' => $request->guru_id,
                'mapel_id' => $request->mapel_id,
                'soal' => $soal['soal'],
                'gambar' => $soal['gambar'] ?? null,
                'opsi_a' => $soal['opsi_a'] ?? null,
                'opsi_b' => $soal['opsi_b'] ?? null,
                'opsi_c' => $soal['opsi_c'] ?? null,
                'opsi_d' => $soal['opsi_d'] ?? null,
                'opsi_e' => $soal['opsi_e'] ?? null,
                'jawaban_benar' => strtoupper($soal['jawaban_benar'] ?? ''),
                'tipe' => $soal['tipe'] ?? 'pg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Batch insert ke tabel bank
    foreach ($bankData as $data) {
    DB::table('bank')->insert($data);
}
        
        // ============================================
        // Ambil ID bank yang baru diinsert
        // ============================================
        $lastId = banksoal::latest()->first()->id;
        $totalSoal = count($bankData);
        $newBankIds = range($lastId - $totalSoal + 1, $lastId);
        
        // ============================================
        // BATCH INSERT KE PIVOT (ujian_soals)
        // ============================================
        $relasiData = [];
        foreach ($newBankIds as $bankId) {
            $relasiData[] = [
                'ujian_id' => $ujian->id,
                'bank_id' => $bankId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert ke tabel pivot
        Ujian_soals::insert($relasiData);
        
        // ============================================
        // Update status ujian
        // ============================================
        $ujian->update(['status' => 'ready']);
        
        DB::commit();
        
        // Log success
        \Log::info('Import Excel berhasil', [
            'total_soal' => $totalSoal,
            'uji_id' => $request->uji_id,
            'total_query' => 3
        ]);
        
        return response()->json([
            'success' => true,
            'imported' => $totalSoal,
            'message' => "✅ Berhasil mengimport {$totalSoal} soal ke ujian " . $ujian->nama_ujian,
            'redirect_url' => route('guru.index')
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Confirm Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
public function bareroll(Request $request)
{
    // Validasi input
    // 'siswa_ids' kita biarkan validasinya, meskipun tidak kita pakai untuk update DB saat ini
    
    
    DB::beginTransaction();
    
    try {
        
        // Ambil data ujian
        $uji = Ujian::findOrFail($request->ujian_id);
        
        // Cek relasi kelas (opsional)
        if (!$uji->kelas()->where('kelas_id', $request->kelas_id)->exists()) {
            $uji->kelas()->attach($request->kelas_id);
        }
        
        // 1. ACAK PENGAWAS SECARA OTOMATIS
        $guru = Guru::inRandomOrder()->first();
        if (!$guru) {
            throw new \Exception("Gagal mengacak pengawas. Belum ada data guru di database.");
        }
        
        $pengawas = Pengawas::firstOrCreate(
            [
                'guru_id' => $guru->id,
                'user_id' => $guru->user_id,
            ]
        );
        
        // 2. HITUNG WAKTU
        $tanggalSekarang = \Carbon\Carbon::now()->format('Y-m-d');
        $waktuMulai = \Carbon\Carbon::parse($tanggalSekarang . ' ' . $request->waktu_mulai);
        $waktuSelesai = \Carbon\Carbon::parse($tanggalSekarang . ' ' . $request->waktu_selesai);
        
        // 3. SIMPAN JADWAL
        // PENTING: 'ujian_id' dimasukkan ke array pertama agar unik tiap ujian.
        // Jika tidak ada, jadwal Ujian A akan tertimpa oleh Ujian B jika kelas & tanggalnya sama.
        $jads = Jadwal::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'ujian_id' => $request->ujian_id, // AGAR UNIK PER UJIAN
            ],
            [
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'tanggal' => $tanggalSekarang,
                'keterangan' => $request->keterangan,
                'untuk_susulan' => 1,
                'pengawas_id' => $pengawas->id,
            ]
        );
        
        // ==========================================
        // 4. UPDATE UJIAN (Sesuai Permintaan)
        // ==========================================
        // Anda bilang "Di ujian itu butuh jadwal_id", jadi kita update.
        $uji->update([
            "jadwal_id" => $jads->id, // Update ke ID jadwal susulan yang baru dibuat
            "status" => "ready",
        ]);
        if ($request->has('siswa_ids') && is_array($request->siswa_ids)) {

    Siswa::whereIn('id_siswa', $request->siswa_ids)
        ->update([
            'status' => 'ready'
        ]);
}
        DB::commit();
        
        $message = $jads->wasRecentlyCreated 
            ? "Jadwal susulan hari ini berhasil dibuat! (Pengawas: {$guru->nama})" 
            : "Jadwal susulan hari ini berhasil diperbarui! (Pengawas: {$guru->nama})";
        
        return redirect()->route("guru.hasil", ["id" => $request->ujian_id])
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors([
            'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ])->withInput();
    }
}
    

    public function updateJadwalSusulan(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'pengawas_id' => 'required',
        ]);
        
        DB::beginTransaction();
        
        try {
            $jadwal = Jadwal::findOrFail($id);
            $uji = Ujian::findOrFail($jadwal->ujian_id);
            
            // Hitung waktu
            $waktuMulai = Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($uji->durasi);
            
            // Cek bentrok (kecuali dengan dirinya sendiri)
            $bentrok = Jadwal::where('kelas_id', $jadwal->kelas_id)
                ->where('id', '!=', $id)
                ->where('tanggal', $request->tanggal)
                ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                    $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai]);
                })
                ->exists();
            
            if ($bentrok) {
                return back()->with('error', 'Waktu bentrok dengan jadwal lain di kelas ini!');
            }
            
            // Update jadwal
            $jadwal->update([
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'pengawas_id' => $request->pengawas_id,
                'keterangan' => $request->keterangan
            ]);
            
            DB::commit();
            
            return redirect()->route('guru.hasil', $jadwal->ujian_id)
                ->with('success', 'Jadwal susulan berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update jadwal: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus jadwal susulan
     */
    public function destroyJadwalSusulan($id)
    {
        DB::beginTransaction();
        
        try {
            $jadwal = Jadwal::findOrFail($id);
            $ujianId = $jadwal->ujian_id;
            Susulan::where('jadwal_id', $id)->update([
                'jadwal_id' => null,
                'status' => 'menunggu'
            ]);
            
            $jadwal->delete();
            
            DB::commit();
            
            return redirect()->route('guru.hasil', $ujianId)
                ->with('success', 'Jadwal susulan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus jadwal: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan form buat jadwal susulan
     */
    public function formJadwalSusulan($ujianId)
    {
        $ujian = Ujian::with('jadwal')->findOrFail($ujianId);
        
        // Ambil siswa yang mengajukan susulan
        $siswaSusulan = Susulan::with('siswa')
            ->where('ujian_id', $ujianId)
            ->get();
        
        // Ambil daftar pengawas
        $pengawas = User::where('role', 'guru')->get();
        
        // Ambil kelas yang terkait dengan ujian
        $kelas = $ujian->kelas;
        
        return view('guru.form-jadwal-susulan', compact('ujian', 'siswaSusulan', 'pengawas', 'kelas'));
    }
    public function updateNilai(Request $request) {
    try {
        // 1. Validasi
        $request->validate([
            'peserta_id' => 'required',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // 2. Cari data - Coba ganti find ke findOrFail agar error tertangkap catch
        // Pastikan nama modelnya benar (Peserta_ujian atau PesertaUjian)
        $peserta = \App\Models\Peserta_ujian::findOrFail($request->peserta_id);
        
        $peserta->nilai = $request->nilai;
        $saved = $peserta->save();

        if ($saved) {
            return response()->json([
                'success' => true, 
                'message' => 'Nilai berhasil diupdate ke ' . $request->nilai
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal simpan'], 500);

    } catch (\Exception $e) {
        // Ini akan mengirimkan pesan error PHP asli ke console browser kamu
        return response()->json([
            'success' => false, 
            'message' => 'Error Server: ' . $e->getMessage()
        ], 500);
    }
}
    
}
