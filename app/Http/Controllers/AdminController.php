<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Pengawas;
use App\Models\Kelas;
use App\Models\GuruMapel;
use App\Models\SiswaKelas;
use App\Models\Mapel;

class AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $use = User::query();
      if($request->has("role") && $request->role != ''){
        $use->where("role",$request->role);
      }
      $sort = $request->get("sort","name");
      $order = $request->get("order","asc");
      $data = $use->orderBy($sort,$order,)->get();
      $ire = Auth::user();
        return view("admin.index",compact("data","ire"));
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
        $validated = $request->validate([
            "nama" => "required",
          "password" => "required",
          "role" => "required",
          "nip" => "nullable",
          ]);
          $ps = Hash::make($validated["password"]);
          $usrr = [
            "nama" => $validated["nama"],
            "password" => $ps,
            "role" => $validated["role"],
            ];
            User::create($usrr);
          if($validated["role"] === "siswa"){
            $on = 1;
            $su = [
              "user_id" => $on++,
              "nisn" => $request->nisn,
              "kelas" => $request->kelas,
              ];
            Siswa::create($su);
          }else if($validated["role"] === "guru"){
            $i = 1;
            $su = [
              "user_id" => $i++,
              "nama" => $validated["nama"],
              "nip" => $validated["nip"],
              ];
            Guru::create($su);
          }
          return redirect()->route("admin.index");
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
    public function update(Request $request, $admin)
    {
      $usef = User::findOrFail($admin);
        $usef->update($request->all());
        return redirect()->route("admin.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($admin)
    {
      $user = User::findOrFail($admin);
        $user->delete();
        return redirect()->route("admin.index");
    }
    public function KelasIndex()
    {
      $ire = Auth::user();
      $dat = Kelas::all();
      $siswa = Siswa::all();
      $dt = SiswaKelas::all();
      return view("admin.kelas",compact("dat","ire","siswa","dt"));
    }
    public function KelasCreate(Request $request)
    {
      $request->validate([
        "nama_kelas" => "required"]);
      Kelas::create($request->all());
      return redirect()->route("admin.kelas")->with("sip","Berhasil Menambah Kelas");
    }
    public function KelasUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_kelas" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.kelas")->with("sip","Berhasil Update Kelas");
    }
    public function KelasDestroy($id)
    {
      $hi = Kelas::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.kelas")->with("sip","Berhasil hapus Kelas");
    }
    public function AddSiswa(Request $request ,$id)
    {
      $sis = Siswa::where("nama",$request->nama)->first();
      $kela = Kelas::findOrFail($id);
      SiswaKelas::create([
        "siswa_id" => $request->ids,
        "kelas_id" => $id,
        ]);
      return redirect()->route("admin.kelas")->with("sip","Sip Menambah Siswa Pada Kelas");
    }
    public function MapelIndex()
    {
      $ire = Auth::user();
      $dat = Mapel::all();
      $guru = Guru::all();
      
      $dt = GuruMapel::all();
      return view("admin.mapel",compact("dat","ire","guru","dt"));
    }
    public function MapelUpdate(Request $request ,$id)
    {
      $us = Kelas::findOrFail($id);
      
      $request->validate([
        "nama_mapel" => "required"]);
      $us->update($request->all());
      return redirect()->route("admin.mapel")->with("sip","Berhasil Update Mapel");
    }
    public function MapelDestroy($id)
    {
      $hi = Kelas::findOrFail($id);
      $hi->delete();
      return redirect()->route("admin.mapel")->with("sip","Berhasil hapus Mapel");
    }
    public function AddGuru(Request $request)
    {
      $sis = Guru::where("nama",$request->nama)->first();
      GuruMapel::create([
        "guru_id" => $request->ids,
        "mapel_id" => $request->mapel_id,
        ]);
      return redirect()->route("admin.mapel")->with("sip","Sip Menambahkan Guru Ke Mapel");
    }
    public function Made(Request $request)
    {
      $request->validate([
        "nama_mapel" => "required"]);
      Mapel::create($request->all());
      return redirect()->route("admin.mapel")->with("sip","Berhasil Menambah Mapel");
    }
}
