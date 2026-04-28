<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Riwayat Ujian - Dashboard Siswa</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">

<style>
    /* Animasi masuk (Berjalan otomatis saat halaman baru dibuka) */
    .main-content{
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

    /* Animasi keluar (Ditambahkan oleh JavaScript saat klik link) */
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
</style>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

body {
    background: linear-gradient(135deg, #f3f5f9 0%, #eef2f7 100%);
    min-height: 100vh;
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
    min-height: calc(100vh - 70px);
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 280px;
    background: #53629E;
    min-height: 100vh;
    padding: 25px 0;
    color: white;
    position: sticky;
    top: 70px;
    height: calc(100vh - 70px);
    overflow-y: auto;
    box-shadow: 4px 0 20px rgba(0,0,0,0.08);
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
.main {
    flex: 1;
    padding: 30px 35px;
    background: #f8fafc;
}

/* Page Header */
.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e2a3e;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.page-header h1 i {
    color: #2e5b9a;
    font-size: 1.8rem;
}

.page-header p {
    color: #64748b;
    font-size: 0.9rem;
}

/* Stats Cards */
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

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e5b9a;
    font-size: 1.5rem;
}

.stat-info h3 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e2a3e;
    line-height: 1.2;
}

.stat-info p {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.filter-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    font-weight: 600;
    color: #1e2a3e;
}

.filter-title i {
    color: #2e5b9a;
    font-size: 1.1rem;
}

.filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

.filter-btn {
    background: #f1f5f9;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-btn i {
    font-size: 0.8rem;
}

.filter-btn:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

.filter-btn.active {
    background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(46, 91, 154, 0.3);
}

.filter-btn.all-btn {
    background: #2e5b9a;
    color: white;
}

/* Search Box */
.search-box {
    margin-left: auto;
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 40px;
    padding: 5px 15px;
}

.search-box i {
    color: #94a3b8;
}

.search-box input {
    border: none;
    background: transparent;
    padding: 8px 12px;
    font-size: 0.85rem;
    outline: none;
    width: 200px;
}

.search-box input::placeholder {
    color: #94a3b8;
}

/* Section Title */
.section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-title h2 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e2a3e;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title h2 i {
    color: #2e5b9a;
    font-size: 1.3rem;
}

.date-badge {
    background: white;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #2e5b9a;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Riwayat Grid */
.riwayat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

/* Riwayat Card */
.riwayat-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.03);
    animation: fadeInUp 0.4s ease forwards;
}

.riwayat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.card-header-custom {
    padding: 16px 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #eef2f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.1rem;
}

.student-details h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #1e2a3e;
    margin-bottom: 4px;
}

.student-details p {
    font-size: 0.7rem;
    color: #64748b;
}

.date-badge-custom {
    background: #e8f0fe;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    color: #2e5b9a;
    font-weight: 600;
}

.card-body-custom {
    padding: 20px;
}

.exam-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e2a3e;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.exam-title i {
    color: #2e5b9a;
    font-size: 1rem;
}

.exam-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eef2f6;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    color: #5a6e8a;
}

.meta-item i {
    color: #2e5b9a;
    width: 14px;
    font-size: 0.7rem;
}

.score-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
}.buttond{
    display: flex;
    align-items: center;
    justify-content: center;
}
.buttond a{
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
    background:#e8f0fe;
    color: #2e5b9a;
}

.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 1rem;
}

