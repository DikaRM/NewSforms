<?php

namespace App\Jobs;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Illuminate\Support\Facades\Redis;
use App\Models\Siswa;
use App\Models\banksoal;
use App\Models\Jawaban_Siswa;
use App\Models\Peserta_ujian;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProsesUjianJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $tries = 3;
    public $timeout = 120;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $ujianId = null;
    $siswaId = null;
        try {
            $ujianId = $this->data['ujian_id'];
$siswaId = $this->data['siswa_id'];

$jawabanSiswa = $this->data['jawaban'] ?? [];
$fileJawaban = $this->data['file_jawaban'] ?? [];

$modeUjian = $this->data['mode_ujian'] ?? 'cbt';
            
            $siswa = Siswa::where('id_siswa', $siswaId)->first();
            if (!$siswa) {
                throw new \Exception("Siswa tidak ditemukan");
            }
            
            // Cek duplikat
            $existing = Peserta_ujian::where('ujian_id', $ujianId)
                ->where('siswa_id', $siswaId)
                ->where('status', 'done')
                ->first();
                
            if ($existing) {
                Log::info("Job skip: sudah diproses sebelumnya");
                return;
            }
            
            $soal_ids = array_unique(array_merge(
    array_keys($jawabanSiswa ?? []),
    array_keys($fileJawaban ?? [])
));
            $soals = banksoal::whereIn("id", $soal_ids)->get()->keyBy("id");
            
            $score = 0;
$total_soal = count($jawabanSiswa);

$bobotPerSoal = $total_soal > 0 ? (100 / $total_soal) : 0;
            
            foreach($soal_ids as $soal_id)
{
    $jawabans = $jawabanSiswa[$soal_id] ?? null;
                $soal = $soals[$soal_id] ?? null;
                if (!$soal) continue;
                
                // Default nilai benar adalah 0
                $benar = 0;
                
                // 1. LOGIKA UNTUK MODE PRAKTIK
                // Jika mode praktik, kita tidak menilai otomatis. 
                // Nilai tetap 0, nanti guru yang menilai manual.
                if ($modeUjian === 'praktik') {
                    $benar = 0; 
                    
                    // Jika ingin lebih spesifik: Cek apakah soal ini punya file?
                    // if (isset($fileJawaban[$soal_id])) { ... }
                } 
                // 2. LOGIKA UNTUK MODE CBT (Ketat)
                else {
                    if($soal->opsi_a != null) {
                        // Pilihan Ganda
                        if (strtoupper(trim($jawabans)) == strtoupper(trim($soal->jawaban_benar))) {
    
    $benar = 1;

} else {

    $benar = 0;
}
 if ($benar == 1) {
        $score += $bobotPerSoal;
    }
                    } else {
                        // Essay (Auto Grade Similarity Text)
                        $similarity = $this->hitungNilaiEssay(
    $jawabans,
    $soal->jawaban_benar
);

// nilai parsial essay
$nilaiEssay = ($similarity / 100) * $bobotPerSoal;

// tambah ke total score
$score += $nilaiEssay;

// hanya indikator
$benar = $similarity >= 50 ? 1 : 0;
                    }
                }
                
                
                
                // SIMPAN JAWABAN KE DATABASE
                Log::info([
    'soal_id' => $soal_id,
    'file' => $fileJawaban[$soal_id] ?? null
]);
                Jawaban_Siswa::updateOrCreate(
                    [
                        'ujian_id' => $ujianId,
                        'siswa_id' => $siswaId,
                        'bank_id' => $soal->id
                    ],
                    [
                        'jawaban' => $jawabans,
                        'benar' => $benar,
                        'file_jawaban' => $fileJawaban[$soal_id] ?? null, // Simpan path file jika ada
                        'updated_at' => now(),
                    ]
                );
            }
            
            // HITUNG NILAI AKHIR
            // Jika Praktik, nilai akhir 0 (menunggu koreksi guru)
            // Jika CBT, nilai dihitung otomatis
            if ($modeUjian === 'praktik') {
                $nilai = 0; 
            } else {
                $nilai = round($score);
            }
            Peserta_ujian::updateOrCreate(
                [
                    "ujian_id" => $ujianId,
                    "siswa_id" => $siswaId,
                ],
                [
                    "nilai" => $nilai,
                    "status" => "done",
                ]
            );
            
            
            Log::info("Job BERHASIL untuk siswa {$siswaId}, Mode: {$modeUjian}, Nilai: {$nilai}");
            
        } catch (\Exception $e) {
            Log::error("Job GAGAL: " . $e->getMessage());
            throw $e;
        }finally {

        $redis = Redis::connection();

        $key = "ujian:submit:{$ujianId}:{$siswaId}";

        $redis->del($key);

        Log::info("Redis lock dihapus: {$key}");
    }
    }

    private function hitungNilaiEssay($jawaban, $kunci)
{
    $jawaban = $this->normalizeText($jawaban);
    $kunci = $this->normalizeText($kunci);

    $jawaban = $this->removeStopwords($jawaban);
    $kunci = $this->removeStopwords($kunci);

    $jawaban = $this->stemming($jawaban);
    $kunci = $this->stemming($kunci);

    $similarity = $this->tfidfSimilarity($jawaban, $kunci);

$spam = $this->antiSpam($jawaban);

$panjang = $this->cekPanjangJawaban($jawaban);

$final =
    $similarity *
    ($spam / 100) *
    ($panjang / 100);

return round($final, 2);
}
    private function normalizeText($text)
{
    $text = strtolower($text);
    
    // hapus simbol
    $text = preg_replace('/[^\w\s]/', '', $text);

    // rapikan spasi
    $text = preg_replace('/\s+/', ' ', $text);

    return trim($text);
}
private function stemming($text)
{
    $factory = new StemmerFactory();
    
    $stemmer = $factory->createStemmer();

    return $stemmer->stem($text);
}
private function cekPanjangJawaban($jawaban)
{
    $jumlahKata = str_word_count($jawaban);

    if ($jumlahKata <= 2) {
        return 50;
    }

    return 100;
}
private function antiSpam($jawaban)
{
    $words = explode(' ', $jawaban);

    $unique = array_unique($words);

    $ratio = count($unique) / max(count($words), 1);

    return $ratio * 100;
}
private function removeStopwords($text)
{
    $factory = new StopWordRemoverFactory();

    $stopword = $factory->createStopWordRemover();

    return $stopword->remove($text);
}
private function tfidfSimilarity($text1, $text2)
{
    $doc1 = explode(' ', $text1);
    $doc2 = explode(' ', $text2);

    $allWords = array_unique(array_merge($doc1, $doc2));

    $tf1 = [];
    $tf2 = [];
    $idf = [];

    foreach ($allWords as $word) {

        // TF
        $tf1[$word] = substr_count($text1, $word);
        $tf2[$word] = substr_count($text2, $word);

        // DF
        $df = 0;

        if (in_array($word, $doc1)) $df++;
        if (in_array($word, $doc2)) $df++;

        // IDF
        $idf[$word] = log(2 / (1 + $df));
    }

    $vec1 = [];
    $vec2 = [];

    foreach ($allWords as $word) {
        $vec1[$word] = $tf1[$word] * $idf[$word];
        $vec2[$word] = $tf2[$word] * $idf[$word];
    }

    // Cosine Similarity
    $dot = 0;
    $norm1 = 0;
    $norm2 = 0;

    foreach ($allWords as $word) {

        $dot += $vec1[$word] * $vec2[$word];

        $norm1 += pow($vec1[$word], 2);
        $norm2 += pow($vec2[$word], 2);
    }

    if ($norm1 == 0 || $norm2 == 0) {
        return 0;
    }

    $similarity = $dot / (sqrt($norm1) * sqrt($norm2));

    return round($similarity * 100, 2);
}

}