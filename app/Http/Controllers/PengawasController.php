<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengawas;
use App\Models\Jadwal;
use App\Models\Berita;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pelanggaran;
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
      $jads = Jadwal::with("ujian")->whereIn("pengawas_id",$datas)->get();
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
      $js = Jadwal::find($request->ujian_id);
        Berita::create([
          "siswa_id" => $request->siswa_id,
          "ujian_id" => $request->ujian_id,
          "pengawas_id" => $js->pengawas_id,
          "catatan" => $request->catatan,
          
          ]);
        Pelanggaran::create([
          "ujian_id" => $request->ujian_id,
          "siswa_id" => $request->siswa_id,
          "jenis_pelanggaran" => $request->catatan,
          ]);
          return redirect()->route("pengawas.show",["id" => $js->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $jadk = Jadwal::find($id);
      $data = Siswa::with("kelas")->where("kelas_id",$jadk->kelas_id)->get();
      $pelan = Pelanggaran::with("siswa")->get();
        return view("pengawas.main",compact("data","jadk","pelan"));
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
}
