<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hasil Ujian - {{ $ujian->nama_ujian ?? 'Ujian' }} | Sistem Ujian</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    
    <style>
        /* ===== S T Y L E  (sama seperti sebelumnya, tidak diubah) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #f3f5f9; overflow-x: hidden; }
        .header { background: #2e5b9a; color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header h2 { font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .user-dropdown { position: relative; cursor: pointer; }
        .user-info { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 8px; transition: background 0.3s ease; }
        .user-info:hover { background: rgba(255,255,255,0.15); }
        .user-avatar { width: 34px; height: 34px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e5b9a; font-weight: bold; }
        .user-name { font-weight: 500; font-size: 0.85rem; }
        .user-name i { font-size: 0.7rem; margin-left: 5px; }
        .dropdown-menu-custom { position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); min-width: 180px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1001; }
        .user-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item-custom { padding: 10px 16px; display: flex; align-items: center; gap: 12px; color: #333; text-decoration: none; transition: background 0.2s ease; border-bottom: 1px solid #eee; font-size: 0.85rem; }
        .dropdown-item-custom:hover { background: #f5f5f5; }
        .dropdown-item-custom i { width: 18px; color: #2e5b9a; }
        .logout-btn { color: #dc3545; }
        .logout-btn i { color: #dc3545; }
        .app-wrapper { display: flex; margin-top: 56px; min-height: calc(100vh - 56px); }
        .sidebar { width: 260px; background: #5c6fa6; position: fixed; left: 0; top: 56px; bottom: 0; z-index: 99; transition: transform 0.3s ease; overflow-y: auto; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; margin: 4px 12px; color: white; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
        .sidebar-item i { width: 22px; font-size: 1rem; }
        .sidebar-item span { font-size: 0.85rem; font-weight: 500; }
        .sidebar-item:hover { background: rgba(255,255,255,0.2); }
        .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 3px solid white; }
        .sidebar-logout { position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 12px; }
        .sidebar-logout .sidebar-item { color: white; }
        .sidebar-logout .sidebar-item:hover { background: #dc3545; }
        .main-content { flex: 1; margin-left: 260px; padding: 24px; transition: margin-left 0.3s ease; width: calc(100% - 260px); }
        .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
        .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
        .sidebar-overlay.active { display: block; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: 0.3s; border: 1px solid #eef2f6; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .stat-card .heading { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #6c757d; margin-bottom: 10px; }
        .stat-card .title { font-size: 2rem; font-weight: 700; color: #2e5b9a; }
        .table-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; border: 1px solid #eef2f6; }
        .table-card .card-header { background: white; border-bottom: 2px solid #f0f2f5; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .table-card .card-header-title { font-weight: 600; color: #2e5b9a; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
        .table-card .card-content { padding: 0; }
        .table { width: 100%; margin-bottom: 0; }
        .table thead th { background: #f8f9fc; color: #495057; font-weight: 600; font-size: 0.85rem; border-bottom: 2px solid #e9ecef; padding: 12px 16px; }
        .table tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; font-size: 0.85rem; }
        .table tbody tr:hover { background: #fafbfe; }
        .tag-score { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .tag-score-excellent { background: #d4edda; color: #155724; }
        .tag-score-good { background: #cfe2ff; color: #2e5b9a; }
        .tag-score-average { background: #fff3cd; color: #856404; }
        .tag-score-poor { background: #f8d7da; color: #721c24; }
        .tag-cheat { background: #f8d7da; color: #dc3545; font-weight: 600; padding: 6px 12px; border-radius: 20px; }
        .tag-safe { background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; }
        .notification-toast { position: fixed; top: 70px; right: 20px; padding: 12px 18px; border-radius: 8px; color: white; z-index: 1100; animation: slideInRight 0.3s ease; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .notification-success { background: #28a745; }
        .notification-error { background: #dc3545; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .breadcrumb-custom { margin-bottom: 20px; }
        .breadcrumb-custom ul { list-style: none; display: flex; flex-wrap: wrap; gap: 8px; }
        .breadcrumb-custom li { display: flex; align-items: center; }
        .breadcrumb-custom li:not(:last-child):after { content: "/"; margin-left: 8px; color: #adb5bd; }
        .breadcrumb-custom a { color: #5c6fa6; text-decoration: none; font-size: 0.85rem; }
        .breadcrumb-custom a:hover { color: #2e5b9a; }
        .breadcrumb-custom .is-active a { color: #2e5b9a; font-weight: 600; }
        .berita-acara-card { background: #2e5b9a; border-radius: 12px; padding: 20px; color: white; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(46,91,154,0.4); }
        .action-btn { background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; transition: all 0.3s ease; }
        .action-btn:hover { background: #c82333; transform: translateY(-1px); }
        @media (max-width: 768px) {
            .header h2 span, .user-name span { display: none; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
            .mobile-toggle { display: flex; }
            .stat-card .title { font-size: 1.5rem; }
            .table thead th, .table tbody td { padding: 8px 12px; font-size: 0.75rem; }
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #5c6fa6; border-radius: 3px; }
    </style>
</head>
<body>

<!-- Header (sama) -->
<header class="header">
    <h2><img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34" style="height:30px"/>
        <span>SMK NEGERI 1 CIOMAS</span></h2>
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
            <div class="user-name">
                @if(Auth::check()) <span>{{ Auth::user()->name }}</span> @else <span>Guru</span> @endif
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="dropdown-menu-custom">
            <div class="dropdown-item-custom"><i class="fas fa-user-circle"></i><span>Profil Saya</span></div>
            <div class="dropdown-divider"></div>
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
        </div>
    </div>
</header>

<button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('guru.index') }}" class="sidebar-item"><i class="fas fa-home"></i><span>Dashboard</span></a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item"><i class="fas fa-calendar-alt"></i><span>Jadwal Ujian</span></a>
            <a href="{{ route('guru.riwayat') }}" class="sidebar-item"><i class="fas fa-history"></i><span>Riwayat Ujian</span></a>
            <a href="{{ route('guru.result') }}" class="sidebar-item active"><i class="fas fa-file-alt"></i><span>Hasil Ujian</span></a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="sidebar-item" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
        </div>
    </aside>

    <main class="main-content" id="mainContent">
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif

        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <ul>
                <li><a href="{{ route('guru.index') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('guru.result') }}">Hasil Ujian</a></li>
                <li class="is-active"><a href="#">{{ $ujian->nama_ujian ?? 'Detail' }}</a></li>
            </ul>
        </div>

        <!-- Header Ujian -->
        <div class="level is-mobile" style="margin-bottom:24px; flex-wrap:wrap; gap:12px;">
            <div class="level-left">
                <div>
                    <h1 style="color:#2e5b9a; font-size:1.5rem; font-weight:600;"><i class="fas fa-chart-line"></i> Hasil Ujian</h1>
                    <h2 style="color:#5c6fa6; font-size:1rem;">{{ $ujian->nama_ujian ?? 'Ujian' }}</h2>
                    @if($ujian && isset($ujian->tanggal_ujian))
                        <p style="color:#6c757d; font-size:0.85rem;"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') }}</p>
                    @endif
                </div>
            </div>
            <div class="level-right">
                <span class="tag is-info is-light" style="background:#eef2ff; color:#2e5b9a;"><i class="fas fa-users"></i> Total Peserta: {{ $pesertaUjian->count() }}</span>
            </div>
        </div>

        <!-- Statistik Ringkas -->
        @if($pesertaUjian->count() > 0)
        @php
            $rataNilai = $pesertaUjian->avg('nilai');
            $nilaiTertinggi = $pesertaUjian->max('nilai');
            $jumlahCurang = $pesertaUjian->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0)->count();
        @endphp
        <div class="columns is-multiline" style="margin-bottom:24px;">
            <div class="column is-4"><div class="stat-card"><p class="heading"><i class="fas fa-chart-simple"></i> Rata-rata Nilai</p><p class="title">{{ number_format($rataNilai, 2) }}</p></div></div>
            <div class="column is-4"><div class="stat-card"><p class="heading"><i class="fas fa-trophy"></i> Nilai Tertinggi</p><p class="title">{{round( $nilaiTertinggi) ?: '-' }}</p></div></div>
            <div class="column is-4"><div class="stat-card"><p class="heading"><i class="fas fa-exclamation-triangle"></i> Kecurangan</p><p class="title" style="color:#dc3545;">{{ $jumlahCurang }}</p></div></div>
        </div>
        @endif

        <!-- ========== TABEL SEMUA SISWA PER KELAS ========== -->
        @php
            $kelompokKelas = $siswa->groupBy(fn($s) => $s->kelas->nama_kelas ?? 'Tidak Diketahui');
        @endphp

        @foreach($kelompokKelas as $namaKelas => $siswaPerKelas)
        <div class="table-card" style="margin-bottom:30px;">
            <div class="card-header">
                <div class="card-header-title"><i class="fas fa-users"></i> Kelas {{ $namaKelas }}</div>
                <div class="tags has-addons">
                    <span class="tag is-info is-light">{{ $siswaPerKelas->count() }} siswa</span>
                    @php
                        $siswaIds = $siswaPerKelas->pluck('id_siswa')->toArray();
                        $pesertaDiKelas = $pesertaUjian->whereIn('siswa_id', $siswaIds);
                        $rataKelas = $pesertaDiKelas->avg('nilai');
                    @endphp
                    <span class="tag is-success is-light">Rata-rata: {{ number_format($rataKelas, 2) }}</span>
                </div>
            </div>
            <div class="card-content">
                <div style="overflow-x:auto;">
                    <table class="table is-striped is-hoverable is-fullwidth">
                        <thead>
                            <tr><th>No</th><th>NISN</th><th>Nama Siswa</th><th>Nilai</th><th>Status</th><th>Kehadiran</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($siswaPerKelas as $index => $siswaItem)
                            @php
                                $peserta = $pesertaUjian->firstWhere('siswa_id', $siswaItem->id_siswa);
                                $susulan = $siswaSusulan->firstWhere('siswa_id', $siswaItem->id_siswa);
                                $absen = $absensi->get($siswaItem->id_siswa);

                                if($peserta) { // typo? harusnya $peserta
                                    $statusKehadiran = 'hadir';
                                    $nilai = $peserta->nilai;
                                    $hasPelanggaran = $peserta->siswa->pelanggaran && $peserta->siswa->pelanggaran->count() > 0;
                                } elseif($susulan) {
                                    $statusKehadiran = 'susulan';
                                    $nilai = null;
                                    $hasPelanggaran = false;
                                } elseif($absen) {
                                    $statusKehadiran = $absen->status_kehadiran;
                                    $nilai = null;
                                    $hasPelanggaran = false;
                                } else {
                                    $statusKehadiran = 'alfa';
                                    $nilai = null;
                                    $hasPelanggaran = false;
                                }

                                $statusColor = match($statusKehadiran) {
                                    'hadir' => 'success', 'sakit' => 'warning', 'izin' => 'info', 'susulan' => 'warning', default => 'danger'
                                };
                                $scoreClass = '';
                                if($nilai >= 80) $scoreClass = 'tag-score-excellent';
                                elseif($nilai >= 70) $scoreClass = 'tag-score-good';
                                elseif($nilai >= 60) $scoreClass = 'tag-score-average';
                                elseif($nilai) $scoreClass = 'tag-score-poor';
                            @endphp
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $siswaItem->nisn ?? '-' }}</td>
                                <td><strong>{{ $siswaItem->nama ?? '-' }}</strong></td>
                                <td>
                                    @if($nilai) <span class="tag-score {{ $scoreClass }}">{{ round($nilai )}}</span> @else <span class="tag is-light">-</span> @endif
                                </td>
                                <td>
                                    @if($hasPelanggaran) <span class="tag-cheat"><i class="fas fa-exclamation-circle"></i> Curang</span>
                                    @else <span class="tag-safe"><i class="fas fa-check-circle"></i> Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="tag is-{{ $statusColor }} is-light">
                                        <i class="fas {{ $statusKehadiran == 'hadir' ? 'fa-check' : ($statusKehadiran == 'susulan' ? 'fa-clock' : 'fa-bed') }}"></i>
                                        {{ ucfirst($statusKehadiran) }}
                                    </span>
                                </td>
                                <td>
                                    @if(!$hasPelanggaran && !$susulan && $statusKehadiran == 'hadir')
                                        <button class="action-btn" onclick="showCurangModal({{ $ujian->id }}, {{ $siswaItem->id_siswa }}, '{{ $siswaItem->nama }}')">
                                            <i class="fas fa-gavel"></i> Curang
                                        </button>
                                    @else <span class="tag is-light">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <td><td colspan="7" class="has-text-centered">Tidak ada siswa</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Ringkasan per kelas -->
                <div style="margin-top:15px; padding-top:15px; border-top:1px solid #eee; display:flex; justify-content:center;gap:20px; flex-wrap:wrap; font-size:0.85rem;">
                    @php
                        $siswaIds = $siswaPerKelas->pluck('id_siswa')->toArray();
                        $pesertaDiKelas = $pesertaUjian->whereIn('siswa_id', $siswaIds);
                        $lulus = $pesertaDiKelas->filter(fn($p) => $p->nilai >= 75)->count();
                        $tidakLulus = $pesertaDiKelas->count() - $lulus;
                        $curang = $pesertaDiKelas->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0)->count();
                    @endphp
                    <div><i class="fas fa-check-circle" style="color:#28a745;"></i> <strong>Lulus</strong> <br><p class="ml-5">{{ $lulus }}</p></div>
                    <div><i class="fas fa-times-circle" style="color:#dc3545;"></i> <strong>Tidak Lulus</strong> <br><p class="ml-5">{{ $tidakLulus }}</p></div>
                    <div><i class="fas fa-exclamation-triangle" style="color:#ffc107;"></i> <strong>Kecurangan</strong> <br><p class="ml-5">{{ $curang }}</p></div>
                    <div><i class="fas fa-chart-line" style="color:#2e5b9a;"></i> <strong>Tertinggi:</strong> <br><p class="ml-5">{{ $pesertaDiKelas->max('nilai') ?: '-' }}</p></div>
                    <div><i class="fas fa-chart-line" style="color:#6c757d;"></i> <strong>Terendah:</strong> <br><p class="ml-5">{{ $pesertaDiKelas->min('nilai') ?: '-' }}</p></div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Tabel khusus kecurangan -->
        @php $pesertaCurang = $pesertaUjian->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0); @endphp
        @if($pesertaCurang->count())
        <div class="table-card" style="border-left:4px solid #dc3545;">
            <div class="card-header"><div class="card-header-title" style="color:#dc3545;"><i class="fas fa-gavel"></i> Daftar Siswa yang Melakukan Kecurangan <span class="tag is-danger is-light ml-2">{{ $pesertaCurang->count() }}</span></div></div>
            <div class="card-content">
                <div style="overflow-x:auto;">
                    <table class="table is-striped is-fullwidth">
                        <thead><tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Nilai</th><th>Jenis Kecurangan</th></tr></thead>
                        <tbody>
                            @foreach($pesertaCurang as $index => $curang)
                            @php $pelanggaran = $curang->siswa->pelanggaran->first(); @endphp
                            <tr><td>{{ $index+1 }}</td><td><strong>{{ $curang->siswa->nama ?? '-' }}</strong></td><td><span class="tag is-light">{{ $curang->siswa->kelas->nama_kelas ?? '-' }}</span></td><td><span class="tag-score tag-score-poor">{{ round($curang->nilai )?: '-' }}</span></td><td><span class="tag is-danger"><i class="fas fa-ban"></i> {{ $pelanggaran->jenis_pelanggaran ?? 'Kecurangan' }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel siswa susulan -->
    @if($siswaSusulan->count() > 0)
    <div class="table-card" style="border-left: 4px solid #ffc107;">
    <div class="card-header">
        <div class="card-header-title" style="color: #856404;">
            <i class="fas fa-clock"></i> Daftar Siswa Susulan
            <span class="tag is-warning ml-2">{{ $siswaSusulan->count() }}</span>
        </div>
    </div>
    <div class="card-content">
        <div style="overflow-x: auto;">
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Alasan</th>
                        <th>Jadwal Susulan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaSusulan as $index => $susulan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $susulan->siswa->nama ?? '-' }}</strong></td>
                        <td>{{ $susulan->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $susulan->alasan ?? '-' }}</td>
                        <td>
                            @php
                                // Cari jadwal susulan untuk ujian dan kelas ini
                                $jadwalSusulan = \App\Models\Jadwal::where('ujian_id', $ujian->id)
                                    ->where('kelas_id', $susulan->kelas_id)
                                    ->where('untuk_susulan', true)
                                    ->first();
                            @endphp
                            
                            @if($jadwalSusulan)
                                <div class="tags">
                                    <span class="tag is-info">
                                        <i class="fas fa-calendar"></i> 
                                        {{ \Carbon\Carbon::parse($jadwalSusulan->tanggal)->format('d/m/Y') }}
                                    </span>
                                    <span class="tag is-primary">
                                        <i class="fas fa-clock"></i> 
                                        {{ date('H:i', strtotime($jadwalSusulan->waktu_mulai)) }}
                                    </span>
                                    <span class="tag is-light">
                                        <i class="fas fa-door-open"></i> 
                                        
                                    </span>
                                </div>
                            @else
                                <span class="tag is-warning is-light">
                                    <i class="fas fa-hourglass"></i> Belum dijadwalkan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @php
            $sudahAdaJadwal = \App\Models\Jadwal::where('ujian_id', $ujian->id)
                ->where('untuk_susulan', true)
                ->exists();
        @endphp
        
        @if(!$sudahAdaJadwal)
        <div style="margin-top: 15px; text-align: right;">
           <!-- Tombol Buat Jadwal Susulan -->
<button class="button is-primary is-small" 
        onclick='openCreateJadwalSusulanModal("{{ $ujian->id }}", "{{ $ujian->nama_ujian }}")'>
    <i class="fas fa-calendar-plus"></i> Buat Jadwal Susulan
</button>
        </div>
        @else
        <div style="margin-top: 15px; text-align: right;">
           
        </div>
        @endif
    </div>
</div>
@endif

        <!-- Berita Acara (sudah diperbaiki) -->
        @foreach($berita as $b)
        @php
            $kelasBerita = \App\Models\Kelas::find($b->kelas_id);
            $namaKelasBerita = $kelasBerita->nama_kelas ?? 'Tidak Diketahui';
        @endphp
        <div class="berita-acara-card">
            <div class="card-header-title" style="color:white; padding:0; margin-bottom:15px;">BERITA ACARA UJIAN - KELAS {{ $namaKelasBerita }}</div>
            <div class="berita-acara-stats">
                @if($b->kelas_id == $ujian->jadwal->kelas_id && $b->ujian_id == $ujian->id)
                    <h5 class="subtitle has-text-light">{{ $b->catatan }}</h5>
                @else
                    <h5 class="subtitle has-text-light">Belum Ada Catatan</h5>
                @endif
            </div>
            <div style="margin-top:15px; padding-top:15px; border-top:1px solid rgba(255,255,255,0.2); font-size:0.85rem;">
                <p><i class="fas fa-calendar-check"></i> Tanggal Ujian: {{ \Carbon\Carbon::parse($ujian->jadwal->tanggal)->format('d M Y') ?? '-' }}</p>
                <p> Pengawas: {{ $ujian->jadwal->pengawas->guru->nama?? Auth::user()->nama ?? '-' }}</p>
            </div>
        </div>
        @endforeach
    </main>
</div>

<!-- MODAL YANG SUDAH DIPERBAIKI (FORM DI DALAM) -->
<div class="modal" id="curangModal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title"><i class="fas fa-gavel" style="color:#dc3545;"></i> Catat Kecurangan</p>
            <button class="delete" aria-label="close" onclick="closeCurang()"></button>
        </header>
        <form id="curangForm" action="{{ route('guru.catat-kecurangan') }}" method="POST">
            @csrf
            <section class="modal-card-body">
                <input type="hidden" name="ujian_id" id="modal_ujian_id">
                <input type="hidden" name="siswa_id" id="modal_siswa_id">
                <div class="field">
                    <label class="label">Nama Siswa</label>
                    <div class="control"><input class="input" type="text" id="modal_siswa_nama" readonly></div>
                </div>
                <div class="field">
                    <label class="label">Jenis Pelanggaran <span class="has-text-danger">*</span></label>
                    <div class="control"><input class="input" type="text" name="jenis_pelanggaran" id="jenis_pelanggaran" required></div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button type="submit" class="button is-success"><i class="fas fa-save"></i> Simpan</button>
                    <button type="button" class="button" onclick="closeCurangModal()"><i class="fas fa-times"></i> Batal</button>
                </div>
            </footer>
        </form>
    </div>
</div>
<!-- Modal Buat/Edit Jadwal Susulan -->
<div class="modal" id="jadwalSusulanModal">
    <div class="modal-background"></div>
    <div class="modal-card" style="width: 90%; max-width: 600px;">
        <header class="modal-card-head" style="background: #ffc107; color: #856404;">
            <p class="modal-card-title">
                <i class="fas fa-calendar-plus"></i> 
                <span id="modalTitle">Buat Jadwal Susulan</span>
            </p>
            <button class="delete" aria-label="close" onclick="closeJadwalSusulanModal()"></button>
        </header>
        
        <form id="jadwalSusulanForm" action="{{ route('guru.jadwal-susulan.store') }}" method="POST">
            @csrf
            
            <section class="modal-card-body">
                <!-- Hidden Inputs -->
                <input type="hidden" name="ujian_id" id="form_ujian_id" value="{{ $ujian->id ?? '' }}">
                <input type="hidden" name="kelas_id" id="form_kelas_id" value="">
                <input type="hidden" name="untuk_susulan" value="1">
                
                <!-- Nama Ujian -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-book"></i> Nama Ujian
                    </label>
                    <div class="control">
                        <input class="input" type="text" id="form_nama_ujian" 
                               value="{{ $ujian->nama_ujian ?? '-' }}" readonly style="background: #f5f5f5;">
                    </div>
                </div>
                
                <!-- Kelas -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-users"></i> Kelas <span class="has-text-danger">*</span>
                    </label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="kelas_id" id="form_kelas_select" required>
                                <option value="">Pilih Kelas</option>
                                @php
                                    $kelasList = \App\Models\Kelas::all();
                                @endphp
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Daftar Siswa Susulan -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-user-graduate"></i> Daftar Siswa Susulan <span class="has-text-danger">*</span>
                    </label>
                    <div class="control">
                        <div class="buttons mb-2">
                            <button type="button" class="button is-small is-info" onclick="selectAllSiswa()">
                                <i class="fas fa-check-double"></i> Pilih Semua
                            </button>
                            <button type="button" class="button is-small is-light" onclick="unselectAllSiswa()">
                                <i class="fas fa-times"></i> Batal Semua
                            </button>
                        </div>
                        <div id="siswaSusulanList" style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                            <p class="has-text-grey-light has-text-centered">Pilih kelas terlebih dahulu</p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Tanggal Susulan -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-calendar"></i> Tanggal Susulan <span class="has-text-danger">*</span>
                    </label>
                    <div class="control">
                        <input class="input" type="date" name="tanggal_susulan" id="form_tanggal" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                
                <!-- Waktu -->
                <div class="columns">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">
                                <i class="fas fa-clock"></i> Waktu Mulai <span class="has-text-danger">*</span>
                            </label>
                            <div class="control">
                                <input class="input" type="time" name="waktu_mulai" id="form_waktu_mulai" required>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">
                                <i class="fas fa-clock"></i> Waktu Selesai <span class="has-text-danger">*</span>
                            </label>
                            <div class="control">
                                <input class="input" type="time" name="waktu_selesai" id="form_waktu_selesai" required>
                                <p class="help" id="durationHelp"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pengawas - PAKAI GURU ID -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-user-check"></i> Pengawas <span class="has-text-danger">*</span>
                    </label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="guru_id" id="form_guru_id" required>
                                <option value="">Pilih Pengawas</option>
                                @php
                                    $guruList = \App\Models\Guru::with('user')->get();
                                @endphp
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Keterangan -->
                <div class="field">
                    <label class="label">
                        <i class="fas fa-sticky-note"></i> Keterangan
                    </label>
                    <div class="control">
                        <textarea class="textarea" name="keterangan" id="form_keterangan" rows="2" 
                                  placeholder="Catatan tambahan untuk jadwal susulan..."></textarea>
                    </div>
                </div>
            </section>
            
            <footer class="modal-card-foot" style="justify-content: flex-end;">
                <div class="buttons">
                    <button type="button" class="button" onclick="closeJadwalSusulanModal()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="button is-success">
                        <i class="fas fa-save"></i> Simpan Jadwal
                    </button>
                </div>
            </footer>
        </form>
    </div>
</div>

<script>
// Data siswa susulan (dikirim dari controller)
let siswaSusulanData = @json($siswaSusulan ?? []);

// Fungsi buka modal untuk CREATE
function openCreateJadwalSusulanModal(ujianId, ujianNama) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Buat Jadwal Susulan';
    document.getElementById('form_ujian_id').value = ujianId;
    document.getElementById('form_nama_ujian').value = ujianNama;
    
    // Reset form
    document.getElementById('jadwalSusulanForm').reset();
    document.getElementById('jadwalSusulanForm').action = "{{ route('guru.jadwal-susulan.store') }}";
    
    // Reset kelas select
    const kelasSelect = document.getElementById('form_kelas_select');
    kelasSelect.value = '';
    
    // Kosongkan list siswa
    document.getElementById('siswaSusulanList').innerHTML = '<p class="has-text-grey-light has-text-centered">Pilih kelas terlebih dahulu</p>';
    
    // Set default waktu
    const now = new Date();
    const defaultTime = new Date(now.getTime() + 60 * 60 * 1000);
    document.getElementById('form_waktu_mulai').value = formatTime(now);
    document.getElementById('form_waktu_selesai').value = formatTime(defaultTime);
    
    // Buka modal
    document.getElementById('jadwalSusulanModal').classList.add('is-active');
    
    calculateDuration();
}

// Load siswa susulan berdasarkan kelas
function loadSiswaSusulanByKelas(kelasId) {
    const container = document.getElementById('siswaSusulanList');
    
    const filteredSiswa = siswaSusulanData.filter(s => s.kelas_id == kelasId);
    
    if (filteredSiswa.length === 0) {
        container.innerHTML = '<p class="has-text-warning has-text-centered">Tidak ada siswa susulan di kelas ini</p>';
        return;
    }
    
    let html = '';
    filteredSiswa.forEach((siswa, index) => {
        html += `
            <div class="field" style="margin-bottom: 8px;">
                <label class="checkbox">
                    <input type="checkbox" name="siswa_ids[]" value="${siswa.id}">
                    <strong>${siswa.siswa?.nama || 'Siswa'}</strong>
                    <span class="has-text-grey"> - ${siswa.alasan || 'Tidak hadir'}</span>
                </label>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Event listener ketika kelas berubah
document.getElementById('form_kelas_select').addEventListener('change', function() {
    const kelasId = this.value;
    if (kelasId) {
        loadSiswaSusulanByKelas(kelasId);
        document.getElementById('form_kelas_id').value = kelasId;
    } else {
        document.getElementById('siswaSusulanList').innerHTML = '<p class="has-text-grey-light has-text-centered">Pilih kelas terlebih dahulu</p>';
    }
});

// Hitung durasi
function calculateDuration() {
    const waktuMulai = document.getElementById('form_waktu_mulai').value;
    const waktuSelesai = document.getElementById('form_waktu_selesai').value;
    const helpSpan = document.getElementById('durationHelp');
    
    if (waktuMulai && waktuSelesai) {
        const mulai = waktuMulai.split(':');
        const selesai = waktuSelesai.split(':');
        const durasiMenit = (parseInt(selesai[0]) * 60 + parseInt(selesai[1])) - 
                           (parseInt(mulai[0]) * 60 + parseInt(mulai[1]));
        
        if (durasiMenit > 0) {
            const jam = Math.floor(durasiMenit / 60);
            const menit = durasiMenit % 60;
            helpSpan.innerHTML = `<i class="fas fa-hourglass-half"></i> Durasi: ${jam} jam ${menit} menit`;
            helpSpan.style.color = '#28a745';
        } else if (durasiMenit === 0) {
            helpSpan.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Durasi minimal 1 menit';
            helpSpan.style.color = '#dc3545';
        } else {
            helpSpan.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Waktu selesai harus setelah waktu mulai';
            helpSpan.style.color = '#dc3545';
        }
    }
}

function formatTime(date) {
    return date.toTimeString().substring(0, 5);
}

function selectAllSiswa() {
    const checkboxes = document.querySelectorAll('#siswaSusulanList input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = true);
}

function unselectAllSiswa() {
    const checkboxes = document.querySelectorAll('#siswaSusulanList input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
}

function closeJadwalSusulanModal() {
    document.getElementById('jadwalSusulanModal').classList.remove('is-active');
}

// Validasi sebelum submit
document.getElementById('jadwalSusulanForm').addEventListener('submit', function(e) {
    const kelasId = document.getElementById('form_kelas_select').value;
    const tanggal = document.getElementById('form_tanggal').value;
    const waktuMulai = document.getElementById('form_waktu_mulai').value;
    const waktuSelesai = document.getElementById('form_waktu_selesai').value;
    const guruId = document.getElementById('form_guru_id').value;
    
    const checkedSiswa = document.querySelectorAll('#siswaSusulanList input[type="checkbox"]:checked');
    
    if (!kelasId) {
        e.preventDefault();
        alert('Pilih kelas terlebih dahulu!');
        return false;
    }
    
    if (checkedSiswa.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 siswa susulan!');
        return false;
    }
    
    if (!tanggal) {
        e.preventDefault();
        alert('Pilih tanggal susulan!');
        return false;
    }
    
    if (!waktuMulai || !waktuSelesai) {
        e.preventDefault();
        alert('Isi waktu mulai dan selesai!');
        return false;
    }
    
    if (waktuSelesai <= waktuMulai) {
        e.preventDefault();
        alert('Waktu selesai harus setelah waktu mulai!');
        return false;
    }
    
    if (!guruId) {
        e.preventDefault();
        alert('Pilih pengawas!');
        return false;
    }
    
    if (confirm(`Apakah yakin ingin menyimpan jadwal susulan untuk ${checkedSiswa.length} siswa?`)) {
        return true;
    }
    
    e.preventDefault();
    return false;
});

document.getElementById('form_waktu_mulai').addEventListener('change', calculateDuration);
document.getElementById('form_waktu_selesai').addEventListener('change', calculateDuration);

// Tutup modal jika klik background
document.addEventListener('click', function(e) {
    const modal = document.getElementById('jadwalSusulanModal');
    if (e.target === modal) closeJadwalSusulanModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('jadwalSusulanModal');
        if (modal.classList.contains('is-active')) closeJadwalSusulanModal();
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User dropdown
    const userDropdown = document.getElementById('userDropdown');
    if(userDropdown) {
        userDropdown.addEventListener('click', function(e) { e.stopPropagation(); userDropdown.classList.toggle('active'); });
        document.addEventListener('click', function() { if(userDropdown) userDropdown.classList.remove('active'); });
    }
    // Mobile sidebar
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
        const icon = mobileToggle.querySelector('i');
        if(sidebar.classList.contains('open')) { icon.classList.remove('fa-bars'); icon.classList.add('fa-times'); }
        else { icon.classList.remove('fa-times'); icon.classList.add('fa-bars'); }
    }
    if(mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', toggleSidebar);
    // Auto hide notification
    document.querySelectorAll('.notification-toast').forEach(n => {
        setTimeout(() => { n.style.opacity = '0'; setTimeout(() => n.style.display = 'none', 300); }, 5000);
    });
    // Resize handler
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if(window.innerWidth > 768 && sidebar.classList.contains('open')) toggleSidebar();
        }, 250);
    });
    // Close sidebar after link click on mobile
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            if(window.innerWidth <= 768 && sidebar.classList.contains('open')) setTimeout(toggleSidebar, 150);
        });
    });

    // === VALIDASI FORM SEBELUM SUBMIT ===
    const curangForm = document.getElementById('curangForm');
    if(curangForm) {
        curangForm.addEventListener('submit', function(e) {
            const jenis = document.getElementById('jenis_pelanggaran');
            if(!jenis.value.trim()) {
                e.preventDefault();
                alert('Jenis pelanggaran harus diisi!');
                return false;
            }
        });
    }
});

// Fungsi modal
function showCurangModal(ujianId, siswaId, siswaNama) {
    document.getElementById('modal_ujian_id').value = ujianId;
    document.getElementById('modal_siswa_id').value = siswaId;
    document.getElementById('modal_siswa_nama').value = siswaNama;
    document.getElementById('curangModal').classList.add('is-active');
}
function closeCurangModal() {
    document.getElementById('curangModal').classList.remove('is-active');
    document.getElementById('curangForm').reset();
}
// Tutup modal jika klik background
document.addEventListener('click', function(e) {
    const modal = document.getElementById('curangModal');
    if(e.target === modal) closeCurangModal();
});
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') {
        const modal = document.getElementById('curangModal');
        if(modal.classList.contains('is-active')) closeCurangModal();
    }
});
</script>

</body>
</html>