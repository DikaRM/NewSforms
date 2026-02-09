<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Kelas;
use App\Models\SiswaKelas;
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
      $data = Siswa::where("nama",$ire->nama)->first();
      $uhs = SiswaKelas::where("siswa_id",$data->id)->first();
      $uji = Ujian::find($uhs->kelas_id);
      return view("siswa.index",compact("ire","data"));
    }
    
}
