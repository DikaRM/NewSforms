<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
      $sd = Guru::all();
      $kop = GuruMapel::all();
      $map = Mapel::all();
      return view("guru.index",compact("ire","uji","dt","klas","map"));
    }
    public function CreateUjian(Request $request)
    {
      $es = Guru::where("nama",$request->nama)->first();
      Ujian::create([
        "mapel" => $request->mapel_id,
        "guru_id" => $es->id,
        "nama_ujian" => $request->nama_ujian,
        "durasi" => $request->durasi,
        "grade" => $request->grade,
        "catatan" => $request->catatan,
        "status" => "draft",
        ]);
        return redirect()->route("guru.index")->with("success","Berhasil Buat Ujian");
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
      #'$ju = Ujian_soals::find($id2); 
      $ujian->delete();
      #if($ju){
      #$ju->delete();
      #dd("Halo");
      #}
      return redirect()->route("guru.create",['id' => $id]);
    }
    public function def($id)
    {
      $ujia = Ujian::find($id);
      $ujia->update([
        "status" => "ready"]);
      return redirect()->route("guru.index");
    }
    public function result(){
      $data = Ujian::all();
      $val = Peserta_ujian::with("siswa");
      $ire = Auth::user();
      return view("guru.hasil",compact("data","ire","val"));
    }
    public function hasil($id){
      $data = Ujian::with("kelas")->where("id",$id)->get();
      $val = Peserta_ujian::with("siswa")->whereHas("siswa")->get();
      $ire = Auth::user();
      return view("guru.result",compact("data","ire","val"));
    }
    public function riwayat(){
      $data = Ujian::with("jadwal")->get();
      $ire = Auth::user();
      return view("guru.riwayat",compact("data","ire"));
    }
    public function jadwal(){
      $data = Jadwal::with("kelas");
      $ire = Auth::user();
      return view("guru.jadwal",compact("data","ire"));
    }
    
    
}
