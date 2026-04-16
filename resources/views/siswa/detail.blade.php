<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Detail Jawaban - {{ $peserta->ujian->nama_ujian ?? 'Ujian' }}</title>

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
    
    padding: 24px;
    transition: margin-left 0.3s ease;
    width: calc(100% - 260px);
}

/* Mobile Toggle */
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

/* ===== BACK BUTTON ===== */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2e5b9a;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 20px;
    padding: 8px 0;
    transition: color 0.2s ease;
}

.back-button:hover {
    color: #1e3a6b;
}

.back-button i {
    font-size: 1rem;
}

/* ===== PAGE TITLE ===== */
.page-title {
    margin-bottom: 24px;
}

.page-title h1 {
    color: #2e5b9a;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.page-title p {
    color: #666;
    font-size: 0.9rem;
}

/* ===== SUMMARY CARD ===== */
.summary-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
}

.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.summary-header h3 {
    color: #2e5b9a;
    font-weight: 600;
    font-size: 1.1rem;
}

.nilai-badge {
    background: #2e5b9a;
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1.2rem;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 10px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.stat-label {
    font-size: 0.8rem;
    color: #666;
}

.stat-item.benar .stat-number { color: #22c55e; }
.stat-item.salah .stat-number { color: #ef4444; }

/* ===== FILTER BAR ===== */
.filter-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    background: white;
    border: 1px solid #e5e7eb;
    color: #666;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    background: #f3f4f6;
}

.filter-btn.active {
    background: #2e5b9a;
    color: white;
    border-color: #2e5b9a;
}

/* ===== JAWABAN LIST ===== */
.jawaban-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.jawaban-item {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.jawaban-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.jawaban-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.nomor-soal {
    width: 35px;
    height: 35px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #2e5b9a;
    font-size: 1rem;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-badge.benar {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.salah {
    background: #fee2e2;
    color: #dc2626;
}

.jenis-soal {
    margin-left: auto;
    font-size: 0.75rem;
    color: #888;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 20px;
}

.soal-text {
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.6;
    padding: 5px 0;
}

/* Jawaban Section */
.jawaban-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 15px;
}

.jawaban-box {
    padding: 15px;
    border-radius: 10px;
    background: #fafbfc;
    border: 1px solid #e5e7eb;
}

.jawaban-box.jawaban-siswa {
    border-left: 3px solid #2e5b9a;
}

.jawaban-box.jawaban-benar {
    border-left: 3px solid #22c55e;
}

.jawaban-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #888;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.jawaban-label i {
    font-size: 0.8rem;
}

.jawaban-value {
    font-size: 1rem;
    color: #333;
    font-weight: 500;
}

.jawaban-value.empty {
    color: #999;
    font-style: italic;
    font-weight: 400;
}

/* Opsi Pilihan Ganda */
.opsi-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: 5px;
}

.opsi-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 0;
    font-size: 0.9rem;
    color: #555;
}

.opsi-letter {
    font-weight: 700;
    min-width: 25px;
    color: #2e5b9a;
}

.opsi-item.selected {
    color: #2e5b9a;
    font-weight: 500;
}

.opsi-item.selected .opsi-letter {
    color: #2e5b9a;
}

.opsi-item.correct {
    color: #16a34a;
}

.opsi-item.correct .opsi-letter {
    color: #16a34a;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.empty-state i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 15px;
}

.empty-state p {
    color: #999;
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header h2 span { display: none; }
    .user-name span { display: none; }
    .user-name i { display: none; }
    .user-avatar { width: 32px; height: 32px; }
    
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 16px;
    }
    
    .mobile-toggle { display: flex; }
    
    .summary-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .jawaban-section {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .summary-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .summary-stats {
        grid-template-columns: 1fr;
    }
    
    .filter-bar {
        justify-content: center;
    }
    
    .jawaban-header {
        flex-wrap: wrap;
    }
    
    .jenis-soal {
        margin-left: 0;
        width: 100%;
        text-align: center;
    }
}

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: #f1f1f1; }
::-webkit-scrollbar-thumb { background: #5c6fa6; border-radius: 3px; }
</style>
</head>

<body>

<!-- Header -->
<header class="header">
    <h2>
        <i class="fas fa-graduation-cap"></i>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name">
                <span>{{ $ire->nama ?? 'Siswa' }}</span>
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
                <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%; background: none; border: none;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Toggle -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Back Button -->
        <a href="{{ route('siswa.riwayat') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>
        
        <!-- Page Title -->
        <div class="page-title">
            <h1>{{ $peserta->ujian->nama_ujian ?? 'Detail Ujian' }}</h1>
            <p>
                <i class="fas fa-book-open"></i> {{ $peserta->ujian->mapels->nama_mapel ?? '-' }} • 
                <i class="fas fa-user"></i> {{ $peserta->siswa->nama }} • 
                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($peserta->updated_at)->format('d/m/Y H:i') }}
            </p>
        </div>
        
        <!-- Summary Card -->
        <div class="summary-card">
            <div class="summary-header">
                <h3><i class="fas fa-chart-bar"></i> Ringkasan Hasil</h3>
                <div class="nilai-badge">
                    {{ number_format($peserta->nilai, 1) }}
                </div>
            </div>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSoal }}</div>
                    <div class="stat-label">Total Soal</div>
                </div>
                <div class="stat-item benar">
                    <div class="stat-number">{{ $jawabanBenar }}</div>
                    <div class="stat-label">Jawaban Benar</div>
                </div>
                <div class="stat-item salah">
                    <div class="stat-number">{{ $jawabanSalah }}</div>
                    <div class="stat-label">Jawaban Salah</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSoal > 0 ? round(($jawabanBenar/$totalSoal)*100) : 0 }}%</div>
                    <div class="stat-label">Nilai</div>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-list"></i> Semua ({{ $totalSoal }})
            </button>
            <button class="filter-btn" data-filter="benar">
                <i class="fas fa-check-circle"></i> Benar ({{ $jawabanBenar }})
            </button>
            <button class="filter-btn" data-filter="salah">
                <i class="fas fa-times-circle"></i> Salah ({{ $jawabanSalah }})
            </button>
        </div>
        
        <!-- Jawaban List -->
        @if($jawaban->count() > 0)
            <div class="jawaban-list" id="jawabanList">
                @foreach($jawaban as $index => $jwb)
                @php
                    $bank = $jwb->bank;
                    $isBenar = $jwb->benar == 1;
                    $isPilihanGanda = !empty($bank->opsi_a);
                    
                    // Parse opsi untuk pilihan ganda
                    $opsiList = [];
                    if($isPilihanGanda) {
                        if($bank->opsi_a) $opsiList['A'] = $bank->opsi_a;
                        if($bank->opsi_b) $opsiList['B'] = $bank->opsi_b;
                        if($bank->opsi_c) $opsiList['C'] = $bank->opsi_c;
                        if($bank->opsi_d) $opsiList['D'] = $bank->opsi_d;
                        if($bank->opsi_e) $opsiList['E'] = $bank->opsi_e;
                    }
                @endphp
                
                <div class="jawaban-item" data-status="{{ $isBenar ? 'benar' : 'salah' }}">
                    <div class="jawaban-header">
                        <div class="nomor-soal">{{ $index + 1 }}</div>
                        <div class="status-badge {{ $isBenar ? 'benar' : 'salah' }}">
                            @if($isBenar)
                                <i class="fas fa-check-circle"></i> Benar
                            @else
                                <i class="fas fa-times-circle"></i> Salah
                            @endif
                        </div>
                        <div class="jenis-soal">
                            <i class="fas fa-{{ $isPilihanGanda ? 'list' : 'pencil' }}"></i>
                            {{ $isPilihanGanda ? 'Pilihan Ganda' : 'Essay' }}
                        </div>
                    </div>
                    
                    <div class="soal-text">
                        {!! nl2br(e($bank->soal ?? 'Soal tidak tersedia')) !!}
                    </div>
                    
                    @if($isPilihanGanda)

                    @else
                        <!-- Tampilan Essay -->
                        <div class="jawaban-section">
                            <div class="jawaban-box jawaban-siswa">
                                
                                <div class="jawaban-value {{ empty($jwb->jawaban) ? 'empty' : '' }}">
                                    @if(empty($jwb->jawaban))
                                        <i>Tidak ada jawaban</i>
                                    @else
                                        {!! nl2br(e($jwb->jawaban)) !!}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="jawaban-box jawaban-benar">
                                <div class="jawaban-label">
                                    <i class="fas fa-key"></i> Kunci Jawaban
                                </div>
                                <div class="jawaban-value">
                Rahasia          
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <p>Tidak ada data jawaban untuk ujian ini.</p>
            </div>
        @endif
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    document.addEventListener('click', () => userDropdown?.classList.remove('active'));
    
    // Mobile Sidebar
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
        const icon = mobileToggle.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    }
    
    mobileToggle?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
    
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const jawabanItems = document.querySelectorAll('.jawaban-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            jawabanItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else {
                    const status = item.dataset.status;
                    item.style.display = status === filter ? 'block' : 'none';
                }
            });
        });
    });
    
    // Close sidebar on link click (mobile)
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
                setTimeout(toggleSidebar, 150);
            }
        });
    });
});
</script>

</body>
</html>