<?php
        
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Redis;
use App\Jobs\ProsesUjianJob;
use Illuminate\Support\Facades\Log;
        use App\Http\Controllers\Controller;
        use App\Models\Siswa;
        use App\Models\Ujian;
        use App\Models\banksoal;
        use App\Models\Ujian_soals;
        use App\Models\Jawaban_Siswa;
        use App\Models\Peserta_ujian;
        use App\Models\Jadwal;
        use App\Models\Pelanggaran;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Validator;
        use Carbon\Carbon;
        class SiswaController extends Controller
        {

            /**
             * Get jadwal ujian siswa
             */
            public function jadwal(Request $request)
        {
            $user = $request->user();
            $siswa = $this->getSiswaFromUser($user);

            // ✅ Perbaikan: Gunakan left join dan subquery
            $jadwal = Jadwal::with(['ujian.mapels'])
                ->where('kelas_id', $siswa->kelas_id)
                ->whereHas('ujian', function($q) {
                    $q->where('status', 'ready');
                })
                ->with(['pesertaUjian' => function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id_siswa);
                }])  // ✅ Eager loading peserta ujian!
                ->get()
                ->map(function($item) use ($siswa) {
                    $peserta = $item->pesertaUjian->first(); // ✅ Sudah tersedia, tidak query lagi
                    
                    return [
                        'id' => $item->id,
                        'ujian_id' => $item->ujian->id,
                        'nama_ujian' => $item->ujian->nama_ujian,
                        'mapel' => $item->ujian->mapels->nama_mapel ?? 'Unknown',
                        'tanggal' => $item->waktu_mulai,
                        'durasi' => $item->ujian->durasi ?? 120,
                        'status_peserta' => $peserta ? ($peserta->nilai ? 'selesai' : 'belum') : 'belum',
                        'nilai' => $peserta ? $peserta->nilai : null,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $jadwal
            ], 200);
        }

            /**
             * Get riwayat ujian siswa
             */
            public function riwayat(Request $request)
            {
                $user = $request->user();
                $siswa = $this->getSiswaFromUser($user);
                $riwayat = Peserta_ujian::with(['ujian.mapels','ujian.jadwal'])
                    ->where('siswa_id', $siswa->id_siswa)
                    ->whereNotNull('nilai')
                    ->get()
                    ->map(function($peserta) {
                        return [
                            'id' => $peserta->ujian->id,
                            'nama_ujian' => $peserta->ujian->nama_ujian,
                            'mapel' => $peserta->ujian->mapels->nama_mapel ?? 'Unknown',
                            'nilai' => $peserta->nilai,
                            'tanggal_selesai' => Carbon::parse(
    $peserta->ujian->jadwal->tanggal
)
->locale('id')
->translatedFormat('l, d F Y'),
                            'status' => 'selesai',
                        ];
                    });

                return response()->json([
                    'status' => 'success',
                    'data' => $riwayat
                ], 200);
            }

            /**
            * Mulai ujian
            */
           

            /**
            * Simpan jawaban ujian
            */
public function simpanJawaban(Request $request)
    {
         $jawaban = $request->input('jawaban', []);
    
    // Jika jawaban adalah string (error), konversi ke array
    if (is_string($jawaban)) {
        // Coba decode JSON jika mungkin
        $decoded = json_decode($jawaban, true);
        if (is_array($decoded)) {
            $jawaban = $decoded;
        } else {
            $jawaban = [];
        }
    }
    
    // Jika masih tidak array, set ke array kosong
    if (!is_array($jawaban)) {
        $jawaban = [];
    }
    
    // Log untuk debugging
    Log::info('Jawaban yang diterima', [
        'type' => gettype($jawaban),
        'data' => $jawaban
    ]);
        $ujianId = $request->ujian_id;
        $user = $request->user();
        $siswa = $this->getSiswaFromUser($user);
        
        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $uji = Ujian::find($ujianId);
        if (!$uji) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ujian tidak ditemukan'
            ], 404);
        }

        $modeUjian = $uji->mode ?? 'cbt';
        
        // ========== VALIDASI SESUAI MODE UJIAN ==========
        if ($modeUjian === 'praktik') {
            // Cek apakah sudah submit sebelumnya
            $sudahSubmit = Jawaban_Siswa::where('ujian_id', $ujianId)
                ->where('siswa_id', $siswa->id_siswa)
                ->exists();
                
            if ($sudahSubmit) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kamu sudah mengumpulkan tugas praktik ini!'
                ], 400);
            }
            
            // Validasi: Pastikan ada file untuk soal tipe upload
            $soalList = $uji->soals()
    ->where('tipe', 'upload')
    ->get();
                
            foreach ($soalList as $soal) {
                $soalId = $soal->id;
                $fileKey = "file_jawaban.{$soalId}";
                
                if (!$request->hasFile($fileKey) || !$request->file($fileKey)->isValid()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "File untuk soal ID {$soalId} wajib diupload"
                    ], 400);
                }
            }
        } else {
            // Mode CBT: Pastikan ada jawaban
            if (empty($request->jawaban)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jawaban tidak boleh kosong'
                ], 400);
            }
            
            // Cek apakah sudah mengerjakan
            $sudahMengerjakan = Peserta_ujian::where('ujian_id', $ujianId)
                ->where('siswa_id', $siswa->id_siswa)
                ->where('status', 'done')
                ->exists();
                
            if ($sudahMengerjakan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kamu sudah mengerjakan ujian ini!'
                ], 400);
            }
        }

        // ========== REDIS LOCK (Cegah Double Submit) ==========
        $redis = Redis::connection();
        $lockKey = "ujian:submit:{$ujianId}:{$siswa->id_siswa}";
        $lockValue = uniqid('', true);
        $locked = $redis->set($lockKey, $lockValue, 'EX', 300, 'NX');
        
        if (!$locked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ujian sedang diproses, mohon tunggu!'
            ], 429);
        }

        try {
            // ========== PROSES FILE UPLOAD (Untuk Mode Praktik) ==========
            $filesPath = [];
            
            if ($request->hasFile('file_jawaban')) {
                foreach ($request->file('file_jawaban') as $soalId => $file) {
                    if ($file && $file->isValid()) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = "{$siswa->id_siswa}_{$ujianId}_{$soalId}_" . time() . "_" . uniqid() . ".{$extension}";
                        $path = $file->storeAs('jawaban_praktik', $filename, 'public');
                        $filesPath[$soalId] = $path;
                        
                        Log::info("File uploaded for job", [
                            'soal_id' => $soalId,
                            'path' => $path,
                            'siswa_id' => $siswa->id_siswa
                        ]);
                    }
                }
            }

            // ========== DATA UNTUK JOB ==========
            $dataJob = [
                'ujian_id' => $ujianId,
                'siswa_id' => $siswa->id_siswa,
                'jawaban' => $jawaban,
                'file_jawaban' => $filesPath,
                'mode_ujian' => $modeUjian,
                'waktu_submit' => now()->toDateTimeString(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ];

            // Dispatch ke queue
            ProsesUjianJob::dispatch($dataJob);
            
            // Hapus lock setelah dispatch
            $redis->del($lockKey);

            // ========== RESPONSE BERDASARKAN MODE ==========
            $successMessage = $modeUjian === 'praktik'
                ? "Tugas praktik berhasil dikumpulkan! Jawaban sedang diproses."
                : "Jawaban berhasil disimpan! Sedang diproses. Nilai akan muncul dalam beberapa saat.";

            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
                'data' => [
                    'mode' => $modeUjian,
                    'ujian_id' => $ujianId,
                    'total_answers' => count($request->jawaban ?? []),
                    'total_files' => count($filesPath),
                    'status' => 'processing'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            // Hapus lock jika error
            $redis->del($lockKey);
            
            Log::error("Failed to dispatch job", [
                'ujian_id' => $ujianId,
                'siswa_id' => $siswa->id_siswa,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek status submission (Polling)
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|integer',
        ]);

        $user = $request->user();
        $siswa = $this->getSiswaFromUser($user);
        
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $peserta = Peserta_ujian::where('ujian_id', $request->ujian_id)
            ->where('siswa_id', $siswa->id_siswa)
            ->first();

        if (!$peserta) {
            return response()->json([
                'status' => 'processing',
                'message' => 'Ujian sedang diproses'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'nilai' => $peserta->nilai,
                'status' => $peserta->status,
                'selesai_pengerjaan' => $peserta->selesai_pengerjaan
            ]
        ]);
    }

    /**
     * Auto Save (Untuk CBT)
     */
    public function autoSave(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|integer',
            'soal_id' => 'required|integer',
            'jawaban' => 'nullable|string',
        ]);

        $user = $request->user();
        $siswa = $this->getSiswaFromUser($user);
        
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        // Simpan ke Redis cache (expire 1 jam)
        $key = "auto_save:{$request->ujian_id}:{$siswa->id_siswa}:{$request->soal_id}";
        Redis::setex($key, 3600, $request->jawaban ?? '');

        return response()->json([
            'status' => 'success',
            'message' => 'Auto-save berhasil'
        ]);
    }

    /**
     * Mulai Ujian (Ambil Soal)
     */
     public function mulaiUjian(Request $request, $id)
{
    $user = $request->user();
    $siswa = Siswa::where('user_id', $user->id)->first();
    
    if (!$siswa) {
        return response()->json([
            'status' => 'error',
            'message' => 'Siswa tidak ditemukan'
        ], 404);
    }
    
    $ujianId = $id;
    $ujian = Ujian::find($ujianId);
    
    if (!$ujian) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ujian tidak ditemukan'
        ], 404);
    }
    
    // Ambil soal melalui relasi many-to-many
    $soalList = $ujian->soals()->get();
    
    // Ambil jawaban sebelumnya dari auto-save (Redis)
    $jawabanSebelumnya = [];
    foreach ($soalList as $soal) {
        $key = "auto_save:{$ujianId}:{$siswa->id_siswa}:{$soal->id}";
        $savedAnswer = Redis::get($key);
        if ($savedAnswer) {
            $jawabanSebelumnya[$soal->id] = $savedAnswer;
        }
    }
    
    return response()->json([
        'status' => 'success',
        'message' => 'Soal berhasil dimuat',
        'data' => [
            'ujian' => [
                'id' => $ujian->id,
                'nama' => $ujian->nama_ujian,
                'durasi' => $ujian->durasi,
                'mode' => $ujian->mode ?? 'cbt',
            ],
            'soal' => $soalList->map(function($soal) use ($jawabanSebelumnya) {
                // Tentukan tipe soal berdasarkan kolom 'tipe' di database
                // Nilai yang mungkin: 'pg', 'essay', 'upload', 'av'
                $tipe = $soal->tipe ?? 'pg';
                
                // Siapkan opsi (untuk tipe pg dan av yang memiliki opsi)
                $opsi = null;
                if ($tipe == 'pg' || $tipe == 'av') {
                    $opsi = [];
                    if ($soal->opsi_a != null && $soal->opsi_a != '') {
                        $opsi['a'] = $soal->opsi_a;
                    }
                    if ($soal->opsi_b != null && $soal->opsi_b != '') {
                        $opsi['b'] = $soal->opsi_b;
                    }
                    if ($soal->opsi_c != null && $soal->opsi_c != '') {
                        $opsi['c'] = $soal->opsi_c;
                    }
                    if ($soal->opsi_d != null && $soal->opsi_d != '') {
                        $opsi['d'] = $soal->opsi_d;
                    }
                    if ($soal->opsi_e != null && $soal->opsi_e != '') {
                        $opsi['e'] = $soal->opsi_e;
                    }
                    
                    // Jika tidak ada opsi sama sekali, set ke null
                    if (empty($opsi)) {
                        $opsi = null;
                    }
                }
                
                // Siapkan media untuk tipe AV
                $mediaUrl = null;
                $mediaFile = null;
                if ($tipe == 'av') {
                    if (!empty($soal->media_url)) {
                        $mediaUrl = $soal->media_url;
                    }
                    if (!empty($soal->media_file)) {
                        $mediaFile = asset('storage/' . $soal->media_file);
                    }
                }
                
                return [
                    'id' => $soal->id,
                    'pertanyaan' => $soal->soal,
                    'tipe' => $tipe,  // 'pg', 'essay', 'upload', 'av'
                    'opsi' => $opsi,   // Ada isinya jika tipe pg atau av yang punya opsi
                    'media_url' => $mediaUrl,
                    'media_file' => $mediaFile,
                    'jawaban_sebelumnya' => $jawabanSebelumnya[$soal->id] ?? null,
                ];
            }),
        ]
    ], 200);
}
    /**
     * Helper functions
     */
    private function getSiswaFromUser($user)
    {
        if ($user->role === 'siswa') {
            return Siswa::where('user_id', $user->id)->first();
        }
        return null;
    }

            /**
            * Hitung nilai essay
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

            /**
            * Catat pelanggaran
            */
            public function pelanggaran(Request $request)
            {
                $request->validate([
                    'ujian_id' => 'required|integer',
                    'jenis_pelanggaran' => 'required|string',
                ]);

                $user = $request->user();
                $siswa = $this->getSiswaFromUser($user);

                $pelanggaran = Pelanggaran::create([
                    "ujian_id" => $request->ujian_id,
                    "siswa_id" => $siswa->id_siswa,
                    "jenis_pelanggaran" => $request->jenis_pelanggaran,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Pelanggaran dicatat',
                    'data' => $pelanggaran
                ], 200);
            }

            /**
            * Simpan jawaban sementara (untuk auto-save)
            */
            public function simpanJawabanSementara(Request $request)
            {
                $request->validate([
                    'ujian_id' => 'required|integer',
                    'bank_id' => 'required|integer',
                    'jawaban' => 'required|string',
                ]);

                $user = $request->user();
                $siswa = $this->getSiswaFromUser($user);

                Jawaban_Siswa::updateOrCreate([
                    "ujian_id" => $request->ujian_id,
                    "siswa_id" => $siswa->id_siswa,
                    "bank_id" => $request->bank_id,
                ], [
                    "jawaban" => $request->jawaban,
                    "benar" => 0, // Sementara, akan dihitung saat submit
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Jawaban disimpan sementara'
                ], 200);
            }
        }