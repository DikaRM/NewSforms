<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProsesUjianJob;
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
use App\Models\BlockSiswa;
use App\Models\banksoal;
use App\Models\Ujian_soals;
use App\Models\Jawaban_Siswa;
use App\Models\Peserta_ujian;
use App\Models\Jadwal;
use App\Models\Pelanggaran;
use Illuminate\Support\Facades\Storage;
class SiswaController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Siswa::with("kelas","user")->orderBy("nomor_absen","asc");

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('nama', 'ilike', '%' . $request->search . '%')
              ->orWhere('nisn', 'ilike', '%' . $request->search . '%');
        });
    }

    $data = $query->paginate(10)->withQueryString();

    if ($request->ajax()) {
        return view('admin.siswa.partial.table', compact('data'))->render();
    }

    $ire = Auth::user();
    $kelas = Kelas::all();
    $isSearching = $request->filled('search');
    return view("admin.siswa.index", compact("data","ire","kelas","isSearching"));
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
        'kelas_id' => 'required',
        'nomor_absen' => 'required'
    ]);

    $namaParts = explode(' ', trim($request->nama));

    $username = null;

    // Cari kata yang valid (>2 huruf)
    foreach ($namaParts as $part) {

        $candidate = Str::lower(Str::slug($part, ''));

        // skip kalau terlalu pendek
        if (strlen($candidate) <= 2) {
            continue;
        }

        $exists =
            User::where('username', $candidate)->exists();

        // kalau belum ada, pakai
        if (!$exists) {
            $username = $candidate;
            break;
        }
    }

    // fallback kalau semua sudah dipakai
    if (!$username) {

        $baseUsername = Str::lower(
            Str::slug($namaParts[0] ?? 'siswa', '')
        );

        // kalau nama pertama juga pendek
        if (strlen($baseUsername) <= 2) {
            $baseUsername = 'siswa';
        }

        $username = $baseUsername;
        $i = 1;

        while (
            User::where('username', $username)->exists()
            ||
            Siswa::where('username', $username)->exists()
        ) {
            $username = $baseUsername . $i;
            $i++;
        }
    }

    $user = User::create([
        "nama" => $request->nama,
        "password" => Hash::make($request->password),
        "role" => "siswa",
        "username" => $username,
    ]);

    Siswa::create([
        "user_id" => $user->id,
        "nama" => $request->nama,
        "nisn" => $request->nisn,
        'kelas_id' => $request->kelas_id,
        "nomor_absen" => $request->nomor_absen,
        "jenis_kelamin" => $request->jenis_kelamin,
    ]);

    return redirect()
        ->route("admin.siswa.index")
        ->with("success", "Berhasil Tambah Siswa!");
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
        "password" => "nullable|min:6",
    ]);

    $siswa = Siswa::findOrFail($id);
    $usd = User::findOrFail($siswa->user_id);

    // Pecah nama dan filter kata > 2 huruf
    $namaParts = collect(explode(' ', trim($request->nama)))
        ->map(fn($item) => strtolower(trim($item)))
        ->filter(fn($item) => strlen($item) > 2)
        ->values();

    // Fallback kalau semua kata <= 2
    if ($namaParts->isEmpty()) {
        $namaParts = collect(['user']);
    }

    // Default username
    $username = $namaParts[0];

    // Cari username yang available
    $found = false;

    foreach ($namaParts as $part) {

        $existsUser = User::whereRaw('LOWER(username) = ?', [$part])
            ->where('id', '!=', $usd->id)
            ->exists();


        if (!$existsUser) {
            $username = $part;
            $found = true;
            break;
        }
    }

    // Kalau semua nama sudah dipakai
    if (!$found) {

        $baseUsername = $namaParts[0];
        $i = 1;

        while (true) {

            $candidate = $baseUsername . $i;

            $existsUser = User::whereRaw('LOWER(username) = ?', [$candidate])
                ->where('id', '!=', $usd->id)
                ->exists();

            if (!$existsUser) {
                $username = $candidate;
                break;
            }

            $i++;
        }
    }

    // UPDATE USER
    $usd->nama = $request->nama;
    $usd->username = $username;

    if ($request->filled("password")) {
        $usd->password = Hash::make($request->password);
    }

    $usd->save();

    // UPDATE SISWA
    $siswa->nama = $request->nama;
    
    $siswa->nisn = $request->nisn;
    $siswa->jenis_kelamin = $request->jenis_kelamin;
    $siswa->nomor_absen = $request->nomor_absen;

    $siswa->save();

    return redirect()
        ->route("admin.siswa.index")
        ->with("success", "Berhasil Update Siswa!");
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
    $ujians = Ujian_soals::where("ujian_id", $uji->id)->pluck('bank_id')->toArray();
    if(!$peserta) {
         $acak = banksoal::whereIn('id', $ujians)
        ->inRandomOrder()
        ->pluck('id')
        ->toArray();

    $peserta = Peserta_ujian::create([
        'siswa_id' => $sis->id_siswa,
        'ujian_id' => $uji->id,
        'status' => 'mulai',
        'nilai'=> 0,
        'urutan_soal' => json_encode($acak),
    ]);
    }
    if($peserta && !$peserta->urutan_soal) {
    $acak = banksoal::whereIn('id', $ujians)
        ->inRandomOrder()
        ->pluck('id')
        ->toArray();

    $peserta->update([
        'urutan_soal' => json_encode($acak)
    ]);
}
    $urutan = json_decode($peserta->urutan_soal, true);

