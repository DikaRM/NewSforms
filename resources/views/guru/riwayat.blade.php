<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Ujian - Guru | Sistem Ujian</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
    /* Animasi masuk */
    .main-content {
        animation: pageEnter 0.3s ease-out forwards;
    }

    @keyframes pageEnter {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi keluar */
    .main-content.page-leaving {
        animation: pageLeave 0.25s ease-in forwards !important;
    }

    @keyframes pageLeave {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-12px);
        }
    }

    /* Variables & Reset */
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

    .sidebar-item:hover { background: rgba(255,255,255,0.25);  color:#2e5b9a;border-left: 4px solid white;}
    .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 4px solid white; }
    

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

    /* ===== CARD STYLES ===== */
    .history-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
    }

    .history-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .history-card .card-header {
        background: white;
        border-bottom: 1px solid #f0f2f5;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .history-card .card-header-title {
        font-weight: 600;
        color: #2e5b9a;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .history-card .card-content {
        padding: 20px;
    }

    /* Stat Card */
    .stat-badge {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fc;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
    }

    .stat-item i {
        color: #2e5b9a;
        font-size: 0.9rem;
    }

    /* Tag Status */
    .tag-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Tag Warna Berdasarkan Status */
    .tag-draft {
        background: #e2e8f0; /* Abu-abu muda */
        color: #64748b;
    }
    .tag-ready {
        background: #dbeafe; /* Biru muda */
        color: #0369a1;
    }
    .tag-done {
        background: #d1fae5; /* Hijau muda */
        color: #065f46;
    }

    /* Button */
    .btn-outline-sm {
        background: transparent;
        border: 1px solid #2e5b9a;
        color: #2e5b9a;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-outline-sm:hover {
        background: #2e5b9a;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .empty-state i {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 16px;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        margin-bottom: 20px;
    }

    .breadcrumb-custom ul {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .breadcrumb-custom li {
        display: flex;
        align-items: center;
    }

    .breadcrumb-custom li:not(:last-child):after {
        content: "/";
        margin-left: 8px;
        color: #adb5bd;
    }

    .breadcrumb-custom a {
        color: #5c6fa6;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .breadcrumb-custom a:hover {
        color: #2e5b9a;
    }

    .breadcrumb-custom .is-active a {
        color: #2e5b9a;
        font-weight: 600;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .filter-tab {
        padding: 8px 20px;
        border-radius: 30px;
        background: white;
        color: #5c6fa6;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .filter-tab:hover {
        border-color: #2e5b9a;
        color: #2e5b9a;
    }

    .filter-tab.active {
        background: #2e5b9a;
        color: white;
        border-color: #2e5b9a;
    }

    /* Year Selector */
    .year-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .year-selector select {
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        font-size: 0.85rem;
        color: #333;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .notif-dropdown{right: -100px;} 
        .header h2 span {
            display: inline;
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
        
        .history-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stat-badge {
            gap: 10px;
        }
        
        .stat-item {
            padding: 4px 12px;
            font-size: 0.7rem;
        }
        
        .filter-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 8px;
        }
        
        .filter-tab {
            white-space: nowrap;
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
            <div class="user-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="user-name">
                @if(isset($ire))
                    <span>{{ $ire->nama }}</span>
                @else
                    <span>Guru</span>
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
            <a href="{{ route('guru.index') }}" class="sidebar-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Jadwal Ujian</span>
            </a>
            <a href="{{ route('guru.riwayat') }}" class="sidebar-item active">
                <i class="fas fa-history"></i>
                <span>Riwayat Ujian</span>
            </a>
            <a href="{{ route('guru.result') }}" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                <span>Hasil Ujian</span>
            </a>
            <a href="{{ route('pengawas.index', isset($dt) ? $dt->id : '') }}" class="sidebar-item">
                <i class="fas fa-users"></i>
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
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <ul>
                <li><a href="{{ route('guru.index') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="is-active"><a href="#">Riwayat Ujian</a></li>
            </ul>
        </div>
        
        <!-- Header -->
        <div class="level is-mobile" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div class="level-left">
                <div>
                    <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 600; margin-bottom: 4px;">
                        <i class="fas fa-history"></i> Riwayat Ujian
                    </h1>
                    <p style="color: #6c757d; font-size: 0.85rem;">
                        Arsip ujian yang telah dilaksanakan
                    </p>
                </div>
            </div>
            <div class="level-right">
                <div class="year-selector">
                    <i class="fas fa-calendar-alt" style="color: #5c6fa6;"></i>
                    <select id="yearFilter">
                        <option value="all">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs (DIUPDATE) -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua</button>
            <button class="filter-tab" data-filter="draft">Draft</button>
            <button class="filter-tab" data-filter="ready">Terjadwal (Ready)</button>
            <button class="filter-tab" data-filter="done">Selesai</button>
        </div>
        
        <!-- Riwayat Cards -->
        <div id="riwayatContainer">
            @forelse($data ?? [] as $index => $dt)
            @php
                // Logika Fix Status
                $statusRaw = $dt->status ?? 'draft'; // Nilai dari DB: draft, ready, done
                
                // Tentukan CSS Class & Tampilan Teks
                if ($statusRaw == 'draft') {
                    $statusText = 'Draft';
                    $statusClass = 'tag-draft';
                    $filterVal = 'draft';
                } elseif ($statusRaw == 'ready') {
                    $statusText = 'Terjadwal'; // atau Ready
                    $statusClass = 'tag-ready';
                    $filterVal = 'ready';
                } else { // done
                    $statusText = 'Selesai';
                    $statusClass = 'tag-done';
                    $filterVal = 'done';
                }

                // Hitung Tahun
                $waktuSelesai = isset($dt->jadwal->waktu_selesai) ? \Carbon\Carbon::parse($dt->jadwal->waktu_selesai) : null;
                $tahun = $waktuSelesai ? $waktuSelesai->format('Y') : date('Y');
            @endphp
            
            <div class="history-card" data-status="{{ $filterVal }}" data-tahun="{{ $tahun }}">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="fas fa-file-alt" style="color: #2e5b9a;"></i>
                        {{ $dt->nama_ujian ?? 'Ujian' }}
                    </div>
                    <div>
                        <span class="tag-status {{ $statusClass }}">
                            @if($statusText == 'Selesai')
                                <i class="fas fa-check-circle"></i>
                            @elseif($statusText == 'Terjadwal')
                                <i class="fas fa-clock"></i>
                            @else
                                <i class="fas fa-pen-fancy"></i>
                            @endif
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
                <div class="card-content">
                    <div class="stat-badge">
                        <div class="stat-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>
                                @if($waktuSelesai)
                                    {{ $waktuSelesai->format('l, d F Y') }}
                                @else
                                    Tanggal tidak tersedia
                                @endif
                            </span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            <span>
                                @if($waktuSelesai)
                                    Selesai {{ $waktuSelesai->format('H:i') }}
                                @else
                                    Waktu tidak tersedia
                                @endif
                            </span>
                        </div>
                        @if(isset($dt->mapels) && $dt->mapels)
                        <div class="stat-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $dt->mapels->nama_mapel ?? '-' }}</span>
                        </div>
                        @endif
                        @if(isset($dt->grade))
                        <div class="stat-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $dt->grade }}</span>
                        </div>
                        @endif
                        @if(isset($dt->durasi))
                        <div class="stat-item">
                            <i class="fas fa-hourglass-half"></i>
                            <span>{{ $dt->durasi }} menit</span>
                        </div>
                        @endif
                    </div>
                    
                    @if(isset($dt->catatan) && $dt->catatan)
                    <div class="mt-2 mb-3">
                        <span class="tag is-light" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                            <i class="fas fa-sticky-note"></i> {{ $dt->catatan }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="level is-mobile" style="margin-top: 12px;">
                        <div class="level-left">
                            <div class="tags">
                                @if(isset($dt->peserta_count))
                                <span class="tag is-info is-light">
                                    <i class="fas fa-users"></i> {{ $dt->peserta_count }} Peserta
                                </span>
                                @endif
                                @if(isset($dt->nilai_rata))
                                <span class="tag is-success is-light">
                                    <i class="fas fa-chart-line"></i> Rata-rata: {{ number_format($dt->nilai_rata, 1) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="level-right">
                            <a href="{{ route('guru.hasil', $dt->id) }}" class="btn-outline-sm">
                                <i class="fas fa-chart-line"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada riwayat ujian</p>
                <p class="is-size-7 mt-2">Ujian yang telah selesai akan muncul di sini</p>
                <div class="mt-3">
                    <a href="{{ route('guru.index') }}" class="btn-outline-sm">
                        <i class="fas fa-plus-circle"></i> Buat Ujian
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Statistik Tambahan jika ada data -->
        @if(isset($data) && count($data) > 0)
        <div class="columns is-multiline mt-4" style="margin-top: 24px;">
            <div class="column is-4">
                <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <p class="heading" style="color: #6c757d; font-size: 0.75rem;">Total Ujian</p>
                    <p class="title is-4" style="color: #2e5b9a;">{{ count($data) }}</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <p class="heading" style="color: #6c757d; font-size: 0.75rem;">Total Peserta</p>
                    <p class="title is-4" style="color: #2e5b9a;">
                        {{ collect($data)->sum(function($item) { return $item->peserta_count ?? 0; }) }}
                    </p>
                </div>
            </div>
            <div class="column is-4">
                <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <p class="heading" style="color: #6c757d; font-size: 0.75rem;">Rata-rata Nilai</p>
                    <p class="title is-4" style="color: #2e5b9a;">
                        {{ number_format(collect($data)->avg(function($item) { return $item->nilai_rata ?? 0; }), 1) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Toggle
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
    
    // ==========================================
    // LOGIKA FILTER TABS (DIUPDATE)
    // ==========================================
    const filterTabs = document.querySelectorAll('.filter-tab');
    const historyCards = document.querySelectorAll('.history-card');
    const yearFilter = document.getElementById('yearFilter');
    
    function filterCards() {
        const activeTab = document.querySelector('.filter-tab.active');
        const filterValue = activeTab ? activeTab.getAttribute('data-filter') : 'all';
        const yearValue = yearFilter ? yearFilter.value : 'all';
        
        let visibleCount = 0;
        
        historyCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status'); // draft, ready, done
            const cardYear = card.getAttribute('data-tahun');
            
            let statusMatch = filterValue === 'all' || cardStatus === filterValue;
            let yearMatch = yearValue === 'all' || cardYear === yearValue;
            
            if (statusMatch && yearMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        const emptyState = document.querySelector('.empty-state');
        const container = document.getElementById('riwayatContainer');
        
        if (visibleCount === 0 && historyCards.length > 0) {
            if (!emptyState || emptyState.parentElement !== container) {
                const newEmpty = document.createElement('div');
                newEmpty.className = 'empty-state';
                newEmpty.innerHTML = `
                    <i class="fas fa-filter"></i>
                    <p>Tidak ada riwayat dengan filter ini</p>
                    <p class="is-size-7 mt-2">Coba ubah filter atau pilih tahun lain</p>
                `;
                container.appendChild(newEmpty);
            } else if (emptyState) {
                emptyState.style.display = '';
            }
        } else if (visibleCount > 0 && emptyState && emptyState.parentElement === container) {
            emptyState.remove();
        }
    }
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            filterCards();
        });
    });
    
    if (yearFilter) {
        yearFilter.addEventListener('change', filterCards);
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    let mainContent = document.querySelector(".main-content")
    // ==========================================
    // 1. INTERCEPT LINK KLIK (Smooth Redirect)
    // ==========================================
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
</body>
</html>