.score-excellent {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.score-good {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
}

.score-average {
    background: linear-gradient(135deg, #cfe2ff 0%, #b8d4ff 100%);
    color: #2e5b9a;
}

.score-low {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.score-value {
    font-size: 1.3rem;
    font-weight: 800;
}

.status-done {
    background: #e8f0fe;
    color: #2e5b9a;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 24px;
    grid-column: 1 / -1;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.2rem;
    color: #475569;
    margin-bottom: 8px;
}

.empty-state p {
    color: #94a3b8;
    font-size: 0.85rem;
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

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 98;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header {
        padding: 12px 16px;
    }
    
    .header h2 span {
        display: inline;
    }
    
    .user-name span {
        display: none;
    }
    
    .sidebar {
        position: fixed;
        left: -280px;
        top: 0;
        z-index: 99;
        transition: left 0.3s ease;
        height: 100vh;
        margin-top:25px;
    }
    
    .sidebar.open {
        left: 0;
    }
    
    .main {
        padding: 20px;
        margin-left: 0;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .sidebar-overlay.active {
        display: block;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-card {
        padding: 14px;
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
    }
    
    .stat-info h3 {
        font-size: 1.3rem;
    }
    
    .riwayat-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .page-header h1 {
        font-size: 1.4rem;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        margin-left: 0;
        width: 100%;
    }
    
    .search-box input {
        width: 100%;
    }
    
    .filter-btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .main {
        padding: 16px;
    }
    
    .card-header-custom {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 6px;
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

<header class="header">
    <h2>
       <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34" style="height:30px;"/>
        <span class="has-text-light">SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name">
                @if(isset($ire))
                    <span>{{ $ire->nama }}</span>
                @else
                    <span>Siswa</span>
                @endif
                <i class="fas fa-chevron-down"></i>
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
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('siswa.index') }}" class="sidebar-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
             <a href="{{ route('siswa.jadwal') }}" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Jadwal Ujian</span>
            </a>
             <a href="{{ route('siswa.uji') }}" class="sidebar-item">
                <i class="fas fa-book"></i>
                <span>Ujian</span>
            </a>
            <a href="{{ route('siswa.riwayat') }}" class="sidebar-item active">
                <i class="fas fa-history"></i>
                <span>Riwayat Ujian</span>
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
        @if(session('success'))
            <div class="notification-toast notification-success" id="notification">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="notification-toast notification-error" id="notification">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        
        
        <!-- Cards Menu -->
       
        
        <!-- Ujian Hari Ini -->

    <!-- Main Content -->
    <div class="main">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-history"></i>
                Riwayat Ujian
            </h1>
            <p>Lihat riwayat ujian yang telah Anda kerjakan beserta nilai yang diperoleh</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalUjian">{{ $data->count() }}</h3>
                    <p>Total Ujian</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-info">
                    @php
                        $avgNilai = $data->avg('nilai');
                        $passedCount = $data->filter(function($item) {
                            return $item->nilai >= 75;
                        })->count();
                    @endphp
                    <h3>{{ number_format($avgNilai, 1) }}</h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    @php
                        $bestScore = $data->max('nilai');
                    @endphp
                    <h3>{{ round($bestScore )?? 0 }}</h3>
                    <p>Nilai Tertinggi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $passedCount }}</h3>
                    <p>Ujian Lulus</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                <span>Filter & Pencarian</span>
            </div>
            <div class="filter-group">
                <button class="filter-btn all-btn active" data-filter="all">
                    <i class="fas fa-list"></i> Semua
                </button>
                <button class="filter-btn" data-filter="excellent">
                    <i class="fas fa-crown"></i> Istimewa (≥85)
                </button>
                <button class="filter-btn" data-filter="good">
                    <i class="fas fa-smile"></i> Baik (75-84)
                </button>
                <button class="filter-btn" data-filter="average">
                    <i class="fas fa-meh"></i> Cukup (60-74)
                </button>
                <button class="filter-btn" data-filter="low">
                    <i class="fas fa-frown"></i> Kurang (<60)
                </button>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama ujian...">
                </div>
            </div>
        </div>

        <!-- Riwayat Section -->
        <div class="section-title">
            <h2>
                <i class="fas fa-list-ol"></i>
                Daftar Riwayat Ujian
            </h2>
            <div class="date-badge">
                <i class="fas fa-calendar-alt"></i> 
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

        <div class="riwayat-grid" id="riwayatGrid">
            @if(isset($data) && $data->count() > 0)
                @foreach($data as $dt)
                    @php
                        $nilai = $dt->nilai;
                        if ($nilai >= 85) {
                            $scoreClass = 'score-excellent';
                            $filterCategory = 'excellent';
                        } elseif ($nilai >= 75) {
                            $scoreClass = 'score-good';
                            $filterCategory = 'good';
                        } elseif ($nilai >= 60) {
                            $scoreClass = 'score-average';
                            $filterCategory = 'average';
                        } else {
                            $scoreClass = 'score-low';
                            $filterCategory = 'low';
                        }
                    @endphp
                    <div class="riwayat-card" data-filter="{{ $filterCategory }}" data-name="{{ strtolower($dt->ujian->nama_ujian ?? '') }}">
                        <div class="card-header-custom">
                            <div class="student-info">
                                <div class="student-avatar">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="student-details">
                                    <h4>{{ $dt->siswa->nama ?? $ire->nama }}</h4>
                                    <p>{{ $dt->siswa->nisn ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="date-badge-custom">
                                <i class="fas fa-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($dt->created_at)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="card-body-custom">
                            <div class="exam-title">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $dt->ujian->nama_ujian ?? 'Ujian' }}</span>
                            </div>
                            <div class="exam-meta">
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Durasi: {{ $dt->ujian->durasi ?? '-' }} Menit</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Dikerjakan: {{ \Carbon\Carbon::parse($dt->created_at)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                            <div class="score-container">
                                <div class="score-badge {{ $scoreClass }}">
                                    <i class="fas fa-star"></i>
                                    <span class="score-value">{{ round($nilai )}}</span>
                                    <span>/ 100</span>
                                </div>
                                <div class="status-done">
                                    <i class="fas fa-check-circle"></i>
                                    Selesai
                                </div>
                            </div>
                            <div class="buttond">
                                <a class="button is-info mx-auto" href="{{route('siswa.detail',$dt->id)}}">Detail</a>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum Ada Riwayat Ujian</h3>
                    <p>Anda belum mengerjakan ujian apapun. Silakan ikuti ujian yang tersedia.</p>
                </div>
            @endif
        </div>
        
        <!-- Empty State untuk filter -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Tidak Ditemukan</h3>
            <p>Tidak ada riwayat ujian yang sesuai dengan filter yang dipilih</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Sidebar Toggle

    var userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.classList.remove('active');
    });
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
    
    // Close sidebar on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            if (mobileToggle) {
                const icon = mobileToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });
    
    // Close sidebar after clicking link on mobile
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });
    
    // ===== FILTER FUNCTIONALITY =====
    const filterButtons = document.querySelectorAll('.filter-btn');
    const riwayatCards = document.querySelectorAll('.riwayat-card');
    const searchInput = document.getElementById('searchInput');
    const emptyState = document.getElementById('emptyState');
    const riwayatGrid = document.getElementById('riwayatGrid');
    
    let currentFilter = 'all';
    let currentSearch = '';
    
    function updateDisplay() {
        let visibleCount = 0;
        
        riwayatCards.forEach(card => {
            const filterValue = card.getAttribute('data-filter');
            const cardName = card.getAttribute('data-name') || '';
            
            let matchesFilter = (currentFilter === 'all' || filterValue === currentFilter);
            let matchesSearch = (currentSearch === '' || cardName.includes(currentSearch.toLowerCase()));
            
            if (matchesFilter && matchesSearch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0 && riwayatCards.length > 0) {
            emptyState.style.display = 'block';
            riwayatGrid.style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            riwayatGrid.style.display = 'grid';
        }
        
        // Jika tidak ada card sama sekali (data kosong)
        if (riwayatCards.length === 0) {
            emptyState.style.display = 'block';
            riwayatGrid.style.display = 'none';
        }
    }
    
    // Filter button click
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            updateDisplay();
        });
    });
    
    // Search input
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentSearch = this.value;
            updateDisplay();
        });
    }
    
    // Update stats based on visible items if needed
    function updateStats() {
        let visibleCount = 0;
        riwayatCards.forEach(card => {
            if (card.style.display !== 'none') visibleCount++;
        });
        // Optional: update stats display
    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. INTERCEPT LINK KLIK (Smooth Redirect)
    // ==========================================
    document.addEventListener('click', function(e) {
        // Cari elemen <a> yang diklik (berlaku jika klik teks atau icon di dalam <a>)
        const link = e.target.closest('a');
        
        if (!link) return; // Bukan link, abaikan
        
        const href = link.getAttribute('href');
        const target = link.getAttribute('target');
        
        // Abaikan jika:
        // - Link kosong / # / javascript
        // - Link untuk buka tab baru (target="_blank")
        // - Link eksternal (mailto, tel, http lain)
        if (!href || 
            href.startsWith('#') || 
            href.startsWith('javascript:') || 
            href.startsWith('mailto:') || 
            href.startsWith('tel:') || 
            target === '_blank') {
            return;
        }
        
        // Abaikan link khusus jika ada (misal: tombol yang buat modal, dll)
        if (link.classList.contains('no-transition') || link.getAttribute('data-turbolinks') === 'false') {
            return;
        }
        
        // Cek apakah link internal (domain yang sama atau relative path /)
        const isLocal = href.startsWith(window.location.origin) || href.startsWith('/');
        const mainContent = document.querySelector(".main-content")
        if (isLocal) {
            e.preventDefault(); // Cegah pindah halaman secara langsung
            
            // Tambahkan class animasi keluar
           mainContent.classList.add('page-leaving');
            
            // Tunggu animasi selesai, baru redirect
            setTimeout(function() {
                window.location.href = href;
            }, 250); // 250ms harus sama dengan durasi CSS pageLeave
        }
    });

    // ==========================================
    // 2. INTERCEPT FORM SUBMIT (Smooth Post/Logout)
    // ==========================================
    // Khusus untuk form biasa (misal: form logout, form cari)
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            mainContent.classList.add('page-leaving');
            // Jangan pakai e.preventDefault() biar Laravel tetap proses data/CSRF dengan normal
        });
    });

    // Khusus untuk AJAX/Fetch (misal: form absensi, hapus jadwal yang pakai fetch)
    const originalFetch = window.fetch;
    window.fetch = function() {
        mainContent.classList.add('page-leaving');
        
        // Tunggu 150ms lalu hilangkan animasi (supaya tidak menghitam saat loading AJAX lama)
        setTimeout(function() {
            mainContent.classList.add('page-leaving');
        }, 150);
        
        return originalFetch.apply(this, arguments);
    };
});
</script>
</body>
</html>