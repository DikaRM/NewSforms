@extends("layouts.siswa")
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

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

@section("alert")

@endsection
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>
                <i class="fas fa-calendar-check"></i>
                Jadwal Ujian
            </h1>
            <p>Filter jadwal berdasarkan hari untuk melihat ujian yang akan datang</p>
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
                     Semua Hari
                </button>
                <button class="filter-btn" data-day="monday">
                     Senin
                </button>
                <button class="filter-btn" data-day="tuesday">
                     Selasa
                </button>
                <button class="filter-btn" data-day="wednesday">
                    Rabu
                </button>
                <button class="filter-btn" data-day="thursday">
                    Kamis
                </button>
                <button class="filter-btn" data-day="friday">
                     Jumat
                </button>
                <button class="filter-btn" data-day="saturday">
                    Sabtu
                </button>
                <button class="filter-btn" data-day="sunday">
                    Minggu
                </button>
            </div>
        </div>

        <!-- Jadwal Section -->
        <div class="section-title">
            <h2 id="sectionTitle">
                <i class="fas fa-list-ol"></i>
                Semua Jadwal Ujian
            </h2>
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
                        <div class="jadwal-card" data-day="{{ strtolower($day) }}">
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




<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. INTERCEPT LINK KLIK (Smooth Redirect)
    // ==========================================
    const filterButtons = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.jadwal-card');
const emptyState = document.getElementById('emptyState');

filterButtons.forEach(btn => {
    btn.addEventListener('click', function () {
        
        // set active button
        filterButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const selectedDay = this.getAttribute('data-day');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardDay = card.getAttribute('data-day');

            if (selectedDay === 'all' || cardDay === selectedDay) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // handle empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    });
});
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
        

    // ==========================================
    // 2. INTERCEPT FORM SUBMIT (Smooth Post/Logout)
    // ==========================================
    // Khusus untuk form biasa (misal: form logout, form cari)
});
});
</script>
@endsection