$soal = banksoal::whereIn('id', $urutan)->get()
    ->sortBy(function ($item) use ($urutan) {
        return array_search($item->id, $urutan);
    })
    ->values();
    
    if($soal->isEmpty()) {
        return redirect()->back()->with('error', 'Belum ada soal untuk ujian ini');
    }
    
    return view("siswa.ujian", compact("uji", "soal", "ire", "sis", "ujians", "peserta"));
}
   






    public function Saved(Request $request)
{
    // Log awal request
    Log::info('=== SAVED METHOD CALLED ===');
    Log::info('Request data:', $request->all());
    
    try {
        // Validasi
        Log::info('Validating request...');
        $request->validate([
            "ujian_id" => "required|integer|exists:ujian,id",
            "siswa_id" => "required|exists:siswa,id_siswa",
            "jawaban" => "nullable|array",
            "file_jawaban" => "nullable|array",
            "file_jawaban.*" => "nullable|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip,mp4",
        ]);
        Log::info('Validation passed');

        $ujianId = $request->ujian_id;
        Log::info('Ujian ID: ' . $ujianId);
        
        $uji = Ujian::findOrFail($ujianId);
        $siswaId = (string)$request->siswa_id; 
        $siswa = Siswa::where("id_siswa", $siswaId)->first();
        
        Log::info('Mode ujian: ' . $uji->mode);
        
        // Redis: Cegah double submit
        if($uji->mode === "praktik"){
            Log::info('Mode PRAKTIK, checking existing submission...');
            $alreadySubmit = Jawaban_Siswa::where('ujian_id', $ujianId)
                ->where('siswa_id', $siswaId)
                ->exists();
                
            if ($alreadySubmit) {
                Log::warning('Already submitted in PRAKTIK mode');
                return redirect()
                    ->route('siswa.index')
                    ->with('error', 'Kamu sudah mengerjakan ujian ini!');
            }
        }
        
        if($uji->mode === "cbt"){
            Log::info('Mode CBT, updating status to unready...');
            $siswa->update(["status" => "unready"]);
            Log::info('Status updated, new status: ' . $siswa->fresh()->status);
        }
        
        // Redis lock
        $redis = Redis::connection();
        $key = "ujian:submit:{$ujianId}:{$siswaId}";
        $jawabanRaw = $request->jawaban ?? [];
    $jawaban = [];
    
    foreach ($jawabanRaw as $soalId => $jawab) {
        // Jika nilai "-" atau kosong, simpan sebagai string kosong
        $jawaban[$soalId] = ($jawab === '-' || $jawab === '') ? '' : $jawab;
    }
        
        if ($redis->get($key)) {
            Log::warning('Redis lock detected for key: ' . $key);
            return redirect()->route("siswa.index")->with("error", "Ujian sedang diproses, mohon tunggu!");
        }
        $redis->setex($key, 300, true);
        Log::info('Redis lock acquired for key: ' . $key);
        
        // --- PROSES KHUSUS MODE PRAKTIK (UPLOAD FILE) ---
        $filesPath = [];
        if ($request->hasFile('file_jawaban')) {
            Log::info('Processing file uploads...');
            foreach ($request->file('file_jawaban') as $soalId => $file) {
                if ($file && $file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$siswaId}_{$ujianId}_{$soalId}_" . Str::random(5) . ".{$extension}";
                    $path = $file->storeAs('jawaban_praktik', $filename, 'public');
                    $filesPath[$soalId] = $path;
                    Log::info('File saved: ' . $path);
                }
            }
        }
        
        // Data yang dikirim ke Job
        $dataJob = [
            'ujian_id'       => $ujianId,
            'siswa_id'       => $siswaId,
            'jawaban'        => $jawaban,
            'file_jawaban'   => $filesPath, 
            'mode_ujian'     => $uji->mode ?? 'cbt',
        ];
        
        Log::info('Dispatching job...');
        ProsesUjianJob::dispatch($dataJob);
        Log::info('Job dispatched successfully');
        
        Log::info('Redirecting to siswa.index');
        return redirect()->route("siswa.index")
            ->with("success", "Jawaban sedang disimpan. Nilai Ujian Mu bisa dilihat di halaman Riwayat");
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('VALIDATION ERROR: ' . json_encode($e->errors()));
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('MODEL NOT FOUND: ' . $e->getMessage());
        return redirect()->route("siswa.index")
            ->with("error", "Data ujian atau siswa tidak ditemukan!");
            
    } catch (\Exception $e) {
        Log::error('UNEXPECTED ERROR in Saved():');
        Log::error('Message: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return redirect()->route("siswa.index")
            ->with("error", "Terjadi kesalahan: " . $e->getMessage());
    }
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
    $siswa = Siswa::with('kelas','user')
        ->where('nama', $user->nama)
        ->first();
    
    if(!$siswa) {
        return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan');
    }
    
    // Ambil ujian HARI INI saja untuk dashboard
    $today = now()->format('Y-m-d');
    
    $uji = Ujian::with(['mapels', 'jadwal', 'kelas'])->where("mode","cbt")
        ->whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('waktu_mulai', $today);
        })
        ->whereHas('kelas', function($query) use ($siswa) {
            $query->where('kelas_ujian.kelas_id', $siswa->kelas_id);
        })
        ->with(['peserta' => function($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id_siswa);
        }])
        ->latest()->first();
     $praktik = Ujian::with("mapels","jadwal","kelas")
        ->where("mode","praktik")
        ->whereHas('jadwal', function($query) {
    $query->where('waktu_mulai', '<=', now())
          ->where('waktu_selesai', '>=', now());
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
        ->count();
    
    // Hitung rata-rata nilai
    $rataNilai = Peserta_ujian::where('siswa_id', $siswa->id_siswa)
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
        "praktik",
        "totalUjian", 
        "ujianSelesai", 
        "rataNilai",
        "jadwalMendatang",
        "today"
    ));
}
public function reportViolation(Request $request)
{
    \Log::info('=== REPORT VIOLATION DIPANGGIL ===');
    \Log::info('Request data:', $request->all());
    
    $validated = $request->validate([
        'ujian_id' => 'required|exists:ujian,id',
        'siswa_id' => 'required|exists:siswa,id_siswa',
        'jenis_pelanggaran' => 'required|string',
        'detail' => 'nullable|string',
        'user_agent' => 'nullable|string',
        'screen_resolution' => 'nullable|string',
        'timestamp' => 'nullable|string'
    ]);
    
    // Cari siswa
    $siswa = Siswa::where('id_siswa', $validated['siswa_id'])->first();
    
    if (!$siswa) {
        return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
    }
    
    // Simpan pelanggaran ke tabel riwayat
    try {
        $violation = Pelanggaran::create([
            'ujian_id' => $validated['ujian_id'],
            'siswa_id' => $siswa->id_siswa,
            'jenis_pelanggaran' => $validated['jenis_pelanggaran'],
            'detail' => $validated['detail'] ?? null,
            'user_agent' => $validated['user_agent'] ?? null,
            'screen_resolution' => $validated['screen_resolution'] ?? null,
            'timestamp' => $validated['timestamp'] ?? now(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal simpan: ' . $e->getMessage()], 500);
    }
    
    // ========== AMBIL ATAU BUAT BlockSiswa ==========
    $block = BlockSiswa::firstOrCreate(
        [
            'siswa_id' => $siswa->id_siswa,
            'ujian_id' => $validated['ujian_id'],
        ],
        [
            'violation_count' => 0,  // default 0
        ]
    );
    
    // ========== INCREMENT violation_count +1 SETIAP PELANGGARAN ==========
    $block->increment('violation_count');
    
    // Ambil nilai terbaru setelah increment
    $totalViolations = $block->violation_count;
    
    \Log::info('Violation count sekarang: ' . $totalViolations);
    
    // JIKA SUDAH MENCAPAI 3 PELANGGARAN, SET BLOKIR
    if ($totalViolations >= 3 && !$block->blocked_at) {
        $block->update([
            'blocked_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);
        \Log::info('Siswa diblokir! Pelanggaran ke-' . $totalViolations);
    }
    
    // JIKA BELUM MENCAPAI 3, PASTIKAN TIDAK TERBLOKIR
    if ($totalViolations < 3 && $block->blocked_at) {
        $block->update([
            'blocked_at' => null,
            'expires_at' => null,
        ]);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Pelanggaran tercatat',
        'violation_id' => $violation->id,
        'blocked' => $totalViolations >= 3,
        'violation_count' => $totalViolations,  // 1, 2, 3, 4, 5, ...
        'max_violation' => 3,
        'block_expiry' => $block->expires_at ?? null,
        'message' => $totalViolations >= 3 ? 'Akun Anda diblokir karena melanggar aturan ujian' : null
    ]);
}
 public function checkBlockStatus($siswa_id, $ujian_id)
{
    $siswa = Siswa::where('id_siswa', $siswa_id)->first();
    
    if (!$siswa) {
        return response()->json([
            'is_blocked' => false, 
            'total_violations' => 0,
            'error' => 'Siswa tidak ditemukan'
        ]);
    }
    
    // CEK DI TABEL BLOCK_SISWA
    $block = BlockSiswa::where('siswa_id', $siswa->id_siswa)
        ->where('ujian_id', $ujian_id)
        ->first();
    
    // Gunakan nama field yang SAMA dengan frontend
    $isBlocked = $block && $block->blocked_at && ($block->expires_at > now());
    $violationCount = $block ? $block->violation_count : 0;
    
    return response()->json([
        'is_blocked' => $isBlocked,           // ← Ubah dari 'blocked'
        'total_violations' => $violationCount, // ← Ubah dari 'violation_count'
        'max_violation' => 3,
        'block_expiry' => $block->expires_at ?? null,
        'block_message' => $isBlocked ? 'Akun Anda diblokir karena melanggar aturan ujian' : null
    ]);
}

 
 
}

