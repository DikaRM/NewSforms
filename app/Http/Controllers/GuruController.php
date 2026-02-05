<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\User;
use App\Models\Ujian;
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
      return redirect()->route("admin-guru.index")->with("succes","Berhasil Menambah Data");
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
        $use = User::findOrFail($id);
        $gu = Guru::findOrFail($id);
         $gu->delete();
        $use->delete();
        return redirect()->route("admin-guru.index")->with("succes","Berhasil Dihapus");
    }
    public function TeachIndex()
    {
      $ire = Auth::user();
      $dt = Guru::where("nama",$ire->nama)->first();
      $uji = Ujian::all();
      return view("guru.index",compact("ire","uji","dt"));
    }
    public function CreateUjian(Request $request)
    {
      
      Ujian::create([
        "mapel_id" => $request->mapel_id,
        "kelas_id" => $request->kelas_id,
        "guru_id" => $request->kelas_id,
        "nama_ujian" => $request->nama_ujian,
        "waktu_mulai" => $request->waktu_mulai,
        "waktu_selesai" => $request->waktu_selesai,
        "durasi" => $request->durasi,
        "status" => "draft",
        ]);
        return redirect()->route("guru.index")->with("success","Berhasil Buat Ujian");
    }
    public function Uji()
    {
      
      return view("guru.test",compact("ire","dt","uji","qs"));
    }
    public function CreateSoal(Request $request)
    {
      //
    }
}
