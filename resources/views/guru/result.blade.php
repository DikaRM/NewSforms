<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $ujian->nama_ujian }}</title>
    <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
    <style>
        .table-container {
            overflow-x: auto;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 3px rgba(10,10,10,0.1);
        }
    </style>
</head>
<body>
    <section class="section">
        <div class="container">
            {{-- Header dengan informasi --}}
            <div class="level mb-5">
                <div class="level-left">
                    <div>
                        <h1 class="title">Hasil Ujian</h1>
                        <h2 class="subtitle">{{ $ujian->nama_ujian ?? 'Ujian' }}</h2>
                    </div>
                </div>
                <div class="level-right">
                    <div class="tags are-large">
                        <span class="tag is-info">
                            Total Peserta: {{ $pesertaUjian->count() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Statistik Ringkas --}}
            @if($pesertaUjian->count() > 0)
            <div class="columns mb-5">
                <div class="column is-4">
                    <div class="box has-text-centered">
                        <p class="heading">Rata-rata Nilai</p>
                        <p class="title is-3">
                            {{ number_format($pesertaUjian->avg('nilai'), 2) }}
                        </p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="box has-text-centered">
                        <p class="heading">Nilai Tertinggi</p>
                        <p class="title is-3">{{ $pesertaUjian->max('nilai') }}</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="box has-text-centered">
                        <p class="heading">Jumlah Kecurangan</p>
                        <p class="title is-3">
                            {{ $pesertaUjian->filter(fn($p) => $p->pelanggaran)->count() }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tabel Utama --}}
            <div class="card">
                <div class="card-header">
                    <p class="card-header-title">
                        <span class="icon mr-2"></span>
                        Daftar Nilai Peserta
                    </p>
                    <div class="card-header-icon">
                        <span class="tag is-light">{{ $pesertaUjian->count() }} data</span>
                    </div>
                </div>

                <div class="card-content">
                    <div class="table-container">
                        <table class="table is-striped is-hoverable is-fullwidth">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Nilai</th>
                                    <th>Status Kecurangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesertaUjian as $index => $peserta)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="is-family-monospace">
                                            {{ $peserta->siswa->nisn ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $peserta->siswa->nama ?? 'Data tidak ditemukan' }}</strong>
                                    </td>
                                    <td>
                                        @if($peserta->siswa && $peserta->siswa->kelas)
                                            <span class="tag is-light">
                                                {{ $peserta->siswa->kelas->nama_kelas }}
                                            </span>
                                        @else
                                            <span class="tag is-light">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="tag is-medium 
                                            @if($peserta->nilai >= 80) is-success
                                            @elseif($peserta->nilai >= 70) is-info
                                            @elseif($peserta->nilai >= 60) is-warning
                                            @else is-danger
                                            @endif">
                                            {{ $peserta->nilai }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($peserta->pelanggaran)
                                            <div class="tags has-addons">
                                                <span class="tag is-danger">⚠️ Curang</span>
                                                <span class="tag is-danger is-light">
                                                    {{ $peserta->pelanggaran->jenis_pelanggaran }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="tag is-success is-light">aman</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="has-text-centered">
                                        <div class="notification is-warning is-light">
                                            <p>Belum ada peserta ujian</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tabel Khusus Kecurangan --}}
            @php
                $pesertaCurang = $pesertaUjian->filter(fn($p) => $p->pelanggaran);
            @endphp

            @if($pesertaCurang->count() > 0)
            <div class="card mt-5">
                <div class="card-header">
                    <p class="card-header-title has-text-danger">
                        <span class="icon mr-2">⚠️</span>
                        Daftar Siswa yang Melakukan Kecurangan ({{ $pesertaCurang->count() }})
                    </p>
                </div>
                <div class="card-content">
                    <div class="table-container">
                        <table class="table is-striped is-fullwidth">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Nilai</th>
                                    <th>Jenis Kecurangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaCurang as $index => $curang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $curang->siswa->nama }}</td>
                                    <td>{{ $curang->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td>
                                        <span class="tag is-danger">{{ $curang->nilai }}</span>
                                    </td>
                                    <td>
                                        <span class="tag is-danger is-light">
                                            {{ $curang->pelanggaran->jenis_pelanggaran }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</body>
</html>