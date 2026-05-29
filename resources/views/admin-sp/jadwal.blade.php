<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Penjadwalan Ujian - {{$klas->nama_kelas}} | Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">

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
       background: #ffffff96;
     border-left : 4px solid #fff;
     color:#2e5b9a;
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
        background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .day-header:hover {
        filter: brightness(1.1);
    }
    
    /* Warna elegan senada tema (Tidak pelangi) */
    .day-header.sunday { background: linear-gradient(135deg, #3a6db5 0%, #6b83b8 100%); }
    .day-header.monday { background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%); }
    .day-header.tuesday { background: linear-gradient(135deg, #26538e 0%, #4e659d 100%); }
    .day-header.wednesday { background: linear-gradient(135deg, #4475be 0%, #7a90c2 100%); }
    .day-header.thursday { background: linear-gradient(135deg, #2a5393 0%, #566faa 100%); }
    .day-header.friday { background: linear-gradient(135deg, #365fa8 0%, #6079b4 100%); }
    .day-header.saturday { background: linear-gradient(135deg, #4266a5 0%, #7489bc 100%); }
    
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
        border-left: 5px solid #2e5b9a;
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
    .swal2-popup {
    font-family: 'Segoe UI', sans-serif;
    padding: 24px !important;
}

.swal2-confirm {
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 10px 20px !important;
}

.swal2-cancel {
    border-radius: 12px !important;
    font-weight: 600 !important;
    color: #444 !important;
}

.swal2-input:focus,
.swal2-select:focus {
    border-color: #2e5b9a !important;
    box-shadow: 0 0 0 3px rgba(46,91,154,0.15) !important;
}
.swal-title-custom{
    margin-bottom: 8px !important;
    padding-bottom: 0 !important;
}

.swal-html-custom{
    margin-top: 0 !important;
    padding-top: 0 !important;
}
.schedule-count {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    backdrop-filter: blur(4px);
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
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="user-name">
                @if(isset($panitia))
                    <span>{{ $panitia->nama ?? 'Panitia' }}</span>
                @else
                    
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
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn logout-button" style="width: 100%; background: none; border: none; cursor: pointer;">
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
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-button" style="width: 100%; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        
        
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
             $readyUjian = \App\Models\Ujian::where('status', 'ready')
    ->whereHas('kelas', function ($q) use ($klas) {
        $q->where('kelas.id', $klas->id);
    })
    ->whereDoesntHave('jadwal', function ($q) use ($klas) {
        $q->where('kelas_id', $klas->id);
    })
    ->get();
            @endphp

            @foreach($days as $enDay => $idDay)
            <div class="day-card" data-day="{{$enDay}}">
                <div class="day-header {{strtolower($enDay)}}" onclick="toggleDay('{{$enDay}}')">
                    <div>
                        <span class="is-size-5 has-text-weight-bold">{{$idDay}}</span>
                            <span class="schedule-count">
    <i class="fas fa-calendar-check"></i>
    {{ isset($groupedJad[$enDay]) ? $groupedJad[$enDay]->count() : 0 }} 
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
                          if($jd->tanggal != $other->tanggal) {
        continue;
    }

    if($other->id != $jd->id) {
        $start1_dt = \Carbon\Carbon::parse($jd->waktu_mulai);
$end1_dt   = \Carbon\Carbon::parse($jd->waktu_selesai);

$start2_dt = \Carbon\Carbon::parse($other->waktu_mulai);
$end2_dt   = \Carbon\Carbon::parse($other->waktu_selesai);
        if($start1_dt < $end2_dt && $end1_dt > $start2_dt) {
            $bentrok = true;
            break;
        }
    }
                          
                        }
                      @endphp
                      <!-- DATA ID PENTING UNTUK FITUR EDIT & DELETE -->
                      <div class="schedule-item {{$bentrok ? 'conflict' : ''}}" data-id="{{$jd->id}}" data-pengawas="{{$jd->pengawas->guru_id ?? 0}}" data-tanggal="{{$jd->tanggal}}">
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
                                      <h5 class="title is-5 " style="margin-top:20px;">{{$jd->ujian->nama_ujian ?? "Ujian Undefined"}}</h5>
                                      <div class="tags">
                                          <span class="tag is-info is-light">
                                              <i class="fas fa-chalkboard-teacher" style="margin-right:6px;"></></i> {{$jd->pengawas->guru->nama ?? "Pengawas Misterius"}}
                                          </span>
                                          <span class="tag is-warning is-light">
                                              <i class="fas fa-hourglass-half" style="margin-right:6px;"></></i> {{$jd->ujian->durasi ?? "10 "}} Menit
                                          </span>
                                          <span class="tag is-primary is-light">
                                              <i class="fas fa-sort-numeric-up" style="margin-right:6px;"></i> Jam ke-{{$jd->jam_mapel}}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="level-right">
                                  <div class="buttons are-small">
                                      <!-- Tombol Edit -->
                                      <button class="button is-warning" onclick="editSchedule({{$jd->id}})">
                                          <i class="fas fa-edit"></i>
                                      </button>
                                      <!-- Tombol Hapus -->
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
                    <div style="
    background:#ffffff;
    border:1px solid #eef2f6;
    border-radius:16px;
    padding:18px;
    margin-top:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.04);
">

    <!-- HEADER MINI -->
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:14px;
    ">
        <div style="font-weight:700;color:#2e5b9a;">
            + Tambah Jadwal {{ $idDay }}
        </div>

        <div style="
            font-size:0.7rem;
            color:#9ca3af;
        ">
            Auto assign system
        </div>
    </div>

    <form action="{{route('admin-ops.sav')}}" method="post"
          onsubmit="return validateForm(this, '{{$enDay}}')">
        @csrf

        <!-- GRID INPUT -->
        <div style="
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:12px;
        ">

            <!-- JAM MULAI -->
            <div>
                <label style="font-size:0.75rem;color:#6b7280;">Jam Mulai</label>
                <input type="time" name="waktu_mulai" value="08:00" required
                    style="
                        width:100%;
                        padding:10px 12px;
                        border-radius:10px;
                        border:1px solid #e5e7eb;
                        outline:none;
                    ">
            </div>

            <!-- TANGGAL -->
            <div>
                <label style="font-size:0.75rem;color:#6b7280;">Tanggal</label>
                <select name="tanggal" class="tanggal-select"
                        data-day="{{$enDay}}" required
                        style="
                            width:100%;
                            padding:10px 12px;
                            border-radius:10px;
                            border:1px solid #e5e7eb;
                            background:white;
                        ">
                    <option value="">Pilih Tanggal</option>
                </select>
            </div>

            <!-- UJIAN -->
            <div>
                <label style="font-size:0.75rem;color:#6b7280;">Ujian</label>
                <select name="ujian_id" required
                        style="
                            width:100%;
                            padding:10px 12px;
                            border-radius:10px;
                            border:1px solid #e5e7eb;
                            background:white;
                        ">
                    <option value="">Pilih Ujian</option>
                    @foreach($readyUjian as $uj)
                        <option value="{{$uj->id}}" data-durasi="{{$uj->durasi}}">
                            {{$uj->nama_ujian}} ({{$uj->durasi}}m)
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- FOOTER ACTION -->
        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-top:16px;
        ">

            <input type="hidden" name="kelas_id" value="{{$klas->id}}">
            <input type="hidden" name="hari" value="{{$enDay}}">

            <button type="reset" style="
                padding:8px 14px;
                border-radius:10px;
                border:1px solid #e5e7eb;
                background:#f9fafb;
                cursor:pointer;
            ">
                Reset
            </button>

            <button type="submit" style="
                padding:10px 16px;
                border-radius:10px;
                background:#2e5b9a;
                color:white;
                border:none;
                font-weight:600;
                cursor:pointer;
                box-shadow:0 4px 10px rgba(46,91,154,0.2);
            ">
                Simpan Jadwal
            </button>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
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

<script>
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
    // ========== FUNGSI OTOMATIS JAM KE- ==========
       // ========== FUNGSI OTOMATIS JAM KE- (DIPERBAIKI) ==========
    function updateAutoJamMapel() {
        document.querySelectorAll('.day-card').forEach(card => {
            // Ambil nilai tanggal dari input form "Tambah Jadwal" di kartu ini
            const dateInput = card.querySelector('input[name="tanggal"]');
            const currentDate = dateInput ? dateInput.value : '';
            
            const jamInput = card.querySelector('.auto-jam');
            
            if (jamInput) {
                let count = 0;
                if (currentDate) {
                    // Filter: Hitung HANYA jadwal yang tanggalnya SAMA dengan input tanggal
                    const matchingItems = card.querySelectorAll(`.schedule-item[data-tanggal="${currentDate}"]`);
                    count = matchingItems.length;
                }
                
                // Jika ada jadwal di tanggal itu, lanjutkan hitungan. Jika tidak, mulai dari 1.
                jamInput.value = count > 0 ? count + 1 : 1;
            }
        });
    }

    // ========== VALIDASI TANGGAL (DIPERBAIKI) ==========
    function updateDay(expectedDay, dateString) {
      const date = new Date(dateString + 'T12:00:00');
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const actualDay = days[date.getDay()];
      
      // --- LOGIC BARU: UPDATE JAM MAPEL BERDASARKAN TANGGAL ---
      const form = event.target.closest('form');
      const jamInput = form.querySelector('.auto-jam');
      const dayCard = form.closest('.day-card');

      if (jamInput && dateString) {
          // Cari semua jadwal di kartu ini yang tanggalnya SAMA dengan inputan user
          const sameDateSchedules = dayCard.querySelectorAll(`.schedule-item[data-tanggal="${dateString}"]`);
          
          if (sameDateSchedules.length > 0) {
              jamInput.value = sameDateSchedules.length + 1;
          } else {
              jamInput.value = 1; // Default 1 jika tanggal beda / belum ada jadwal
          }
      }
      // ------------------------------------------------------

      if (actualDay !== expectedDay) {
        if (!confirm(`Tanggal yang dipilih adalah hari ${getIndonesianDay(actualDay)}.\nApakah Anda yakin ingin menambah jadwal di hari ${getIndonesianDay(expectedDay)}?`)) {
          event.target.value = '{{ now()->format('Y-m-d') }}';
          
          // Trigger ulang hitungan jam mapel jika user membatalkan (kembali ke tanggal hari ini)
          updateAutoJamMapel();
        }
      }
    }

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
    function generateDatesByDay() {
    const dayMap = {
        Sunday: 0,
        Monday: 1,
        Tuesday: 2,
        Wednesday: 3,
        Thursday: 4,
        Friday: 5,
        Saturday: 6
    };

    document.querySelectorAll('.tanggal-select').forEach(select => {

        const targetDay = select.dataset.day;
        const targetDayNumber = dayMap[targetDay];

        select.innerHTML = '<option value="">Pilih Tanggal</option>';

        const today = new Date();

        // generate 12 minggu ke depan
        for (let i = 0; i < 84; i++) {

            const d = new Date();
            d.setDate(today.getDate() + i);

            if (d.getDay() === targetDayNumber) {

                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');

                const value = `${yyyy}-${mm}-${dd}`;

                const indoDate = d.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                select.innerHTML += `
                    <option value="${value}">
                        ${indoDate}
                    </option>
                `;
            }
        }
    });
}
    function applyFilters() {
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
    
    
    function getIndonesianDay(day) {
      const days = {
        'Sunday': 'Minggu', 'Monday': 'Senin', 'Tuesday': 'Selasa',
        'Wednesday': 'Rabu', 'Thursday': 'Kamis', 'Friday': 'Jumat', 'Saturday': 'Sabtu'
      };
      return days[day] || day;
    }
    
    // ========== VALIDASI FORM ==========
    function validateForm(form, expectedDay) {
      const jamMapel = form.querySelector('[name="jam_mapel"]').value;
      const jamMulai = form.querySelector('[name="waktu_mulai"]').value;
      const tanggal = form.querySelector('[name="tanggal"]').value;
      const ujian = form.querySelector('[name="ujian_id"]').value;
      
      if (!jamMapel || !jamMulai || !tanggal || !ujian) {
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
    
    // --- FUNGSI EDIT (Membuka Modal Form SweetAlert) ---
    function editSchedule(id) {
        // 1. Ambil data jadwal yang diklik dari HTML
        const item = document.querySelector(`.schedule-item[data-id="${id}"]`);
        
        // Parse waktu "08:00 - 10:00"
        const timeText = item.querySelector('.time-badge').innerText.replace('WIB', '').trim();
        const [start, end] = timeText.split(' - ').map(t => t.trim());
        
        const namaUjian = item.querySelector('h5').innerText;
        const jamKe = item.querySelector('.tag.is-primary').innerText.replace('Jam ke-', '');

        // 2. Ambil opsi ujian dari form "Tambah" di bawah agar dinamis
        const addFormSelect = document.querySelector('select[name="ujian_id"]');
        let ujianOptions = '';
        if(addFormSelect) {
            Array.from(addFormSelect.options).forEach(opt => {
                if(opt.value) ujianOptions += `<option value="${opt.value}" data-durasi="${opt.getAttribute('data-durasi')}">${opt.text}</option>`;
            });
        }

        // 3. Tampilkan Modal Edit
       Swal.fire({
    title: ``,
    
    html: `
     
        <div style="margin-top:5px;text-align:left;">
        <div style="
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 14px;
    border-radius:14px;
    background:linear-gradient(135deg,#eef4ff,#f5f7ff);
    color:#2e5b9a;
    font-weight:700;
    font-size:0.95rem;
    margin-bottom:14px;
    box-shadow:0 2px 8px rgba(46,91,154,0.08);
">

    <div style="
        width:34px;
        height:34px;
        border-radius:10px;
        background:#2e5b9a;
        display:flex;
        align-items:center;
        justify-content:center;
        color:white;
        font-size:14px;
    ">
        <i class="fas fa-pen"></i>
    </div>

    <div>
        Edit Jadwal
        <div style="
            font-size:0.7rem;
            font-weight:500;
            color:#6b7280;
            margin-top:2px;
        ">
            Perbarui waktu & ujian
        </div>
    </div>

</div>
            <!-- Info Jadwal Lama -->
            <div style="
                background:#f8fafc;
                border:1px solid #e5e7eb;
                border-radius:14px;
                padding:14px;
                margin-bottom:18px;
                margin-top:10px;
            ">
                <div style="
                    font-size:0.75rem;
                    color:#888;
                    margin-bottom:6px;
                ">
                    Jadwal Saat Ini
                </div>

                <div style="
                    font-weight:600;
                    color:#1f2937;
                    margin-bottom:8px;
                ">
                    ${namaUjian}
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <span style="
                        background:#eef4ff;
                        color:#2e5b9a;
                        padding:5px 10px;
                        border-radius:999px;
                        font-size:0.75rem;
                        font-weight:600;
                    ">
                        ${start} - ${end}
                    </span>

                    <span style="
                        background:#f3f4f6;
                        color:#444;
                        padding:5px 10px;
                        border-radius:999px;
                        font-size:0.75rem;
                        font-weight:600;
                    ">
                        Jam ke-${jamKe}
                    </span>
                </div>
            </div>

            <!-- Pilih Ujian -->
            <div style="margin-bottom:14px;">
                <label style="
                    display:block;
                    font-size:0.82rem;
                    font-weight:600;
                    margin-bottom:8px;
                    color:#374151;
                ">
                    Pilih Ujian Baru
                </label>

                <select 
                    id="swal-select-ujian"
                    style="
                        width:100%;
                        height:48px;
                        border:1px solid #dbe3ec;
                        border-radius:12px;
                        padding:0 14px;
                        font-size:0.9rem;
                        outline:none;
                    "
                >
                    <option value="">-- Pilih Ujian --</option>
                    ${ujianOptions}
                </select>
            </div>

            <!-- Grid -->
            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:14px;
            ">
                <div>
                    <label style="
                        display:block;
                        font-size:0.82rem;
                        font-weight:600;
                        margin-bottom:8px;
                        color:#374151;
                    ">
                        Jam Mulai
                    </label>

                    <input 
                        type="time"
                        id="swal-input2"
                        value="${start}"
                        style="
                            width:100%;
                            height:48px;
                            border:1px solid #dbe3ec;
                            border-radius:12px;
                            padding:0 14px;
                            font-size:0.9rem;
                            outline:none;
                        "
                    >
                </div>

                <div>
                    <label style="
                        display:block;
                        font-size:0.82rem;
                        font-weight:600;
                        margin-bottom:8px;
                        color:#374151;
                    ">
                        Jam Ke-
                    </label>

                    <input 
                        type="number"
                        id="swal-input3"
                        value="${jamKe}"
                        style="
                            width:100%;
                            height:48px;
                            border:1px solid #dbe3ec;
                            border-radius:12px;
                            padding:0 14px;
                            font-size:0.9rem;
                            outline:none;
                        "
                    >
                </div>
            </div>

        </div>
    `,

    width: 650,
    background: '#ffffff',
    borderRadius: '22px',

    showCancelButton: true,

    confirmButtonText: `
        <i class="fas fa-save"></i>
        Simpan
    `,

    cancelButtonText: `
        <i class="fas fa-times"></i>
        Batal
    `,

    confirmButtonColor: '#2e5b9a',
    cancelButtonColor: '#e5e7eb',

   customClass: {
    title: 'swal-title-custom',
    htmlContainer: 'swal-html-custom',
    popup: 'animated fadeInDown',
    confirmButton: 'swal-confirm-modern',
    cancelButton: 'swal-cancel-modern'
},

    buttonsStyling: true,

    focusConfirm: false,

    preConfirm: () => {
        return {
            ujian_id: document.getElementById('swal-select-ujian').value,
            waktu_mulai: document.getElementById('swal-input2').value,
            jam_mapel: document.getElementById('swal-input3').value,
        }
    }
})
    }

    function updateScheduleAjax(id, data) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mengecek ketersediaan pengawas...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Ambil tanggal dari input date di form "Tambah" (hari yang sama)
        const dayCard = document.querySelector(`.schedule-item[data-id="${id}"]`).closest('.day-card');
        const dateInput = dayCard.querySelector('input[name="tanggal"]');
        const tanggal = dateInput ? dateInput.value : new Date().toISOString().split('T')[0];

        const formData = new FormData();
        formData.append('ujian_id', data.ujian_id);
        formData.append('waktu_mulai', data.waktu_mulai);
        formData.append('jam_mapel', data.jam_mapel);
        formData.append('tanggal', tanggal);
        formData.append('kelas_id', KELAS_ID);
        formData.append('_token', CSRF_TOKEN);

        // Request ke Route yang baru dibuat
        fetch(`/admin-ops/jadwal/update/${id}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Jika sukses, reload halaman agar data guru/ujian baru tampil
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message + (data.teacher_name ? `\nPengawas: ${data.teacher_name}` : ''),
                    confirmButtonColor: '#2e5b9a'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error');
            console.error(error);
        });
    }


    // --- FUNGSI DELETE (Menggunakan Konfirmasi SweetAlert) ---
   function deleteSchedule(id) {
    // 1. Ambil referensi elemen DOM di awal (sebelum fetch)
    // agar kita yakin bisa mengakses tombol/jadwal untuk animasi nanti.
    const btn = event.target.closest('button');
    const scheduleItem = btn.closest('.schedule-item');

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Jadwal akan dihapus dan status ujian kembali ke draft!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });

            fetch(`/admin-ops/jadwal/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                // 2. Cek Status HTTP dulu
                if (!response.ok) {
                    // Jika status 500 (Error Server) atau 404, lempar ke catch
                    throw new Error(`HTTP Error! Status: ${response.status}`);
                }
                
                // 3. Coba parse JSON. Jika body HTML (PHP Crash), ini akan error (SyntaxError)
                // dan akan tertangkap di blok .catch di bawah.
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Animasi Hapus
                    scheduleItem.style.transition = 'all 0.5s ease';
                    scheduleItem.style.transform = 'translateX(100px)';
                    scheduleItem.style.opacity = '0';
                    
                    setTimeout(() => {
                        scheduleItem.remove();
                        updateCounters();
                        updateAutoJamMapel();
                        
                        Swal.fire(
                            'Terhapus!',
                            data.message,
                            'success'
                        );
                    }, 500);
                } else {
                    // Validasi gagal dari logic (misal: "Jadwal tidak ditemukan")
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Detail Error JavaScript:', error);
                
                // Ini yang penting. Jika DB terhapus tapi muncul alert ini,
                // berarti Response Server bukan JSON valid (kemungkinan Fatal Error PHP)
                Swal.fire(
                    'Gagal Proses Data', 
                    'Terjadi kesalahan koneksi atau server. Cek Console (F12).', 
                    'error'
                );
                
                // Opsional: Reload halaman paksa jika terjadi error ini agar tampilan sinkron
                // setTimeout(() => location.reload(), 2000);
            });
        }
    });
}
    
    function updateCounters() {
      document.querySelectorAll('.day-card').forEach(card => {
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
        
        // 1. Kumpulkan data lengkap (Termasuk Tanggal)
        schedules.forEach(schedule => {
          const timeText = schedule.querySelector('.time-badge').innerText;
          const timeMatch = timeText.match(/(\d{2}:\d{2}) - (\d{2}:\d{2})/);
          
          if (timeMatch) {
            scheduleList.push({
              element: schedule,
              date: schedule.dataset.tanggal, // AMBIL DATA TANGGAL
              start: timeMatch[1],
              end: timeMatch[2]
            });
          }
        });
        
        // 2. Cek Bentrok
        for (let i = 0; i < scheduleList.length; i++) {
          for (let j = i + 1; j < scheduleList.length; j++) {
            const a = scheduleList[i];
            const b = scheduleList[j];
            
            // --- PERBAIKAN UTAMA: LEWATI JIKA TANGGAL BEDA ---
            if (a.date !== b.date) {
                continue; 
            }
            // ------------------------------------------------
            
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
        generateDatesByDay();
      document.querySelectorAll('.day-content').forEach(content => {
        content.classList.add('active');
      });
      
      checkAllConflicts();
      updateAutoJamMapel(); // SUDAH DITAMBAHKAN SAAT LOAD
      
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