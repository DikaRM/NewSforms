<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Siswa - Sistem Ujian</title>
    
    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ================= RESET & DASAR ================= */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        
        :root {
            --primary: #2e5b9a;       /* Warna Header & Tombol (Asli) */
            --sidebar-bg: #53629E;   /* Warna Sidebar (Asli) */
            --bg-body: #f3f5f9;      /* Background Body (Asli) */
            --text-light: #ffffff;
            --text-dark: #333333;
            --border-color: #e2e8f0;
        }

        body {
            background: var(--bg-body);
            color: var(--text-dark);
            overflow-x: hidden;
            /* Animasi Masuk */
            
        }
        .main-content{
            animation: pageEnter 0.3s ease-out forwards;
        }
        /* Animasi Transisi Halaman */
        @keyframes pageEnter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .main-content.page-leaving {
            animation: pageLeave 0.25s ease-in forwards !important;
            pointer-events: none;
        }

        @keyframes pageLeave {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-12px); }
        }

        a { text-decoration: none; color: inherit; transition: 0.2s; }
        ul { list-style: none; }

        /* ================= LAYOUT UTAMA ================= */
        .app-wrapper {
            display: flex;
            margin-top: 0; /* Header fixed jadi margin-top 0 di body wrapper */
            min-height: 100vh;
        }

        /* ================= HEADER ================= */
        .header {
            background: var(--primary);
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
            height: 60px;
        }

        .header h2 {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header img { height: 30px; object-fit: contain; }

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

        .user-info:hover { background: rgba(255,255,255,0.15); }

        .user-avatar {
            width: 34px;
            height: 34px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: bold;
        }

        .user-name { font-weight: 500; font-size: 0.85rem; }
        .user-name i { font-size: 0.7rem; margin-left: 5px; }

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
            opacity: 1; visibility: visible; transform: translateY(0);
        }

        .dropdown-item-custom {
            padding: 10px 16px;
            display: flex; align-items: center; gap: 12px;
            color: #333; text-decoration: none;
            transition: background 0.2s ease;
            border-bottom: 1px solid #eee;
            font-size: 0.85rem;
            background: none; border: none; width: 100%; text-align: left; cursor: pointer;
        }
        
        .dropdown-item-custom:last-child { border-bottom: none; }
        .dropdown-item-custom:hover { background: #f5f5f5; }
        .dropdown-item-custom i { width: 18px; color: var(--primary); }
        .logout-btn { color: #dc3545; }
        .logout-btn i { color: #dc3545; }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0;
            top: 60px; /* Height of header */
            bottom: 0;
            z-index: 99;
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding-top: 20px;
        }

        .sidebar-menu { padding-bottom: 60px; }

        .sidebar-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px;
            margin: 4px 12px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .sidebar-item i { width: 22px; font-size: 1rem; text-align: center; }
        
        .sidebar-item:hover { background: rgba(255,255,255,0.2); }
        .sidebar-item.active {
            background: rgba(255,255,255,0.25);
            border-left: 3px solid white;
        }

        .sidebar-logout {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.1);
            padding: 15px 12px;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 24px;
            padding-top: 80px; /* Space for fixed header */
            transition: margin-left 0.3s ease;
            width: calc(100% - 260px);
        }

        /* ================= COMPONENTS ================= */
        
        /* Identity Card */
        .identity-card {
            background: var(--primary);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            color: white;
            box-shadow: 0 10px 25px rgba(46, 91, 154, 0.3);
        }

        .identity-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        
        .identity-avatar {
            width: 70px; height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; border: 3px solid white;
        }

        .identity-info h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 5px; }
        .identity-info p { opacity: 0.9; font-size: 0.9rem; }

        .identity-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .detail-item {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.15);
            padding: 12px; border-radius: 10px;
        }
        .detail-label { font-size: 0.75rem; opacity: 0.7; display: block; }
        .detail-value { font-weight: 600; font-size: 0.95rem; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white; padding: 20px; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex; align-items: center; gap: 15px;
            transition: transform 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-3px); }

        .stat-icon {
            width: 50px; height: 50px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        /* Warna Stat (Sama dengan asli) */
        .stat-icon.blue { background: #e3f2fd; color: #1976d2; }
        .stat-icon.green { background: #e8f5e9; color: #388e3c; }
        .stat-icon.orange { background: #fff3e0; color: #f57c00; }
        .stat-icon.purple { background: #f3e5f5; color: #7b1fa2; }

        .stat-content h3 { font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 4px; }
        .stat-content p { font-size: 0.85rem; color: #666; }

        /* Tanggal */
        .date-display {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .date-display h2 { color: var(--primary); font-size: 1.3rem; font-weight: 600; }
        .date-badge {
            background: #e3f2fd; color: #1976d2;
            padding: 8px 16px; border-radius: 20px; font-weight: 500; font-size: 0.9rem;
        }

        /* Custom Cards (Jadwal & Riwayat) - Flexbox replacement for Bulma Columns */
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 30px;
        }

        .card-custom {
            width: 320px; /* Lebih lebar sedikit agar rapi */
            padding: 25px;
            border-radius: 15px;
            color: #333;
            position: relative;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
            text-decoration: none;
            display: block;
        }

        /* Warna Card Asli */
        .card-custom.pink { background: #EBF1FA; border: 1px solid #dbeafe; }
        .card-custom.yellow { background: #D3DFF5; border: 1px solid #bfdbfe; }
        
        .card-custom:hover { transform: translateY(-5px); }

        .card-layout {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .card-img-col img { height: 60px; width: auto; object-fit: contain; }
        
        .card-text-col h3 { margin-bottom: 8px; color: var(--primary); font-weight: 700; font-size: 1.1rem; }
        .card-text-col p { font-size: 0.85rem; color: #555; margin-bottom: 12px; }
        
        .card-arrow {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 35px; height: 35px;
            background: rgba(255,255,255,0.6);
            border-radius: 50%;
            color: var(--sidebar-bg);
        }

        /* Ujian Container */
        .exam-container {
            background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 1.1rem; font-weight: 600; color: var(--primary);
            margin-bottom: 20px; padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 10px;
        }

        .exam-card {
            margin-bottom: 20px; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease; border: 1px solid #f0f0f0;
            background: white;
        }
        .exam-card:hover { transform: translateY(-2px); }

        .exam-card-content { padding: 20px; }
        
        .exam-card .media { border-bottom: 2px solid #f5f5f5; padding-bottom: 15px; margin-bottom: 15px; }
        .exam-card .title-4 { color: var(--primary); font-weight: 700; font-size: 1rem; margin-bottom: 5px; }
        .exam-card .subtitle-6 { color: #7f8c8d; font-size: 0.75rem; display: flex; align-items: center; gap: 5px; }

        /* Tombol & Status */
        .btn-custom {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px; border-radius: 6px;
            font-size: 0.9rem; font-weight: 600;
            transition: 0.2s; cursor: pointer; border: none; text-decoration: none;
        }
        .btn-primary-custom { background: var(--primary); color: white; }
        .btn-primary-custom:hover { background: #244a82; }
        
        .btn-warning-custom { background: #f59e0b; color: white; }
        .btn-success-custom { background: #28a745; color: white; border: 1px solid #28a745; }
        .btn-success-custom:hover { background: #218838; }

        .status-badge {
            padding: 5px 15px; border-radius: 20px; font-size: 0.85rem;
            background: #e5e7eb; color: #6b7280; display: inline-block;
        }

        /* Upcoming Schedule */
        .upcoming-schedule { background: white; border-radius: 12px; padding: 20px; }
        .schedule-item { display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
        .schedule-item:last-child { border-bottom: none; }
        .schedule-date { background: #f5f5f5; padding: 8px 12px; border-radius: 8px; text-align: center; min-width: 60px; }
        .schedule-day { font-size: 1.2rem; font-weight: 700; color: var(--primary); }
        .schedule-month { font-size: 0.75rem; color: #666; }
        .schedule-info h4 { font-weight: 600; color: #333; margin-bottom: 4px; }
        .schedule-info p { font-size: 0.8rem; color: #666; }

        /* ================= RESPONSIVE ================= */
        .mobile-toggle {
            display: none;
            position: fixed; bottom: 20px; right: 20px;
            width: 50px; height: 50px; background: var(--primary);
            border-radius: 50%; align-items: center; justify-content: center;
            cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: none; color: white;
        }
        .sidebar-overlay { display: none; position: fixed; top: 60px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }

        @media (max-width: 768px) {
            .header h2 span, .user-name span, .user-name i { display: inline; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 80px 16px 16px 16px; }
            .mobile-toggle { display: flex; }
            .sidebar-overlay.active { display: block; }
            .cards-container { flex-direction: column; }
            .card-custom { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .identity-header { flex-direction: column; text-align: center; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--sidebar-bg); border-radius: 3px; }
    </style>
</head>

<body>

<!-- Header -->
<header class="header">
    <h2>
       <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" alt="Logo" style="height:30px;"/>
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
            <div class="dropdown-divider" style="height:1px; background:#eee; margin:4px 0;"></div>
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn">
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
            <a href="{{ route('siswa.index') }}" class="sidebar-item active">
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
            <a href="{{ route('siswa.riwayat') }}" class="sidebar-item">
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2e5b9a'
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
        
        <!-- IDENTITAS SISWA CARD -->
        <div class="identity-card">
            <div class="identity-header">
                <div class="identity-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="identity-info">
                    <h2>{{ $siswa->nama }}</h2>
                    <p><i class="fas fa-school"></i> {{ $siswa->kelas->nama_kelas ?? 'Kelas belum diatur' }}</p>
                </div>
            </div>
            <div class="identity-details">
                <div class="detail-item">
                    <i class="fas fa-id-card"></i>
                    <div>
                        <span class="detail-label">NISN</span>
                        <span class="detail-value">{{ $siswa->nisn }}</span>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-user-tag"></i>
                    <div>
                        <span class="detail-label">Username</span>
                        <span class="detail-value">{{ $siswa->username ?? '-' }}</span>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <span class="detail-label">Tahun Ajaran</span>
                        <span class="detail-value">2025/2026</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- STATISTIK / RESUME -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalUjian }}</h3>
                    <p>Total Ujian</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $ujianSelesai }}</h3>
                    <p>Ujian Selesai</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($rataNilai, 1) }}</h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalUjian - $ujianSelesai }}</h3>
                    <p>Belum Selesai</p>
                </div>
            </div>
        </div>
        
        <!-- TANGGAL HARI INI -->
        <div class="date-display">
            <h2>
                <i class="fas fa-calendar-day" style="margin-right: 10px;"></i>
                {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}
            </h2>
            <div class="date-badge">
                <i class="far fa-clock"></i> 
                <span id="liveTime"></span>
            </div>
        </div>

        <!-- QUICK LINKS (Jadwal & Riwayat) - REPLACEMENT FOR BULMA COLUMNS -->
        <div class="cards-container">
            <!-- Jadwal Card -->
            <a href="{{ route('siswa.jadwal') }}" class="card-custom pink">
               <div class="card-layout">
                    <div class="card-img-col">
                        <img src="{{asset('Siswa/Jadwal-ujian.png')}}" alt="Jadwal" onerror="this.style.display='none'">
                    </div>
                    <div class="card-text-col">
                        <h3>Jadwal Ujian</h3>
                        <p>Halaman untuk melihat jadwal ujian siswa.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fa fa-arrow-right"></i>
                    </div>
                </div>
            </a>
            
            <!-- Riwayat Card -->
            <a href="{{ route('siswa.riwayat') }}" class="card-custom yellow">
                <div class="card-layout">
                    <div class="card-img-col">
                        <img src="{{asset('Siswa/RIWAYAT.png')}}" alt="Riwayat" onerror="this.style.display='none'">
                    </div>
                    <div class="card-text-col">
                        <h3>Riwayat</h3>
                        <p>Halaman melihat riwayat ujian.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fa fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- UJIAN HARI INI -->
        <div class="exam-container">
            <div class="section-title">
                <i class="fas fa-pencil-alt"></i> Ujian Hari Ini
            </div>
        @if($siswa->status === "ready")  
            @if(isset($uji) && count($uji) > 0)
                @foreach($uji as $uj)
                @php 
                    $peserta = $uj->peserta->first();
                    
                    if($peserta) {
                        $statusUjian = $peserta->status;
                        $nilaiSiswa = $peserta->nilai;
                    } else {
                        $statusUjian = 'belum';
                        $nilaiSiswa = null;
                    }
                    
                    $isUjianReady = ($uj->status === "ready");
                    $isUjianOngoing = ($uj->status === "ongoing");
                @endphp
                
                <div class="exam-card">
                    <div class="exam-card-content">
                        <div class="media">
                            <div class="media-content">
                                <p class="title-4">
                                    {{ $uj->mapels->nama_mapel ?? $uj->nama_ujian }}
                                </p>
                                <p class="subtitle-6">
                                    <span class="icon"><i class="fas fa-clock"></i></span>
                                    @if(isset($uj->jadwal))
                                        {{ \Carbon\Carbon::parse($uj->jadwal->waktu_mulai)->format('H:i') }} WIB
                                    @else
                                        Waktu Belum ditentukan
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="content">
                            @if($statusUjian == 'selesai')
                                <div style="text-align: center;">
                                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 8px;">
                                        <i class="fas fa-check-circle" style="color: #22c55e; font-size: 2rem;"></i>
                                        <div style="font-size: 2rem; font-weight: 700; color: #22c55e; margin: 10px 0;">
                                            {{ round($nilaiSiswa) ?? 0 }}
                                        </div>
                                        <span style="background: #22c55e; color: white; margin: 5px 0; border-radius: 20px; font-size: 0.85rem; display:inline-block; padding: 4px 12px;">
                                            Ujian Selesai
                                        </span>
                                        <br><br>
                                        <a class="btn-custom btn-success-custom" href="{{route('siswa.detail',$peserta->id)}}">Detail Nilai</a>
                                    </div>
                                </div>
                            @elseif($statusUjian == 'mulai')
                                <div style="text-align: center;">
                                    <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-bottom: 15px; color: #d97706; font-weight: 600;">
                                        <i class="fas fa-hourglass-half"></i> Ujian sedang berjalan
                                    </div>
                                    <a href="{{ route('siswa.resume', $uj->id) }}" class="btn-custom btn-warning-custom" style="width: 100%; justify-content: center;">
                                        <span><i class="fas fa-play-circle"></i></span>
                                        <span>Lanjutkan Ujian</span>
                                    </a>
                                </div>
                            @else
                                @if($isUjianReady)
                                    <div style="text-align: center;">
                                        <a href="{{ route('siswa.shop', $uj->id) }}" class="btn-custom btn-primary-custom" style="width: 100%; justify-content: center;">
                                            <span><i class="fas fa-play"></i></span>
                                            <span>Mulai Ujian</span>
                                        </a>
                                    </div>
                                @else
                                    <div style="text-align: center;">
                                        <span class="status-badge">
                                            <i class="fas fa-info-circle"></i> Belum tersedia
                                        </span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-inbox fa-3x" style="margin-bottom: 15px;"></i>
                    <p>Tidak ada ujian untuk hari ini</p>
                    <p style="font-size: 0.85rem;">Silakan cek jadwal ujian untuk informasi lebih lanjut</p>
                </div>
            @endif
        </div>
        
        @if(isset($jadwalMendatang) && count($jadwalMendatang) > 0)
        <div class="upcoming-schedule">
            <div class="section-title">
                <i class="fas fa-calendar-week"></i> Jadwal Mendatang
            </div>
            
            @foreach($jadwalMendatang as $jadwal)
            <div class="schedule-item">
                <div class="schedule-date">
                    <div class="day">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('d') }}</div>
                    <div class="month">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('M') }}</div>
                </div>
                <div class="schedule-info">
                    <h4>{{ $jadwal->ujian->nama_ujian ?? 'Ujian' }}</h4>
                    <p>
                        <i class="far fa-clock"></i> 
                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
      @else
      <h5 style="text-align: center; margin-top: 50px; color: var(--primary);">Belum Di Absen</h5>
      @endif
      
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live Time
    function updateTime() {
        const timeElement = document.getElementById('liveTime');
        if (timeElement) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    updateTime();
    setInterval(updateTime, 1000);
    
    // User Dropdown Toggle
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
    
    // Mobile Sidebar Toggle
    var mobileToggle = document.getElementById('mobileToggle');
    var sidebar = document.getElementById('sidebar');
    var sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        var icon = mobileToggle.querySelector('i');
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
    
    // Close sidebar on mobile after clicking link
    var sidebarItems = document.querySelectorAll('.sidebar-item');
    for (var i = 0; i < sidebarItems.length; i++) {
        sidebarItems[i].addEventListener('click', function() {
            if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });
    }
});

// Page Transition Script (Original logic preserved)
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (!link) return;
    
    const href = link.getAttribute('href');
    const target = link.getAttribute('target');
    
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || target === '_blank') {
        return;
    }
    
    if (link.classList.contains('no-transition') || link.getAttribute('data-turbolinks') === 'false') {
        return;
    }
     
    const isLocal = href.startsWith(window.location.origin) || href.startsWith('/');
    const mainContent = document.querySelector(".main-content")
    if (isLocal) {
        e.preventDefault();
       mainContent.classList.add("page-leaving")
        setTimeout(function() {
            window.location.href = href;
        }, 250);
    }
});

document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
        mainContent.classList.add("page-leaving")
    });
});
</script>
</body>
</html>