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

        /* ===== CARD STYLES ===== */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #eef2f6;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .stat-card .heading {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .stat-card .title {
            font-size: 2rem;
            font-weight: 700;
            color: #2e5b9a;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            border: 1px solid #eef2f6;
        }

        .table-card .card-header {
            background: white;
            border-bottom: 2px solid #f0f2f5;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card .card-header-title {
            font-weight: 600;
            color: #2e5b9a;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-card .card-content {
            padding: 0;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fc;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
            border-bottom: 2px solid #e9ecef;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
        }

        .table tbody tr:hover {
            background: #fafbfe;
        }

        /* Tag Styles */
        .tag-score {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .tag-score-excellent {
            background: #d4edda;
            color: #155724;
        }

        .tag-score-good {
            background: #cfe2ff;
            color: #2e5b9a;
        }

        .tag-score-average {
            background: #fff3cd;
            color: #856404;
        }

        .tag-score-poor {
            background: #f8d7da;
            color: #721c24;
        }

        .tag-cheat {
            background: #f8d7da;
            color: #dc3545;
            font-weight: 600;
        }

        .tag-safe {
            background: #e8f5e9;
            color: #2e7d32;
        }

        /* Notification */
        .notification-toast {
            position: fixed;
            top: 70px;
            right: 20px;
            padding: 12px 18px;
            border-radius: 8px;
            color: white;
            z-index: 1100;
            animation: slideInRight 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .notification-success {
            background: #28a745;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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
            
            .stat-card .title {
                font-size: 1.5rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 8px 12px;
                font-size: 0.75rem;
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
                @if(Auth::check())
                    <span>{{ Auth::user()->name }}</span>
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
            <a href="{{ route('guru.riwayat') }}" class="sidebar-item">
                <i class="fas fa-history"></i>
                <span>Riwayat Ujian</span>
            </a>
            <a href="{{ route('guru.result') }}" class="sidebar-item active">
                <i class="fas fa-file-alt"></i>
                <span>Hasil Ujian</span>
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
        
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <ul>
                <li><a href="{{ route('guru.index') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('guru.result') }}">Hasil Ujian</a></li>
                <li class="is-active"><a href="#">{{ $ujian->nama_ujian ?? 'Detail' }}</a></li>
            </ul>
        </div>
        
        <!-- Header -->
        <div class="level is-mobile" style="margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
            <div class="level-left">
                <div>
                    <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 600; margin-bottom: 4px;">
                        <i class="fas fa-chart-line"></i> Hasil Ujian
                    </h1>
                    <h2 style="color: #5c6fa6; font-size: 1rem; font-weight: 500;">
                        {{ $ujian->nama_ujian ?? 'Ujian' }}
                    </h2>
                    @if($ujian)
                        <p style="color: #6c757d; font-size: 0.85rem; margin-top: 5px;">
                            <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') ?? 'Tanggal tidak tersedia' }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="level-right">
                <div class="tags are-medium">
                    <span class="tag is-info is-light" style="background: #eef2ff; color: #2e5b9a;">
                        <i class="fas fa-users"></i> Total Peserta: {{ $pesertaUjian->count() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistik Ringkas -->
        @if($pesertaUjian->count() > 0)
        @php
            // Hitung statistik dari data yang ada
            $rataNilai = $pesertaUjian->avg('nilai');
            $nilaiTertinggi = $pesertaUjian->max('nilai');
            $jumlahCurang = $pesertaUjian->filter(function($peserta) {
                return $peserta->pelanggaran != null;
            })->count();
        @endphp
        <div class="columns is-multiline" style="margin-bottom: 24px;">
            <div class="column is-4">
                <div class="stat-card">
                    <p class="heading">
                        <i class="fas fa-chart-simple"></i> Rata-rata Nilai
                    </p>
                    <p class="title">
                        {{ number_format($rataNilai, 2) }}
                    </p>
                </div>
            </div>
            <div class="column is-4">
                <div class="stat-card">
                    <p class="heading">
                        <i class="fas fa-trophy"></i> Nilai Tertinggi
                    </p>
                    <p class="title">
                        {{ $nilaiTertinggi ?: '-' }}
                    </p>
                </div>
            </div>
            <div class="column is-4">
                <div class="stat-card">
                    <p class="heading">
                        <i class="fas fa-exclamation-triangle"></i> Kecurangan
                    </p>
                    <p class="title" style="color: #dc3545;">
                        {{ $jumlahCurang }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel Utama Daftar Nilai -->
        <div class="table-card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="fas fa-list-ul"></i> Daftar Nilai Peserta
                </div>
                <div class="tags has-addons">
                    <span class="tag is-light">{{ $pesertaUjian->count() }} data</span>
                </div>
            </div>
            <div class="card-content">
                <div style="overflow-x: auto;">
                    <table class="table is-striped is-hoverable is-fullwidth">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th style="width: 100px;">Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesertaUjian as $index => $peserta)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="is-family-monospace" style="font-size: 0.8rem;">
                                        {{ $peserta->siswa->nisn ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $peserta->siswa->nama ?? 'Data tidak ditemukan' }}</strong>
                                </td>
                                <td>
                                    @if($peserta->siswa && $peserta->siswa->kelas)
                                        <span class="tag is-light" style="background: #f0f2f5;">
                                            {{ $peserta->siswa->kelas->nama_kelas }}
                                        </span>
                                    @else
                                        <span class="tag is-light">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $nilai = $peserta->nilai;
                                        $scoreClass = '';
                                        if($nilai >= 80) $scoreClass = 'tag-score-excellent';
                                        elseif($nilai >= 70) $scoreClass = 'tag-score-good';
                                        elseif($nilai >= 60) $scoreClass = 'tag-score-average';
                                        else $scoreClass = 'tag-score-poor';
                                    @endphp
                                    <span class="tag-score {{ $scoreClass }}">
                                        {{ $nilai ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($peserta->pelanggaran)
                                        <span class="tag-cheat tag-score">
                                            <i class="fas fa-exclamation-circle"></i> 
                                            {{ $peserta->pelanggaran->jenis_pelanggaran ?? 'Kecurangan' }}
                                        </span>
                                    @else
                                        <span class="tag-safe tag-score">
                                            <i class="fas fa-check-circle"></i> Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="has-text-centered" style="padding: 40px;background:white;">
                                    <i class="fas fa-inbox fa-3x" style="color: #adb5bd; margin-bottom: 12px;"></i>
                                    <p style="color: #6c757d;">Belum ada peserta ujian</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Khusus Kecurangan -->
        @php
            $pesertaCurang = $pesertaUjian->filter(function($peserta) {
                return $peserta->pelanggaran != null;
            });
        @endphp

        @if($pesertaCurang->count() > 0)
        <div class="table-card" style="border-left: 4px solid #dc3545;">
            <div class="card-header">
                <div class="card-header-title" style="color: #dc3545;">
                    <i class="fas fa-gavel"></i> Daftar Siswa yang Melakukan Kecurangan
                    <span class="tag is-danger is-light ml-2" style="margin-left: 8px;">{{ $pesertaCurang->count() }}</span>
                </div>
            </div>
            <div class="card-content">
                <div style="overflow-x: auto;">
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Nilai</th>
                                <th>Jenis Kecurangan</th>
                            </tr>
                        </thead>
                        <tbody style="background:transparent;">
                            @foreach($pesertaCurang as $index => $curang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $curang->siswa->nama ?? '-' }}</strong>
                                </td>
                                <td>
                                    <span class="tag is-light">
                                        {{ $curang->siswa->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="tag-score tag-score-poor">{{ $curang->nilai ?: '-' }}</span>
                                </td>
                                <td>
                                    <div class="tags has-addons">
                                        <span class="tag is-danger">
                                            <i class="fas fa-ban"></i> {{ $curang->pelanggaran->jenis_pelanggaran ?? 'Kecurangan' }}
                                        </span>
                                        @if($curang->pelanggaran && $curang->pelanggaran->deskripsi)
                                            <span class="tag is-light">
                                                {{ $curang->pelanggaran->deskripsi }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
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
    
    // Auto hide notification
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(function() {
            notification.style.opacity = '0';
            setTimeout(function() {
                notification.style.display = 'none';
            }, 300);
        }, 5000);
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