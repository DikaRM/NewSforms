<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Hasil Ujian - {{ $ujian->nama_ujian ?? 'Ujian' }} | Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* ===== TAMBAHKAN INI DI STYLE CSS ANDA ===== */

/* Animasi Lonceng Notifikasi (Shake) */
@keyframes bellShake {
    0% { transform: rotate(0); }
    15% { transform: rotate(15deg); }
    30% { transform: rotate(-10deg); }
    45% { transform: rotate(5deg); }
    60% { transform: rotate(-5deg); }
    75% { transform: rotate(2deg); }
    100% { transform: rotate(0); }
}

.has-new-notif .fa-bell {
    animation: bellShake 2s ease-in-out infinite;
    color: #ffd700; /* Warna emas jika ada notif baru */
}

/* Wrapper Notifikasi */
.notif-wrapper {
    position: relative;
}

.notif-btn {
    position: relative;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.notif-btn:hover {
    background: rgba(255,255,255,0.15);
}

.notif-btn i {
    font-size: 1.2rem;
    color: white;
}

.notif-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #dc3545;
    color: white;
    font-size: 0.65rem;
    font-weight: bold;
    height: 18px;
    min-width: 18px;
    padding: 0 4px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #2e5b9a;
    z-index: 2;
}

.notif-badge.hidden {
    display: none;
}

/* Dropdown Notifikasi */
.notif-dropdown {
    position: absolute;
    top: 120%;
    right: -10px;
    width: 320px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1002;
}

.notif-wrapper.active .notif-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.notif-header {
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notif-header h4 {
    font-size: 0.9rem;
    color: #2e5b9a;
    font-weight: 700;
}

.notif-header .mark-read {
    font-size: 0.75rem;
    color: #5c6fa6;
    cursor: pointer;
    text-decoration: underline;
}

.notif-body {
    max-height: 350px;
    overflow-y: auto;
}

.notif-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f5f5f5;
    display: flex;
    gap: 12px;
    transition: background 0.2s;
    cursor: pointer;
    color: inherit;
    text-decoration: none;
}

.notif-item:hover {
    background: #f8f9fa;
}

.notif-item.unread {
    background: #f0f7ff;
    border-left: 3px solid #2e5b9a;
}

.notif-icon-box {
    width: 36px;
    height: 36px;
    background: #e3eaf5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e5b9a;
    flex-shrink: 0;
}

.notif-content {
    flex: 1;
}

.notif-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
    line-height: 1.3;
}

.notif-desc {
    font-size: 0.75rem;
    color: #666;
    margin-bottom: 4px;
}

.notif-time {
    font-size: 0.7rem;
    color: #999;
}

.notif-footer {
    padding: 10px;
    text-align: center;
    border-top: 1px solid #eee;
}

.notif-footer a {
    font-size: 0.8rem;
    color: #2e5b9a;
    text-decoration: none;
    font-weight: 500;
}

.empty-notif {
    padding: 30px;
    text-align: center;
    color: #999;
}

