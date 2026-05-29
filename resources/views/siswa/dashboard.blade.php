@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')
@section('content')
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



        <style>
            .btn-custom {
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
}

/* Hover utama */
.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(46, 91, 154, 0.18);
}

/* Efek klik */
.btn-custom:active {
    transform: scale(0.97);
}

/* Shine animation */
.btn-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: -120%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.35),
        transparent
    );
    transition: 0.6s;
}

.btn-custom:hover::before {
    left: 120%;
}
.btn-primary-custom {
    background: linear-gradient(135deg, #2e5b9a, #3f6fc0);
    color: white;
    border: none;
}

.btn-primary-custom:hover {
    background: linear-gradient(135deg, #24497d, #345ea5);
}

.btn-warning-custom {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: white;
    border: none;
}

.btn-warning-custom:hover {
    background: linear-gradient(135deg, #d97706, #f59e0b);
}
        </style>
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
                        <span class="detail-value">{{ $siswa->user->username ?? '-' }}</span>
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
                {{ \Carbon\Carbon::parse($today)->locale("id")->translatedFormat('l, d F Y') }}
            </h2>
            <div class="date-badge">
                <i class="far fa-clock"></i> 
                <span id="liveTime"></span>
            </div>
        </div>

        <!-- QUICK LINKS (Jadwal & Riwayat) -->
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
            @if($uji)
    @php
        $uj = $uji;
        
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
                                        <a href="{{ route('siswa.shop', $uj->id) }}" class="btn-custom btn-primary-custom exam-action-btn">
                                            <span><i class="fas fa-play"></i></span>
                                            <span>Mulai Ujian</span>
                                        </a>
                                    </div>
                                @else
                                    <div style="text-align: center;">
                                        <span class="status-badge">
                                            <i class="fas fa-info-circle"></i> Tidak tersedia
                                        </span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
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
                    <div class="day">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->locale("id")->translatedformat('d') }}</div>
                    <div class="month">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->locale("id")->translatedFormat('M') }}</div>
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
<!-- PRAKTIK HARI INI -->
@if($praktik && $praktik->count() > 0)
<div class="exam-container" style="margin-top: 30px;">
    <div class="section-title">
        <i class="fas fa-flask"></i> Praktik Hari Ini
    </div>
    
    @foreach($praktik as $prak)
@php
    $peserta = $prak->peserta->first();

    if($peserta) {
        $statusUjian = $peserta->status;
        $nilaiSiswa = $peserta->nilai;
    } else {
        $statusUjian = 'belum';
        $nilaiSiswa = null;
    }

    $sudahMengerjakan = $prak->peserta->isNotEmpty();

    $isUjianReady = ($prak->status === "ready");
    $isUjianOngoing = ($prak->status === "ongoing");
@endphp
    
    <div class="exam-card" style="border-left: 4px solid #f59e0b;">
        <div class="exam-card-content">
            <div class="media">
                <div class="media-content">
                    <p class="title-4">
                        <i class="fas fa-microscope" style="color: #f59e0b;"></i>
                        {{ $prak->mapels->nama_mapel ?? $prak->nama_ujian }}
                        <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; margin-left: 10px;">
                            Praktik
                        </span>
                        @if($sudahMengerjakan)
                         <span style="background: #dcfce7;color: #166534;padding: 2px 8px;border-radius: 20px;font-size: 0.7rem;margin-left: 6px;">
                            <i class="fas fa-check-circle"></i>
                                Sudah Dikerjakan
                         </span>
                        @endif
                    </p>
                    <p class="subtitle-6">
                        <span class="icon"><i class="fas fa-clock"></i></span>
                        @if(isset($prak->jadwal))
                            {{ \Carbon\Carbon::parse($prak->jadwal->waktu_mulai)->format('H:i') }} WIB
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
                            <span style="background: #22c55e; color: white; border-radius: 20px; font-size: 0.85rem; display:inline-block; padding: 4px 12px;">
                                Praktik Selesai
                            </span>
                            <br><br>
                            <a class="btn-custom btn-success-custom" href="{{route('siswa.detail',$peserta->id)}}">Detail Nilai</a>
                        </div>
                    </div>
                @elseif($statusUjian == 'mulai')
                    <div style="text-align: center;">
                        <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-bottom: 15px; color: #d97706; font-weight: 600;">
                            <i class="fas fa-hourglass-half"></i> Praktik sedang berjalan
                        </div>
                        <a href="{{ route('siswa.resume', $prak->id) }}" class="btn-custom btn-warning-custom" style="width: 100%; justify-content: center;">
                            <span><i class="fas fa-play-circle"></i></span>
                            <span>Lanjutkan Praktik</span>
                        </a>
                    </div>
                @else

    @if($sudahMengerjakan)
        <div style="text-align:center;">
            <div style="
                background:#ecfdf5;
                color:#166534;
                padding:12px;
                border-radius:10px;
                font-weight:600;
            ">
                <i class="fas fa-check-circle"></i>
                Kamu sudah mengerjakan praktik ini
            </div>
        </div>

    @elseif($isUjianReady)
        <div style="text-align: center;">
            <a href="{{ route('siswa.shop', $prak->id) }}"
               class="btn-custom"
               style="
                    background: linear-gradient(135deg, #f59e0b, #fbbf24);
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 12px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
               ">
                <span><i class="fas fa-play"></i></span>
                <span>Mulai Praktik</span>
            </a>
        </div>

    @else
        <div style="text-align: center;">
            <span class="status-badge">
                <i class="fas fa-info-circle"></i> Tidak tersedia
            </span>
        </div>
    @endif

@endif
                    
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection