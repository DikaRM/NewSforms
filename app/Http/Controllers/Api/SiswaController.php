<?php

namespace App\Http\Controllers\Api;

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

class SiswaController extends Controller
{
    private function getSiswaFromUser($user)
    {
        return Siswa::with('kelas')->where('user_id', $user->id)->first();
    }

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

        $riwayat = Peserta_ujian::with(['ujian.mapels'])
            ->where('siswa_id', $siswa->id_siswa)
            ->whereNotNull('nilai')
            ->get()
            ->map(function($peserta) {
                return [
                    'id' => $peserta->ujian->id,
                    'nama_ujian' => $peserta->ujian->nama_ujian,
                    'mapel' => $peserta->ujian->mapels->nama_mapel ?? 'Unknown',
                    'nilai' => $peserta->nilai,
                    'tanggal_selesai' => $peserta->updated_at,
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
    public function mulaiUjian(Request $request, $id)
{
    $user = $request->user();
    $siswa = $this->getSiswaFromUser($user);

    $ujian = Ujian::with(['mapels', 'jadwal'])->where('id', $id)->first();
    
    if (!$ujian) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ujian tidak ditemukan'
        ], 404);
    }

    $existingPeserta = Peserta_ujian::where('ujian_id', $id)
        ->where('siswa_id', $siswa->id_siswa)
        ->first();

    if ($existingPeserta && $existingPeserta->nilai) {
        return response()->json([
            'status' => 'error',
            'message' => 'Anda sudah mengerjakan ujian ini'
        ], 400);
    }

    // ✅ Perbaikan: Ambil soal dan jawaban sekaligus
    $soalIds = Ujian_soals::where('ujian_id', $id)->pluck('bank_id');
    $soal = banksoal::whereIn('id', $soalIds)->get();
    
    // ✅ Ambil semua jawaban sebelumnya dalam 1 query!
    $jawabanSebelumnya = Jawaban_Siswa::where('ujian_id', $id)
        ->where('siswa_id', $siswa->id_siswa)
        ->whereIn('bank_id', $soalIds)
        ->get()
        ->keyBy('bank_id');  // Key by bank_id untuk akses cepat

    if ($soal->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Belum ada soal untuk ujian ini'
        ], 404);
    }

    $peserta = Peserta_ujian::updateOrCreate(
        [
            'ujian_id' => $id,
            'siswa_id' => $siswa->id_siswa,
        ],
        [
            'status' => 'ongoing',
            'mulai_pengerjaan' => now(),
        ]
    );

    // ✅ Format soal tanpa query tambahan
    $soalList = $soal->map(function($item) use ($jawabanSebelumnya) {
        $jawaban = $jawabanSebelumnya->get($item->id);
        
        return [
            'id' => $item->id,
            'pertanyaan' => $item->pertanyaan,
            'tipe' => $item->opsi_a ? 'pilihan_ganda' : 'essay',
            'opsi' => $item->opsi_a ? [
                'a' => $item->opsi_a,
                'b' => $item->opsi_b,
                'c' => $item->opsi_c,
                'd' => $item->opsi_d,
                'e' => $item->opsi_e,
            ] : null,
            'gambar' => $item->gambar,
            'jawaban_sebelumnya' => $jawaban ? $jawaban->jawaban : null,
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => [
            'ujian' => [...],
            'soal' => $soalList,
            'total_soal' => count($soalList),
        ]
    ], 200);
}

    /**
     * Simpan jawaban ujian
     */
    public function simpanJawaban(Request $request)
{
    $request->validate([
        'ujian_id' => 'required|integer',
        'jawaban' => 'required|array',
    ]);

    $user = $request->user();
    $siswa = $this->getSiswaFromUser($user);

    $jawabanSiswa = $request->jawaban;
    $soal_ids = array_keys($jawabanSiswa);
    $soals = banksoal::whereIn("id", $soal_ids)->get()->keyBy("id");
    
    $score = 0;
    $total_soal = count($jawabanSiswa);
    
    // ✅ Siapkan data untuk batch insert/update
    $jawabanData = [];
    
    foreach($jawabanSiswa as $soal_id => $jawabans) {
        $soal = $soals[$soal_id] ?? null;
        if (!$soal) continue;
        
        $benar = 0;
        
        if($soal->opsi_a != null) {
            $benar = (strtoupper(trim($jawabans)) == strtoupper(trim($soal->jawaban_benar))) ? 1 : 0;
        } else {
            $nilai = $this->hitungNilaiEssay($jawabans, $soal->jawaban_benar);
            $benar = ($nilai >= 80) ? 1 : 0;
        }
        
        if($benar) {
            $score += 1;
        }
        
        $jawabanData[] = [
            "ujian_id" => $request->ujian_id,
            "siswa_id" => $siswa->id_siswa,
            "bank_id" => $soal->id,
            "jawaban" => $jawabans,
            "benar" => $benar,
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
    
    // ✅ Batch insert/update (gunakan upsert)
    Jawaban_Siswa::upsert(
        $jawabanData,
        ['ujian_id', 'siswa_id', 'bank_id'], // Unique constraint
        ['jawaban', 'benar', 'updated_at']   // Fields to update
    );
    
    $nilai = ($total_soal > 0) ? round(($score / $total_soal) * 100, 2) : 0;
    
    Peserta_ujian::updateOrCreate([
        "ujian_id" => $request->ujian_id,
        "siswa_id" => $siswa->id_siswa,
    ], [
        "nilai" => $nilai,
        "status" => "done",
        "selesai_pengerjaan" => now(),
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Ujian selesai!',
        'data' => [
            'nilai' => $nilai,
            'benar' => $score,
            'total_soal' => $total_soal,
        ]
    ], 200);
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