.empty-notif i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #ddd;
}
    /* --- RESET & BASE --- */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif; }
    body { background: #f3f5f9; overflow-x: hidden; }
    
    /* --- LAYOUT (PANITIA STYLE) --- */
    .app-wrapper { display: flex; margin-top: 56px; min-height: calc(100vh - 56px); }
    .main-content { flex: 1; margin-left: 260px; padding: 24px; transition: margin-left 0.3s ease; width: calc(100% - 260px); }
    
    /* --- HEADER --- */
    .header { background: #2e5b9a; color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .header h2 { font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .user-dropdown { position: relative; cursor: pointer; }
    .user-info { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 8px; transition: background 0.3s ease; }
    .user-info:hover { background: rgba(255,255,255,0.15); }
    .user-avatar { width: 34px; height: 34px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e5b9a; font-weight: bold; }
    .user-name { font-weight: 500; font-size: 0.85rem; }
    .dropdown-menu-custom { position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); min-width: 180px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1001; }
    .user-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }
    .dropdown-item-custom { padding: 10px 16px; display: flex; align-items: center; gap: 12px; color: #333; text-decoration: none; transition: background 0.2s ease; border-bottom: 1px solid #eee; font-size: 0.85rem; }
    .dropdown-item-custom:hover { background: #f5f5f5; }
    
    /* --- SIDEBAR --- */
    .sidebar { width: 260px; background: #5c6fa6; position: fixed; left: 0; top: 56px; bottom: 0; z-index: 99; transition: transform 0.3s ease; overflow-y: auto; }
    .sidebar-menu { padding: 20px 0; }
    .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; margin: 4px 12px; color: white; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
    .sidebar-item:hover { background: rgba(255,255,255,0.2); }
    .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 3px solid white; }
    
    /* --- MOBILE --- */
    .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
    .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
    
    /* --- CARDS & GRID --- */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #eef2f6; display: flex; align-items: center; gap: 16px; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon i { font-size: 26px; }
    .stat-info { flex: 1; }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-label { color: #6c757d; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }
    
    .section-title { font-size: 1.2rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 10px; }
    
    /* --- CLASS GRID --- */
    .class-grid { display: grid; grid-template-columns: 1fr; gap: 24px; margin-bottom: 40px; }
    .class-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #eef2f6; }
    .class-header { padding: 14px 20px; background: linear-gradient(135deg, #5c6fa6 0%, #2e5b9a 100%); color: white; display: flex; justify-content: space-between; align-items: center; }
    .class-header h3 { font-size: 1.1rem; font-weight: 600; }
    .class-body { padding: 20px; }
    
    /* --- TABLE STYLES --- */
    .table thead th { background: #f8f9fc; color: #495057; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #e9ecef; padding: 12px 16px; }
    .table tbody td { padding: 12px 16px; vertical-align: middle; font-size: 0.9rem; border-bottom: 1px solid #f0f0f0; }
    .table tbody tr:hover { background: #fafbfe; }
    
    /* --- TAGS & BADGES --- */
    .tag-score { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .tag-score-excellent { background: #d4edda; color: #155724; }
    .tag-score-good { background: #cfe2ff; color: #2e5b9a; }
    .tag-score-average { background: #fff3cd; color: #856404; }
    .tag-score-poor { background: #f8d7da; color: #721c24; }
    
    .badge-cheat { background: #f8d7da; color: #dc3545; font-weight: 600; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; }
    .badge-safe { background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; }
    
    .action-btn { background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; transition: all 0.3s ease; }
    .action-btn:hover { background: #c82333; transform: translateY(-1px); }
    .btn-edit { background: #2e5b9a; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; transition: all 0.3s ease; }
    .btn-edit:hover { background: #264a82; transform: translateY(-1px); }

    /* --- MODALS --- */
    .modal { display: none; }
    .modal.is-active { display: flex; }
    .modal-card { width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 16px; background: white; }
    .modal-background { background: rgba(0,0,0,0.6); }
    .modal-card-head { padding: 20px 24px; border-bottom: 1px solid #eef2f6; display: flex; justify-content: space-between; align-items: center; }
    .modal-card-head.bg-yellow { background: #ffc107; color: #856404; border-radius: 16px 16px 0 0; }
    .modal-card-head.bg-danger { background: #dc3545; color: white; border-radius: 16px 16px 0 0; }
    .modal-card-head.bg-primary { background: #2e5b9a; color: white; border-radius: 16px 16px 0 0; border-bottom: none; }
    .modal-card-title { font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .modal-card-body { padding: 24px; }
    .modal-card-foot { padding: 15px 24px; background: #fafbfe; border-top: 1px solid #eef2f6; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 16px 16px;}
    .delete { background: rgba(255,255,255,0.3); }
    
    /* --- BERITA ACARA --- */
    .berita-acara-card { background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%); border-radius: 12px; padding: 20px; color: white; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(46,91,154,0.4); }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .header h2 span, .user-name span { display: inline; }
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
        .mobile-toggle { display: flex; }
        .stats-grid { grid-template-columns: 1fr; }
        .stat-card { padding: 14px; }
        .modal-card { width: 95%; max-height: 95vh; }
    }
    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }
</style>
</head>
<body>

<!-- Header -->
<header class="header">
    <h2>
        <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34" style="height:30px"/>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    <div class="header-actions">
         <div class="notif-wrapper" id="notifWrapper">
        <div class="notif-btn" id="notifBtn">
            <i class="fas fa-bell"></i>
            <span class="notif-badge" id="notifCount">0</span>
        </div>
        
        <div class="notif-dropdown">
            <div class="notif-header">
                <h4><i class="fas fa-bell"></i> Notifikasi</h4>
                <span class="mark-read" onclick="markAllRead()">Tandai dibaca</span>
            </div>
            <div class="notif-body" id="notifList">
                <!-- Data Notifikasi akan dimuat via JS -->
            </div>
            <div class="notif-footer">
                <a href="{{ route('pengawas.index', isset($dt) ? $dt->id : '') }}">Lihat Semua Jadwal</a>
            </div>
        </div>
    </div>
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
            <div class="user-name">
                @if(Auth::check()) <span>{{ Auth::user()->name }}</span> @else <span>Guru</span> @endif
                <i class="fas fa-chevron-down" style="font-size:0.7rem; margin-left:5px;"></i>
            </div>
        </div>
        <div class="dropdown-menu-custom">
            <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
        <i class="fas fa-user-circle"></i>
        <span>Profil Saya</span>
    </a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
        </div>
    </div>
    </div>
    
</header>

<button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <main class="main-content" id="mainContent">

        @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#2e5b9a' });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#dc3545' });
        </script>
        @endif

        <!-- Header Halaman -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap:10px;">
            <div>
                <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 700;"><i class="fas fa-chart-line"></i> Hasil Ujian</h1>
                <h2 style="color: #6c757d; font-size: 1rem; font-weight: 400;">{{ $ujian->nama_ujian ?? 'Ujian' }}</h2>
                <p style="color: #6c757d; font-size: 0.85rem; margin-top: 5px;">
                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') }} 
                    <span style="margin: 0 5px;">|</span>
                    <i class="fas fa-users"></i> {{ $pesertaUjian->count() }} Peserta
                </p>
            </div>
            <a href="{{ route('guru.result') }}" class="button" style="border-radius: 8px; border: 1px solid #e5e7eb;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- 1. Statistik Ringkas -->
        @if($pesertaUjian->count() > 0)
        @php
            $rataNilai = $pesertaUjian->avg('nilai');
            $nilaiTertinggi = $pesertaUjian->max('nilai');
            $jumlahCurang = $pesertaUjian->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0)->count();
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-icon" style="background: #eef2ff;"><i class="fas fa-chart-simple" style="color: #2e5b9a;"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($rataNilai, 2) }}</div>
                    <div class="stat-label">Rata-rata Nilai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #d4edda;"><i class="fas fa-trophy" style="color: #28a745;"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ round($nilaiTertinggi) ?: '-' }}</div>
                    <div class="stat-label">Nilai Tertinggi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f8d7da;"><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color: #dc3545;">{{ $jumlahCurang }}</div>
                    <div class="stat-label">Kecurangan</div>
                </div>
            </div>
        </div>
        @endif

        <!-- 2. TABEL PER KELAS -->
        @php
            $kelompokKelas = $siswa->groupBy(fn($s) => $s->kelas->nama_kelas ?? 'Tidak Diketahui');
        @endphp

        <h3 style="font-size: 1.2rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb;">Rekap Per Kelas</h3>
        
        <div class="class-grid">
            @foreach($kelompokKelas as $namaKelas => $siswaPerKelas)
            <div class="class-card">
                <div class="class-header">
                    <div>
                        <span style="font-size: 0.8rem; opacity: 0.8; display: block;">Kelas</span>
                        <h3>{{ $namaKelas }}</h3>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <span class="tag is-white has-text-primary">{{ $siswaPerKelas->count() }} Siswa</span>
                        @php
                            $siswaIds = $siswaPerKelas->pluck('id_siswa')->toArray();
                            $pesertaDiKelas = $pesertaUjian->whereIn('siswa_id', $siswaIds);
                            $rataKelas = $pesertaDiKelas->avg('nilai');
                        @endphp
                        <span class="tag is-success">Rata: {{ number_format($rataKelas, 2) }}</span>
                    </div>
                </div>
                
                <div class="class-body">
                    <div style="overflow-x: auto;">
                        <table class="table is-striped is-hoverable is-fullwidth">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaPerKelas as $index => $siswaItem)
                                @php
                                    $peserta = $pesertaUjian->firstWhere('siswa_id', $siswaItem->id_siswa);
                                    $susulan = $siswaSusulan->firstWhere('siswa_id', $siswaItem->id_siswa);
                                    $absen = $absensi->get($siswaItem->id_siswa);

                                    if($peserta) {
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

                                    $scoreClass = '';
                                    if($nilai >= 80) $scoreClass = 'tag-score-excellent';
                                    elseif($nilai >= 70) $scoreClass = 'tag-score-good';
                                    elseif($nilai >= 60) $scoreClass = 'tag-score-average';
                                    elseif($nilai) $scoreClass = 'tag-score-poor';
                                @endphp
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>
                                        <strong>{{ $siswaItem->nama }}</strong>
                                        <div style="font-size: 0.75rem; color: #6c757d;">{{ $siswaItem->nisn }}</div>
                                    </td>
                                    <td>
                                        @if($nilai) 
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <span class="tag-score {{ $scoreClass }}">{{ round($nilai) }}</span>
                                                <!-- FITUR: TOMBOL EDIT NILAI -->
                                                <button class="btn-edit" onclick="openEditNilaiModal({{ $peserta->id }}, {{ $nilai }}, '{{ $siswaItem->nama }}')">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            </div>
                                        @else <span class="tag is-light">0</span> @endif
                                    </td>
                                    <td>
                                        @if($hasPelanggaran) 
                                            <span class="badge-cheat"><i class="fas fa-exclamation-triangle"></i> Curang</span>
                                        @else 
                                            <span class="badge-safe"><i class="fas fa-check-circle"></i> Aman</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hasPelanggaran && !$susulan && $statusKehadiran == 'hadir')
                                            <button class="action-btn" onclick="showCurangModal({{ $ujian->id }}, {{ $siswaItem->id_siswa }}, '{{ $siswaItem->nama }}', {{ $nilai ?? 0 }})">
                                                <i class="fas fa-gavel"></i> Curang
                                            </button>
                                        @else 
                                            <span class="tag is-light">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="has-text-centered">Tidak ada siswa</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer Statistik Kelas -->
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-around; font-size: 0.85rem; color: #495057;">
                        @php
                            $lulus = $pesertaDiKelas->filter(fn($p) => $p->nilai >= 75)->count();
                            $tidakLulus = $pesertaDiKelas->count() - $lulus;
                            $curang = $pesertaDiKelas->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0)->count();
                        @endphp
                        <div style="text-align: center;"><strong>Lulus</strong><br>{{ $lulus }}</div>
                        <div style="text-align: center;"><strong>Remed</strong><br>{{ $tidakLulus }}</div>
                        <div style="text-align: center; color: #dc3545;"><strong>Curang</strong><br>{{ $curang }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- 3. SISWA SUSULAN -->
        @if($siswaSusulan->count() > 0)
        <h3 style="font-size: 1.2rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; margin-top: 40px;">Siswa Susulan</h3>
        <div class="class-card" style="border-left: 4px solid #ffc107;">
            <div class="class-header" style="background: #ffc107; color: #856404;">
                <h3>Daftar Siswa Susulan</h3>
                <span class="tag is-white has-text-warning-dark">{{ $siswaSusulan->count() }} Orang</span>
            </div>
            <div class="class-body">
                <div style="overflow-x: auto;">
                    <table class="table is-fullwidth is-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Alasan</th>
                                <th>Status Jadwal</th>
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
                                        $jadwalSusulan = \App\Models\Jadwal::where('ujian_id', $ujian->id)
                                            ->where('kelas_id', $susulan->kelas_id)
                                            ->where('untuk_susulan', true)
                                            ->first();
                                    @endphp
                                    
                                    @if($jadwalSusulan)
                                        <span class="tag is-success"><i class="fas fa-check"></i> {{ date('H:i', strtotime($jadwalSusulan->waktu_mulai)) }} WIB</span>
                                    @else
                                        <span class="tag is-warning"><i class="fas fa-hourglass"></i> Belum Dijadwalkan</span>
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
                <div style="margin-top: 20px; text-align: right;">
                   <!-- Tombol Buat Jadwal Susulan -->
                    <button class="button is-warning" style="border-radius: 8px;" 
                            onclick='openCreateJadwalSusulanModal("{{ $ujian->id }}", "{{ $ujian->nama_ujian }}")'>
                        <i class="fas fa-calendar-plus"></i> Buat Jadwal Susulan
                    </button>
                </div>
                @else
                <div style="margin-top: 20px; text-align: right;">
                    <span class="tag is-light">Semua siswa susulan sudah memiliki jadwal.</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- 4. KECURANGAN & BERITA ACARA -->
        <div style="display: flex; gap: 24px; margin-top: 40px; flex-wrap: wrap;">
            
            <!-- Kolom Kiri: Kecurangan -->
            @php $pesertaCurang = $pesertaUjian->filter(fn($p) => $p->siswa->pelanggaran && $p->siswa->pelanggaran->count() > 0); @endphp
            @if($pesertaCurang->count())
            <div style="flex: 1; min-width: 300px;">
                <div class="class-card" style="border: 1px solid #f8d7da;">
                    <div class="class-header" style="background: #f8d7da; color: #721c24;">
                        <h3><i class="fas fa-gavel"></i> Daftar Pelanggar</h3>
                        <span class="tag is-white has-text-danger">{{ $pesertaCurang->count() }}</span>
                    </div>
                    <div class="class-body" style="padding: 10px;">
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table is-narrow is-fullwidth">
                                @foreach($pesertaCurang as $index => $curang)
                                @php $pelanggaran = $curang->siswa->pelanggaran->first(); @endphp
                                <tr>
                                    <td>{{ $index+1 }}. {{ $curang->siswa->nama }}</td>
                                    <td><span class="tag is-danger is-light">{{ $pelanggaran->jenis_pelanggaran }}</span></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Kolom Kanan: Berita Acara -->
            <div style="flex: 1; min-width: 300px;">
                @foreach($berita as $b)
                @php
                    $kelasBerita = \App\Models\Kelas::find($b->kelas_id);
                    $namaKelasBerita = $kelasBerita->nama_kelas ?? 'Tidak Diketahui';
                @endphp
                <div class="berita-acara-card">
                    <div style="margin-bottom: 10px; font-weight: 600;">
                        <i class="fas fa-file-alt"></i> Berita Acara - {{ $namaKelasBerita }}
                    </div>
                    <div style="font-size: 0.9rem; opacity: 0.95;">
                        {{ $b->catatan ?: 'Tidak ada catatan.' }}
                    </div>
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.3); font-size: 0.75rem; opacity: 0.8;">
                        <i class="fas fa-user-check"></i> {{ $b->pengawas->guru->nama ?? Auth::user()->name }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </main>
</div>

<!-- =========================================
     MODAL 1: CATAT CURANG (DENGAN FITUR SKORS NILAI)
     ========================================= -->
<div class="modal" id="curangModal">
    <div class="modal-background" onclick="closeCurangModal()"></div>
    <div class="modal-card" style="max-width: 450px;">
        <header class="modal-card-head bg-danger">
            <p class="modal-card-title"><i class="fas fa-gavel"></i> Catat Kecurangan</p>
            <button class="delete" aria-label="close" onclick="closeCurangModal()"></button>
        </header>
        <form id="curangForm" action="{{ route('guru.catat-kecurangan') }}" method="POST">
            @csrf
            <section class="modal-card-body">
                <input type="hidden" name="ujian_id" id="modal_ujian_id">
                <input type="hidden" name="siswa_id" id="modal_siswa_id">
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Nama Siswa</label>
                    <input class="input" type="text" id="modal_siswa_nama" readonly style="background: #f5f5f5; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; padding: 10px;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Jenis Pelanggaran</label>
                    <input class="input" type="text" name="jenis_pelanggaran" placeholder="Contoh: Curang, HP ketahuan..." required style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                </div>

                <!-- FITUR BARU: SKORS NILAI - INPUT NUMBER -->
                <div style="background: #fff3cd; color: #856404; padding: 16px; border-radius: 8px; border: 1px solid #ffeeba;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong style="font-size: 0.9rem;">Skors Nilai</strong>
                    </div>
                    
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 6px; font-size: 0.8rem;">Nilai Siswa Saat Ini</label>
                        <div style="font-size: 1.2rem; font-weight: 700; color: #856404;" id="display_nilai_siswa">0</div>
                    </div>
                    
                     <div style="margin-top: 15px;">
        <label style="display: block; margin-bottom: 8px; font-size: 0.8rem;">
            Jumlah Pengurangan: <strong id="skors_value_display" style="font-size: 1.1rem;">0</strong> Poin
        </label>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <!-- Slider Range -->
            <input type="range" 
                   name="skors_nilai" 
                   id="modal_skors_nilai"
                   min="0" 
                   max="100" 
                   value="0"
                   step="1"
                   style="width: 100%; height: 6px; background: #ffeeba; border-radius: 5px; outline: none; cursor: pointer; -webkit-appearance: none;">
                   
            <!-- Ikon Slider (Optional Visual) -->
            <i class="fas fa-sliders-h" style="color: #856404;"></i>
        </div>
        
        <p style="font-size: 0.75rem; color: #856404; margin-top: 8px; opacity: 0.8;">
            <i class="fas fa-info-circle"></i> Geser slider untuk menentukan poin pengurangan.
        </p>
    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button type="button" class="button" onclick="closeCurangModal()">Batal</button>
                <button type="submit" class="button is-danger">Simpan</button>
            </footer>
        </form>
    </div>
</div>

<!-- =========================================
     MODAL 2: EDIT NILAI
     ========================================= -->
<div class="modal" id="editNilaiModal">
    <div class="modal-background" onclick="closeEditNilaiModal()"></div>
    <div class="modal-card" style="max-width: 400px;">
        <header class="modal-card-head bg-primary">
            <p class="modal-card-title"><i class="fas fa-pencil-alt"></i> Edit Nilai</p>
            <button class="delete" aria-label="close" onclick="closeEditNilaiModal()"></button>
        </header>
        <form id="editNilaiForm" action="{{ route('guru.update-nilai') }}" method="POST">
            @csrf
            <section class="modal-card-body">
                <input type="hidden" name="peserta_id" id="edit_peserta_id">
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Nama Siswa</label>
                    <input class="input" type="text" id="edit_siswa_nama" readonly style="background: #f5f5f5; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; padding: 10px; font-weight: 600;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Nilai Baru</label>
                    <input class="input" type="number" name="nilai" id="edit_nilai" required min="0" max="100" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1.1rem; font-weight: bold;">
                    <p style="font-size: 0.75rem; color: #6c757d; margin-top: 5px;">Nilai saat ini: <strong id="edit_nilai_lama">0</strong></p>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button type="button" class="button" onclick="closeEditNilaiModal()">Batal</button>
                <button type="submit" class="button is-info">Update Nilai</button>
            </footer>
        </form>
    </div>
</div>

<!-- =========================================
     MODAL 3: JADWAL SUSULAN (SAMA SEBELUMNYA)
     ========================================= -->
<div class="modal" id="jadwalSusulanModal">
    <div class="modal-background" onclick="closeJadwalSusulanModal()"></div>
    <div class="modal-card" style="max-width: 700px;">
        <header class="modal-card-head bg-yellow">
            <p class="modal-card-title" style="color: #856404;">
                <i class="fas fa-calendar-plus"></i> Buat Jadwal Susulan
            </p>
            <button class="delete" aria-label="close" onclick="closeJadwalSusulanModal()"></button>
        </header>
        
        <form id="jadwalSusulanForm" action="{{ route('guru.jadwal-susulan.store') }}" method="POST">
            @csrf
            
            <section class="modal-card-body">
                <input type="hidden" name="ujian_id" id="form_ujian_id" value="{{ $ujian->id ?? '' }}">
                
                <!-- PENTING: HIDDEN INPUT NAME KELAS -->
                <input type="hidden" name="kelas_id" id="form_kelas_id" value="">
                <input type="hidden" name="untuk_susulan" value="1">
                <input type="hidden" name="tanggal_susulan" id="form_tanggal" value="">
                <input type="hidden" name="waktu_mulai" id="form_waktu_mulai" value="">
                <input type="hidden" name="waktu_selesai" id="form_waktu_selesai" value="">
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Nama Ujian</label>
                    <input class="input" type="text" id="form_nama_ujian" 
                           value="{{ $ujian->nama_ujian ?? '-' }}" readonly style="background: #f5f5f5; border: none; font-weight: 600; width: 100%; padding: 10px; border-radius: 8px;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Kelas <span style="color: #dc3545;">*</span></label>
                    <!-- PENTING: SELECT TIDAK PUNYA NAME AGAR TIDAK KONFLIK -->
                    <div style="position: relative;">
                        <select id="form_kelas_select" required style="width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px; font-size: 0.9rem;">
                            <option value="">Pilih Kelas</option>
                            @php $kelasList = \App\Models\Kelas::all(); @endphp
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Daftar Siswa Susulan <span style="color: #dc3545;">*</span></label>
                    <div>
                        <div style="margin-bottom: 8px;">
                            <button type="button" class="btn-edit" style="background: #2e5b9a; color: white;" onclick="selectAllSiswa()"><i class="fas fa-check-double"></i> Pilih Semua</button>
                            <button type="button" style="background: #f1f5f9; color: #333; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem;" onclick="unselectAllSiswa()"><i class="fas fa-times"></i> Batal Semua</button>
                        </div>
                        <div id="siswaSusulanList" style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                            <p style="color: #94a3b8; text-align: center; font-size: 0.85rem;">Pilih kelas terlebih dahulu</p>
                        </div>
                    </div>
                </div>
                
                <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 8px; border: 1px solid #ffeeba; font-size: 0.85rem; margin-bottom: 20px;">
                    <div style="display: flex; align-items: start; gap: 10px;">
                        <i class="fas fa-info-circle" style="margin-top: 4px;"></i>
                        <div>
                            <strong style="color: #856404;">Sistem akan membuat jadwal SEKARANG.</strong>
                            <div style="margin-top: 4px;">
                                Durasi: <span id="durasiInfoUjian">{{ $ujian->durasi ?? 0 }}</span> menit<br>
                                <span id="waktuOtomatisText" style="font-weight: 600; color: #856404; display: block; margin-top: 4px;">-- : -- s/d -- : -- WIB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem;">Pengawas <span style="color: #dc3545;">*</span></label>
                    <div style="position: relative;">
                        <select name="guru_id" id="form_guru_id" required style="width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px;">
                            <option value="">Pilih Pengawas</option>
                            @php $guruList = \App\Models\Guru::with('user')->get(); @endphp
                            @foreach($guruList as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
            
            <footer class="modal-card-foot">
                <button type="button" class="button" onclick="closeJadwalSusulanModal()"><i class="fas fa-times"></i> Batal</button>
                <button type="submit" class="button is-warning"><i class="fas fa-save"></i> Simpan Jadwal</button>
            </footer>
        </form>
    </div>
</div>

<script>
// Data siswa susulan
// Logic untuk update tampilan angka saat slider digeser
document.addEventListener('DOMContentLoaded', function() {
        // ... kode JS yang sudah ada ...

    // ==========================================
    // TAMBAHKAN LOGIKA NOTIFIKASI INI
    // ==========================================
    
    // Ambil data dari Controller ($notifData)
    // Pastikan controller Anda melempar variabel $notifData
    var notifData = @json($notifData ?? []);

    const notifWrapper = document.getElementById('notifWrapper');
    const notifBtn = document.getElementById('notifBtn');
    const notifList = document.getElementById('notifList');
    const notifCountBadge = document.getElementById('notifCount');

    // Fungsi Render Notifikasi
    function renderNotifications() {
        // Hitung yang unread
        const unreadCount = notifData.filter(n => n.unread).length;
        
        // Update badge
        notifCountBadge.innerText = unreadCount;
        if (unreadCount > 0) {
            notifCountBadge.classList.remove('hidden');
            notifWrapper.classList.add('has-new-notif');
        } else {
            notifCountBadge.classList.add('hidden');
            notifWrapper.classList.remove('has-new-notif');
        }

        // Render List
        if (notifData.length === 0) {
            notifList.innerHTML = `
                <div class="empty-notif">
                    <i class="fas fa-check-circle"></i>
                    <p>Tidak ada notifikasi baru</p>
                </div>`;
            return;
        }

        let html = '';
        notifData.forEach(notif => {
            html += `
            <a href="{{ route('pengawas.index', isset($dt) ? $dt->id : '') }}" class="notif-item ${notif.unread ? 'unread' : ''}" onclick="openNotif(${notif.id})">
                <div class="notif-icon-box">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">${notif.title}</div>
                    <div class="notif-desc">${notif.desc}</div>
                    <div class="notif-time">${notif.time}</div>
                </div>
            </a>
            `;
        });
        notifList.innerHTML = html;
    }

    // Toggle Notifikasi (Klik Tombol Lonceng)
    if (notifBtn) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifWrapper.classList.toggle('active');
            
            // Tutup user dropdown jika terbuka
            const userDropdown = document.getElementById('userDropdown');
            if(userDropdown) userDropdown.classList.remove('active');
        });
    }

    // Klik item notif (Opsional: Bisa diarahkan ke link tertentu)
    window.openNotif = function(id) {
        console.log("Membuka notif ID: " + id);
        
        // Hilangkan status unread lokal saat diklik
        const notif = notifData.find(n => n.id === id);
        if(notif) notif.unread = false;
        renderNotifications();
    }

    // Tandai semua dibaca
    window.markAllRead = function() {
        notifData.forEach(n => n.unread = false);
        renderNotifications();
    }

    // Jalankan render pertama kali
    renderNotifications();
    // ==========================================
    // SELESAI LOGIKA NOTIFIKASI
    // ==========================================
    const rangeInput = document.getElementById('modal_skors_nilai');
    const displayValue = document.getElementById('skors_value_display');

    if(rangeInput && displayValue) {
        rangeInput.addEventListener('input', function() {
            // Update teks angka sesuai posisi slider
            displayValue.textContent = this.value;
            
            // (Opsional) Efek visual warna berubah jika poin besar
            if(this.value > 50) {
                displayValue.style.color = '#dc3545'; // Merah jika potong > 50
            } else {
                displayValue.style.color = '#856404'; // Warna asli
            }
        });
    }
});
let siswaSusulanData = @json($siswaSusulan ?? []);

// --- 1. FUNGSI EDIT NILAI ---
function openEditNilaiModal(pesertaId, nilaiSekarang, namaSiswa) {
    document.getElementById('edit_peserta_id').value = pesertaId;
    document.getElementById('edit_siswa_nama').value = namaSiswa;
    document.getElementById('edit_nilai').value = nilaiSekarang;
    document.getElementById('edit_nilai_lama').innerText = nilaiSekarang;
    
    document.getElementById('editNilaiModal').classList.add('is-active');
}

function closeEditNilaiModal() {
    document.getElementById('editNilaiModal').classList.remove('is-active');
}

// Handle Submit Edit Nilai
document.getElementById('editNilaiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = 'Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('{{route('guru.update-nilai')}}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Koneksi gagal', 'error');
        btn.innerHTML = originalText;
    });
});

// --- 2. FUNGSI CURANG (UPDATED) ---
function showCurangModal(ujianId, siswaId, namaSiswa, nilaiSekarang) {
    document.getElementById('modal_ujian_id').value = ujianId;
    document.getElementById('modal_siswa_id').value = siswaId;
    document.getElementById('modal_siswa_nama').value = namaSiswa;
    
    // Set nilai siswa saat ini
    document.getElementById('display_nilai_siswa').innerText = nilaiSekarang;
    
    // Configure input skors nilai
    const skorsInput = document.getElementById('modal_skors_nilai');
    skorsInput.max = nilaiSekarang;
    skorsInput.placeholder = `0 (maks: ${nilaiSekarang})`;
    skorsInput.value = ''; // Reset
    
    document.getElementById('curangModal').classList.add('is-active');
}

function closeCurangModal() {
    document.getElementById('curangModal').classList.remove('is-active');
}

// --- 3. FUNGSI JADWAL SUSULAN ---
function openCreateJadwalSusulanModal(ujianId, ujianNama) {
    document.getElementById('form_ujian_id').value = ujianId;
    document.getElementById('form_nama_ujian').value = ujianNama;
    
    document.getElementById('jadwalSusulanForm').reset();
    document.getElementById('jadwalSusulanForm').action = "{{ route('guru.jadwal-susulan.store') }}";
    
    const kelasSelect = document.getElementById('form_kelas_select');
    kelasSelect.value = '';
    
    document.getElementById('siswaSusulanList').innerHTML = '<p style="color: #94a3b8; text-align: center; font-size: 0.85rem;">Pilih kelas terlebih dahulu</p>';
    
    // LOGIKA OTOMATIS WAKTU
    const now = new Date();
    const durasiUjian = parseInt("{{ $ujian->durasi ?? 60 }}") || 60; 
    const waktuSelesai = new Date(now.getTime() + (durasiUjian * 60 * 1000));
    
    const formatTime = (date) => date.toTimeString().substring(0, 5);
    const formatTanggal = (date) => date.toISOString().split('T')[0];
    
    document.getElementById('form_tanggal').value = formatTanggal(now);
    document.getElementById('form_waktu_mulai').value = formatTime(now);
    document.getElementById('form_waktu_selesai').value = formatTime(waktuSelesai);
    
    document.getElementById('durasiInfoUjian').innerText = durasiUjian;
    document.getElementById('waktuOtomatisText').innerText = `${formatTime(now)} - ${formatTime(waktuSelesai)} WIB`;
    
    document.getElementById('jadwalSusulanModal').classList.add('is-active');
}

function loadSiswaSusulanByKelas(kelasId) {
    const container = document.getElementById('siswaSusulanList');
    // Update Hidden Input
    document.getElementById('form_kelas_id').value = kelasId; 

    const filteredSiswa = siswaSusulanData.filter(s => s.kelas_id == kelasId);
    
    if (filteredSiswa.length === 0) {
        container.innerHTML = '<p style="color: #d97706; background: #fff7ed; padding: 10px; border-radius: 8px; text-align: center; font-size: 0.85rem;">Tidak ada siswa susulan di kelas ini</p>';
        return;
    }
    
    let html = '';
    filteredSiswa.forEach((siswa, index) => {
        html += `
            <div style="margin-bottom: 8px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="siswa_ids[]" value="${siswa.siswa_id}">
                    <strong>${siswa.siswa?.nama || 'Siswa'}</strong>
                    <span style="color: #94a3b8;"> - ${siswa.alasan || 'Tidak hadir'}</span>
                </label>
            </div>
        `;
        console.log(siswa);
    });
    
    container.innerHTML = html;
}

document.getElementById('form_kelas_select').addEventListener('change', function() {
    const kelasId = this.value;
    if (kelasId) {
        loadSiswaSusulanByKelas(kelasId);
    } else {
        document.getElementById('siswaSusulanList').innerHTML = '<p style="color: #94a3b8; text-align: center; font-size: 0.85rem;">Pilih kelas terlebih dahulu</p>';
        document.getElementById('form_kelas_id').value = ''; 
    }
});

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
    const kelasId = document.getElementById('form_kelas_id').value;
    const guruId = document.getElementById('form_guru_id').value;
    const checkedSiswa = document.querySelectorAll('#siswaSusulanList input[type="checkbox"]:checked');
    
    if (!kelasId) { e.preventDefault(); alert('Pilih kelas terlebih dahulu!'); return false; }
    if (checkedSiswa.length === 0) { e.preventDefault(); alert('Pilih minimal 1 siswa susulan!'); return false; }
    if (!guruId) { e.preventDefault(); alert('Pilih pengawas!'); return false; }
    
    if (confirm('Yakin ingin menyimpan jadwal susulan yang dimulai SEKARANG?')) { return true; }
    e.preventDefault(); return false;
});

// General UI Scripts (Dropdown, Mobile)
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) { e.stopPropagation(); userDropdown.classList.toggle('active'); });
        document.addEventListener('click', function() { if (userDropdown) userDropdown.classList.remove('active'); });
    }
    const mobileToggle = document.getElementById('mobileToggle'); const sidebar = document.getElementById('sidebar'); const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() {
        sidebar.classList.toggle('open'); sidebarOverlay.classList.toggle('active');
        const icon = mobileToggle.querySelector('i');
        if (sidebar.classList.contains('open')) { icon.classList.remove('fa-bars'); icon.classList.add('fa-times'); }
        else { icon.classList.remove('fa-times'); icon.classList.add('fa-bars'); }
    }
    if (mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('jadwalSusulanModal').classList.contains('is-active')) closeJadwalSusulanModal();
        if (document.getElementById('curangModal').classList.contains('is-active')) closeCurangModal();
        if (document.getElementById('editNilaiModal').classList.contains('is-active')) closeEditNilaiModal();
    }
});
</script>

</body>
</html>