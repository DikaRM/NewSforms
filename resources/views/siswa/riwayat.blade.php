@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section("content")
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script Alert dipindah ke sini agar hanya jalan di dashboard -->
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



<style>

.page-header {
   display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap; /* biar responsive */
    gap: 10px;
    margin-bottom: 20px;
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
    text-align:center;
    gap: 12px;
    padding:20px 50px;
    background: linear-gradient(135deg, #cfe2ff 0%, #b8d4ff 100%);
    color: #2e5b9a;
    border-radius:50px;
    transition:0.3s linear;
}
.buttond a:hover{
    background: #2e5b9a;
    color: #b8d4ff;
    
}

.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.7rem;
}

.score-excellent {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    font-size: 0.9rem;
}

.score-good {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
    font-size: 0.9rem;
}

.score-average {
    background: linear-gradient(135deg, #cfe2ff 0%, #b8d4ff 100%);
    color: #2e5b9a;
    font-size: 0.9rem;
}

.score-low {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    font-size: 0.9rem;
}

.score-value {
    font-size: 0.9rem;
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Card */
.stat-card {
    background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
    color: white;
    padding: 20px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 10px 25px rgba(46, 91, 154, 0.2);
}

/* Icon */
.stat-icon {
    font-size: 2rem;
    background: rgba(255,255,255,0.2);
    padding: 12px;
    border-radius: 12px;
}

/* Text */
.stat-info h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
}

.stat-info p {
    margin: 0;
    font-size: 0.8rem;
    opacity: 0.9;
}
/* Responsive */
@media (max-width: 768px) {
    
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
/* Filter Mode Buttons */
.filter-mode-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.filter-mode-btn i {
    font-size: 0.85rem;
}

.filter-mode-btn:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.filter-mode-btn.active {
    background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(46, 91, 154, 0.3);
}

/* Mode Tag pada Card */
.mode-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.mode-tag.cbt {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #0369a1;
}

.mode-tag.praktik {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: #fff;
}

.mode-tag i {
    font-size: 0.7rem;
}
</style>

        <!-- Page Header -->
        <div class="page-header">
            <div> 
            <h1>
                <i class="fas fa-history"></i>
                Riwayat Ujian
            </h1>
            <p>Lihat riwayat ujian yang telah Anda kerjakan beserta nilai yang diperoleh</p>
</div>
             <div class="date-badge">
                <i class="fas fa-calendar-alt"></i> 
                {{ \Carbon\Carbon::parse(now())->locale("id")->isoFormat('dddd, D MMMM YYYY') }}
            </div>
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
                
            </div>
            <div class="filter-group" style="margin-top: 15px; border-top: 1px solid #eef2f6; padding-top: 15px;">
        <span style="font-size: 0.8rem; color: #64748b; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-tag"></i> Filter Mode:
        </span>
        <button class="filter-mode-btn active" data-mode="all-mode">
            <i class="fas fa-layer-group"></i> Semua Mode
        </button>
        <button class="filter-mode-btn" data-mode="cbt">
            <i class="fas fa-laptop-code"></i> CBT (Computer Based Test)
        </button>
        <button class="filter-mode-btn" data-mode="praktik">
            <i class="fas fa-flask"></i> Praktik
        </button>
    </div>
        </div>

        <!-- Riwayat Section -->
        <div class="section-title">
            <h2>
                <i class="fas fa-list-ol"></i>
                Daftar Riwayat Ujian
            </h2>
            <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama ujian...">
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
                    <div class="riwayat-card" 
     data-filter="{{ $filterCategory }}" 
     data-mode="{{ $dt->ujian->mode ?? 'cbt' }}"
     data-name="{{ strtolower($dt->ujian->nama_ujian ?? '') }}">
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
                                {{ \Carbon\Carbon::parse($dt->created_at)->locale("id")->translatedformat('d M Y') }}
                            </div>
                        </div>
                        <div class="card-body-custom">
                            <div class="exam-title">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $dt->ujian->nama_ujian ?? 'Ujian' }}</span>
                            </div>
                             <div style="margin-bottom: 12px;">
        @php
            $modeUjian = $dt->ujian->mode ?? 'cbt'; // Ambil dari database, default 'cbt'
        @endphp
        <span class="mode-tag {{ $modeUjian == 'cbt' ? 'cbt' : 'praktik' }}">
            <i class="fas {{ $modeUjian == 'cbt' ? 'fa-laptop-code' : 'fa-flask' }}"></i>
            {{ $modeUjian == 'cbt' ? 'CBT (Computer Based Test)' : 'Ujian Praktik' }}
        </span>
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
                                <a class="button mx-auto" href="{{route('siswa.detail',$dt->id)}}">Detail</a>
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
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter elements
    const filterButtons = document.querySelectorAll('.filter-btn');
    const filterModeButtons = document.querySelectorAll('.filter-mode-btn');
    const riwayatCards = document.querySelectorAll('.riwayat-card');
    const searchInput = document.getElementById('searchInput');
    const emptyState = document.getElementById('emptyState');
    const riwayatGrid = document.getElementById('riwayatGrid');
    
    let currentFilter = 'all';      // Filter nilai
    let currentMode = 'all-mode';    // Filter mode ujian
    let currentSearch = '';
    
    function updateDisplay() {
        let visibleCount = 0;
        
        riwayatCards.forEach(card => {
            const filterValue = card.getAttribute('data-filter');
            const modeValue = card.getAttribute('data-mode') || 'cbt';
            const cardName = card.getAttribute('data-name') || '';
            
            let matchesFilter = (currentFilter === 'all' || filterValue === currentFilter);
            let matchesMode = (currentMode === 'all-mode' || modeValue === currentMode);
            let matchesSearch = (currentSearch === '' || cardName.includes(currentSearch.toLowerCase()));
            
            if (matchesFilter && matchesMode && matchesSearch) {
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
        
        if (riwayatCards.length === 0) {
            emptyState.style.display = 'block';
            riwayatGrid.style.display = 'none';
        }
    }
    
    // Filter nilai button click
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            updateDisplay();
        });
    });
    
    // Filter mode button click
    filterModeButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterModeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentMode = this.getAttribute('data-mode');
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
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Sidebar Toggle

    
    
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


@endsection