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

/* ===== CARDS ===== */
.cards {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.card {
    width: 300px;
    padding: 25px;
    border-radius: 15px;
    color: #333;
    position: relative;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
    text-decoration: none;
    display: block;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin-bottom: 10px;
}

.card p {
    font-size: 14px;
    color: #555;
}

.card .arrow {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 20px;
}

.pink {
    background: #f8d7da;
}

.yellow {
    background: #fff3cd;
}

/* ===== EXAM CARD ===== */
.exam-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2e5b9a;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
}

.exam-card {
    margin-bottom: 20px;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.exam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.exam-card .card-content {
    padding: 20px;
}

.exam-card .media {
    border-bottom: 2px solid #f5f5f5;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.exam-card .title.is-4 {
    color: #2e5b9a;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 5px !important;
}

.exam-card .subtitle.is-6 {
    color: #7f8c8d;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.exam-card .subtitle.is-6 .icon {
    font-size: 0.75rem;
    color: #2e5b9a;
}

.nilai-container {
    text-align: center;
    padding: 15px 0;
}

.nilai-container .title.is-3 {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    color: #27ae60 !important;
    margin: 5px 0 !important;
}

.exam-card .button.is-primary {
    background: #2e5b9a;
    min-width: 160px;
    height: 40px;
    font-weight: 600;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.exam-card .button.is-primary:hover {
    background: #1e3a6b;
    transform: scale(1.02);
}

.tag-custom {
    display: inline-block;
    padding: 6px 14px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 20px;
}

.tag-success {
    background: #d4edda;
    color: #155724;
}

.tag-warning {
    background: #fff3cd;
    color: #856404;
}

.tag-info {
    background: #cfe2ff;
    color: #2e5b9a;
}

.tag-done {
    background: #d1ecf1;
    color: #0c5460;
    font-weight: 600;
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

.notification-error {
    background: #dc3545;
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

/* Responsive */
@media (max-width: 768px) {
    
    
   
    
    .cards {
        gap: 15px;
    }
    
    .card {
        width: calc(50% - 15px);
        min-width: 150px;
        padding: 20px;
    }
    
    .card h3 {
        font-size: 1rem;
    }
    
    .card p {
        font-size: 0.75rem;
    }
    
    .exam-card .title.is-4 {
        font-size: 0.9rem;
    }
    
    .exam-card .button.is-primary {
        min-width: 130px;
        height: 38px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .cards {
        flex-direction: column;
    }
    
    .card {
        width: 100%;
    }
    
    
    
    .exam-card .card-content {
        padding: 15px;
    }
    
    .nilai-container .title.is-3 {
        font-size: 2rem !important;
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
.exam-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    padding-top: 10px;
}

.exam-notice {
    width: 100%;
    padding: 14px 16px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.exam-notice.warning {
    background: #fff7e6;
    color: #d97706;
    border: 1px solid #facc15;
}

.exam-notice.info {
    background: #eef4ff;
    color: #2e5b9a;
    border: 1px solid #bfd4ff;
}

.exam-btn {
    width: 100%;
    max-width: 240px;
    height: 46px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.92rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: 0.25s ease;
}

.exam-btn:hover {
    transform: translateY(-2px);
}

.exam-btn-primary {
    background: #2e5b9a;
    color: white;
}

.exam-btn-warning {
    background: #f59e0b;
    color: white;
}

.exam-status-tag {
    padding: 10px 18px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.exam-status-tag.info {
    background: #eef4ff;
    color: #2e5b9a;
}

.exam-status-tag.warning {
    background: #fff7e6;
    color: #d97706;
}
.exam-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* Mobile */
@media (max-width: 768px) {
    .exam-grid {
        grid-template-columns: 1fr;
    }
}
.exam-btn-start{
    background: #2e5b9a;
    color: white;
    
    height: 38px;
    padding: 0 16px;
    
    border-radius: 10px;
    
    font-size: 0.85rem;
    font-weight: 600;

    display: inline-flex;
    align-items: center;
    gap: 6px;

    transition: 0.2s ease;
}

.exam-btn-start:hover{
    background: #24497d;
    color: #ffffff2f;
    transform: translateY(-1px);
}
</style>

<div class="page-header">
            <div>
                <h1>
                <i class="fas fa-book"></i>
                 Ujian Mode
            </h1>
            <p>Lihat Ujian Yang akan Dilaksanakan</p>
            </div>
            
            <div class="date-badge">
                <i class="fas fa-calendar-alt"></i> 
                {{ \Carbon\Carbon::parse(now())->locale("id")->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

<div class="exam-container">
    <div class="section-title">
        <i class="fas fa-calendar-day"></i> Ujian Hari Ini - {{ date('d/m/Y') }}
    </div>
    
  @if($siswa->status === "ready")
  <div class="exam-grid">
    @if(isset($uji) && count($uji) > 0)
        @foreach($uji as $uj)
        @php 
            // Ambil data peserta dari relasi yang sudah didefinisikan di controller
            $peserta = $uj->peserta->first();
            
            // Tentukan status berdasarkan data peserta
            if($peserta) {
                $statusUjian = $peserta->status; // 'mulai' atau 'selesai'
                $nilaiSiswa = $peserta->nilai;
            } else {
                $statusUjian = 'belum'; // Belum pernah mengerjakan
                $nilaiSiswa = null;
            }
            
            // Cek apakah ujian sedang berjalan (status dari tabel ujian)
            $isUjianReady = ($uj->status === "ready");
            $isUjianOngoing = ($uj->status === "ongoing");
            
        @endphp
        
        <div class="card exam-card" style="background:white;">
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="title is-4">{{ $uj->mapels->nama_mapel ?? $uj->nama_ujian }}</p>
                        <p class="title is-4">{{ $uj->nama_ujian ?? $uj->nama_ujian }}</p>
                        <p class="subtitle is-6" style="margin-top:10px;">
                            <span class="icon">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            @if(isset($uj->jadwal))
                                {{ \Carbon\Carbon::parse($uj->jadwal->waktu_mulai)->locale('id')->translatedFormat('l, d F Y H:i') }}
                            @else
                                Waktu Belum ditentukan
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="content">

                    @if($statusUjian == 'selesai')
                        <div class="nilai-container has-text-centered">
                            <div class="box" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                <span class="icon has-text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </span>
                                <div class="title is-2 has-text-success" style="margin-top: 10px;">
                                    {{ $nilaiSiswa ?? 0 }}
                                </div>
                                <div class="subtitle is-6">
                                    <span class="tag is-success is-medium mx-auto">
                                        <i class="fas fa-check-double"></i> Ujian Selesai
                                    </span>
                                 </div>
                 <a class="button is-info is-outlined block" href="{{route('siswa.detail',$peserta->id)}}">Detail</a>               <p class="has-text-grey is-size-7">
                                    <i class="fas fa-calendar-check"></i> Telah dikerjakan pada {{ \Carbon\Carbon::parse($peserta->selesai_pada)->format('d/m/Y H:i') ?? '-' }}
                                </p>
                           </div>
                        </div>
                    
                    {{-- 2. STATUS MULAI: Tampilkan tombol lanjutkan --}}
                    @elseif($statusUjian == 'mulai')
                        <div class="has-text-centered">
                            <div class="notification is-light" style="color:#2e5b9a;font-size:12px;">
                                <i class="fas fa-hourglass-half"></i> Ujian sedang berjalan
                            </div>
                            <a href="{{ route('siswa.resume', $uj->id) }}" class="button is-warning is-medium">
                                <span class="icon">
                                    <i class="fas fa-play-circle"></i>
                                </span>
                                <span>Lanjutkan Ujian</span>
                            </a>
                            <p class="is-size-7 has-text-grey mt-2">
                                <i class="fas fa-info-circle"></i> Anda memiliki ujian yang belum selesai
                            </p>
                        </div>
                    

                    @elseif($statusUjian == 'belum')
                        @if($isUjianReady)
                            <div class="has-text-centered">
                                <a href="{{ route('siswa.shop', $uj->id) }}" class="button exam-btn-start" style="background:#2e5b9a;">
                                    <span class="icon">
                                        <i class="fas fa-play"></i>
                                    </span>
                                    <span>Mulai Ujian</span>
                                </a>
                            </div>
                        @elseif($isUjianOngoing)
                            <div class="has-text-centered">
                                <span class="tag is-warning is-medium">
                                    <i class="fas fa-hourglass-half"></i> Sedang Berlangsung
                                </span>
                            </div>
                        @else
                            <div class="has-text-centered">
                                <span class="tag is-info is-light is-medium" style="display:inline-flex; align-items:center; gap:8px;">
                                    <i class="fas fa-info-circle" ></i> Belum tersedia
                                </span>
                            </div>
                        @endif
                    
                    {{-- 4. DEFAULT: Error handling --}}
                    @else
                        <div class="has-text-centered">
                            <span class="tag is-danger is-light is-medium">
                                <i class="fas fa-exclamation-triangle"></i> Status tidak valid
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="has-text-centered" style="padding: 40px; color: #999;">
            <i class="fas fa-inbox fa-3x" style="margin-bottom: 15px;"></i>
            <p>Tidak ada ujian untuk hari ini</p>
            <p class="is-size-7">Silakan cek jadwal ujian untuk informasi lebih lanjut</p>
        </div>
    @endif
    </div>
  @else
   <h5>Belum Di Absen</h5>
  @endif
</div>
@endsection