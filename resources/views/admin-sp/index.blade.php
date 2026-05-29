<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Panitia Ujian | Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">

<style>
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
    .dropdown-item-custom:last-child { border-bottom: none; }
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
    .sidebar-item:hover {
       background: #ffffff96;
     border-left : 4px solid #fff;
     color:#2e5b9a;
    }
    .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 3px solid white; }
    .sidebar-logout { position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 12px; }
    .sidebar-logout .sidebar-item { color: white; }
    .sidebar-logout .sidebar-item:hover { background: #dc3545; }
    .main-content { flex: 1; margin-left: 260px; padding: 24px; transition: margin-left 0.3s ease; width: calc(100% - 260px); }
    .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
    .mobile-toggle i { font-size: 22px; }
    .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
    .sidebar-overlay.active { display: block; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #eef2f6; display: flex; align-items: center; gap: 16px; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon i { font-size: 26px; }
    .stat-info { flex: 1; }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-label { color: #6c757d; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }
    .section-title { font-size: 1.1rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 10px; }
    .exam-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .exam-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #eef2f6; }
    .exam-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .exam-header { padding: 14px 16px; background: #2e5b9a; color: white; display: flex; justify-content: space-between; align-items: center; }
    .exam-header span:first-child { font-weight: 600; font-size: 0.9rem; }
    .exam-status { padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; }
    .status-ready { background: #28a745; color: white; }
    .status-done { background: #6c757d; color: white; }
    .status-draft { background: #ffc107; color: #856404; }
    .status-ongoing { background: #17a2b8; color: white; }
    .exam-body { padding: 16px; }
    .exam-info-item { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 0.85rem; color: #4b5563; }
    .exam-info-item i { width: 20px; color: #5c6fa6; }
    .progress-bar { width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; margin: 12px 0 8px; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #28a745, #2e5b9a); border-radius: 3px; transition: width 0.3s ease; }
    .exam-footer { padding: 12px 16px; background: #fafbfe; border-top: 1px solid #eef2f6; display: flex; justify-content: space-between; align-items: center; }
    .class-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
    .class-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #eef2f6; }
    .class-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .class-header { padding: 14px 16px; background: linear-gradient(135deg, #5c6fa6 0%, #2e5b9a 100%); color: white; font-weight: 600; font-size: 0.9rem; }
    .class-body { padding: 16px; }
    .class-stats { display: flex; justify-content: space-around; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #eef2f6; }
    .class-stat { text-align: center; }
    .class-stat-value { font-size: 1.3rem; font-weight: 700; color: #2e5b9a; }
    .class-stat-label { font-size: 0.7rem; color: #6c757d; }
    .btn-action-card { color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease; text-align: center; display: inline-block; text-decoration: none; flex: 1; }
    .btn-action-card:hover { transform: translateY(-2px); color: white; }
    .empty-state { text-align: center; padding: 40px; background: white; border-radius: 12px; color: #6c757d; border: 1px solid #eef2f6; }
    .empty-state i { font-size: 3rem; color: #adb5bd; margin-bottom: 12px; }
    .badge-custom { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; background: #eef2ff; color: #2e5b9a; }

    /* Modal Styles */
    .modal { display: none; }
    .modal.is-active { display: flex; overflow: hidden;}
    #jadwalSusulanModal{
        margin-left:150px;
        overflow:hidden;
    }
    .modal-card { width: 90%; max-width: 900px;margin-left:300px;margin-bottom:-75px;
    max-height: 80vh; overflow-y: auto; border-radius: 16px; }
    .modal-card-head { background: #2e5b9a; color: white; border-bottom: none; border-radius: 16px 16px 0 0; padding: 20px 24px; }
    .modal-card-title { color: white; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .modal-card-body { padding: 24px; overflow-y: auto;}
    .modal-card-foot { background: #fafbfe; border-top: 1px solid #eef2f6; justify-content: flex-end; padding: 15px 24px; border-radius: 0 0 16px 16px; }
    .modal-background { background: rgba(0,0,0,0.6); }
    .delete { background: rgba(255,255,255,0.3); }
    
    /* Inner Recap Styles */
    .inner-section-title { font-weight: 600; color: #2e5b9a; margin-bottom: 12px; margin-top: 24px; display: flex; align-items: center; gap: 8px; font-size: 0.95rem; border-bottom: 1px solid #eef2f6; padding-bottom: 8px;}
    .absensi-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
    .absensi-stat-box { padding: 10px; border-radius: 8px; text-align: center; }
    .absensi-stat-box .val { font-weight: 700; font-size: 1.1rem; }
    .absensi-stat-box .lbl { font-size: 0.75rem; margin-top: 2px; }
    .ujian-group-box { border: 1px solid #eef2f6; border-radius: 10px; padding: 15px; margin-bottom: 15px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.03); }
    .ujian-group-title { font-weight: 600; color: #1f2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; font-size: 0.9rem;}
    .berita-acara-inner { padding: 15px; background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%); border-radius: 10px; color: white; margin-bottom: 10px; }
    .berita-acara-empty { padding: 15px; background: #f8f9fa; border-radius: 10px; border: 1px dashed #ccc; color: #6c757d; text-align: center; }

    @media (max-width: 768px) {
        .header h2 span, .user-name span { display: inline; }
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
        .mobile-toggle { display: flex; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card { padding: 14px; }
        .stat-icon { width: 44px; height: 44px; }
        .stat-value { font-size: 1.3rem; }
        .exam-grid, .class-grid { grid-template-columns: 1fr; }
        .absensi-stats-grid { grid-template-columns: repeat(2, 1fr); }
        .modal-card { width: 95%; max-height: 90vh; margin-left:20px;
        ;margin-bottom:-75px;}
    }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #5c6fa6; border-radius: 3px; }
</style>
</head>
<body>

<header class="header">
    <h2>
        <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34" style="height:30px"/>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
            <div class="user-name">
                @if(isset($panitia)) <span>{{ $panitia->nama ?? 'Panitia' }}</span> @else  @endif
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="dropdown-menu-custom">
    <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
        <i class="fas fa-user-circle"></i>
        <span>Profil Saya</span>
    </a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn logout-button" style="width: 100%; background: none; border: none; cursor: pointer;"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
        </div>
    </div>
</header>

<button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('admin-ops.index') }}" class="sidebar-item active"><i class="fas fa-home"></i><span>Dashboard</span></a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-button" style="width: 100%; background: none; border: none; cursor: pointer;"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </form>
        </div>
    </aside>
    
    <main class="main-content" id="mainContent">
        
        
        <div style="margin-bottom: 24px;">
            <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 600; margin-bottom: 4px;"><i class="fas fa-tachometer-alt"></i> Dashboard Operasional</h1>
            <p style="color: #6c757d; font-size: 0.85rem;">Kelola jadwal ujian, absensi, dan berita acara</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #eef2ff;"><i class="fas fa-school" style="color: #2e5b9a;"></i></div>
                <div class="stat-info"><div class="stat-value">{{ $kla->count() ?? 0 }}</div><div class="stat-label">Total Kelas</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #d4edda;"><i class="fas fa-book-open" style="color: #28a745;"></i></div>
                <div class="stat-info"><div class="stat-value">{{ $uji->where('status', '!=', 'done')->count() ?? 0 }}</div><div class="stat-label">Ujian Aktif</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3cd;"><i class="fas fa-check-circle" style="color: #ffc107;"></i></div>
                <div class="stat-info"><div class="stat-value">{{ $uji->where('status', 'done')->count() ?? 0 }}</div><div class="stat-label">Ujian Selesai</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #cfe2ff;"><i class="fas fa-clock" style="color: #17a2b8;"></i></div>
                <div class="stat-info"><div class="stat-value">{{ $uji->where('status', 'ready')->count() ?? 0 }}</div><div class="stat-label">Ready to Start</div></div>
            </div>
        </div>

        <!-- Ujian Ready -->
        <div class="section-title"><i class="fas fa-play-circle"></i> Ujian Siap Dilaksanakan</div>
        @php $readyExams = isset($uji) ? $uji->where('status', 'ready') : collect(); @endphp
        @if($readyExams->count() > 0)
            <div class="exam-grid">
                @foreach($readyExams as $uj)
                <div class="exam-card">
                    <div class="exam-header">
                        <span>{{ $uj->nama_ujian ?? 'Ujian' }}</span>
                        <span class="exam-status status-ready">Ready</span>
                    </div>
                    <div class="exam-body">
                        <div class="exam-info-item"><i class="fas fa-book"></i><span>{{ $uj->mapels->nama_mapel ?? '-' }}</span></div>
                        <div class="exam-info-item"><i class="fas fa-hourglass-half"></i><span>Durasi: {{ $uj->durasi ?? '-' }} Menit</span></div>
                        @php $totalSchedules = isset($jad) ? $jad->where('ujian_id', $uj->id)->count() : 0; $totalKelas = $uj->kelas->count() ?? 0; $progress = $totalKelas > 0 ? ($totalSchedules / $totalKelas) * 100 : 0; @endphp
                        <div class="progress-bar"><div class="progress-fill" style="width: {{ $progress }}%"></div></div>
                        <div class="is-flex is-justify-content-space-between mt-1">
                            <span class="is-size-7 has-text-grey">Progress Penjadwalan</span>
                            <span class="is-size-7 has-text-weight-bold">{{ $totalSchedules }}/{{ $totalKelas }} Kelas</span>
                        </div>
                       <div style="display: flex; flex-wrap: wrap; gap: 6px;">
    @foreach($uj->kelas as $ujd)
        <span class="tag is-info is-light">
            {{ $ujd->nama_kelas }}
        </span>
    @endforeach
</div>
                    </div>
                    <div class="exam-footer">
                        <span class="badge-custom"><i class="fas fa-clock"></i> {{ isset($uj->created_at) ? $uj->created_at->diffForHumans() : '-' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="fas fa-hourglass-half"></i><p>Tidak ada ujian dengan status ready</p></div>
        @endif

        <!-- Semua Ujian -->
        <div class="section-title mt-4"><i class="fas fa-list"></i> Semua Ujian</div>
        <div class="exam-grid">
            @forelse($uji ?? [] as $uj)
            <div class="exam-card">
                <div class="exam-header">
                    <span>{{ $uj->nama_ujian ?? 'Ujian' }}</span>
                    <span class="exam-status @if($uj->status == 'ready') status-ready @elseif($uj->status == 'done') status-done @elseif($uj->status == 'ongoing') status-ongoing @else status-draft @endif">{{ $uj->status ?? 'draft' }}</span>
                </div>
                <div class="exam-body">
                    <div class="exam-info-item"><i class="fas fa-book"></i><span>{{ $uj->mapels->nama_mapel ?? '-' }}</span></div>
                    <div class="exam-info-item"><i class="fas fa-hourglass-half"></i><span>Durasi: {{ $uj->durasi ?? '-' }} Menit</span></div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1/-1;"><i class="fas fa-folder-open"></i><p>Belum ada ujian</p></div>
            @endforelse
        </div>

        <!-- Daftar Kelas + INTEGRASI REKAP -->
        <div class="section-title mt-4"><i class="fas fa-users"></i> Daftar Kelas & Rekap Ujian</div>
        <div class="class-grid">
            @forelse($kla ?? [] as $k)
            <div class="class-card">
                <div class="class-header"><i class="fas fa-door-open mr-2"></i> {{ $k->nama_kelas ?? 'Kelas' }}</div>
                <div class="class-body">
                    @php
                        $totalSiswa = isset($sis) ? $sis->where("kelas_id", $k->id)->count() : 0;
                        $kelasId = $k->id;

$red = App\Models\Ujian::whereHas('kelas', function ($q) use ($kelasId) {
    $q->where('kelas.id', $kelasId);
})->where('status', 'ready')->where('mode','cbt')->get();
                        $jadwalKelas = isset($jad) ? $jad->where('kelas_id', $k->id) : collect();
                    @endphp
                    <div class="class-stats">
                        <div class="class-stat"><div class="class-stat-value">{{ $totalSiswa }}</div><div class="class-stat-label">Siswa</div></div>
                        <div class="class-stat"><div class="class-stat-value">{{ $jadwalKelas->count() }}</div><div class="class-stat-label">Ujian</div></div>
                        <div class="class-stat"><div class="class-stat-value" style="color:#28a745;">{{ $red->count() }}</div><div class="class-stat-label">Ready</div></div>
                    </div>

                    <!-- Dua Tombol Aksi -->
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('admin-ops.set', $k->id) }}" class="btn-action-card" style="background: #2e5b9a;">
                            <i class="fas fa-calendar-alt" style="margin-right:6px;"></i> Atur Jadwal
                        </a>
                        <button class="btn-action-card" style="background: #5c6fa6;" onclick="openRekapModal({{ $k->id }}, '{{ $k->nama_kelas }}')">
                            <i class="fas fa-clipboard-list" style="margin-right:6px;"></i> Lihat Rekap
                        </button>
                    </div>
                </div>

                <!-- HIDDEN DATA REKAP (Tidak terlihat di UI, dipanggil oleh JS) -->
                <div id="rekap-data-{{ $k->id }}" style="display: none;">
                    @php
                        $absensiKelas = \App\Models\Absensi::where('kelas_id', $k->id)->get();
                        $beritaKelas = \App\Models\Berita::where('kelas_id', $k->id)->get();
                        $susulanKelas = \App\Models\Susulan::where('kelas_id', $k->id)->get();
                        
                        $hadirCount = $absensiKelas->where('status_kehadiran', 'hadir')->count();
                        $sakitCount = $absensiKelas->where('status_kehadiran', 'sakit')->count();
                        $izinCount = $absensiKelas->where('status_kehadiran', 'izin')->count();
                        $alfaCount = $absensiKelas->where('status_kehadiran', 'alfa')->count();
                        
                        $absensiByUjian = $absensiKelas->groupBy('ujian_id');
                        $beritaByUjian = $beritaKelas->groupBy('ujian_id');
                        $susulanByUjian = $susulanKelas->groupBy('ujian_id');
                    @endphp
                    
                    <div class="inner-section-title" style="margin-top:0;"><i class="fas fa-chart-pie"></i> Statistik Kehadiran Keseluruhan</div>
                    <div class="absensi-stats-grid">
                        <div class="absensi-stat-box" style="background: #d4edda;"><div class="val" style="color: #155724;">{{ $hadirCount }}</div><div class="lbl" style="color: #155724;">Hadir</div></div>
                        <div class="absensi-stat-box" style="background: #fff3cd;"><div class="val" style="color: #856404;">{{ $sakitCount }}</div><div class="lbl" style="color: #856404;">Sakit</div></div>
                        <div class="absensi-stat-box" style="background: #cfe2ff;"><div class="val" style="color: #084298;">{{ $izinCount }}</div><div class="lbl" style="color: #084298;">Izin</div></div>
                        <div class="absensi-stat-box" style="background: #f8d7da;"><div class="val" style="color: #842029;">{{ $alfaCount }}</div><div class="lbl" style="color: #842029;">Alfa</div></div>
                    </div>

                    @if($absensiByUjian->count() > 0)
                    <div class="inner-section-title"><i class="fas fa-clipboard-check"></i> Detail Absensi Per Ujian</div>
                    @foreach($absensiByUjian as $ujianId => $absensinya)
                        @php $namaUjian = \App\Models\Ujian::find($ujianId)?->nama_ujian ?? 'Ujian Dihapus'; @endphp
                        <div class="ujian-group-box">
                            <div class="ujian-group-title"><i class="fas fa-book" style="color:#2e5b9a;"></i> {{ $namaUjian }}</div>
                            <div style="overflow-x: auto;">
                                <table class="table is-fullwidth is-striped is-narrow">
                                    <thead><tr><th>Nama Siswa</th><th>Status</th><th>Waktu</th></tr></thead>
                                    <tbody>
                                        @foreach($absensinya as $abs)
                                        <tr>
                                            <td>{{ $abs->siswa->nama ?? '-' }}</td>
                                            <td><span class="tag is-{{ $abs->status_kehadiran == 'hadir' ? 'success' : ($abs->status_kehadiran == 'sakit' ? 'warning' : ($abs->status_kehadiran == 'izin' ? 'info' : 'danger')) }} is-light">{{ ucfirst($abs->status_kehadiran) }}</span></td>
                                            <td>{{ $abs->created_at? \Carbon\Carbon::parse($abs->created_at)->format('H:i') : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    @else
                    <div class="berita-acara-empty" style="margin-bottom: 20px;"><i class="fas fa-clipboard"></i> Belum ada data absensi.</div>
                    @endif

                    @if($beritaByUjian->count() > 0)
                    <div class="inner-section-title">
    <i class="fas fa-file-alt"></i> Berita Acara
</div>

@foreach($beritaByUjian as $ujianId => $beritas)
    @php
        $namaUjian = \App\Models\Ujian::find($ujianId)?->nama_ujian ?? 'Ujian Dihapus';
    @endphp

    <div class="ujian-group-box">
        <details style="cursor:pointer;">
            <summary class="ujian-group-title" style="background: #eef4ff; color: #2e5b9a;padding:8px 10px;">
                <i class="fas fa-book" style="color:#2e5b9a;"></i>
                {{ $namaUjian }}
                <span style="margin-left:auto; font-size:0.75rem; color:#6b7280;">
                    ({{ count($beritas) }} laporan)
                </span>
            </summary>

            <div style="margin-top:12px;">
                @foreach($beritas as $b)
    @php
        $kelasBerita = \App\Models\Kelas::find($b->kelas_id);
        $namaKelasBerita = $kelasBerita->nama_kelas ?? 'Tidak Diketahui';
    @endphp

    <div class="class-card" style="border: 1px solid #dbeafe; margin-bottom: 16px;">
        
        <!-- Header -->
        

        <!-- Body -->
        <div class="class-body">

            <!-- Isi Catatan -->
            <div class="class-header" style="color: #2e5b9a;background:white;   ">
            

               {{ $b->catatan ?: 'Tidak ada catatan.' }}
        </div>

            <!-- Footer -->
            <div style="
                margin-top: 5px;
                padding-top: 5px;
                border-top: 1px solid #edf2f7;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.8rem;
                color: #64748b;
            ">
                <div>
                    <i class="fas fa-user-check"></i>
                    {{ $b->pengawas->guru->nama ?? Auth::user()->name }}
                </div>

                <div>
                    <i class="fas fa-clock"></i>
                    {{ \Carbon\Carbon::parse($b->created_at)->locale('id')->translatedformat('d M Y H:i') }}
                </div>
            </div>

        </div>
    </div>
    @endforeach
                </div>
        </details>
    </div>
@endforeach
                    @else
                                <div class="berita-acara-empty">
                                <i class="fas fa-file-alt"></i> Belum ada berita acara.
                            </div>

                    @endif

                    <div class="inner-section-title"><i class="fas fa-clock"></i> Data Susulan</div>
                    @if($susulanByUjian->count() > 0)
                    @foreach($susulanByUjian as $ujianId => $susulans)
                        @php 
                            $namaUjianSus = \App\Models\Ujian::find($ujianId)?->nama_ujian ?? 'Ujian Dihapus';
                            $hasJadwalSusulan = \App\Models\Jadwal::where('ujian_id', $ujianId)
    ->where('kelas_id', $k->id)
    ->where('untuk_susulan', DB::raw('true'))
    ->exists();
                        @endphp
                        <div class="ujian-group-box">
                            <div class="ujian-group-title"><i class="fas fa-book" style="color:#2e5b9a;"></i> {{ $namaUjianSus }}</div>
                            <div style="overflow-x: auto;">
                                <table class="table is-fullwidth is-striped is-narrow">
                                    <thead><tr><th>Nama</th><th>Alasan</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @foreach($susulans as $sus)
                                        <tr>
                                            <td>{{ $sus->siswa->nama ?? '-' }}</td>
                                            <td>{{ $sus->alasan ?? '-' }}</td>
                                            <td>
                                                @if($hasJadwalSusulan)
                                                    <span class="tag is-success is-light">Sudah Dijadwalkan</span>
                                                @else
                                                    <span class="tag is-warning is-light">Belum Dijadwalkan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(!$hasJadwalSusulan)
                            <button class="button is-warning is-small mt-3" onclick="openCreateJadwalSusulanModal({{ $k->id }}, '{{ $k->nama_kelas }}', {{ $ujianId }}, '{{ addslashes($namaUjianSus) }}', {{ $uj->durasi ?? 0 }})">
                                <i class="fas fa-calendar-plus" style="margin-right:6px;"></i> Buat Jadwal Susulan
                            </button>
                            @endif
                        </div>
                    @endforeach
                    @else
                    <div class="berita-acara-empty"><i class="fas fa-clock"></i> Tidak ada siswa susulan.</div>
                    @endif

                    <script>window.susulanDataKelas_{{ $k->id }} = @json($susulanKelas);</script>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1/-1;"><i class="fas fa-school"></i><p>Belum ada data kelas</p></div>
            @endforelse
        </div>
    </main>
</div>

<!-- MODAL REKAP KELAS -->
<div class="modal" id="rekapKelasModal">
    <div class="modal-background" onclick="closeRekapModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title" id="rekapModalTitle"><i class="fas fa-clipboard-list"></i> Rekap Kelas</p>
            <button class="delete" aria-label="close" onclick="closeRekapModal()"></button>
        </header>
        <section class="modal-card-body" id="rekapModalBody">
            <!-- Diisi oleh Javascript -->
        </section>
        <footer class="modal-card-foot">
            <button class="button is-danger has-text-light is-fullwidth"
             onclick="closeRekapModal()"><i class="fas fa-times"></i></button>
        </footer>
    </div>
</div>


<!-- MODAL JADWAL SUSULAN -->
<div class="modal" id="jadwalSusulanModal">
    <div class="modal-background" onclick="closeJadwalSusulanModal()"></div>
    <div class="modal-card" style="max-width: 600px;">
        <header class="modal-card-head" style="background: #ffc107; color: #856404;">
            <p class="modal-card-title" style="color: #856404;"><i class="fas fa-calendar-plus"></i> Buat Jadwal Susulan Hari Ini</p>
            <button class="delete" aria-label="close" onclick="closeJadwalSusulanModal()"></button>
        </header>
        <form id="jadwalSusulanForm" action="{{ route('admin-ops.jadwal-susulan.store') }}" method="POST">
            @csrf
            <section class="modal-card-body">
                <input type="hidden" name="kelas_id" id="form_kelas_id" value="">
                <input type="hidden" name="untuk_susulan" value="1">
                
                <div class="field">
                    <label class="label"><i class="fas fa-book"></i> Ujian <span class="has-text-danger">*</span></label>
                    <div class="control">
                    <input type="text" name="ujian_nama" id="form_susulan_ujian_nama" class="input" readonly/>
                            <input type="hidden" name="ujian_id" id="form_susulan_ujian_id"  required/>
                    </div>
                </div>

                <div class="field">
                    <label class="label"><i class="fas fa-users"></i> Kelas</label>
                    <div class="control"><input class="input" type="text" id="form_nama_kelas_display" readonly style="background: #f5f5f5;"></div>
                </div>
                
                <div class="field">
                    <label class="label"><i class="fas fa-user-graduate"></i> Siswa Susulan</label>
                    <div class="control">
                        <div class="buttons mb-2">
                            <button type="button" class="button is-small is-info" onclick="selectAllSiswa()"><i class="fas fa-check-double"  style="margin-right:6px;"></i> Pilih Semua</button>
                            <button type="button" class="button is-small is-light" onclick="unselectAllSiswa()"><i class="fas fa-times"  style="margin-right:6px;"></i> Batal Semua</button>
                        </div>
                        <div id="siswaSusulanList" style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                            <p class="has-text-grey-light has-text-centered">Pilih kelas dari rekap terlebih dahulu</p>
                        </div>
                    </div>
                </div>
                <hr>
                
                <!-- Info Pengingat -->
                <div class="notification is-warning is-light py-2 mb-4" style="font-size: 0.8rem; border-radius: 8px;">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Catatan:</strong> Jadwal akan langsung dibuat untuk <strong>hari ini</strong> dan pengawas akan <strong>diacak otomatis</strong> oleh sistem.
                </div>

                <div class="columns">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"><i class="fas fa-clock"></i> Mulai <span class="has-text-danger">*</span></label>
                            <div class="control"><input class="input" type="time" name="waktu_mulai" id="form_waktu_mulai" required></div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"><i class="fas fa-clock"></i> Selesai <span class="has-text-danger">*</span></label>
                            <div class="control"><input class="input" type="time" name="waktu_selesai" id="form_waktu_selesai" readonly></div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot" style="justify-content: flex-end;">
                <div class="buttons">
                    <button type="button" class="button is-danger " onclick="closeJadwalSusulanModal()"><i class="fas fa-times"></i></button>
                    <button type="submit" class="button is-success"><i class="fas fa-save"></i></button>
                </div>
            </footer>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.logout-form').forEach(function(form) {

        let submitted = false;

        form.addEventListener('submit', function(e) {

            if (submitted) {
                e.preventDefault();
                return;
            }

            submitted = true;

            const btn = form.querySelector('.logout-button');

            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
            }
        });
    });
    let currentDurasi = 0;
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

// REKAP MODAL LOGIC
function openRekapModal(kelasId, namaKelas) {
    document.getElementById('rekapModalTitle').innerHTML = `<i class="fas fa-clipboard-list"></i> Rekap Ujian - ${namaKelas}`;
    const dataDiv = document.getElementById(`rekap-data-${kelasId}`);
    if(dataDiv) {
        document.getElementById('rekapModalBody').innerHTML = dataDiv.innerHTML;
        document.getElementById('rekapKelasModal').classList.add('is-active');
        document.body.style.overflow = 'hidden';
    } else {
        alert('Data rekap tidak ditemukan!');
    }
}

function closeRekapModal() {
    document.getElementById('rekapKelasModal').classList.remove('is-active');
    document.body.style.overflow = '';
}

// SUSULAN MODAL LOGIC
function openCreateJadwalSusulanModal(kelasId, kelasNama, ujianId, ujianNama, durasi) {
    currentDurasi = durasi;
    
    document.getElementById('jadwalSusulanForm').reset();
    document.getElementById('form_kelas_id').value = kelasId;
    document.getElementById('form_susulan_ujian_nama').value = ujianNama;
    document.getElementById('form_nama_kelas_display').value = kelasNama;
    document.getElementById('form_susulan_ujian_id').value = ujianId;
    let data = window['susulanDataKelas_' + kelasId] || [];

    // 🔥 ISI CHECKBOX SISWA
    populateSusulanCheckboxes(data, ujianId);
    document.getElementById('jadwalSusulanModal').classList.add('is-active');
}

function closeJadwalSusulanModal() {
    document.getElementById('jadwalSusulanModal').classList.remove('is-active');
    document.body.style.overflow = '';
}

function populateSusulanCheckboxes(data, ujianId) {
    const container = document.getElementById('siswaSusulanList');
    let filtered = data;
    if(ujianId) { filtered = data.filter(s => s.ujian_id == ujianId); }

    if(filtered.length === 0) {
        container.innerHTML = '<p class="has-text-warning has-text-centered">Tidak ada siswa susulan pada ujian ini</p>';
        return;
    }
    
    let html = '';
    filtered.forEach(siswa => {
        html += `<div class="field" style="margin-bottom: 8px;">
            <label class="checkbox">
                <input type="checkbox" name="siswa_ids[]" value="${siswa.siswa_id}">
                <strong>${siswa.siswa?.nama || 'Siswa'}</strong>
                <span class="has-text-grey"> - ${siswa.alasan || 'Tidak hadir'}</span>
            </label>
        </div>`;
    });
    container.innerHTML = html;
}

function selectAllSiswa() { document.querySelectorAll('#siswaSusulanList input[type="checkbox"]').forEach(cb => cb.checked = true); }
function unselectAllSiswa() { document.querySelectorAll('#siswaSusulanList input[type="checkbox"]').forEach(cb => cb.checked = false); }

// VALIDASI FORM SUSULAN
// VALIDASI FORM SUSULAN
document.getElementById('jadwalSusulanForm').addEventListener('submit', function(e) {
    if(!this.querySelector('[name="ujian_id"]').value || !this.querySelector('[name="waktu_mulai"]').value || !this.querySelector('[name="waktu_selesai"]').value) {
        e.preventDefault(); alert('Harap lengkapi Ujian, Waktu Mulai, dan Waktu Selesai!'); return;
    }
    const checkedSiswa = document.querySelectorAll('#siswaSusulanList input[type="checkbox"]:checked');
    if(checkedSiswa.length === 0) { e.preventDefault(); alert('Pilih minimal 1 siswa susulan!'); return; }
    if(!confirm(`Yakin ingin menyimpan jadwal susulan untuk HARI INI dengan pengawas ACAK?`)) { e.preventDefault(); }
});
document.getElementById('form_waktu_mulai').addEventListener('change', function() {
    if (!this.value || !currentDurasi) return;

    let [jam, menit] = this.value.split(':').map(Number);

    let totalMenit = jam * 60 + menit + currentDurasi;

    let selesaiJam = Math.floor(totalMenit / 60) % 24;
    let selesaiMenit = totalMenit % 60;

    let formatJam = String(selesaiJam).padStart(2, '0');
    let formatMenit = String(selesaiMenit).padStart(2, '0');

    document.getElementById('form_waktu_selesai').value = `${formatJam}:${formatMenit}`;
});
// TUTUP MODAL DENGAN TOMBOL ESCAPE
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('rekapKelasModal').classList.contains('is-active')) closeRekapModal();
        if (document.getElementById('jadwalSusulanModal').classList.contains('is-active')) closeJadwalSusulanModal();
    }
});
</script>

</body>
</html>