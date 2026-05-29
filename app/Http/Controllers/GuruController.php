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
use App\Models\Jawaban_Siswa;
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
use Illuminate\Support\Facades\Validator;
class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $ire = Auth::user();
      $query = Guru::with("user");
       if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('nama', 'ilike', '%' . $request->search . '%')
              ->orWhere('nip', 'ilike', '%' . $request->search . '%');
        });
    }
      $data = $query->paginate(10);
      $isSearching = $request->filled('search');
        return view("admin.guru.index",compact("data","ire","isSearching"));
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
        "username" => "required",
        "password" => "required|min:6",
        "nip" => "required|digits:18"
    ]);

    // Pecah nama
    $namaParts = collect(explode(' ', trim($request->username)))
        ->map(fn($item) => strtolower(trim($item)))
        ->filter(fn($item) => strlen($item) > 2)
        ->values();

    // Fallback
    if ($namaParts->isEmpty()) {
        $namaParts = collect(['guru']);
    }

    // Default username
    $username = $namaParts[0];

    $found = false;

    // Cari username yang tersedia
    foreach ($namaParts as $part) {

        $existsUser = User::whereRaw('LOWER(username) = ?', [$part])->exists();

        if (!$existsUser) {
            $username = $part;
            $found = true;
            break;
        }
    }

    // Kalau semua sudah dipakai
    if (!$found) {

        $baseUsername = $namaParts[0];
        $i = 1;

        while (true) {

            $candidate = $baseUsername . $i;

            $existsUser = User::whereRaw('LOWER(username) = ?', [$candidate])->exists();

            if (!$existsUser) {
                $username = $candidate;
                break;
            }

            $i++;
        }
    }

    // Create user
    $user = User::create([
        "nama" => $request->username,
        "password" => Hash::make($request->password),
        "role" => "guru",
        "username" => $username,
    ]);

    // Create guru
    Guru::create([
        "user_id" => $user->id,
        "nama" => $request->username,
        "nip" => $request->nip,
    ]);

    return redirect()
        ->route("admin.guru.index")
        ->with("success", "Berhasil Menambah Data");
}

    /**
     * Display the specified resource.
     */
   public function detail($id)
{
    // Ambil data ujian + soal-soalnya (eager loading via pivot)
    $ire = Auth::user();
    $ujian = Ujian::with('soals')->findOrFail($id);
    $gurus = Guru::find($ujian->guru_id);
    return view('guru.detail', compact('ujian','ire','gurus'));
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
    // Cari data guru
    $guru = Guru::findOrFail($id);

    // Validasi
    $request->validate([
        "username" => "required",
        "password" => "nullable|min:6",
        "nip" => "required|digits:18"
    ]);

    // Cari user terkait
    $user = User::findOrFail($guru->user_id);

    // Pecah nama
    $namaParts = collect(explode(' ', trim($request->username)))
        ->map(fn($item) => strtolower(trim($item)))
        ->filter(fn($item) => strlen($item) > 2)
        ->values();

    // Fallback
    if ($namaParts->isEmpty()) {
        $namaParts = collect(['guru']);
    }

    // Default username
    $username = $namaParts[0];

    $found = false;

    // Cari username tersedia
    foreach ($namaParts as $part) {

        $existsUser = User::whereRaw('LOWER(username) = ?', [$part])
            ->where('id', '!=', $user->id)
            ->exists();


        if (!$existsUser) {
            $username = $part;
            $found = true;
            break;
        }
    }

    // Kalau semua sudah dipakai
    if (!$found) {

        $baseUsername = $namaParts[0];
        $i = 1;

        while (true) {

            $candidate = $baseUsername . $i;

            $existsUser = User::whereRaw('LOWER(username) = ?', [$candidate])
                ->where('id', '!=', $user->id)
                ->exists();

            if (!$existsUser ) {
                $username = $candidate;
                break;
            }

            $i++;
        }
    }

    // UPDATE USER
    $userData = [
        "nama" => $request->username,
        "username" => $username,
    ];

    // Update password jika diisi
    if ($request->filled('password')) {
        $userData["password"] = Hash::make($request->password);
    }

    $user->update($userData);

    // UPDATE GURU
    $guru->update([
        "nama" => $request->username,
        "nip" => $request->nip,
    ]);

    return redirect()
        ->back()
        ->with('success', 'Data berhasil diupdate');
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
    $uji = Ujian::with("jadwal")
        ->where("guru_id", $dt->id)
        ->latest()
        ->limit(3)
        ->get();
    
    // Data Referensi
    $klas = Kelas::all();
    $sd = Guru::with("mapel")->where("id",$dt->id)->get();
    $guruMapel = GuruMapel::with("mapel")->where("guru_id",$dt->id)->get();
    $map = Mapel::all();
    $kelasList = Kelas::all();

    // --- KODE BARU: MENGAMBIL DATA NOTIFIKASI MENGAWAS ---
    // Ambil jadwal di mana guru ini bertindak sebagai pengawas

    $jadwalMengawas = Jadwal::with('ujian','kelas.ruangan')
    ->where('pengawas_id', $dt->id)
    ->whereDate('tanggal', now())
    ->orderBy('waktu_mulai', 'asc')
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
        'mode' => 'required|in:cbt,praktik',
        'grade' => 'nullable|string',
        'kelas_id' => 'required|array',
        'kelas_id.*' => 'exists:kelas,id',
        'catatan' => 'nullable|string'
    ]);

    $guru = Guru::where("nama", $request->nama)->first();
    
    // Simpan data ujian
    $ujian = Ujian::create([
        "mapel" => $request->mapel_id,
        "guru_id" => $guru->id,
        "nama_ujian" => $request->nama_ujian,
        "mode" => $request->mode,
        "durasi" => $request->durasi,
        "grade" => $request->grade,
        "catatan" => $request->catatan,
        "status" => "draft",
    ]);
    
    // Simpan relasi many-to-many dengan kelas
    if ($request->has('kelas_id')) {
        $ujian->kelas()->attach($request->kelas_id);
    }
    
    // Jika mode praktik dan memiliki jadwal
   
    
    // Redirect berdasarkan mode
    if ($request->mode === 'praktik') {
        return redirect()->route("guru.create", ["id" => $ujian->id])
            ->with("success", "Ujian praktik berhasil dibuat! Siswa dapat mengakses ujian sesuai jadwal yang ditentukan.");
    }
    
    return redirect()->route("guru.create", ["id" => $ujian->id])
        ->with("success", "Berhasil Buat Ujian CBT");
}
    public function Uji()
    {
      
      return view("guru.test",compact("ire","dt","uji","qs"));
    }
    public function sept(Request $request){
        $ujian = Ujian::find($request->ujian_id);
        $ujian->update(["status","ready"]);
        return redirect()->route("guru.index")->with("success","Berhasil Mempublikasikan Ujian");
    }
    public function CreateSoal(Request $request,$id)
    {
      $uji = Ujian::with("jadwal","kelas")->find($id);
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
    $ujian = Ujian::findOrFail($id);

    // hapus data pivot
    $ujian->soals()->detach();

    // hapus ujian
    $ujian->delete();

    return redirect()->route("guru.index")->with("success","Berhasil Hapus");
}
  public function def(Request $request, $id)
{
    // 1. Validasi Dasar
    $rules = [
        'guru_id'      => 'required|exists:guru,id',
        'mapel_id'     => 'required|exists:mapel,id',
        'soal'         => 'required|array|min:1',
        'soal.*.soal'  => 'required|string',
        'soal.*.tipe'  => 'required|in:pg,essay,av',
        'soal.*.gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        
        // Validasi Media Baru
        'soal.*.media_file' => 'nullable|mimes:mp4,mp3,ogg,webm,wav|max:10240',
        'soal.*.media_url'  => 'nullable|url',
    ];
    
    // 2. Validasi Dinamis untuk Jawaban & Opsi
    foreach ($request->soal as $index => $val) {
        $tipe = $val['tipe'] ?? 'pg';

        // Jawaban Benar Wajib ada
        $rules['soal.'.$index.'.jawaban_benar'] = 'required|string';

        if ($tipe === 'pg') {
            // PG: Opsi A, B, C WAJIB
            $rules['soal.'.$index.'.opsi_a'] = 'required|string';
            $rules['soal.'.$index.'.opsi_b'] = 'required|string';
            $rules['soal.'.$index.'.opsi_c'] = 'required|string';
            $rules['soal.'.$index.'.opsi_d'] = 'nullable|string';
            $rules['soal.'.$index.'.opsi_e'] = 'nullable|string';
        } else {
            // ESSAY atau AV: Opsi BOLEH DIISI atau KOSONG (jika essay)
            // JANGAN dipaksa null dulu, biarkan saja apa yang dikirim
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

            // Handle Media
            $mediaFilePath = null;
            $mediaUrlVal = null;

            if (isset($soalData['media_file']) && $soalData['media_file']->isValid()) {
                $mediaFilePath = $soalData['media_file']->store('soal_media', 'public');
            } elseif (!empty($soalData['media_url'])) {
                $mediaUrlVal = $soalData['media_url'];
            }

            // ========== PERBAIKAN: Handle Opsi untuk AV ==========
            // Simpan opsi jika ada (untuk AV mode PG), kosongkan jika memang tidak ada
            $opsiA = $soalData['opsi_a'] ?? null;
            $opsiB = $soalData['opsi_b'] ?? null;
            $opsiC = $soalData['opsi_c'] ?? null;
            $opsiD = $soalData['opsi_d'] ?? null;
            $opsiE = $soalData['opsi_e'] ?? null;
            
            // Jika tipe AV dan semua opsi kosong, berarti mode Essay
            // Jika ada opsi yang terisi, berarti mode PG dalam AV
            
            $insertData = [
                'guru_id'       => $request->guru_id,
                'mapel_id'      => $request->mapel_id,
                'soal'          => $soalData['soal'],
                'gambar'        => $gambarPath,
                'tipe'          => $tipe,
                'jawaban_benar' => $soalData['jawaban_benar'],
                
                // Media
                'media_file'    => $mediaFilePath,
                'media_url'     => $mediaUrlVal,
                
                // Opsi - SIMPAN SESUAI INPUT (bisa null atau terisi)
                'opsi_a'        => $opsiA,
                'opsi_b'        => $opsiB,
                'opsi_c'        => $opsiC,
                'opsi_d'        => $opsiD,
                'opsi_e'        => $opsiE,
                
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            // Hanya untuk PG: pastikan opsi A,B,C tidak null (default jika kosong)
            if ($tipe === 'pg') {
                $insertData['opsi_a'] = $opsiA ?? '';
                $insertData['opsi_b'] = $opsiB ?? '';
                $insertData['opsi_c'] = $opsiC ?? '';
                $insertData['opsi_d'] = $opsiD ?? '';
                $insertData['opsi_e'] = $opsiE ?? '';
            }
            // Untuk AV: biarkan apa adanya (bisa null atau terisi)

            // Simpan Soal
            $newSoal = banksoal::create($insertData);

            // Simpan Relasi
            Ujian_soals::create([
                'ujian_id' => $ujian->id,
                'bank_id'  => $newSoal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();

        return redirect()->route('guru.ujian.detail', $ujian->id)
            ->with('success', "✅ Berhasil menyimpan " . count($request->soal) . " soal.");

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', '❌ Gagal menyimpan soal: ' . $e->getMessage())
            ->withInput();
    }
}
public function storePraktik(Request $request, $id)
{
    // 1. VALIDASI KHUSUS PRAKTIK
    $request->validate([
        'guru_id'      => 'required|exists:guru,id',
        'mapel_id'     => 'required|exists:mapel,id',
        'soal'         => 'required|array|min:1',
        'soal.*.soal'  => 'required|string',
        'soal.*.tipe'  => 'required|in:upload',
        'jadwal' => 'required|array|min:1',
        'jadwal.*.tanggal' => 'required|date',
        'jadwal.*.deadline' => 'required',
    ]);

    try {
        DB::beginTransaction();
        
        $ujian = Ujian::findOrFail($id);
        
        // CEK APAKAH MODE PRAKTIK
        if ($ujian->mode !== 'praktik') {
            throw new \Exception('Ujian ini bukan mode praktik!');
        }
        
        // 2. SIMPAN SOAL (HANYA 1 SOAL UNTUK PRAKTIK)
        foreach ($request->soal as $index => $soalData) {
            // Upload gambar jika ada
            $gambarPath = null;
            if (isset($soalData['gambar']) && $soalData['gambar']->isValid()) {
                $gambarPath = $soalData['gambar']->store('soal_images', 'public');
            }
            
            $soal = banksoal::create([
                'guru_id'       => $request->guru_id,
                'mapel_id'      => $request->mapel_id,
                'soal'          => $soalData['soal'],
                'gambar'        => $gambarPath,
                'tipe'          => 'upload',
                'jawaban_benar' => 'pending',
            ]);
            
            // Relasi ke ujian
            Ujian_soals::create([
                'ujian_id' => $ujian->id,
                'bank_id'  => $soal->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 3. SIMPAN JADWAL UNTUK PRAKTIK
        foreach ($request->jadwal as $jadwalData) {
            // Tentukan kelas mana yang dapat jadwal ini
            $kelasIds = [];
            
            if (isset($jadwalData['all_kelas']) && $jadwalData['all_kelas'] == '1') {
                // Jika pilih "Semua Kelas", ambil dari relasi ujian->kelas
                $kelasIds = $ujian->kelas->pluck('id')->toArray();
            } elseif (isset($jadwalData['kelas_id']) && is_array($jadwalData['kelas_id'])) {
                $kelasIds = $jadwalData['kelas_id'];
            } elseif (isset($jadwalData['kelas_id']) && !is_array($jadwalData['kelas_id'])) {
                $kelasIds = [$jadwalData['kelas_id']];
            } else {
                // Default: semua kelas yang terdaftar di ujian
                $kelasIds = $ujian->kelas->pluck('id')->toArray();
            }
            
            // Filter kelas kosong
            $kelasIds = array_filter($kelasIds);
            
            if (empty($kelasIds)) {
                throw new \Exception('Tidak ada kelas yang dipilih untuk jadwal!');
            }
            
            foreach ($kelasIds as $kelasId) {
                $waktuMulai = now();
                $waktuSelesai = \Carbon\Carbon::parse($jadwalData['deadline']);
                
                // Validasi waktu
                if ($waktuMulai >= $waktuSelesai) {
                    throw new \Exception("Jam selesai harus lebih besar dari jam mulai!");
                }
                
                // Hitung jam_mapel otomatis
                $jamMapel = (Jadwal::where('kelas_id', $kelasId)
                    ->where('tanggal', $jadwalData['tanggal'])
                    ->max('jam_mapel') ?? 0) + 1;
                
                // Cek apakah sudah ada jadwal di waktu yang sama untuk kelas ini
                $existingJadwal = Jadwal::where('kelas_id', $kelasId)
                    ->where('tanggal', $jadwalData['tanggal'])
                    ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                          ->orWhere(function($q2) use ($waktuMulai, $waktuSelesai) {
                              $q2->where('waktu_mulai', '<=', $waktuMulai)
                                 ->where('waktu_selesai', '>=', $waktuSelesai);
                          });
                    })->exists();
                
               
                
                Jadwal::create([
                    'jam_mapel'     => $jamMapel,
                    'tanggal'       => $jadwalData['tanggal'],
                    'ujian_id'      => $ujian->id,
                    'pengawas_id'   => null,
                    'kelas_id'      => $kelasId,
                    'waktu_mulai'   => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
                    'untuk_susulan' => DB::raw('FALSE'),  
                ]);
            }
        }
        
        // 4. UPDATE STATUS UJIAN MENJADI READY
        $ujian->update([
            'status' => 'ready',
        ]);
        
        DB::commit();
        
        return redirect()->route('guru.ujian.detail', $ujian->id)
            ->with('success', '✅ Ujian praktik berhasil dibuat!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', '❌ Gagal: ' . $e->getMessage())
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

    public function result()
{
    $ire = Auth::user();

    $dt = Guru::where("nama", $ire->nama)->first();

    // Query utama
    $data = Ujian::where("guru_id", $dt->id)
        ->with("peserta.siswa");

    // Filter mode ujian
    if (request('mode')) {
        $data->where('mode', request('mode'));
    }

    // Ambil data
    $data = $data->get();

    $val = Peserta_ujian::with("siswa");

    // Jadwal pengawas hari ini
    $jadwalMengawas = Jadwal::with('ujian', 'kelas.ruangan')
        ->where('pengawas_id', $dt->id)
        ->whereDate('tanggal', now())
        ->orderBy('waktu_mulai', 'asc')
        ->take(3)
        ->get();

    // Format notif
    $notifData = $jadwalMengawas->map(function ($item) {
        return [
            'id'     => $item->id,
            'title'  => "Mengawas: " . ($item->ujian->nama_ujian ?? 'Ujian Tanpa Nama'),
            'kelas'  => optional($item->kelas)->nama_kelas . ' - ' .
                        optional($item->kelas->ruangan)->nama_ruang,

            'time'   => \Carbon\Carbon::parse($item->waktu_mulai)
                        ->locale("id")
                        ->translatedFormat('d M Y, H:i'),

            'unread' => true
        ];
    });

    return view("guru.hasil", compact(
        "data",
        "ire",
        "val",
        "dt",
        "notifData"
    ));
}
    public function hasil($id)
{
    $ujian = Ujian::with('jadwal')->findOrFail($id);
    $ire = Auth::user();
    $dt = Guru::where("nama",$ire->nama)->first();
      $data = Ujian::where("guru_id",$dt->id)->get();
      $val = Peserta_ujian::with("siswa");
      
       $jadwalMengawas = Jadwal::with('ujian','kelas.ruangan')
    ->where('pengawas_id', $dt->id)
    ->whereDate('tanggal', now())

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
    })->orderBy("nomor_absen","asc")
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
       $jadwalMengawas = Jadwal::with('ujian','kelas.ruangan')
    ->where('pengawas_id', $dt->id)
    ->whereDate('tanggal', now())

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
      $data = Ujian::with("jadwal")->where("guru_id",$dt->id)->get();
      return view("guru.riwayat",compact("data","ire","dt","notifData"));
    }
   public function jadwal()
{
    
    $ire = Auth::user();
    $dt = Guru::where("nama", $ire->nama)->first();
     $jadwalMengawas = Jadwal::with('ujian','kelas.ruangan')
    ->where('pengawas_id', $dt->id)
    
    ->whereDate('tanggal', now())


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
    // Jika data guru tidak ditemukan, abort biar aman
    if (!$dt) {
        abort(403, 'Data profil guru tidak ditemukan di database.');
    }

    // AMBIL JADWAL HANYA YANG DIAWASI OLEH GURU INI
    // Tambahkan relasi "pengawas.guru" agar tidak error di view & tidak query berulang kali (N+1)
   

$data = Jadwal::with("kelas", "ujian.mapels", "pengawas.guru")
    ->whereHas('pengawas', function ($query) use ($dt) {
        $query->where('guru_id', $dt->id);
    })
    ->orderBy('tanggal', 'desc')
    ->get();
  

// ✅ TAMBAHKAN INI


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
    
 
    
    /**
     * CONFIRM FUNCTION - Menyimpan ke database
     */
    public function confirm(Request $request)
    {
        try {
            \Log::info('=== CONFIRM START ===');
            
            $request->validate([
                'uji_id' => 'required|exists:ujian,id',
                'mapel_id' => 'required|exists:mapel,id',
                'guru_id' => 'required|exists:guru,id',
                'soal_data' => 'required|array|min:1',
            ]);
            
            DB::beginTransaction();
            
            $ujian = Ujian::findOrFail($request->uji_id);
            $soalList = $request->soal_data;
            $bankData = [];
            
            foreach ($soalList as $index => $soal) {
                $soalText = $this->ensureString($soal['soal'] ?? null);
                
                if (empty($soalText)) {
                    throw new \Exception('Soal nomor ' . ($index + 1) . ' tidak boleh kosong');
                }
                
                $bankData[] = [
                    'guru_id' => $request->guru_id,
                    'mapel_id' => $request->mapel_id,
                    'soal' => $soalText,
                    'gambar' => $this->ensureString($soal['gambar'] ?? null),
                    'opsi_a' => $this->ensureString($soal['opsi_a'] ?? null),
                    'opsi_b' => $this->ensureString($soal['opsi_b'] ?? null),
                    'opsi_c' => $this->ensureString($soal['opsi_c'] ?? null),
                    'opsi_d' => $this->ensureString($soal['opsi_d'] ?? null),
                    'opsi_e' => $this->ensureString($soal['opsi_e'] ?? null),
                    'jawaban_benar' => strtoupper($this->ensureString($soal['jawaban_benar'] ?? '')),
                    'tipe' => $this->ensureString($soal['tipe'] ?? 'pg'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert ke tabel bank
            $insertedIds = [];
            foreach ($bankData as $data) {
                $id = DB::table('bank')->insertGetId($data);
                $insertedIds[] = $id;
            }
            
            // Insert ke pivot ujian_soals
            $relasiData = [];
            foreach ($insertedIds as $bankId) {
                $relasiData[] = [
                    'ujian_id' => $ujian->id,
                    'bank_id' => $bankId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($relasiData)) {
                DB::table('ujian_soals')->insert($relasiData);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'imported' => count($bankData),
                'message' => "Berhasil mengimport " . count($bankData) . " soal ke ujian " . $ujian->nama_ujian,
                'redirect_url' => route('guru.ujian.detail', $ujian->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Confirm Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    /**
 * PREVIEW FUNCTION - SUPPORT PG DAN ESSAY
 */
public function preview(Request $request)
{
    try {
       $request->validate([
            'file_excel' => 'required|file|max:10240', // Hanya cek file dan size
            'uji_id' => 'required'
        ]);
        
        $file = $request->file('file_excel');
        $extension = $file->getClientOriginalExtension();
        
        // Validasi manual ekstensi
        if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
            return response()->json([
                'success' => false,
                'message' => 'File harus berekstensi .xlsx, .xls, atau .csv'
            ]);
        }

        $data = Excel::toArray([], $file);
        
        if (empty($data) || empty($data[0])) {
            return response()->json([
                'success' => false,
                'message' => 'File kosong atau tidak dapat dibaca'
            ]);
        }
        
        $rows = $data[0];
        $headerRow = array_shift($rows);
        
        // Deteksi mapping header
        $mapping = $this->smartMapping($headerRow);
        
        $soalList = [];
        $detectedType = null;
        $hasMixedType = false;
        
        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;
            
            $soal = [];
            
            // Ambil soal
            $soal['soal'] = isset($mapping['soal']) ? $this->ensureString($row[$mapping['soal']] ?? null) : null;
            
            // Ambil jawaban
            $soal['jawaban_benar'] = isset($mapping['jawaban']) ? $this->ensureString($row[$mapping['jawaban']] ?? null) : null;
            
            // Ambil tipe (pg atau essay)
            $soal['tipe'] = isset($mapping['tipe']) ? strtolower($this->ensureString($row[$mapping['tipe']] ?? 'pg')) : 'pg';
            
            // Jika tipe essay, opsi tidak diperlukan
            if ($soal['tipe'] == 'essay') {
                $soal['opsi_a'] = null;
                $soal['opsi_b'] = null;
                $soal['opsi_c'] = null;
                $soal['opsi_d'] = null;
                $soal['opsi_e'] = null;
            } else {
                // PG: ambil opsi
                $soal['opsi_a'] = isset($mapping['opsi_a']) ? $this->ensureString($row[$mapping['opsi_a']] ?? null) : null;
                $soal['opsi_b'] = isset($mapping['opsi_b']) ? $this->ensureString($row[$mapping['opsi_b']] ?? null) : null;
                $soal['opsi_c'] = isset($mapping['opsi_c']) ? $this->ensureString($row[$mapping['opsi_c']] ?? null) : null;
                $soal['opsi_d'] = isset($mapping['opsi_d']) ? $this->ensureString($row[$mapping['opsi_d']] ?? null) : null;
                $soal['opsi_e'] = isset($mapping['opsi_e']) ? $this->ensureString($row[$mapping['opsi_e']] ?? null) : null;
            }
            
            if (!empty($soal['soal'])) {
                // Validasi tipe homogen
                 $currentType = $this->normalizeTipe($soal['tipe'] ?? 'pg');// tambah trim()
    
    // LOG untuk debugging
    \Log::info("Baris " . ($index + 2) . " - Tipe terdeteksi: '" . $currentType . "'");
    
    if ($detectedType === null) {
        $detectedType = $currentType;
    } elseif ($detectedType !== $currentType) {
        \Log::warning("Mixed type detected: {$detectedType} vs {$currentType}");
        $hasMixedType = true;
    }
                $soalList[] = $soal;
            }
        }
        
        if ($hasMixedType) {
            return response()->json([
                'success' => false,
                'message' => 'File mengandung campuran tipe soal (PG dan Essay). Silakan pisahkan.'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'rows' => $soalList,
                'total' => count($soalList),
                'type' => $detectedType,
                'mapping' => $mapping
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * SMART MAPPING - DETEKSI HEADER OTOMATIS
 */
private function smartMapping($headerRow)
{
    $mapping = [];
    $normalized = [];
    
    foreach ($headerRow as $i => $h) {
        $normalized[$i] = $this->normalizeHeader($h);
    }
    
    // 1. DETEKSI SOAL
    $soalKeywords = ['soal', 'pertanyaan', 'question', 'text'];
    foreach ($normalized as $i => $col) {
        foreach ($soalKeywords as $keyword) {
            if (strpos($col, $keyword) !== false) {
                $mapping['soal'] = $i;
                break 2;
            }
        }
    }
    if (!isset($mapping['soal'])) $mapping['soal'] = 0;
    
    // 2. DETEKSI JAWABAN
    $jawabanKeywords = ['jawaban', 'kunci', 'answer', 'correct', 'benar'];
    foreach ($normalized as $i => $col) {
        if (($mapping['soal'] ?? -1) == $i) continue;
        foreach ($jawabanKeywords as $keyword) {
            if (strpos($col, $keyword) !== false) {
                $mapping['jawaban'] = $i;
                break 2;
            }
        }
    }
    
    // 3. DETEKSI TIPE
    $tipeKeywords = ['tipe', 'type', 'jenis'];
    foreach ($normalized as $i => $col) {
        if (($mapping['soal'] ?? -1) == $i) continue;
        if (isset($mapping['jawaban']) && $mapping['jawaban'] == $i) continue;
        foreach ($tipeKeywords as $keyword) {
            if (strpos($col, $keyword) !== false) {
                $mapping['tipe'] = $i;
                break 2;
            }
        }
    }
    
    // 4. DETEKSI OPSI (hanya untuk PG)
    $usedColumns = [$mapping['soal'] ?? -1, $mapping['jawaban'] ?? -1, $mapping['tipe'] ?? -1];
    $opsiFields = ['opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e'];
    $opsiIndex = 0;
    
    foreach ($normalized as $i => $col) {
        if (in_array($i, $usedColumns)) continue;
        
        // Deteksi apakah ini kolom opsi
        $isOpsi = false;
        if (preg_match('/[a-e]/i', $col) || 
            strpos($col, 'opsi') !== false || 
            strpos($col, 'pilihan') !== false ||
            strpos($col, 'option') !== false) {
            $isOpsi = true;
        }
        
        if ($isOpsi && $opsiIndex < 5) {
            $mapping[$opsiFields[$opsiIndex]] = $i;
            $opsiIndex++;
        }
    }
    
    return $mapping;
}

/**
 * Konversi angka ke huruf (1=A, 2=B, dst)
 */
private function numberToLetter($number)
{
    $letters = ['', 'A', 'B', 'C', 'D', 'E'];
    $num = intval($number);
    return isset($letters[$num]) ? $letters[$num] : strtoupper($number);
}

/**
 * Normalisasi header (lowercase, hapus spasi, accent)
 */
private function normalizeTipe($tipe)
{
    $tipe = strtolower(trim($tipe ?? 'pg'));
    
    // Standardisasi berbagai variasi essay
    if (in_array($tipe, ['essay', 'esai', 'essai', 'eassy', 'essays'])) {
        return 'essay';
    }
    
    // Standardisasi berbagai variasi pg
    if (in_array($tipe, ['pg', 'pilihanganda', 'multiplechoice', 'mc'])) {
        return 'pg';
    }
    
    return $tipe;
}
private function normalizeHeader($header)
{
    if (empty($header)) return '';
    $header = $this->ensureString($header);
    $header = strtolower($header);
    $header = preg_replace('/[\s_\-\.]+/', '', $header);
    return $header;
}

private function ensureString($value)
{
    if (is_null($value)) return null;
    if (is_string($value)) return trim($value);
    if (is_numeric($value)) return (string) $value;
    if (is_object($value)) {
        if (method_exists($value, '__toString')) return $value->__toString();
    }
    return (string) $value;
}
public function bareroll(Request $request)
{
    DB::beginTransaction();
    
    try {
        
        $uji = Ujian::findOrFail($request->ujian_id);
        
        if (!$uji->kelas()->where('kelas_id', $request->kelas_id)->exists()) {
            $uji->kelas()->attach($request->kelas_id);
        }
        
        $guru = Guru::inRandomOrder()->first();
        if (!$guru) {
            throw new \Exception("Gagal mengacak pengawas. Belum ada data guru di database.");
        }
        
        $pengawas = Pengawas::firstOrCreate([
            'guru_id' => $guru->id,
            'user_id' => $guru->user_id,
        ]);
        
        // ✅ GUNAKAN CARBON OBJECT
        $tanggalSekarang = now();
       // ✅ yang benar
$tanggal = now()->toDateString();

$waktuMulai = Carbon::createFromFormat('Y-m-d H:i', $tanggal.' '.$request->waktu_mulai);
$waktuSelesai = Carbon::createFromFormat('Y-m-d H:i', $tanggal.' '.$request->waktu_selesai);

if ($waktuSelesai->lessThan($waktuMulai)) {
    $waktuSelesai->addDay();
}
        
        $jads = Jadwal::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'ujian_id' => $request->ujian_id,
            ],
            [
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'tanggal' => $tanggalSekarang,
                'keterangan' => $request->keterangan,
                'untuk_susulan' => DB::raw('TRUE'),  // ← PAKAI DB::raw LANGSUNG
                'pengawas_id' => $pengawas->id,
            ]
        );
        
        $uji->update([
            "jadwal_id" => $jads->id,
            "status" => "ready",
        ]);
        
        DB::commit();
        
        $message = $jads->wasRecentlyCreated 
            ? "Jadwal susulan hari ini berhasil dibuat! (Pengawas: {$guru->nama})" 
            : "Jadwal susulan hari ini berhasil diperbarui! (Pengawas: {$guru->nama})";
        
        return redirect()->route("guru.hasil", ["id" => $request->ujian_id])
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
public function ChangeState($id){
    $ujian = Ujian::findOrFail($id);
    $ujian->status = 'ready';
    $ujian->save();
    return redirect()->route("guru.index")->with("success","Ujian Sudah Di Publish");
    }
    public function editSoal(Request $request, $id)
{
    $validator = Validator::make($request->all(), [

        'tipe' => 'required|in:pg,essay,av',
        'soal' => 'required|string',

        // gambar
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        // media baru
        'media_file' => 'nullable|mimes:mp4,mp3,ogg,webm,wav|max:10240',
        'media_url'  => 'nullable|url',

        'jawaban_benar' => 'required|string',

        // PG only
        'opsi_a' => 'required_if:tipe,pg|nullable|string',
        'opsi_b' => 'required_if:tipe,pg|nullable|string',
        'opsi_c' => 'required_if:tipe,pg|nullable|string',

        'opsi_d' => 'nullable|string',
        'opsi_e' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    DB::beginTransaction();

    try {

        $soal = \App\Models\banksoal::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HANDLE GAMBAR
        |--------------------------------------------------------------------------
        */

        $gambarPath = $soal->gambar;

        if ($request->hasFile('gambar')) {

            // hapus lama
            if ($soal->gambar && file_exists(public_path('storage/' . $soal->gambar))) {
                unlink(public_path('storage/' . $soal->gambar));
            }

            $gambarPath = $request->file('gambar')
                ->store('soal_images', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | HANDLE MEDIA FILE / URL
        |--------------------------------------------------------------------------
        */

        $mediaFilePath = $soal->media_file;
        $mediaUrlVal   = $soal->media_url;

        // Jika upload file baru
        if ($request->hasFile('media_file')) {

            // hapus media lama
            if ($soal->media_file &&
                file_exists(public_path('storage/' . $soal->media_file))) {

                unlink(public_path('storage/' . $soal->media_file));
            }

            $mediaFilePath = $request->file('media_file')
                ->store('soal_media', 'public');

            // kosongkan url jika pakai file
            $mediaUrlVal = null;
        }

        // jika pakai URL
        elseif ($request->filled('media_url')) {

            $mediaUrlVal = $request->media_url;

            // opsional:
            // kalau pakai URL baru, kosongkan file
            $mediaFilePath = null;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $soal->update([

            'tipe' => $request->tipe,
            'soal' => $request->soal,

            'gambar' => $gambarPath,

            'media_file' => $mediaFilePath,
            'media_url'  => $mediaUrlVal,

            'jawaban_benar' => $request->jawaban_benar,

            // PG
            'opsi_a' => $request->tipe === 'pg'
                ? $request->opsi_a
                : null,

            'opsi_b' => $request->tipe === 'pg'
                ? $request->opsi_b
                : null,

            'opsi_c' => $request->tipe === 'pg'
                ? $request->opsi_c
                : null,

            'opsi_d' => $request->tipe === 'pg'
                ? $request->opsi_d
                : null,

            'opsi_e' => $request->tipe === 'pg'
                ? $request->opsi_e
                : null,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diperbarui!'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Gagal update: ' . $e->getMessage()
        ], 500);
    }
}

public function deleteSoal($id)
{
    DB::beginTransaction();
    try {
        $soal = \App\Models\banksoal::findOrFail($id);

        // 1. Hapus dari Pivot Table (ujian_soals)
        \App\Models\Ujian_soals::where('bank_id', $id)->delete();

        // 2. Hapus dari Tabel Induk (bank_soals)
        $soal->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal hapus: ' . $e->getMessage()
        ], 500);
    }
}


public function createJadwalSusulan(Request $request)
{
    // Log awal request
    \Log::info('=== START createJadwalSusulan ===');
    \Log::info('Request data:', $request->all());
    
    DB::beginTransaction();
    \Log::info('Transaction started');

    try {
        // Cek ujian
        \Log::info('Mencari ujian dengan ID: ' . $request->ujian_id);
        $uji = Ujian::findOrFail($request->ujian_id);
        \Log::info('Ujian ditemukan:', ['ujian_id' => $uji->id, 'nama' => $uji->nama ?? 'N/A']);

        // Cari guru random
        \Log::info('Mencari guru random');
        $guru = Guru::inRandomOrder()->first();

        if (!$guru) {
            \Log::error('Guru pengawas tidak ditemukan');
            throw new \Exception("Guru pengawas tidak ditemukan");
        }
        \Log::info('Guru ditemukan:', ['guru_id' => $guru->id, 'user_id' => $guru->user_id]);

        // Buat pengawas
        \Log::info('Membuat/update pengawas');
        $pengawas = Pengawas::firstOrCreate([
            'guru_id' => $guru->id,
            'user_id' => $guru->user_id,
        ]);
        \Log::info('Pengawas:', ['pengawas_id' => $pengawas->id]);

        // Proses waktu
        $tanggal = now()->toDateString();
        \Log::info('Tanggal: ' . $tanggal);
        \Log::info('Request waktu_mulai: ' . $request->waktu_mulai);
        \Log::info('Request waktu_selesai: ' . $request->waktu_selesai);

        $waktuMulai = Carbon::createFromFormat(
            'Y-m-d H:i',
            $tanggal.' '.$request->waktu_mulai
        );
        \Log::info('waktuMulai: ' . $waktuMulai->format('Y-m-d H:i:s'));

        $waktuSelesai = Carbon::createFromFormat(
            'Y-m-d H:i',
            $tanggal.' '.$request->waktu_selesai
        );
        \Log::info('waktuSelesai awal: ' . $waktuSelesai->format('Y-m-d H:i:s'));

        if ($waktuSelesai->lessThan($waktuMulai)) {
            $waktuSelesai->addDay();
            \Log::info('waktuSelesai setelah addDay: ' . $waktuSelesai->format('Y-m-d H:i:s'));
        }

        // BUAT / UPDATE JADWAL
        \Log::info('Membuat/update jadwal dengan data:', [
            'kelas_id' => $request->kelas_id,
            'ujian_id' => $request->ujian_id,
            'tanggal' => now(),
            'waktu_mulai' => $waktuMulai->format('Y-m-d H:i:s'),
            'waktu_selesai' => $waktuSelesai->format('Y-m-d H:i:s'),
            'pengawas_id' => $pengawas->id,
        ]);
        
        $jadwal = Jadwal::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'ujian_id' => $request->ujian_id,
            ],
            [
                'tanggal' => now(),
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'pengawas_id' => $pengawas->id,
                'untuk_susulan' => \DB::raw('true'),
                'keterangan' => 'Jadwal susulan',
            ]
        );
        \Log::info('Jadwal berhasil dibuat/update dengan ID: ' . $jadwal->id);

        // UPDATE STATUS SISWA SUSULAN
        \Log::info('Mengupdate status siswa susulan. Jumlah siswa: ' . count($request->siswa_ids));
        \Log::info('Siswa IDs: ' . json_encode($request->siswa_ids));
        
        foreach ($request->siswa_ids as $index => $siswaId) {
            \Log::info("Proses siswa ke-" . ($index+1) . " dengan ID: {$siswaId}");
            
            $updated = Siswa::where('id_siswa', $siswaId)
                ->update([
                    'status' => 'ready'
                ]);
            
            \Log::info("Update status untuk siswa {$siswaId}: " . ($updated ? "Berhasil" : "Tidak ada data yang diupdate"));
        }

        // UPDATE STATUS UJIAN
        \Log::info('Mengupdate status ujian');
        $uji->update([
            'status' => 'ready',
            'jadwal_id' => $jadwal->id,
        ]);
        \Log::info('Status ujian berhasil diupdate');

        DB::commit();
        \Log::info('Transaction committed successfully');
        \Log::info('=== END createJadwalSusulan (SUCCESS) ===');

        return redirect()
            ->back()
            ->with(
                'success',
                'Jadwal susulan berhasil dibuat!'
            );

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('=== ERROR in createJadwalSusulan ===');
        \Log::error('Error message: ' . $e->getMessage());
        \Log::error('Error code: ' . $e->getCode());
        \Log::error('Error file: ' . $e->getFile());
        \Log::error('Error line: ' . $e->getLine());
        \Log::error('Error trace: ' . $e->getTraceAsString());
        
        // Log request data untuk debugging
        \Log::error('Request data saat error:', $request->all());

        return redirect()
            ->back()
            ->withErrors([
                'error' => $e->getMessage()
            ]);
    }
}

public function getJawabanSiswa($pesertaId)
{
    try {

        $peserta = Peserta_ujian::with('siswa')->find($pesertaId);

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta tidak ditemukan'
            ]);
        }

        $jawabanSiswa = Jawaban_Siswa::with([
                'bank:id,soal,tipe'
            ])
            ->where('ujian_id', $peserta->ujian_id)
            ->where('siswa_id', $peserta->siswa_id)
            ->get();

        $data = [];

        foreach ($jawabanSiswa as $j) {

            $data[] = [
                'id' => $j->id,
                'soal_text' => $j->bank->soal ?? '-',
                'tipe_soal' => $j->bank->tipe ?? '-',
                'jawaban_teks' => $j->jawaban,
                'file_path' => $j->file_jawaban,
            ];
        }

        return response()->json([
            'success' => true,
            'jawaban' => $data,
            'nilai_akhir' => $peserta->nilai ?? 0
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
}
