<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\banksoal;
use App\Models\Ujian_soals;
use App\Models\Jawaban_Siswa;
use App\Models\Peserta_ujian;
class SiswaController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data = Siswa::with("kelas")->get();
      $ire = Auth::user();
      $kelas = Kelas::all();
      return view("admin.siswa.index",compact("data","ire","kelas"));
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
      $paa = Hash::make($request->password);
      User::create([
        "nama" => $request->nama,
        "password" => $paa,
        "role" => "siswa",
        ]);
        $us = User::where("nama",$request->nama)->first();
      $sis = Siswa::create([
        "user_id" => $us->id,
        "nama" => $request->nama,
        "nisn" => $request->nisn,
        'kelas_id' => $request->kelas_id,
        ]);
        if($request->has("kelas_id")){
          $kels = Kelas::find($request->kelas_id);
          $sis->kelas()->associate($kels);
          $sis->save();
        }
        return redirect()->route("admin-siswa.index")->with("success","Berhasil Mantap!");
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
      $siswa = Siswa::findOrFail($id);
      $usd = User::findOrFail($siswa->user_id);
      $usd->nama = $request->nama;
      if($request->filled("password")){
      $usd->password = Hash::make($request->password);
      }
      $usd->save();
        $request->validate([
          "nama" => "required",
          "nisn" => "required",
          "password" => "required",
          
          ]);
          $siswa->nama = $request->nama;
          $siswa->nisn = $request->nisn;
        $siswa->save();
        return redirect()->route("admin-siswa.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $admin_siswa)
    {
        $admin_siswa->delete();
        return redirect()->route("admin-siswa.index");
    }
    public function Siswas()
    {
      $ire = Auth::user();
      $data = Siswa::with("kelas")->where("nama",$ire->nama)->first();
      $uji = Ujian::where("kelas_id",$data->kelas->id)->get();
      $hasil = Peserta_ujian::where("siswa_id",$data->id_siswa)->get();
      return view("siswa.index",compact("ire","data","uji","hasil"));
    }
    public function Starts($id)
    {
      $ire = Auth::user();
      $uji = Ujian::with("mapels")->where("id",$id)->first();
      $ujians = Ujian_soals::where("ujian_id",$uji->id)->get();
      $sis = Siswa::with("kelas")->where("nama",$ire->nama)->first();
      $soal = banksoal::all();
      return view("siswa.ujian",compact("uji","soal","ire","sis","ujians"));
    }
    public function Saved(Request $request){
      $request->validate([
        "jawaban" => "required|array"
        ]);
        $jawabanSiswa = $request->jawaban;
        $soal_ids = array_keys($jawabanSiswa);
        $soals = banksoal::whereIn("id",$soal_ids)->get()->keyBy("id");
      $score = 0;
      $total_soal = count($jawabanSiswa);
      foreach($jawabanSiswa as $soal_id => $jawabans){
        $soal = $soals[$soal_id] ?? null;
        if (!$soal) continue;
        $benar = 0;
        if($soal->opsi_a != null){
          $benar = strtoupper($jawabans) == strtoupper($soal->jawaban_benar);
        }
      else{
          $jawaban_siswa = strtolower(trim($jawabans));
          $soale = strtolower(trim($soal->jawaban_benar));
          if(strpos($jawaban_siswa,$soale) !== false){
            $benar = 1;
          }else{
          $benar = 0;
          }
      }
        if($benar){
          $score += 1;
        }
        Jawaban_Siswa::updateOrCreate([
          "ujian_id" => $request->ujian_id,
          "siswa_id" => $request->siswa_id,
          "bank_id" => $soal->id,
          ],
          [
            "jawaban" => $jawabans,
            "benar" => $benar,
            
          ]);
        
      $nilai = ($total_soal > 0) ? ($score / $total_soal) *100 : 0;
      Peserta_ujian::create([
          "ujian_id" => $request->ujian_id,
          "siswa_id" => $request->siswa_id,
          "nilai" => $nilai,
          "status" => "done",
          
          ]);
          
      return redirect()->route("siswa.index");
      
      }
    }
    
}
