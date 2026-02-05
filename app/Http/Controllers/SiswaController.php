<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\User;
class SiswaController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data = Siswa::all();
      $ire = Auth::user();
      return view("admin.siswa.index",compact("data","ire"));
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
      Siswa::create([
        "user_id" => $us->id,
        "nama" => $request->nama,
        "nisn" => $request->nisn,
        ]);
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
        $request->validate([
          "user_id" => "required",
          "nama" => "required",
          "nisn" => "required",
          ]);
        $siswa->update($request->all());
        return redirect()->route("admin-siswa.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route("admin-siswa.index");
    }
    
    
}
