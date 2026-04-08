<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Penjadwalan Ujian - {{$klas->nama_kelas}} | Sistem Ujian</title>

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

    /* ===== BREADCRUMB ===== */
    .breadcrumb-custom ul li:not(:last-child):after {
        content: "/";
        margin: 0 8px;
        color: #adb5bd;
    }

    /* ===== DAY CARD STYLES ===== */
    .day-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        border: 1px solid #eef2f6;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .day-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .day-header {
        padding: 15px 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .day-header:hover {
        filter: brightness(1.05);
    }
    
    /* Warna untuk setiap hari */
    .day-header.sunday { background: linear-gradient(135deg, #f56565 0%, #c53030 100%); }
    .day-header.monday { background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%); }
    .day-header.tuesday { background: linear-gradient(135deg, #48bb78 0%, #2f855a 100%); }
    .day-header.wednesday { background: linear-gradient(135deg, #ecc94b 0%, #b7791f 100%); }
    .day-header.thursday { background: linear-gradient(135deg, #9f7aea 0%, #6b46c1 100%); }
    .day-header.friday { background: linear-gradient(135deg, #ed8936 0%, #c05621 100%); }
    .day-header.saturday { background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%); }
    
    .day-content {
        padding: 25px;
        display: none;
    }
    
    .day-content.active {
        display: block;
    }
    
    .schedule-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 5px solid #4299e1;
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .schedule-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: white;
    }
    
    .schedule-item.conflict {
        border-left-color: #f56565;
        background: #fff5f5;
    }
    
    .time-badge {
        background: #ebf8ff;
        color: #2b6cb0;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .empty-day {
        text-align: center;
        padding: 50px 20px;
        background: #f8fafc;
        border-radius: 12px;
        color: #a0aec0;
        border: 2px dashed #cbd5e0;
    }
    
    .empty-day i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #cbd5e0;
    }
    
    .add-schedule-form {
        background: #f8fafc;
        border-radius: 16px;
        padding: 25px;
        margin-top: 25px;
        border: 2px dashed #cbd5e0;
        transition: all 0.3s ease;
    }
    
    .add-schedule-form:hover {
        border-color: #2e5b9a;
        background: #fff;
    }
    
    .day-tab {
        display: flex;
        overflow-x: auto;
        gap: 8px;
        padding: 15px;
        background: white;
        border-radius: 50px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .day-tab-btn {
        padding: 10px 25px;
        border: none;
        background: #f7fafc;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
        color: #4a5568;
    }
    
    .day-tab-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background: #2e5b9a;
        color: white;
    }
    
    .day-tab-btn.active {
        background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
        color: white;
    }
    
    .conflict-indicator {
        background: #fed7d7;
        color: #c53030;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
        border: 1px solid #eef2f6;
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 70px;
        right: 20px;
        background: white;
        border-radius: 8px;
        padding: 15px 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid #48bb78;
    }
    
    .toast-notification.error {
        border-left-color: #f56565;
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
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #2e5b9a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Back Button */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        border: 1px solid #eef2f6;
        padding: 8px 16px;
        border-radius: 10px;
        color: #2e5b9a;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .back-button:hover {
        background: #2e5b9a;
        color: white;
        transform: translateX(-3px);
    }
    
    /* Class Info Card */
    .class-info-card {
        background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .class-info-card h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .class-info-card p {
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    .class-stats-mini {
        display: flex;
        gap: 20px;
    }
    
    .class-stats-mini .stat {
        text-align: center;
    }
    
    .class-stats-mini .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .class-stats-mini .stat-label {
        font-size: 0.7rem;
        opacity: 0.8;
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
        
        .day-tab {
            border-radius: 12px;
        }
        
        .day-tab-btn {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
        
        .class-info-card {
            flex-direction: column;
            text-align: center;
        }
        
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
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
            <a href="{{ route('admin-ops.index') }}" class="sidebar-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin-ops.index') }}" class="sidebar-item active">
                <i class="fas fa-calendar-alt"></i>
                <span>Penjadwalan</span>
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
                <li><a href="{{ route('admin-ops.index') }}" style="color: #5c6fa6; text-decoration: none;"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('admin-ops.index') }}" style="color: #5c6fa6; text-decoration: none;">Kelas</a></li>
                <li class="is-active" style="color: #2e5b9a; font-weight: 500;">{{$klas->nama_kelas}}</li>
            </ul>
        </div>
        
        <!-- Back Button -->
        <a href="{{ route('admin-ops.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
        
        <!-- Class Info Card -->
        <div class="class-info-card">
            <div>
                <h3><i class="fas fa-door-open mr-2"></i> {{$klas->nama_kelas}}</h3>
                <p><i class="fas fa-map-marker-alt"></i> Penjadwalan Ujian - Atur jadwal ujian untuk kelas ini</p>
            </div>
            <div class="class-stats-mini">
                <div class="stat">
                    <div class="stat-value">{{$uji->where('status', 'ready')->count()}}</div>
                    <div class="stat-label">Ujian Ready</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{$gur->count()}}</div>
                    <div class="stat-label">Pengawas</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{$jad->count()}}</div>
                    <div class="stat-label">Terjadwal</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="field is-grouped is-grouped-multiline" style="margin-bottom: 0;">
                <div class="control">
                    <label class="checkbox">
                        <input type="checkbox" id="showOnlyReady" checked> 
                        Tampilkan hanya ujian Ready
                    </label>
                </div>
                <div class="control">
                    <label class="checkbox">
                        <input type="checkbox" id="showConflicts"> 
                        Tampilkan hanya jadwal bentrok
                    </label>
                </div>
                <div class="control">
                    <div class="select is-small">
                        <select id="filterPengawas">
                            <option value="">Semua Pengawas</option>
                            @foreach($gur as $gu)
                            <option value="{{$gu->id}}">{{$gu->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Day Tabs -->
        <div class="day-tab" id="dayTabs">
            <button class="day-tab-btn active" onclick="showDay('all', this)">Semua Hari</button>
            <button class="day-tab-btn" onclick="showDay('Sunday', this)">Minggu</button>
            <button class="day-tab-btn" onclick="showDay('Monday', this)">Senin</button>
            <button class="day-tab-btn" onclick="showDay('Tuesday', this)">Selasa</button>
            <button class="day-tab-btn" onclick="showDay('Wednesday', this)">Rabu</button>
            <button class="day-tab-btn" onclick="showDay('Thursday', this)">Kamis</button>
            <button class="day-tab-btn" onclick="showDay('Friday', this)">Jumat</button>
            <button class="day-tab-btn" onclick="showDay('Saturday', this)">Sabtu</button>
        </div>

        <!-- Schedule Cards Per Day -->
        <div id="scheduleContainer">
            @php
              $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
                       'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 
                       'Saturday' => 'Sabtu'];
              $groupedJad = $jad->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->tanggal)->format('l');
              });
              
              // Filter ujian yang ready saja
              $readyUjian = $uji->where('status', 'ready');
            @endphp

            @foreach($days as $enDay => $idDay)
            <div class="day-card" data-day="{{$enDay}}">
                <div class="day-header {{strtolower($enDay)}}" onclick="toggleDay('{{$enDay}}')">
                    <div>
                        <span class="is-size-5 has-text-weight-bold">{{$idDay}}</span>
                        <span class="tag is-light ml-2">
                            {{ isset($groupedJad[$enDay]) ? $groupedJad[$enDay]->count() : 0 }} Jadwal
                        </span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="day-content" id="content-{{$enDay}}">
                    <!-- Existing Schedules -->
                    @if(isset($groupedJad[$enDay]) && $groupedJad[$enDay]->count() > 0)
                      @foreach($groupedJad[$enDay] as $jd)
                      @php
                        // Cek bentrok dengan jadwal lain
                        $bentrok = false;
                        foreach($groupedJad[$enDay] as $other) {
                          if($other->id != $jd->id) {
                            $start1 = \Carbon\Carbon::parse($jd->tanggal);
                            $end1 = \Carbon\Carbon::parse($jd->waktu_selesai);
                            $start2 = \Carbon\Carbon::parse($other->waktu_mulai);
                            $end2 = \Carbon\Carbon::parse($other->waktu_selesai);
                            
                            if($start1 < $end2 && $end1 > $start2) {
                              $bentrok = true;
                              break;
                            }
                          }
                        }
                      @endphp
                      <div class="schedule-item {{$bentrok ? 'conflict' : ''}}" data-pengawas="{{$jd->pengawas->guru_id}}">
                          <div class="level" style="flex-wrap: wrap;">
                              <div class="level-left">
                                  <div>
                                      <div class="time-badge mb-2">
                                          <i class="fas fa-clock"></i> 
                                          {{ \Carbon\Carbon::parse($jd->waktu_mulai)->format('H:i') }} - 
                                          {{ \Carbon\Carbon::parse($jd->waktu_selesai)->format('H:i') }}
                                          @if($bentrok)
                                              <span class="conflict-indicator">
                                                  <i class="fas fa-exclamation-triangle"></i> Bentrok!
                                              </span>
                                          @endif
                                      </div>
                                      <h5 class="title is-5 mb-1">{{$jd->ujian->nama_ujian}}</h5>
                                      <div class="tags">
                                          <span class="tag is-info is-light">
                                              <i class="fas fa-chalkboard-teacher"></i> {{$jd->pengawas->guru->nama}}
                                          </span>
                                          <span class="tag is-warning is-light">
                                              <i class="fas fa-hourglass-half"></i> {{$jd->ujian->durasi}} Menit
                                          </span>
                                          <span class="tag is-primary is-light">
                                              <i class="fas fa-sort-numeric-up"></i> Jam ke-{{$jd->jam_mapel}}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="level-right">
                                  <div class="buttons are-small">
                                      <button class="button is-warning" onclick="editSchedule({{$jd->id}})">
                                          <i class="fas fa-edit"></i>
                                      </button>
                                      <button class="button is-danger" onclick="deleteSchedule({{$jd->id}})">
                                          <i class="fas fa-trash"></i>
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      @endforeach
                    @else
                      <div class="empty-day">
                          <i class="fas fa-calendar-times"></i>
                          <p>Belum ada jadwal ujian untuk hari {{$idDay}}</p>
                          <p class="is-size-7 mt-2">Klik form di bawah untuk menambah jadwal</p>
                      </div>
                    @endif

                    <!-- Add Schedule Form for this Day -->
                    <div class="add-schedule-form">
                        <h5 class="title is-6 mb-3">
                            <i class="fas fa-plus-circle" style="color: #2e5b9a;"></i> 
                            Tambah Jadwal {{$idDay}}
                        </h5>
                        
                        <form action="{{route('admin-ops.sav')}}" method="post" onsubmit="return validateForm(this, '{{$enDay}}')">
                            @csrf
                            
                            <div class="columns is-multiline is-variable is-3">
                                <div class="column is-3">
                                    <div class="field">
                                        <label class="label is-small">Jam Ke-</label>
                                        <div class="control">
                                            <input type="number" class="input is-small" name="jam_mapel" min="1" placeholder="Contoh: 1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-3">
                                    <div class="field">
                                        <label class="label is-small">Jam Mulai</label>
                                        <div class="control">
                                            <input type="time" class="input is-small" name="waktu_mulai" 
                                                   value="08:00" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-3">
                                    <div class="field">
                                        <label class="label is-small">Tanggal</label>
                                        <div class="control">
                                            <input type="date" class="input is-small" name="tanggal" 
                                                   value="{{ now()->format('Y-m-d') }}" 
                                                   onchange="updateDay('{{$enDay}}', this.value)" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-3">
                                    <div class="field">
                                        <label class="label is-small">Pilih Ujian</label>
                                        <div class="control">
                                            <div class="select is-small is-fullwidth">
                                                <select name="ujian_id" required>
                                                    <option value="">Pilih Ujian</option>
                                                    @foreach($readyUjian as $uj)
                                                      <option value="{{$uj->id}}" data-durasi="{{$uj->durasi}}">
                                                          {{$uj->nama_ujian}} ({{$uj->durasi}} menit) - {{$uj->mapels->nama_mapel}}
                                                      </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-3">
                                    <div class="field">
                                        <label class="label is-small">Pengawas</label>
                                        <div class="control">
                                            <div class="select is-small is-fullwidth">
                                                <select name="guru_id" required>
                                                    <option value="">Pilih Pengawas</option>
                                                    @foreach($gur as $gu)
                                                    <option value="{{$gu->id}}">{{$gu->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <input type="hidden" name="kelas_id" value="{{$klas->id}}">
                                    <input type="hidden" name="hari" value="{{$enDay}}">
                                    
                                    <div class="field is-grouped is-grouped-right mt-3">
                                        <div class="control">
                                            <button type="reset" class="button is-small is-light">
                                                <i class="fas fa-undo"></i> Reset
                                            </button>
                                        </div>
                                        <div class="control">
                                            <button type="submit" class="button is-small is-primary" style="background: #2e5b9a;">
                                                <i class="fas fa-save"></i> Simpan untuk {{$idDay}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer"></div>

<script>
    // ========== KONFIGURASI ==========
    const CSRF_TOKEN = '{{csrf_token()}}';
    const KELAS_ID = {{$klas->id}};
    let currentDay = 'all';
    
    // ========== FUNGSI TOAST NOTIFICATION ==========
    function showToast(message, type = 'success') {
      const container = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = `toast-notification ${type}`;
      toast.innerHTML = `
        <div class="is-flex is-align-items-center">
          <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
          <span>${message}</span>
        </div>
      `;
      
      container.appendChild(toast);
      
      setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s reverse';
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
    
    // ========== FUNGSI NAVIGASI ==========
    function toggleDay(day) {
      const content = document.getElementById(`content-${day}`);
      const icon = content.previousElementSibling.querySelector('i');
      
      if (content.classList.contains('active')) {
        content.classList.remove('active');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
      } else {
        content.classList.add('active');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
      }
    }
    
    function showDay(day, element) {
      document.querySelectorAll('.day-tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      element.classList.add('active');
      
      currentDay = day;
      
      if (day === 'all') {
        document.querySelectorAll('.day-card').forEach(card => {
          card.style.display = 'block';
        });
      } else {
        document.querySelectorAll('.day-card').forEach(card => {
          if (card.dataset.day === day) {
            card.style.display = 'block';
            document.getElementById(`content-${day}`).classList.add('active');
          } else {
            card.style.display = 'none';
          }
        });
      }
      
      applyFilters();
    }
    
    // ========== FUNGSI FILTER ==========
    function applyFilters() {
      const showOnlyReady = document.getElementById('showOnlyReady')?.checked;
      const showConflicts = document.getElementById('showConflicts')?.checked;
      const filterPengawas = document.getElementById('filterPengawas')?.value;
      
      document.querySelectorAll('.schedule-item').forEach(item => {
        let show = true;
        
        if (showConflicts && !item.classList.contains('conflict')) {
          show = false;
        }
        
        if (filterPengawas && item.dataset.pengawas !== filterPengawas) {
          show = false;
        }
        
        item.style.display = show ? 'block' : 'none';
      });
    }
    
    // ========== VALIDASI TANGGAL ==========
    function updateDay(expectedDay, dateString) {
      const date = new Date(dateString + 'T12:00:00');
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const actualDay = days[date.getDay()];
      
      if (actualDay !== expectedDay) {
        if (!confirm(`Tanggal yang dipilih adalah hari ${getIndonesianDay(actualDay)}.\nApakah Anda yakin ingin menambah jadwal di hari ${getIndonesianDay(expectedDay)}?`)) {
          event.target.value = '{{ now()->format('Y-m-d') }}';
        }
      }
    }
    
    function getIndonesianDay(day) {
      const days = {
        'Sunday': 'Minggu', 'Monday': 'Senin', 'Tuesday': 'Selasa',
        'Wednesday': 'Rabu', 'Thursday': 'Kamis', 'Friday': 'Jumat', 'Saturday': 'Sabtu'
      };
      return days[day] || day;
    }
    
    // ========== VALIDASI FORM ==========
    function validateForm(form, expectedDay) {
      const jamMapel = form.querySelector('[name="jam_mapal"]').value;
      const jamMulai = form.querySelector('[name="waktu_mulai"]').value;
      const tanggal = form.querySelector('[name="tanggal"]').value;
      const ujian = form.querySelector('[name="ujian_id"]').value;
      const guru = form.querySelector('[name="guru_id"]').value;
      
      if (!jamMapel || !jamMulai || !tanggal || !ujian || !guru) {
        showToast('Semua field harus diisi!', 'error');
        return false;
      }
      
      if (jamMapel < 1 || jamMapel > 20) {
        showToast('Jam ke- harus antara 1 - 20', 'error');
        return false;
      }
      
      const selectedDate = new Date(tanggal + 'T00:00:00');
      const today = new Date();
      today.setHours(0,0,0,0);
      
      if (selectedDate < today) {
        showToast('Tanggal tidak boleh kurang dari hari ini!', 'error');
        return false;
      }
      
      const dayCard = form.closest('.day-card');
      const existingSchedules = dayCard.querySelectorAll('.schedule-item');
      const selectedDateTime = new Date(tanggal + 'T' + jamMulai + ':00');
      
      const selectUjian = form.querySelector('[name="ujian_id"]');
      const selectedOption = selectUjian.options[selectUjian.selectedIndex];
      const durasi = parseInt(selectedOption.dataset.durasi || 0);
      const selectedEndTime = new Date(selectedDateTime.getTime() + (durasi * 60000));
      
      for (let schedule of existingSchedules) {
        const timeText = schedule.querySelector('.time-badge').innerText;
        const timeMatch = timeText.match(/(\d{2}:\d{2}) - (\d{2}:\d{2})/);
        
        if (timeMatch) {
          const [, start, end] = timeMatch;
          const scheduleStart = new Date(tanggal + 'T' + start + ':00');
          const scheduleEnd = new Date(tanggal + 'T' + end + ':00');
          
          if (selectedDateTime < scheduleEnd && selectedEndTime > scheduleStart) {
            if (!confirm('Jadwal ini bentrok dengan jadwal existing!\nTetap simpan?')) {
              return false;
            }
          }
        }
      }
      
      return confirm('Apakah Anda yakin dengan jadwal ini?');
    }
    
    // ========== CRUD OPERATIONS ==========
    function editSchedule(id) {
      showToast('Fitur edit akan segera tersedia');
    }
    
    function deleteSchedule(id) {
      if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span>';
        btn.disabled = true;
        
        fetch(`/admin-ops/jadwal/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const scheduleItem = btn.closest('.schedule-item');
            scheduleItem.style.animation = 'slideIn 0.3s reverse';
            setTimeout(() => {
              scheduleItem.remove();
              showToast('Jadwal berhasil dihapus');
              updateCounters();
            }, 300);
          } else {
            showToast('Gagal menghapus jadwal', 'error');
          }
        })
        .catch(error => {
          showToast('Terjadi kesalahan', 'error');
        })
        .finally(() => {
          btn.innerHTML = originalHtml;
          btn.disabled = false;
        });
      }
    }
    
    function updateCounters() {
      const totalJadwal = document.querySelectorAll('.schedule-item').length;
      document.querySelectorAll('.day-card').forEach(card => {
        const day = card.dataset.day;
        const count = card.querySelectorAll('.schedule-item').length;
        const counter = card.querySelector('.tag.is-light');
        if (counter) {
          counter.innerHTML = `${count} Jadwal`;
        }
      });
    }
    
    function checkAllConflicts() {
      document.querySelectorAll('.day-card').forEach(card => {
        const schedules = card.querySelectorAll('.schedule-item');
        const scheduleList = [];
        
        schedules.forEach(schedule => {
          const timeText = schedule.querySelector('.time-badge').innerText;
          const timeMatch = timeText.match(/(\d{2}:\d{2}) - (\d{2}:\d{2})/);
          
          if (timeMatch) {
            scheduleList.push({
              element: schedule,
              start: timeMatch[1],
              end: timeMatch[2]
            });
          }
        });
        
        for (let i = 0; i < scheduleList.length; i++) {
          for (let j = i + 1; j < scheduleList.length; j++) {
            const a = scheduleList[i];
            const b = scheduleList[j];
            
            if (a.start < b.end && a.end > b.start) {
              a.element.classList.add('conflict');
              b.element.classList.add('conflict');
              
              if (!a.element.querySelector('.conflict-indicator')) {
                const badge = a.element.querySelector('.time-badge');
                badge.innerHTML += `<span class="conflict-indicator"><i class="fas fa-exclamation-triangle"></i> Bentrok!</span>`;
              }
            }
          }
        }
      });
    }
    
    // ========== INITIALIZATION ==========
    window.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.day-content').forEach(content => {
        content.classList.add('active');
      });
      
      checkAllConflicts();
      
      document.getElementById('showOnlyReady')?.addEventListener('change', applyFilters);
      document.getElementById('showConflicts')?.addEventListener('change', applyFilters);
      document.getElementById('filterPengawas')?.addEventListener('change', applyFilters);
      
      const today = new Date().toISOString().split('T')[0];
      document.querySelectorAll('input[type="date"]').forEach(input => {
        input.min = today;
      });
      
      // User Dropdown
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
      
      // Mobile Sidebar
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
    });
</script>

</body>
</html>