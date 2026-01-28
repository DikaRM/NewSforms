<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data = User::all();
        return view("admin.index",compact("data"));
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
          "no_id" => "required",
          "role" => "required",
          "mapel" => "nullable",
          "kelas" => "nullable"
          ]);
          $usrr = [
            "nama" => $validated["nama"],
            "password" => $validated["password"],
            "no_id" => $validated["no_id"],
            "role" => $validated["role"],
            ];
          if($validated["role"] === "siswa"){
            $usrr["kelas"] = $validated["kelas"];
            $usrr["mapel"] = null;
          }else if($validated["role"] === "guru"){
            $usrr["kelas"] = null;
            $usrr["mapel"] = $validated["mapel"];
          }
          User::create($usrr);
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
}
