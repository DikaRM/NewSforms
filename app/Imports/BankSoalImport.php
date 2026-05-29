<?php
// app/Imports/BankSoalImport.php

namespace App\Imports;

use App\Models\banksoal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BankSoalImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $guru_id;
    protected $mapel_id;

    public function __construct($guru_id, $mapel_id)
    {
        $this->guru_id = $guru_id;
        $this->mapel_id = $mapel_id;
    }

    public function model(array $row)
    {
        return new banksoal([
            'soal'          => $row['soal'],
            'opsi_a'        => $row['opsi_a'] ?? null,
            'opsi_b'        => $row['opsi_b'] ?? null,
            'opsi_c'        => $row['opsi_c'] ?? null,
            'opsi_d'        => $row['opsi_d'] ?? null,
            'opsi_e'        => $row['opsi_e'] ?? null,
            'jawaban_benar' => $row['jawaban_benar'],
            'tipe'          => $row['tipe'] ?? 'pg',
            'gambar'        => $row['gambar'] ?? null,
            'guru_id'       => $this->guru_id,
            'mapel_id'      => $this->mapel_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'soal' => 'required',
            'jawaban_benar' => 'required',
            'tipe' => 'in:pg,essay'
        ];
    }
}