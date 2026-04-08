<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Panitia Ujian | Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    body {
        background: #f3f5f9;
        overflow-x: hidden;
    }

    /* ===== HEADER ===== */
    .header {
        background: #2e5b9a;
        color: white;
        padding: 12px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .header h2 {
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header h2 i {
        font-size: 1.2rem;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 12px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .user-info:hover {
        background: rgba(255,255,255,0.15);
    }

    .user-avatar {
        width: 34px;
        height: 34px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2e5b9a;
        font-weight: bold;
    }

    .user-name {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .user-name i {
        font-size: 0.7rem;
        margin-left: 5px;
    }

    /* Dropdown Menu */
    .dropdown-menu-custom {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 8px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .user-dropdown.active .dropdown-menu-custom {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item-custom {
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s ease;
        border-bottom: 1px solid #eee;
        font-size: 0.85rem;
    }

    .dropdown-item-custom:last-child {
        border-bottom: none;
    }

    .dropdown-item-custom:hover {
        background: #f5f5f5;
    }

    .dropdown-item-custom i {
        width: 18px;
        color: #2e5b9a;
    }

    .dropdown-divider {
        height: 1px;
        background: #eee;
        margin: 4px 0;
    }

    .logout-btn {
        color: #dc3545;
    }

    .logout-btn i {
        color: #dc3545;
    }

    /* ===== LAYOUT ===== */
    .app-wrapper {
        display: flex;
        margin-top: 56px;
        min-height: calc(100vh - 56px);
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 260px;
        background: #5c6fa6;
        position: fixed;
        left: 0;
        top: 56px;
        bottom: 0;
        z-index: 99;
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        margin: 4px 12px;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sidebar-item i {
        width: 22px;
        font-size: 1rem;
    }

    .sidebar-item span {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .sidebar-item:hover {
        background: rgba(255,255,255,0.2);
    }

    .sidebar-item.active {
        background: rgba(255,255,255,0.25);
        border-left: 3px solid white;
    }

    .sidebar-logout {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        padding: 0 12px;
    }

    .sidebar-logout .sidebar-item {
        color: white;
    }

    .sidebar-logout .sidebar-item:hover {
        background: #dc3545;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        flex: 1;
        margin-left: 260px;
        padding: 24px;
        transition: margin-left 0.3s ease;
        width: calc(100% - 260px);
    }

    /* Mobile Menu Toggle */
    .mobile-toggle {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: #2e5b9a;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        border: none;
        color: white;
    }

    .mobile-toggle i {
        font-size: 22px;
    }

    /* Overlay untuk mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 98;
    }

    .sidebar-overlay.active {
        display: block;
    }

    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 26px;
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* Section Title */
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2e5b9a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Exam Grid */
    .exam-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .exam-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
    }

    .exam-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .exam-header {
        padding: 14px 16px;
        background: #2e5b9a;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .exam-header span:first-child {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .exam-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-ready {
        background: #28a745;
        color: white;
    }

    .status-done {
        background: #6c757d;
        color: white;
    }

    .status-draft {
        background: #ffc107;
        color: #856404;
    }

    .status-ongoing {
        background: #17a2b8;
        color: white;
    }

    .exam-body {
        padding: 16px;
    }

    .exam-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 0.85rem;
        color: #4b5563;
    }

    .exam-info-item i {
        width: 20px;
        color: #5c6fa6;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin: 12px 0 8px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #2e5b9a);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .exam-footer {
        padding: 12px 16px;
        background: #fafbfe;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Class Grid */
    .class-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .class-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
    }

    .class-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .class-header {
        padding: 14px 16px;
        background: linear-gradient(135deg, #5c6fa6 0%, #2e5b9a 100%);
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .class-body {
        padding: 16px;
    }

    .class-stats {
        display: flex;
        justify-content: space-around;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eef2f6;
    }

    .class-stat {
        text-align: center;
    }

    .class-stat-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2e5b9a;
    }

    .class-stat-label {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .btn-set-schedule {
        background: #2e5b9a;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        display: inline-block;
        text-decoration: none;
    }

    .btn-set-schedule:hover {
        background: #1e3a6b;
        transform: translateY(-2px);
        color: white;
    }

    .btn-outline-sm {
        background: transparent;
        border: 1px solid #2e5b9a;
        color: #2e5b9a;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-outline-sm:hover {
        background: #2e5b9a;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 12px;
        color: #6c757d;
        border: 1px solid #eef2f6;
    }

    .empty-state i {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 12px;
    }

    /* Badge */
    .badge-custom {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: #eef2ff;
        color: #2e5b9a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header h2 span {
            display: none;
        }
        
        .user-name span {
            display: none;
        }
        
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
            padding: 16px;
        }
        
        .mobile-toggle {
            display: flex;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .stat-card {
            padding: 14px;
        }
        
        .stat-icon {
            width: 44px;
            height: 44px;
        }
        
        .stat-icon i {
            font-size: 20px;
        }
        
        .stat-value {
            font-size: 1.3rem;
        }
        
        .exam-grid {
            grid-template-columns: 1fr;
        }
        
        .class-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 5px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #5c6fa6;
        border-radius: 3px;
    }
</style>
</head>
<body>

<!-- Header -->
<header class="header">
    <h2>
        <i class="fas fa-chalkboard-user"></i>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="user-name">
                @if(isset($panitia))
                    <span>{{ $panitia->nama ?? 'Panitia' }}</span>
                @else
                    <span>Panitia</span>
                @endif
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div class="dropdown-menu-custom">
            <div class="dropdown-item-custom">
                <i class="fas fa-user-circle"></i>
                <span>Profil Saya</span>
            </div>
            <div class="dropdown-divider"></div>
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Menu Toggle -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('admin-ops.index') }}" class="sidebar-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            


        </div>
        
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom" style="margin-bottom: 20px;">
            <ul style="list-style: none; display: flex; gap: 8px; flex-wrap: wrap;">
                <li><a href="#" style="color: #5c6fa6; text-decoration: none;"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="is-active" style="color: #2e5b9a;">Panitia Ujian</li>
            </ul>
        </div>
        
        <!-- Header -->
        <div style="margin-bottom: 24px;">
            <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 600; margin-bottom: 4px;">
                <i class="fas fa-tachometer-alt"></i> Dashboard Operasional
            </h1>
            <p style="color: #6c757d; font-size: 0.85rem;">
                Kelola jadwal ujian, ruangan, dan pengawas
            </p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #eef2ff;">
                    <i class="fas fa-school" style="color: #2e5b9a;"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $kla->count() ?? 0 }}</div>
                    <div class="stat-label">Total Kelas</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #d4edda;">
                    <i class="fas fa-book-open" style="color: #28a745;"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $uji->where('status', '!=', 'done')->count() ?? 0 }}</div>
                    <div class="stat-label">Ujian Aktif</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3cd;">
                    <i class="fas fa-check-circle" style="color: #ffc107;"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $uji->where('status', 'done')->count() ?? 0 }}</div>
                    <div class="stat-label">Ujian Selesai</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #cfe2ff;">
                    <i class="fas fa-clock" style="color: #17a2b8;"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $uji->where('status', 'ready')->count() ?? 0 }}</div>
                    <div class="stat-label">Ready to Start</div>
                </div>
            </div>
        </div>

        <!-- Ujian yang Siap Dilaksanakan -->
        <div class="section-title">
            <i class="fas fa-play-circle"></i>
            Ujian Siap Dilaksanakan (Status: Ready)
        </div>

        @php
            $readyExams = isset($uji) ? $uji->where('status', 'ready') : collect();
        @endphp

        @if($readyExams->count() > 0)
            <div class="exam-grid">
                @foreach($readyExams as $uj)
                <div class="exam-card">
                    <div class="exam-header">
                        <span>{{ $uj->nama_ujian ?? 'Ujian' }}</span>
                        <span class="exam-status status-ready">Ready</span>
                    </div>
                    <div class="exam-body">
                        <div class="exam-info-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $uj->mapels->nama_mapel ?? '-' }}</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="fas fa-hourglass-half"></i>
                            <span>Durasi: {{ $uj->durasi ?? '-' }} Menit</span>
                        </div>
                        <div class="exam-info-item">
                            <i class="fas fa-calendar"></i>
                            <span>Tanggal: {{ isset($uj->tanggal) ? \Carbon\Carbon::parse($uj->tanggal)->isoFormat('D MMMM Y') : 'Belum dijadwalkan' }}</span>
                        </div>

                        @php
                            $totalSchedules = isset($jad) ? $jad->where('ujian_id', $uj->id)->count() : 0;
                            $totalKelas = $kla->count() ?? 0;
                            $progress = $totalKelas > 0 ? ($totalSchedules / $totalKelas) * 100 : 0;
                        @endphp

                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="is-flex is-justify-content-space-between mt-1">
                            <span class="is-size-7 has-text-grey">Progress Penjadwalan</span>
                            <span class="is-size-7 has-text-weight-bold">{{ $totalSchedules }}/{{ $totalKelas }} Kelas</span>
                        </div>
                    </div>
                    <div class="exam-footer">
                        <span class="badge-custom">
                            <i class="fas fa-clock"></i> {{ isset($uj->created_at) ? $uj->created_at->diffForHumans() : '-' }}
                        </span>

                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-hourglass-half"></i>
                <p>Tidak ada ujian dengan status ready</p>
                <p class="is-size-7">Semua ujian sudah dijadwalkan atau selesai</p>
            </div>
        @endif

        <!-- Semua Ujian -->
        <div class="section-title mt-4">
            <i class="fas fa-list"></i>
            Semua Ujian
        </div>

        <div class="exam-grid">
            @forelse($uji ?? [] as $uj)
            <div class="exam-card">
                <div class="exam-header">
                    <span>{{ $uj->nama_ujian ?? 'Ujian' }}</span>
                    <span class="exam-status 
                        @if($uj->status == 'ready') status-ready
                        @elseif($uj->status == 'done') status-done
                        @elseif($uj->status == 'ongoing') status-ongoing
                        @else status-draft @endif">
                        {{ $uj->status ?? 'draft' }}
                    </span>
                </div>
                <div class="exam-body">
                    <div class="exam-info-item">
                        <i class="fas fa-book"></i>
                        <span>{{ $uj->mapels->nama_mapel ?? '-' }}</span>
                    </div>
                    <div class="exam-info-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Durasi: {{ $uj->durasi ?? '-' }} Menit</span>
                    </div>
                    @if($uj->status == 'done')
                    <div class="exam-info-item">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        <span>Selesai pada: {{ isset($uj->updated_at) ? \Carbon\Carbon::parse($uj->updated_at)->isoFormat('D MMMM Y') : '-' }}</span>
                    </div>
                    @endif
                    @if($uj->status == 'ready' || $uj->status == 'draft')
                    <div class="exam-info-item">
                        <i class="fas fa-users"></i>
                        <span>Perlu dijadwalkan</span>
                    </div>
                    @endif
                </div>
                @if($uj->status == 'ready')
                <div class="exam-footer">
                    <span class="badge-custom">Perlu penjadwalan</span>
                   
                </div>
                @endif
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1/-1;">
                <i class="fas fa-folder-open"></i>
                <p>Belum ada ujian</p>
            </div>
            @endforelse
        </div>

        <!-- Daftar Kelas -->
        <div class="section-title mt-4">
            <i class="fas fa-users"></i>
            Daftar Kelas
        </div>

        <div class="class-grid">
            @forelse($kla ?? [] as $k)
            <div class="class-card">
                <div class="class-header">
                    <i class="fas fa-door-open mr-2"></i>
                    {{ $k->nama_kelas ?? 'Kelas' }}
                </div>
                
                <div class="class-body">
                    @php
                        $totalSiswa = isset($sis) ? $sis->where("kelas_id", $k->id)->count() : 0;
                        $jadwalKelas = isset($jad) ? $jad->where('kelas_id', $k->id) : collect();
                        $totalUjianKelas = $jadwalKelas->count();
                    @endphp
                    
                    <div class="class-stats">
                        <div class="class-stat">
                            <div class="class-stat-value">{{ $totalSiswa }}</div>
                            <div class="class-stat-label">Siswa</div>
                        </div>
                        <div class="class-stat">
                            <div class="class-stat-value">{{ $totalUjianKelas }}</div>
                            <div class="class-stat-label">Ujian</div>
                        </div>
                        <div class="class-stat">
                            <div class="class-stat-value">{{ $readyExams->count() }}</div>
                            <div class="class-stat-label">Ready</div>
                        </div>
                    </div>

                  
                  

                    <a href="{{ route('admin-ops.set', $k->id) }}" class="btn-set-schedule">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Atur Jadwal
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1/-1;">
                <i class="fas fa-school"></i>
                <p>Belum ada data kelas</p>
            </div>
            @endforelse
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Toggle
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.classList.remove('active');
    });
    
    // Mobile Sidebar Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        const icon = mobileToggle.querySelector('i');
        if (sidebar.classList.contains('open')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                const icon = mobileToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }, 250);
    });
    
    // Close sidebar on mobile after clicking link
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    for (let i = 0; i < sidebarItems.length; i++) {
        sidebarItems[i].addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    if (sidebar.classList.contains('open')) toggleSidebar();
                }, 150);
            }
        });
    }
});
</script>

</body>
</html>