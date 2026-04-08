<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Jadwal Ujian - Dashboard Siswa</title>

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
    background: linear-gradient(135deg, #f3f5f9 0%, #eef2f7 100%);
    min-height: 100vh;
}

/* ===== HEADER ===== */
.header {
    background: linear-gradient(135deg, #2e5b9a 0%, #1e3a6b 100%);
    color: white;
    padding: 16px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.header h2 {
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header h2 i {
    font-size: 1.3rem;
}

.user-info-header {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.15);
    padding: 6px 16px 6px 12px;
    border-radius: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-info-header:hover {
    background: rgba(255,255,255,0.25);
}

.user-avatar {
    width: 36px;
    height: 36px;
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
    font-size: 0.9rem;
}

.user-name i {
    font-size: 0.7rem;
    margin-left: 6px;
}

/* ===== LAYOUT ===== */
.app-wrapper {
    display: flex;
    min-height: calc(100vh - 70px);
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #5c6fa6 0%, #4a5a8a 100%);
    min-height: 100vh;
    padding: 25px 0;
    color: white;
    position: sticky;
    top: 70px;
    height: calc(100vh - 70px);
    overflow-y: auto;
    box-shadow: 4px 0 20px rgba(0,0,0,0.08);
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 4px 16px;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 18px;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.sidebar ul li a i {
    width: 22px;
    font-size: 1.1rem;
}

.sidebar ul li a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateX(5px);
}

.sidebar ul li a.active {
    background: rgba(255,255,255,0.25);
    border-left: 3px solid white;
}

.logout {
    position: absolute;
    bottom: 25px;
    left: 0;
    right: 0;
    padding: 0 20px;
}

.logout form button {
    width: 100%;
    background: rgba(220, 53, 69, 0.9);
    border: none;
    color: white;
    padding: 12px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.logout form button:hover {
    background: #dc3545;
    transform: translateY(-2px);
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

.day-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
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

.filter-btn.all-btn:hover {
    background: #1e3a6b;
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

/* Jadwal Grid */
.jadwal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

/* Jadwal Card */
.jadwal-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.03);
    animation: fadeInUp 0.4s ease forwards;
}

.jadwal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.card-day {
    background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
    padding: 16px 20px;
    color: white;
}

.card-day h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-day h3 i {
    font-size: 1.1rem;
}

.card-body {
    padding: 20px;
}

.exam-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e2a3e;
    margin-bottom: 12px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.exam-title i {
    color: #2e5b9a;
    font-size: 1rem;
    margin-top: 2px;
}

.exam-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #eef2f6;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: #5a6e8a;
}

.meta-item i {
    color: #2e5b9a;
    width: 16px;
    font-size: 0.75rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-top: 12px;
}

.status-upcoming {
    background: #e8f0fe;
    color: #2e5b9a;
}

.status-ongoing {
    background: #fff3cd;
    color: #856404;
}

.status-done {
    background: #d4edda;
    color: #155724;
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
        display: none;
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
    
    .jadwal-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .page-header h1 {
        font-size: 1.4rem;
    }
    
    .section-title h2 {
        font-size: 1.1rem;
    }
    
    .filter-btn {
        padding: 6px 16px;
        font-size: 0.75rem;
    }
    
    .day-filter {
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .main {
        padding: 16px;
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

<div class="header">
    <h2>
        <i class="fas fa-graduation-cap"></i>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    <div class="user-info-header" id="userMenu">
        <div class="user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="user-name">
            {{$ire->nama}} <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</div>

<!-- Mobile Menu Toggle -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li>
                <a href="{{route('siswa.index')}}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{route('siswa.jadwal')}}" class="active">
                    <i class="fas fa-calendar-alt"></i> Jadwal Ujian
                </a>
            </li>
            <li>
                <a href="{{route('siswa.riwayat')}}">
                    <i class="fas fa-history"></i> Riwayat
                </a>
            </li>
        </ul>

        <div class="logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-calendar-check"></i>
                Jadwal Ujian
            </h1>
            <p>Filter jadwal berdasarkan hari untuk melihat ujian yang akan datang</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalJadwal">{{ $data->count() }}</h3>
                    <p>Total Jadwal</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 id="upcomingCount">
                        @php
                            $upcoming = $data->filter(function($item) {
                                return \Carbon\Carbon::parse($item->tanggal)->isToday() || 
                                       \Carbon\Carbon::parse($item->tanggal)->isFuture();
                            })->count();
                        @endphp
                        {{ $upcoming }}
                    </h3>
                    <p>Mendatang</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $data->pluck('ujian')->unique('nama_ujian')->count() }}</h3>
                    <p>Mata Ujian</p>
                </div>
            </div>
        </div>

        <!-- Filter Section by Day -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                <span>Filter Berdasarkan Hari</span>
            </div>
            <div class="day-filter" id="dayFilter">
                <button class="filter-btn all-btn active" data-day="all">
                    <i class="fas fa-calendar-week"></i> Semua Hari
                </button>
                <button class="filter-btn" data-day="Monday">
                    <i class="fas fa-calendar-day"></i> Senin
                </button>
                <button class="filter-btn" data-day="Tuesday">
                    <i class="fas fa-calendar-day"></i> Selasa
                </button>
                <button class="filter-btn" data-day="Wednesday">
                    <i class="fas fa-calendar-day"></i> Rabu
                </button>
                <button class="filter-btn" data-day="Thursday">
                    <i class="fas fa-calendar-day"></i> Kamis
                </button>
                <button class="filter-btn" data-day="Friday">
                    <i class="fas fa-calendar-day"></i> Jumat
                </button>
                <button class="filter-btn" data-day="Saturday">
                    <i class="fas fa-calendar-day"></i> Sabtu
                </button>
                <button class="filter-btn" data-day="Sunday">
                    <i class="fas fa-calendar-day"></i> Minggu
                </button>
            </div>
        </div>

        <!-- Jadwal Section -->
        <div class="section-title">
            <h2 id="sectionTitle">
                <i class="fas fa-list-ol"></i>
                Semua Jadwal Ujian
            </h2>
            <div class="date-badge">
                <i class="fas fa-calendar-alt"></i> 
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

        <div class="jadwal-grid" id="jadwalGrid">
            @php
                // Kelompokkan data berdasarkan hari
                $groupedByDay = $data->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->tanggal)->format('l');
                });
                
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $dayNames = [
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                    'Sunday' => 'Minggu'
                ];
            @endphp
            
            @foreach($days as $day)
                @if(isset($groupedByDay[$day]) && $groupedByDay[$day]->count() > 0)
                    @foreach($groupedByDay[$day] as $dt)
                        @php
                            $today = \Carbon\Carbon::now()->startOfDay();
                            $examDate = \Carbon\Carbon::parse($dt->tanggal)->startOfDay();
                            $now = \Carbon\Carbon::now();
                            $startTime = \Carbon\Carbon::parse($dt->waktu_mulai);
                            $endTime = \Carbon\Carbon::parse($dt->waktu_selesai);
                            
                            if ($examDate->lt($today)) {
                                $status = 'done';
                                $statusText = 'Selesai';
                                $statusClass = 'status-done';
                                $statusIcon = 'fas fa-check-circle';
                            } elseif ($examDate->eq($today) && $now->between($startTime, $endTime)) {
                                $status = 'ongoing';
                                $statusText = 'Sedang Berlangsung';
                                $statusClass = 'status-ongoing';
                                $statusIcon = 'fas fa-hourglass-half';
                            } elseif ($examDate->eq($today) && $now->lt($startTime)) {
                                $status = 'upcoming';
                                $statusText = 'Hari Ini, Segera';
                                $statusClass = 'status-upcoming';
                                $statusIcon = 'fas fa-clock';
                            } else {
                                $status = 'upcoming';
                                $statusText = 'Mendatang';
                                $statusClass = 'status-upcoming';
                                $statusIcon = 'fas fa-calendar-week';
                            }
                        @endphp
                        <div class="jadwal-card" data-day="{{ $day }}">
                            <div class="card-day">
                                <h3>
                                    <i class="fas fa-calendar-day"></i>
                                    {{ $dayNames[$day] ?? $day }}, {{ \Carbon\Carbon::parse($dt->tanggal)->isoFormat("D MMMM YYYY") }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="exam-title">
                                    <i class="fas fa-file-alt"></i>
                                    <span>{{ $dt->jam_mapel }} - {{ $dt->ujian->nama_ujian ?? 'Ujian' }}</span>
                                </div>
                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($dt->waktu_mulai)->format("H:i") }} - {{ \Carbon\Carbon::parse($dt->waktu_selesai)->format("H:i") }} WIB</span>
                                    </div>
                                    @if(isset($dt->ujian->durasi))
                                    <div class="meta-item">
                                        <i class="fas fa-hourglass-half"></i>
                                        <span>{{ $dt->ujian->durasi }} Menit</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="status-badge {{ $statusClass }}">
                                    <i class="{{ $statusIcon }}"></i>
                                    <span>{{ $statusText }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
        
        <!-- Empty State untuk filter -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-calendar-times"></i>
            <h3>Tidak Ada Jadwal Ujian</h3>
            <p>Tidak terdapat jadwal ujian pada hari yang dipilih</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // ===== FILTER BY DAY FUNCTIONALITY =====
    const filterButtons = document.querySelectorAll('.filter-btn');
    const jadwalCards = document.querySelectorAll('.jadwal-card');
    const emptyState = document.getElementById('emptyState');
    const sectionTitle = document.getElementById('sectionTitle');
    const totalJadwalSpan = document.getElementById('totalJadwal');
    const upcomingCountSpan = document.getElementById('upcomingCount');
    
    // Map hari ke bahasa Indonesia
    const dayNames = {
        'Monday': 'Senin',
        'Tuesday': 'Selasa',
        'Wednesday': 'Rabu',
        'Thursday': 'Kamis',
        'Friday': 'Jumat',
        'Saturday': 'Sabtu',
        'Sunday': 'Minggu',
        'all': 'Semua Hari'
    };
    
    function updateFilter(selectedDay) {
        let visibleCount = 0;
        let upcomingVisibleCount = 0;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        jadwalCards.forEach(card => {
            const cardDay = card.getAttribute('data-day');
            
            if (selectedDay === 'all' || cardDay === selectedDay) {
                card.style.display = 'block';
                visibleCount++;
                
                // Hitung jadwal mendatang yang terlihat
                const statusBadge = card.querySelector('.status-badge span');
                if (statusBadge && statusBadge.textContent !== 'Selesai') {
                    upcomingVisibleCount++;
                }
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update title section
        const selectedButton = document.querySelector(`.filter-btn[data-day="${selectedDay}"]`);
        const selectedText = selectedButton ? selectedButton.textContent.trim() : 'Semua Hari';
        sectionTitle.innerHTML = `
            <i class="fas fa-list-ol"></i>
            ${selectedText}
        `;
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
            document.getElementById('jadwalGrid').style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            document.getElementById('jadwalGrid').style.display = 'grid';
        }
        
        // Update stats jika filter all
        if (selectedDay === 'all') {
            // Update total jadwal (semua card yang asli)
            const totalCards = document.querySelectorAll('.jadwal-card').length;
            totalJadwalSpan.textContent = totalCards;
            
            // Hitung upcoming dari semua card
            let allUpcoming = 0;
            document.querySelectorAll('.jadwal-card').forEach(card => {
                const statusBadge = card.querySelector('.status-badge span');
                if (statusBadge && statusBadge.textContent !== 'Selesai') {
                    allUpcoming++;
                }
            });
            upcomingCountSpan.textContent = allUpcoming;
        }
    }
    
    // Add click event to filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const selectedDay = this.getAttribute('data-day');
            updateFilter(selectedDay);
        });
    });
});
</script>
</body>
</html>