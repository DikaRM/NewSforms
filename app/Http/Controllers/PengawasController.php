<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengawas;
use App\Models\Absensi;
use App\Models\Susulan;
use App\Models\Jadwal;
use App\Models\Berita;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use Illuminate\Support\Facades\DB;
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
      $jads = Jadwal::with("ujian")->whereIn("pengawas_id",$datas)->whereDate("tanggal",now()->toDateString())->whereTime("waktu_selesai",'>',now()->toTimeString())->orderBy("waktu_selesai",'asc')->get();
      $sis = Siswa::with("kelas")->get();
        return view("pengawas.index",compact('data','jads','sis',"id"));
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
    $ju = Ujian::find($request->ujian_id)->with("jadwal");
      $js = Jadwal::find($ju->jadwal_id);
        Berita::create([
          "kelas_id" => $request->kelas_id,
          "ujian_id" => $request->ujian_id,
          "pengawas_id" => $js->pengawas_id,
          "catatan" => $request->catatan,
          
          ]);
        
          return redirect()->route("pengawas.show",["id" => $js->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $jadk = Jadwal::find($id);
      $da = Pengawas::find($jadk->pengawas_id)->with("user","guru")->latest()->first();
      
      $data = Siswa::with("kelas")->where("kelas_id",$jadk->kelas_id)->get();
      $pelan = Pelanggaran::with("siswa")->get();
      $berita = Berita::all();
        return view("pengawas.main",compact("data","jadk","pelan","da"));
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
    $request->validate([
        'ujian_id' => 'required|integer',
        'kelas_id' => 'required|integer',
        'siswa_id' => 'required|array',
        'status' => 'required|array',
    ]);
    

    if (count($request->siswa_id) != count($request->status)) {
        return back()->with('error', 'Data tidak valid, jumlah siswa dan status tidak sesuai');
    }
    
    // Gunakan DB transaction agar data konsisten
    DB::beginTransaction();
    
    try {
        foreach ($request->siswa_id as $index => $siswaId) {
            $statusKehadiran = $request->status[$index];
            
            // Pastikan status tidak kosong
            if (empty($statusKehadiran)) {
                return back()->with('error', 'Status kehadiran untuk salah satu siswa tidak dipilih');
            }
            
            // Proses simpan ke absensi
            Absensi::create([
                'ujian_id' => $request->ujian_id,
                'kelas_id' => $request->kelas_id,
                'siswa_id' => $siswaId,
                'status_kehadiran' => $statusKehadiran,  // <-- Jangan lupa ini!
                'waktu_absen' => now(),
            ]);
            

            if ($statusKehadiran == 'hadir') {
                Siswa::where('id_siswa', $siswaId)->update(['status' => 'ready']);
            } else {
                Susulan::create([
                    'siswa_id' => $siswaId,
                    'ujian_id' => $request->ujian_id,
                    'kelas_id' => $request->kelas_id,
                    'alasan' => $statusKehadiran,
                ]);
            }
        }
        
        DB::commit();
        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
        
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
    }
}
}
