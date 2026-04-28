<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Guru - Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
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
    .main-content {
        animation: pageEnter 0.3s ease-out forwards;
    }

    @keyframes pageEnter {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Animasi keluar */
    .main-content.page-leaving {
        animation: pageLeave 0.25s ease-in forwards !important;
    }

    @keyframes pageLeave {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-12px); }
    }
    /* ========================
       CSS UTAMA (DARI KODE 2)
       ======================== */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
    body { background: #f3f5f9; overflow-x: hidden; }

    /* ===== HEADER ===== */
    .header {
        background: #2e5b9a; /* Warna solid kode 2, atau pakai gradient kode 1 */
        /* Menggunakan gradient dari kode 1 agar branding tetap sama */
        background: linear-gradient(135deg, #2e5b9a 0%, #1e3a6b 100%);
        
        color: white;
        padding: 12px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed; /* Fixed agar sidebar tidak menabrak */
        top: 0; left: 0; right: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        height: 60px; /* Tinggi fixed */
    }

    .header h2 {
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .header h2 img {
        height: 30px;
        object-fit: contain;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* ===== NOTIFIKASI (Opsional, dari Kode 2) ===== */
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

    .notif-btn:hover { background: rgba(255,255,255,0.15); }
    .notif-btn i { font-size: 1.2rem; }

    .notif-badge {
        position: absolute; top: 2px; right: 2px;
        background: #dc3545; color: white;
        font-size: 0.65rem; font-weight: bold;
        height: 18px; min-width: 18px; padding: 0 4px;
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        border: 2px solid #2e5b9a; z-index: 2;
    }
    .notif-badge.hidden { display: none; }

    /* ===== USER DROPDOWN ===== */
    .user-dropdown { position: relative; cursor: pointer; }

    .user-info {
        display: flex; align-items: center; gap: 10px;
        padding: 6px 12px; border-radius: 8px; transition: background 0.3s ease;
    }
    .user-info:hover { background: rgba(255,255,255,0.15); }

    .user-avatar {
        width: 34px; height: 34px; background: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #2e5b9a; font-weight: bold;
    }

    .user-name { font-weight: 500; font-size: 0.85rem; }
    .user-name i { font-size: 0.7rem; margin-left: 5px; }

    .dropdown-menu-custom {
        position: absolute; top: 100%; right: 0;
        margin-top: 8px; background: white;
        border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        min-width: 180px; opacity: 0; visibility: hidden;
        transform: translateY(-10px); transition: all 0.3s ease; z-index: 1001;
    }

    .user-dropdown.active .dropdown-menu-custom {
        opacity: 1; visibility: visible; transform: translateY(0);
    }

    .dropdown-item-custom {
        padding: 10px 16px; display: flex; align-items: center; gap: 12px;
        color: #333; text-decoration: none; transition: background 0.2s ease;
        border-bottom: 1px solid #eee; font-size: 0.85rem; cursor: pointer;
    }
    .dropdown-item-custom:last-child { border-bottom: none; }
    .dropdown-item-custom:hover { background: #f5f5f5; }
    .dropdown-item-custom i { width: 18px; color: #2e5b9a; }
    .logout-btn { color: #dc3545; }
    .logout-btn i { color: #dc3545; }

    /* ===== LAYOUT ===== */
    .app-wrapper { display: flex; margin-top: 60px; min-height: calc(100vh - 60px); }

    /* ===== SIDEBAR (STYLE BARU) ===== */
    .sidebar {
        width: 260px; /* Lebar fix */
        background: #5c6fa6; /* Warna background kode 2 */
        position: fixed; left: 0; top: 56px; bottom: 0; /* Sesuaikan top dengan tinggi header */
        z-index: 99;
        transition: transform 0.3s ease;
        overflow-y: auto;
    }
    
    .sidebar-menu { padding: 20px 0; }
    
    .sidebar-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 20px; margin: 4px 12px;
        color: white; text-decoration: none;
        border-radius: 8px; transition: all 0.3s ease;
    }
    
    .sidebar-item i { width: 22px; font-size: 1rem; }
    .sidebar-item span { font-size: 0.85rem; font-weight: 500; }
    
    /* Efek Hover & Active sesuai kode 2 */
    .sidebar-item:hover { 
        background: rgba(255,255,255,0.25);  
        color:#2e5b9a;
        border-left: 4px solid white;
    }
    
    .sidebar-item.active { 
        background: rgba(255,255,255,0.25); 
        border-left: 4px solid white; 
    }
    
    .sidebar-logout {
        position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 12px;
    }
    .sidebar-logout .sidebar-item { color: white; width: calc(100% - 24px); }
    .sidebar-logout .sidebar-item:hover { background: #dc3545; color: white; border-left-color: transparent; }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        flex: 1; margin-left: 260px; padding: 24px;
        transition: margin-left 0.3s ease;
        width: calc(100% - 260px);
        background: #f8fafc;
        min-height: calc(100vh - 60px);
    }

    /* Mobile Toggle */
    .mobile-toggle {
        display: none;
        position: fixed; bottom: 20px; right: 20px;
        width: 50px; height: 50px;
        background: #2e5b9a; border-radius: 50%;
        align-items: center; justify-content: center;
        cursor: pointer; z-index: 100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        border: none; color: white;
    }
    .mobile-toggle i { font-size: 22px; }

    .sidebar-overlay {
        display: none; position: fixed;
        top: 60px; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 98;
    }
    .sidebar-overlay.active { display: block; }

    /* ========================
       CSS KONTEN (DARI KODE 1)
       ======================== */

    /* Stats Cards (Pertahankan gaya kode 1 yang cantik) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }

    .stat-icon {
        width: 55px; height: 55px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        color: #2e5b9a; font-size: 1.5rem;
    }
    .stat-info h3 { font-size: 1.8rem; font-weight: 800; color: #1e2a3e; line-height: 1.2; }
    .stat-info p { font-size: 0.8rem; color: #64748b; font-weight: 500; }

    /* Section Title */
    .section-title {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 15px;
    }
    .section-title h2 {
        font-size: 1.3rem; font-weight: 700; color: #1e2a3e;
        display: flex; align-items: center; gap: 10px;
    }
    .section-title h2 i { color: #2e5b9a; font-size: 1.3rem; }

    /* Exam Grid */
    .exam-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 24px; }
    
    .exam-card {
        background: white; border-radius: 20px; overflow: hidden;
        transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.03); animation: fadeInUp 0.4s ease forwards;
    }
    .exam-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }

    .card-header-custom {
        background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
        padding: 18px 20px; color: white;
    }
    .card-header-custom h3 { font-size: 1rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px; }
    
    .card-body-custom { padding: 20px; }
    .exam-detail p { margin-bottom: 8px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: #475569; }
    .exam-detail i { width: 20px; color: #2e5b9a; }

    .exam-stats {
        display: flex; gap: 16px; padding: 12px 0;
        border-top: 1px solid #eef2f6; border-bottom: 1px solid #eef2f6; margin: 12px 0;
    }
    .stat-item-small { flex: 1; text-align: center; }
    .stat-item-small .stat-value { font-size: 1.2rem; font-weight: 800; color: #2e5b9a; }
    .stat-item-small .stat-label { font-size: 0.7rem; color: #64748b; }

    .btn-rekap {
        background: #2e5b9a; color: white; border: none; padding: 12px 20px;
        border-radius: 40px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
        transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;
        gap: 8px; width: 100%; text-decoration: none;
    }
    .btn-rekap:hover { background: #1e3a6b; transform: translateY(-2px); color: white; }

    /* Quick Actions */
    .quick-actions { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .quick-actions h3 { font-size: 1rem; font-weight: 600; color: #1e2a3e; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .action-buttons { display: flex; gap: 16px; flex-wrap: wrap; }
    .action-btn {
        background: white; padding: 12px 24px; border-radius: 40px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 500;
        transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.05); color: #2e5b9a; border: 1px solid #e2e8f0;
    }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); background: #2e5b9a; color: white; }

    /* Modal Styles */
    .modal-card { border-radius: 12px; overflow: hidden; }
    .modal-card-head { background: #2e5b9a; color: white; display: flex; justify-content: space-between; }
    .modal-card-head .title { color: white; margin: 0; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Responsive */
    @media (max-width: 768px) {
        .header {
        background: #2e5b9a;
        color: white;
        padding: 12px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
        .header h2 span { display: inline; }
        .user-name span { display: none; }
        
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 20px; }
        
        .mobile-toggle { display: flex; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .exam-grid { grid-template-columns: 1fr; gap: 16px; }
        .action-buttons { flex-direction: column; }
    }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #5c6fa6; border-radius: 3px; }
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
        <!-- Notifikasi (Dari Kode 2, tapi kosongkan data jika tidak ada) -->
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

        <!-- User Dropdown (Gabungan Struktur Kode 2) -->
        <div class="user-dropdown" id="userDropdown">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-name">
                    <span>{{$ire->nama}}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            
            <div class="dropdown-menu-custom">
                <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
        <i class="fas fa-user-circle"></i>
        <span>Profil Saya</span>
    </a>
                <div style="height: 1px; background: #eee; margin: 4px 0;"></div>
                <form action="{{ route('users.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
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
    <!-- Sidebar (Ganti dengan Struktur Kode 2) -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{route('guru.index')}}" class="sidebar-item ">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{route('guru.jadwal')}}" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Jadwal Ujian</span>
            </a>
            <a href="{{route('guru.riwayat')}}" class="sidebar-item">
                <i class="fas fa-history"></i>
                <span>Riwayat Ujian</span>
            </a>
            <a href="{{route('guru.result')}}" class="sidebar-item active">
                <i class="fas fa-chart-line"></i>
                <span>Hasil Ujian</span>
            </a>
            <a href="{{route('pengawas.index', isset($dt) ? $dt->id : '')}}" class="sidebar-item">
                <i class="fas fa-user-check"></i>
                <span>Pengawas</span>
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
    
    <!-- Main Content (Pertahankan Konten Kode 1) -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <h1 style="font-size: 1.8rem; font-weight: 700; color: #1e2a3e; display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-chalkboard-user"></i>
                Dashboard Guru
            </h1>
            <p style="color: #64748b; font-size: 0.9rem;">Selamat datang, {{$ire->nama}}! Kelola ujian dan pantau hasil belajar siswa</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="stat-info">
                    <h3>{{ $data->count() }}</h3>
                    <p>Total Ujian</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    @php
                        $totalSiswa = 0;
                        foreach($data as $ujian) { $totalSiswa += $ujian->peserta->count() ?? 0; }
                    @endphp
                    <h3>{{ $totalSiswa }}</h3>
                    <p>Total Peserta</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    @php
                        $aktifCount = $data->filter(function($item) {
                            return $item->status === 'ready' || $item->status === 'ongoing';
                        })->count();
                    @endphp
                    <h3>{{ $aktifCount }}</h3>
                    <p>Ujian Aktif</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                <div class="stat-info">
                    @php
                        $avgNilai = 0; $totalNilai = 0; $countNilai = 0;
                        foreach($data as $ujian) {
                            foreach($ujian->peserta as $peserta) {
                                if($peserta->nilai) { $totalNilai += $peserta->nilai; $countNilai++; }
                            }
                        }
                        $avgNilai = $countNilai > 0 ? round($totalNilai / $countNilai, 1) : 0;
                    @endphp
                    <h3>{{ $avgNilai }}</h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
        </div>

        <!-- Daftar Ujian Section -->
        <div class="section-title">
            <h2><i class="fas fa-list-ol"></i> Daftar Ujian</h2>
            <div class="date-badge" style="background: white; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 500; color: #2e5b9a; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

        <div class="exam-grid">
            @if(isset($data) && $data->count() > 0)
                @foreach($data as $dt)
                    @php
                        $pesertaCount = $dt->peserta->count() ?? 0;
                        $sudahUjian = $dt->peserta->filter(function($p) { return $p->nilai !== null; })->count() ?? 0;
                        $belumUjian = $pesertaCount - $sudahUjian;
                        
                        $statusBadge = ''; $statusColor = '';
                        if($dt->status === 'draft') { $statusBadge = 'Draft'; $statusColor = '#94a3b8'; }
                        elseif($dt->status === 'ready') { $statusBadge = 'Siap'; $statusColor = '#28a745'; }
                        elseif($dt->status === 'ongoing') { $statusBadge = 'Berlangsung'; $statusColor = '#ffc107'; }
                        elseif($dt->status === 'done') { $statusBadge = 'Selesai'; $statusColor = '#6c757d'; }
                        else { $statusBadge = $dt->status; $statusColor = '#2e5b9a'; }
                    @endphp
                    <div class="exam-card">
                        <div class="card-header-custom">
                            <h3><i class="fas fa-file-alt"></i> {{ $dt->nama_ujian ?? 'Ujian' }}</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="exam-detail">
                                <p><i class="fas fa-clock"></i> Durasi: {{ $dt->durasi ?? '-' }} Menit</p>
                                <p><i class="fas fa-tag"></i> Status: <span style="color: {{ $statusColor }}; font-weight: 600;">{{ $statusBadge }}</span></p>
                                @if(isset($dt->jadwal))
                                <p><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($dt->jadwal->waktu_mulai)->format('d F Y H:i') }}</p>
                                @endif
                            </div>
                            <div class="exam-stats">
                                <div class="stat-item-small">
                                    <div class="stat-value">{{ $pesertaCount }}</div>
                                    <div class="stat-label">Total Siswa</div>
                                </div>
                                <div class="stat-item-small">
                                    <div class="stat-value">{{ $sudahUjian }}</div>
                                    <div class="stat-label">Sudah Ujian</div>
                                </div>
                                <div class="stat-item-small">
                                    <div class="stat-value">{{ $belumUjian }}</div>
                                    <div class="stat-label">Belum Ujian</div>
                                </div>
                            </div>
                            <a href="{{ route('guru.hasil', $dt->id) }}" class="btn-rekap">
                                <i class="fas fa-chart-simple"></i> Lihat Rekap Nilai <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state" style="text-align: center; padding: 60px 20px; background: white; border-radius: 24px; grid-column: 1 / -1;">
                    <i class="fas fa-folder-open" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h3 style="font-size: 1.2rem; color: #475569; margin-bottom: 8px;">Belum Ada Ujian</h3>
                    <p style="color: #94a3b8; font-size: 0.85rem;">Anda belum membuat ujian apapun.</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3><i class="fas fa-bolt"></i> Akses Cepat</h3>
            <div class="action-buttons">
                <a href="{{ route('guru.index') }}" class="action-btn" onclick="document.getElementById('cret').classList.add('is-active')">
                    <i class="fas fa-plus-circle"></i> Buat Ujian Baru
                </a>
                <a href="{{ route('guru.result') }}" class="action-btn">
                    <i class="fas fa-chart-line"></i> Lihat Hasil Ujian
                </a>
            </div>
        </div>
    </main>
</div>

<!-- Modal Buat Ujian (Pertahankan dari Kode 1) -->
<div class="modal" id="cret" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div class="modal-card" style="background: white; border-radius: 20px; max-width: 500px; width: 90%; overflow: hidden;">
        <div class="modal-card-head">
            <span class="title" style="color: white; font-size: 1.1rem; margin: 0;"><i class="fas fa-plus-circle"></i> Buat Ujian Baru</span>
            <button class="modal-close" onclick="document.getElementById('cret').style.display='none'" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('guru.store') }}" method="post">
            @csrf
            <div class="modal-card-body" style="padding: 20px;">
                <div class="field" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #2c3e50; margin-bottom: 6px;">Nama Ujian</label>
                    <input type="text" name="nama_ujian" class="input" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px;" placeholder="Contoh: Ujian Akhir Semester" required>
                </div>
                <div class="field" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #2c3e50; margin-bottom: 6px;">Durasi (Menit)</label>
                    <input type="number" name="durasi" class="input" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px;" placeholder="90" required>
                </div>
                <div class="field" style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #2c3e50; margin-bottom: 6px;">Catatan (Opsional)</label>
                    <textarea name="catatan" class="textarea" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; resize: vertical; min-height: 80px;" placeholder="Tambahkan catatan untuk ujian ini"></textarea>
                </div>
            </div>
            <div class="modal-card-foot" style="padding: 16px 20px; background: #f8f9fc; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="button" onclick="document.getElementById('cret').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 8px 20px; border-radius: 20px;">Batal</button>
                <button type="submit" class="button" style="background: #2e5b9a; color: white; border: none; padding: 8px 20px; border-radius: 20px;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
// Script Intercept Link (Smooth Transition)
let mainContent = document.querySelector(".main-content")
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return; 
        const href = link.getAttribute('href');
        const target = link.getAttribute('target');
        
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || target === '_blank') return;
        if (link.classList.contains('no-transition') || link.getAttribute('data-turbolinks') === 'false') return;
        
        const isLocal = href.startsWith(window.location.origin) || href.startsWith('/');
        if (isLocal) {
            e.preventDefault();
            mainContent.classList.add('page-leaving');
            setTimeout(function() { window.location.href = href; }, 250);
        }
    });

    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            document.body.classList.add('page-leaving');
        });
    });
});
</script>
<script>
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
    // Logic Sidebar & Mobile Toggle (Dari Kode 2)
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
    
    if (mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

    // Logic User Dropdown (Dari Kode 2)
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            // Tutup notif jika terbuka
            document.getElementById('notifWrapper').classList.remove('active');
        });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.classList.remove('active');
        // Notif logic sederhana jika diperlukan
    });

    // Logic Modal (Dari Kode 1)
    const modal = document.getElementById('cret');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    }
});
</script>

</body>
</html>