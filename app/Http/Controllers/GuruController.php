<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\BankSoalImport;
use App\Imports\BankSoalImportPreview;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Guru;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Peserta_ujian;
use App\Models\banksoal ;
use App\Models\GuruMapel;
use App\Models\Ujian_soals;
use PhpOffice\PhpSpreadsheet\IOFactory;
class GuruController
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
      $pass = Hash::make($request->password);
      User::create([
        "nama" => $request->username,
        "password" => $pass,
        "role" => "guru",
        ]);
      $whe = User::where("nama",$request->username)->first();
        Guru::create([
        "user_id" => $whe->id,
        "nama" => $request->username,
        "nip" => $request->nip,
        ]);
      return redirect()->route("admin.guru.index")->with("succes","Berhasil Menambah Data");
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
        $dat = Guru::findOrFail($id);
        $usew = User::findOrFail($id);
        $pa = Hash::make($request->password);
        $usew->update([
          "nama" => $request->username,
          "password" => $pa,
          ]);
        $dat->update([
          "nama" => $request->username,
          "nip" => $request->nip,
          ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $gu = Guru::findOrFail($id);
        
         $gu->delete();
        return redirect()->route("admin.guru.index")->with("succes","Berhasil Dihapus");
    }
    public function TeachIndex()
    {
      $ire = Auth::user();
      $dt = Guru::where("nama",$ire->nama)->first();
      $uji = Ujian::with("jadwal")->where("guru_id",$dt->id)->get();
      $klas = Kelas::all();
      $sd = Guru::with("mapel")->where("id",$dt->id)->get();
      $guruMapel = GuruMapel::with("mapel")->where("guru_id",$dt->id)->get();
      $map = Mapel::all();
      $kelasList = Kelas::all();
      return view("guru.index",compact("ire","uji","dt","klas","kelasList","sd","guruMapel"));
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
    $request->validate([
        'guru_id' => 'required|exists:guru,id',
        'mapel_id' => 'required|exists:mapel,id',
        'soal' => 'required|array|min:1',
        'soal.*.soal' => 'required|string',
        'soal.*.opsi_a' => 'required|string',
        'soal.*.opsi_b' => 'required|string',
        'soal.*.opsi_c' => 'required|string',
        'soal.*.opsi_d' => 'required|string',
        'soal.*.jawaban_benar' => 'required|in:a,b,c,d',
        'soal.*.gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        DB::beginTransaction();

        // Cari ujian
        $ujian = Ujian::findOrFail($id);
        
        // ============================================
        // OPTIMASI 1: BATCH INSERT BANK SOAL
        // ============================================
        $bankData = [];
        $gambarPaths = []; // Simpan path gambar untuk referensi
        
        // Loop untuk menyiapkan data batch
        foreach ($request->soal as $index => $soalData) {
            // Upload gambar jika ada
            $gambarPath = null;
            if (isset($soalData['gambar']) && $soalData['gambar']->isValid()) {
                $gambarPath = $soalData['gambar']->store('soal_images', 'public');
                $gambarPaths[$index] = $gambarPath;
            }

            // Siapkan data untuk batch insert
            $bankData[] = [
                'guru_id' => $request->guru_id,
                'mapel_id' => $request->mapel_id,
                'soal' => $soalData['soal'],
                'gambar' => $gambarPath,
                'opsi_a' => $soalData['opsi_a'],
                'opsi_b' => $soalData['opsi_b'],
                'opsi_c' => $soalData['opsi_c'],
                'opsi_d' => $soalData['opsi_d'],
                'jawaban_benar' => $soalData['jawaban_benar'],
                'tipe' => 'pg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // BATCH INSERT - 1 QUERY untuk semua soal!
        banksoal::insert($bankData);
        
        // ============================================
        // OPTIMASI 2: Ambil ID yang baru diinsert
        // ============================================
        // Cara 1: Jika pakai auto increment, ambil ID terakhir
        $lastId = banksoal::latest()->first()->id;
        $totalSoal = count($bankData);
        $newBankIds = range($lastId - $totalSoal + 1, $lastId);
        
        // Atau Cara 2: Lebih aman - query berdasarkan timestamp
        // $newBankIds = banksoal::where('guru_id', $request->guru_id)
        //                 ->where('created_at', '>=', now()->subSeconds(2))
        //                 ->pluck('id')
        //                 ->toArray();

        // ============================================
        // OPTIMASI 3: BATCH INSERT RELASI
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
        
        // BATCH INSERT - 1 QUERY untuk semua relasi!
        Ujian_soals::insert($relasiData);

        // ============================================
        // OPTIMASI 4: UPDATE STATUS (tetap 1 query)
        // ============================================
        $ujian->update(['status' => 'ready']);

        DB::commit();

        // Log untuk monitoring performa
        \Log::info('Batch insert berhasil', [
            'total_soal' => count($bankData),
            'total_query' => 3 // Hanya 3 query! (insert bank, insert relasi, update)
        ]);

        return redirect()->route('guru.index')
            ->with('success', "✅ Berhasil menyimpan " . count($bankData) . " soal dengan gambar (Hanya 3 query!)");

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Hapus gambar yang sudah terupload jika gagal
        if (isset($gambarPaths)) {
            foreach ($gambarPaths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        
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
      $ire = Auth::user();
      $dt = Guru::where("nama",$ire->nama)->first();
      return view("guru.hasil",compact("data","ire","val","dt"));
    }
    public function hasil($id)
{
    // Ambil semua peserta ujian berdasarkan id ujian
    $pesertaUjian = Peserta_ujian::with(['siswa' => function($query) {
        $query->with('pelanggaran'); // Memuat pelanggaran melalui relasi siswa
    }])->where('ujian_id', $id)->get();

    // Ambil data ujian (untuk judul, dll)
    $ujian = Ujian::find($id);

    return view('guru.result', compact('pesertaUjian', 'ujian'));
}
    public function riwayat(){
      $ire = Auth::user();
      $dt = Guru::where("nama",$ire->nama)->first();
      $data = Ujian::with("jadwal")->where("guru_id",$dt->id)->get();
      return view("guru.riwayat",compact("data","ire","dt"));
    }
    public function jadwal(){
      
      $ire = Auth::user();
      $dt = Guru::where("nama",$ire->nama)->first();
      $data = Jadwal::with("kelas","ujian.mapels")->get();
      return view("guru.jadwal",compact("data","ire","dt"));
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
                'jawaban_benar' => $row[5] ?? null,
                'tipe' => $row[6] ?? 'pg',
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
    
}
