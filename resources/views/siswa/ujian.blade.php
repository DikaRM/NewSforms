<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ujian {{$uji->nama_ujian}}</title>
  
  <!-- Bulmaswatch CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    /* ============================
       1. CORE LAYOUT & COLORS
       ============================ */
    :root {
      --primary-bg: #f5f7fa;
      --nav-active: #3273dc;    /* Biru Bulma */
      --nav-answered: #48c774;  /* Hijau Bulma */
      --essay-color: #ffdd57;   /* Kuning Bulma */
      --essay-text: #333;
      --danger-color: #f14668;  /* Merah Bulma */
    }

    body {
      background-color: var(--primary-bg);
      min-height: 100vh;
      transition: filter 0.1s ease; /* Transisi cepat untuk blur */
    }

    /* ============================
       TAMBAHAN: BLUR BOMB STYLE
       ============================ */
    .exam-blur-mode {
      filter: blur(15px) grayscale(100%) brightness(0.7) !important;
      pointer-events: none !important; /* Matikan interaksi mouse */
      user-select: none !important;   /* Matikan seleksi teks */
    }

    /* ============================
       2. NAVIGASI SOAL (KIRI)
       ============================ */
    .soal-navigator {
      position: sticky;
      top: 20px;
      max-height: calc(100vh - 40px);
      overflow-y: auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      border: 1px solid #eee;
      border-radius: 8px;
      background: white;
    }
    
    .nav-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      margin-bottom: 15px;
    }
    
    /* Tombol Nomor Soal */
    .nav-btn {
      width: 100%;
      aspect-ratio: 1;
      border-radius: 6px;
      border: 1px solid #dbdbdb;
      background: white;
      cursor: pointer;
      font-weight: 600;
      color: #4a4a4a;
      transition: all 0.2s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
    }
    
    .nav-btn:hover {
      border-color: var(--nav-active);
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* State: Active (Sedang dikerjakan) */
    .nav-btn.current {
      background-color: var(--nav-active);
      color: white;
      border-color: var(--nav-active);
      box-shadow: 0 0 0 3px rgba(50, 115, 220, 0.25);
    }
    
    /* State: Answered (Sudah dijawab) */
    .nav-btn.answered {
      background-color: var(--nav-answered);
      color: white;
      border-color: var(--nav-answered);
    }
    
    /* State: Essay (Tipe Soal) */
    .nav-btn.essay {
      border: 2px solid var(--essay-color);
      background: #fffff0;
      color: #555;
    }
    
    .nav-btn.essay.answered {
      background-color: var(--essay-color);
      color: var(--essay-text);
      border-color: #f0c029;
    }
    
    .nav-btn i {
      font-size: 0.75em;
      margin-top: 2px;
    }

    /* Info Panel */
    .info-panel {
      background: #eff5fb;
      border-left: 4px solid var(--nav-active);
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    .info-panel p { margin-bottom: 5px; color: #363636; }

    /* Legend */
    .legend {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-top: 15px;
      font-size: 0.8rem;
      justify-content: center;
    }
    .legend-item { display: flex; align-items: center; gap: 5px; color: #666; }
    .legend-color {
      width: 16px; height: 16px; border-radius: 3px;
      border: 1px solid #ddd;
    }

    /* ============================
       3. KARTU SOAL (KANAN)
       ============================ */
    .soal-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      border: 1px solid #eee;
      margin-bottom: 20px;
    }
    
    .soal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .soal-nomor {
      background: var(--nav-active);
      color: white;
      padding: 6px 18px;
      border-radius: 50px;
      font-weight: bold;
      font-size: 0.9rem;
      box-shadow: 0 2px 5px rgba(50, 115, 220, 0.3);
    }
    
    .soal-tipe {
      font-size: 0.8rem;
      text-transform: uppercase;
      font-weight: 700;
      color: #888;
      letter-spacing: 0.5px;
    }

    /* Gambar Soal */
    .soal-gambar {
      margin: 20px 0;
      text-align: center;
      background: #fafafa;
      border: 1px dashed #ccc;
      padding: 15px;
      border-radius: 8px;
    }
    .soal-gambar img {
      max-width: 100%;
      max-height: 350px;
      border-radius: 4px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Opsi Jawaban (PG) */
    .opsi-item {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .opsi-item:hover {
      background: #f8fbff;
      border-color: var(--nav-active);
    }
    .opsi-item input[type="radio"] {
      margin-right: 15px;
      transform: scale(1.3);
      cursor: pointer;
    }
    .opsi-label {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 32px;
      height: 32px;
      background: #e6e6e6;
      color: #555;
      border-radius: 50%;
      font-weight: bold;
      margin-right: 15px;
      flex-shrink: 0;
    }

    /* Input Essay */
    .essay-container { margin: 20px 0; }
    .essay-label {
      background: var(--essay-color);
      color: #5a5100;
      padding: 5px 12px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 0.85rem;
      display: inline-block;
      margin-bottom: 10px;
    }
    .essay-input {
      width: 100%;
      padding: 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      font-family: inherit;
      min-height: 150px;
      transition: border-color 0.2s;
    }
    .essay-input:focus {
      border-color: var(--nav-active);
      outline: none;
      background: #fafcff;
    }

    /* ============================
       4. NAVIGASI BAWAH (TOMBOL)
       ============================ */
    .nav-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    /* Tombol Submit Khusus */
    #submitBtnContainer {
      display: none; /* Hidden by default via CSS, controlled by JS */
      animation: popIn 0.3s ease;
    }

    @keyframes popIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    /* ============================
       5. SECURITY OVERLAY STYLES
       ============================ */
    .security-overlay {
      position: fixed;
      inset: 0;
      background: rgba(220, 53, 69, 0.95);
      z-index: 999999;
      display: none;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
      backdrop-filter: blur(8px);
    }
    
    .security-overlay.active { display: flex; animation: slideDown 0.3s ease; }
    
    @keyframes slideDown {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .warning-content {
      background: white;
      padding: 40px 50px;
      border-radius: 16px;
      max-width: 600px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
      text-align: left;
    }
    
    .warning-icon {
      font-size: 50px;
      color: var(--danger-color);
      margin-bottom: 15px;
      display: block;
      text-align: center;
    }
    
    .warning-title {
      font-size: 28px;
      font-weight: 800;
      color: var(--danger-color);
      margin-bottom: 10px;
      text-align: center;
    }
    
    .warning-count {
      background: var(--danger-color);
      color: white;
      padding: 5px 20px;
      border-radius: 20px;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      display: inline-block;
      width: 100%;
      text-align: center;
    }
    
    .warning-detail {
      background: #fff0f1;
      color: #c92a2a;
      padding: 15px;
      border-radius: 8px;
      font-size: 15px;
      margin-bottom: 20px;
      border-left: 4px solid var(--danger-color);
    }
    
    .warning-toast {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #2d3436;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      z-index: 99999;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      display: none;
      align-items: center;
      gap: 10px;
      animation: slideLeft 0.3s ease;
    }
    @keyframes slideLeft { from { transform: translateX(100%); } to { transform: translateX(0); } }

    .countdown-timer {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #2d3436;
      color: white;
      padding: 10px 20px;
      border-radius: 30px;
      font-size: 16px;
      font-weight: bold;
      z-index: 100;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      display: none;
      align-items: center;
      gap: 10px;
    }
    .countdown-timer i { color: #ffdd57; }

    .fullscreen-lock-warning {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      background: linear-gradient(to top, rgba(220, 53, 69, 0.2), transparent);
      padding: 30px 20px 10px;
      text-align: center;
      z-index: 9998;
      pointer-events: none;
      display: none;
    }
    .fullscreen-lock-warning.active { display: block; }
    
    /* Responsive Mobile */
    @media (max-width: 768px) {
      .columns { display: flex; flex-direction: column-reverse; } /* Nav di bawah di HP */
      .column.is-3 { width: 100%; }
      .soal-navigator { position: relative; top: 0; max-height: none; margin-top: 20px; }
      .nav-grid { grid-template-columns: repeat(8, 1fr); gap: 5px; }
      .warning-content { padding: 20px; width: 90%; }
      .warning-title { font-size: 22px; }
      .soal-card { padding: 15px; }
      .nav-controls { flex-direction: column-reverse; gap: 15px; }
      .nav-controls button { width: 100%; }
    }

    /* =========================================
      PAGINATION FIX (PENTING UNTUK NAVIGASI)
      ========================================= */
    
    /* Sembunyikan semua wadah soal secara default */
    .soal-container {
        display: none;
        /* Animasi sedikit agar transisi terasa halus */
        animation: fadeInQuestion 0.3s ease;
    }

    /* Tampilkan hanya soal yang sedang aktif */
    .soal-container.active {
        display: block;
    }

    /* Animasi fade in saat ganti soal */
    @keyframes fadeInQuestion {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  
  <!-- Countdown Timer Global -->
  <div class="countdown-timer" id="countdownTimerDisplay">
    <i class="fas fa-shield-alt"></i> <span id="countdownNumber">60</span>s Detik
  </div>

  <div class="container" style="padding-top: 30px; padding-bottom: 50px;">
    <div class="columns">
      
      <!-- KOLOM KIRI: NAVIGASI -->
      <div class="column is-3">
        <div class="card soal-navigator">
          <div class="card-header" style="background: white; border-bottom: none; padding-bottom: 0;">
            <p class="card-header-title" style="font-size: 1.1rem;">
              <i class="fas fa-th-list" style="margin-right: 10px; color: #3273dc;"></i> Navigasi
            </p>
          </div>
          <div class="card-content">
            <div class="info-panel">
              <p><strong>{{$ire->nama}}</strong></p>
              <p><i class="fas fa-graduation-cap"></i> Kelas: {{$sis->kelas->nama_kelas}}</p>
              <p><i class="fas fa-book"></i> {{$uji->mapels->nama_mapel}}</p>
              <p style="margin-top: 5px; border-top: 1px solid #dbeafe; padding-top: 5px;">
                Waktu: <span id="display" class="has-text-weight-bold has-text-danger">00:00</span>
              </p>
            </div>
            
            <div class="nav-grid" id="soalNavGrid">
              @foreach($soal as $index => $s)
                @php
                  $tipe = $s->tipe ?? 'pg';
                  $extraClass = $tipe == 'essay' ? 'essay' : '';
                  $icon = $tipe == 'essay' ? '<i class="fas fa-pen"></i>' : '';
                @endphp
                <button class="nav-btn {{$extraClass}}" 
                        data-soal-id="{{$s->id}}" 
                        data-index="{{$index}}"
                        data-tipe="{{$tipe}}">
                  {{$index + 1}}
                  {!! $icon !!}
                </button>
              @endforeach
            </div>
            
            <div class="legend">
              <div class="legend-item">
                <div class="legend-color" style="background: var(--nav-active); border:none;"></div>
                <span>Aktif</span>
              </div>
              <div class="legend-item">
                <div class="legend-color" style="background: var(--nav-answered); border:none;"></div>
                <span>Jawab</span>
              </div>
              <div class="legend-item">
                <div class="legend-color" style="background: var(--essay-color); border: 1px solid #eec;"></div>
                <span>Essay</span>
              </div>
            </div>
            
            <div style="margin-top: 25px;">
              <progress class="progress is-primary" id="progressBar" value="0" max="100">0%</progress>
              <p class="has-text-centered is-size-7" style="margin-top: 5px; color:#666;" id="progressText">0/{{count($soal)}} soal terjawab</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- KOLOM KANAN: KONTEN SOAL -->
      <div class="column is-9">
        <div class="card" style="border: none; box-shadow: none; background: transparent;">
          <div class="card-header" style="background: white; border-radius: 8px; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <p class="card-header-title" style="font-size: 1.2rem; color: #333;">
              <i class="fas fa-file-alt" style="margin-right: 10px; color: #aaa;"></i>
              <span id="soalHeader">Soal 1 dari {{count($soal)}}</span>
            </p>
          </div>
          
          <div class="card-content" style="padding-top: 10px;">
            <form action="{{route('siswa.save')}}" method="post" id="form">
              @csrf
              <input type="hidden" name="ujian_id" id="ujian_id" value="{{$uji->id}}">
              <input type="hidden" name="siswa_id" id="siswa_id" value="{{$sis->id_siswa}}">
              
              <div id="soalContainer">
                @foreach($soal as $index => $s)
                  @php
                    $tipe = $s->tipe ?? 'pg';
                  @endphp
                  <div class="soal-container" data-soal-id="{{$s->id}}" data-index="{{$index}}" data-tipe="{{$tipe}}">
                    <div class="soal-card">
                      <div class="soal-header">
                        <span class="soal-nomor">Soal {{$index + 1}}</span>
                        <span class="soal-tipe">
                          @if($tipe == 'essay') <i class="fas fa-pen-fancy"></i> Uraian @else <i class="fas fa-list-ul"></i> Pilihan Ganda @endif
                        </span>
                      </div>
                      
                      <h5 class="subtitle is-5" style="margin-bottom: 15px;">{!! $s->soal !!}</h5>
                      
                      @if(!empty($s->gambar))
                      <div class="soal-gambar">
                          <img src="{{ asset('storage/' . $s->gambar) }}" alt="Gambar Soal">
                      </div>
                      @endif                   
                      
                      @if($tipe == 'pg')
                        <div class="opsi-container">
                          <label class="opsi-item">
                            <input type="radio" name="jawaban[{{$s->id}}]" value="a" class="jawaban-radio">
                            <span class="opsi-label">A</span>
                            <span>{!! $s->opsi_a !!}</span>
                          </label>
                          <label class="opsi-item">
                            <input type="radio" name="jawaban[{{$s->id}}]" value="b" class="jawaban-radio">
                            <span class="opsi-label">B</span>
                            <span>{!! $s->opsi_b !!}</span>
                          </label>
                          <label class="opsi-item">
                            <input type="radio" name="jawaban[{{$s->id}}]" value="c" class="jawaban-radio">
                            <span class="opsi-label">C</span>
                            <span>{!! $s->opsi_c !!}</span>
                          </label>
                          @if(!empty($s->opsi_d))
                          <label class="opsi-item">
                            <input type="radio" name="jawaban[{{$s->id}}]" value="d" class="jawaban-radio">
                            <span class="opsi-label">D</span>
                            <span>{!! $s->opsi_d !!}</span>
                          </label>
                          @endif
                          @if(!empty($s->opsi_e))
                          <label class="opsi-item">
                              <input type="radio" name="jawaban[{{$s->id}}]" value="e" class="jawaban-radio">
                            <span class="opsi-label">E</span>
                            <span>{!! $s->opsi_e !!}</span>
                          </label>
                          @endif
                        </div>
                      @else
                        <div class="essay-container">
                          <div class="essay-label"><i class="fas fa-align-left"></i> KETIK JAWABAN ANDA</div>
                          <textarea class="textarea essay-input jawaban-text" 
                                    name="jawaban[{{$s->id}}]" 
                                    placeholder="Tulis jawaban essay di sini..."
                                    rows="5"></textarea>
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              
              <!-- Navigasi Bawah -->
              <div class="nav-controls">
                <button type="button" class="button is-light" id="prevBtn" disabled>
                    <span class="icon"><i class="fas fa-chevron-left"></i></span>
                    <span>Sebelumnya</span>
                </button>

                <!-- Tombol Berikutnya (Hilang di soal terakhir) -->
                <div id="nextBtnContainer">
                  <button type="button" class="button is-primary" id="nextBtn">
                    <span>Berikutnya</span>
                    <span class="icon"><i class="fas fa-chevron-right"></i></span>
                  </button>
                </div>

                <!-- Tombol Submit (Hanya muncul di soal terakhir) -->
                <div id="submitBtnContainer">
                  <button type="submit" class="button is-danger is-medium" id="submitBtn">
                    <span class="icon"><i class="fas fa-paper-plane"></i></span>
                    <span>Kirim Jawaban (Selesai)</span>
                  </button>
                </div>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Security Warning Overlay -->
  <div class="security-overlay" id="securityOverlay">
    <div class="warning-content">
      <div class="warning-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="warning-count" id="violationCountDisplay">PELANGGARAN #1</div>
      <h1 class="warning-title">PELANGGARAN TERDETEKSI!</h1>
      <p class="subtitle is-5" style="text-align: center; margin-bottom: 20px;" id="violationMessage">
        Anda terdeteksi keluar dari mode fullscreen atau membuka aplikasi lain.
      </p>
      <div class="warning-detail">
        <strong><i class="fas fa-info-circle"></i> Perhatian:</strong>
        <ul style="margin-top: 10px; margin-left: 20px;">
          <li>Dilarang keluar mode Fullscreen.</li>
          <li>Dilarang membuka tab/aplikasi lain (Split Screen/Floating).</li>
          <li>Dilarang melakukan Copy/Paste.</li>
        </ul>
        <p style="margin-top: 10px; font-size: 14px;">Jika pelanggaran melebihi batas, ujian akan otomatis terkunci.</p>
      </div>
      <p style="text-align: center; color: #666; margin-bottom: 25px;" id="warningTimer">
        Anda dapat melanjutkan dalam: <strong><span id="countdownTimer">60</span> detik</strong>
      </p>
      <button class="button is-danger is-fullwidth" onclick="continueExam()" style="padding: 15px; font-size: 18px;">
        <i class="fas fa-check-circle"></i> LANJUTKAN UJIAN
      </button>
    </div>
  </div>

  <!-- Toast Warning -->
  <div class="warning-toast" id="warningToast">
    <i class="fas fa-bell" style="color: #ffdd57; font-size: 20px;"></i>
    <span id="toastMessage">Peringatan!</span>
  </div>

  <!-- Fullscreen Lock Warning (Subtle) -->
  <div class="fullscreen-lock-warning" id="fullscreenLockWarning">
    <span style="color: #dc3545; font-weight: bold; font-size: 18px; background: rgba(255,255,255,0.9); padding:5px 15px; border-radius: 20px; display: inline-block;">
      <i class="fas fa-lock"></i> Mode Fullscreen Wajib Aktif
    </span>
  </div>

<script>
   @php 
    $mulai = \Carbon\Carbon::parse(now());
    $selesai = \Carbon\Carbon::parse($uji->jadwal->waktu_selesai);
    $durasiMenit = $mulai->diffInMinutes($selesai);
  @endphp

// ========== SISTEM KEAMANAN UJIAN (FINAL & COMPLETED) ==========
(function() {
    'use strict';
    
    // ========== KONFIGURASI ==========
    const SERVER_START_TIME = "{{ $uji->mulai_pengerjaan ?? date('Y-m-d H:i:s') }}";
    const DURASI_MENIT = {{ $durasiMenit }};
    const UJIAN_ID = {{$uji->id ?? 0}};
    const SISWA_ID = {{$sis->id_siswa ?? 0}};
    
    // Hitung Target Waktu Berakhir
    const START_TIME_MS = new Date(SERVER_START_TIME).getTime();
    const END_TIME_MS = START_TIME_MS + (DURASI_MENIT * 60 * 1000);
    
    const TOTAL_SOAL = {{count($soal ?? [])}};
    const MAX_VIOLATION = 2; // Batas pelanggaran
    const COOLDOWN_TIME = 60000; // 60 Detik Cooldown
    const VIOLATION_COUNTDOWN = 60; // 60 detik countdown saat pelanggaran
    
    let isSubmitting = false;
    let currentSoalIndex = 0;
    let timerInterval = null;
    let countdownInterval = null;
    
    // COUNTER PELANGGARAN GLOBAL
    let violationCount = 0;
    let lastViolationTime = 0;
    let isDisqualified = false; 
    let isInCountdown = false;
    
    // Variabel Anti-False Positive
    let lastFullscreenToggleTime = 0; 
    let isHandlingFullscreen = false;  
    
    // Fullscreen lock
    let fullscreenLocked = true;
    let forceFullscreenInterval = null;
    
    // ========== DETEKSI DEVICE ==========
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // ========== ELEMEN DOM ==========
    const displayTimer = document.getElementById("display");
    const form = document.getElementById("form");
    const soalContainers = document.querySelectorAll('.soal-container');
    const navButtons = document.querySelectorAll('#soalNavGrid .nav-btn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const soalHeader = document.getElementById('soalHeader');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const securityOverlay = document.getElementById('securityOverlay');
    const warningToast = document.getElementById('warningToast');
    const warningMessage = document.getElementById('violationMessage');
    const violationCountDisplay = document.getElementById('violationCountDisplay');
    const countdownTimerElement = document.getElementById('countdownTimer');
    const countdownNumber = document.getElementById('countdownNumber');
    const countdownTimerDisplay = document.getElementById('countdownTimerDisplay');
    const toastMessage = document.getElementById('toastMessage');
    const fullscreenLockWarning = document.getElementById('fullscreenLockWarning');
    
    // Elemen Button Logic
    const nextBtnContainer = document.getElementById('nextBtnContainer');
    const submitBtnContainer = document.getElementById('submitBtnContainer');

    // =======================
    // PERSISTENCE STATUS (ANTI REFRESH)
    // =======================

    function saveSecurityState() {
        sessionStorage.setItem(`exam_is_disqualified_${UJIAN_ID}`, isDisqualified);
        sessionStorage.setItem(`exam_violation_count_${UJIAN_ID}`, violationCount);
    }

    function loadSecurityState() {
        // Cek apakah sebelumnya sudah dikunci
        const savedDisqualified = sessionStorage.getItem(`exam_is_disqualified_${UJIAN_ID}`);
        const savedCount = sessionStorage.getItem(`exam_violation_count_${UJIAN_ID}`);

        if (savedDisqualified === 'true') {
            // Jika sebelumnya sudah dikunci, KUNCI LAGI SAAT REFRESH
            console.log("⚠️ Mendeteksi status dikualifikasi sebelumnya. Mengunci ulang.");
            
            // Kita buat pesan alasan khusus
            const lockReason = "Anda mencoba refresh halaman setelah ujian dikunci.";
            
            // Langsung panggil fungsi kunci tanpa trigger timer
            forceLockScreen(lockReason);
            return true; // Return true berarti status terkunci
        } else if (savedCount) {
            // Jika refresh tapi belum kunci, pulangkan jumlah pelanggaran
            violationCount = parseInt(savedCount) || 0;
            console.log(`🔍 Status pelanggaran dipulihkan: ${violationCount}`);
        }
        
        // Cek Waktu Juga (Anti Refresh saat Waktu Habis)
        const now = Date.now();
        if (now >= END_TIME_MS) {
            // Jika waktu sudah habis, submit otomatis saat refresh
            submitFormOtomatis();
            return true;
        }
        
        return false; // Status aman
    }

    // Fungsi helper untuk mengunci layar paksa (tanpa memutar ulang timer)
    function forceLockScreen(reason) {
        stopTimer();
        stopCountdownTimer();
        isDisqualified = true; // Set status global
        saveSecurityState(); // Simpan status agar tetap ada jika refresh lagi
        
        document.body.innerHTML = `
            <div style="display:flex; justify-content:center; align-items:center; min-height:100vh; text-align:center; padding:20px; background: #ffebee;">
                <div style="background: white; padding: 50px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
                    <i class="fas fa-ban" style="font-size: 60px; color: #d32f2f; margin-bottom: 20px;"></i>
                    <h1 style="color:#d32f2f; font-size: 2.5rem; margin-bottom:20px;">UJIAN DIKUNCI</h1>
                    <h2 style="margin-bottom:20px; color:#555;">Sistem mendeteksi pelanggaran sebelumnya.</h2>
                    <p style="margin-bottom:15px;"><strong>Alasan:</strong> ${reason}</p>
                    <p style="margin-bottom:30px;">Jangan mencoba me-refresh halaman untuk menghindari hukuman.</p>
                    
                    <div style="background: #f5f5f5; padding: 20px; border-radius: 10px;">
                        <p style="font-size:18px; color:#d32f2f;"><strong>Silakan tunggu sampai waktu ujian berakhir.</strong></p>
                        <p style="margin-top:10px; font-size: 24px; font-weight: bold;">Waktu tersisa: <span id="lockedTimeDisplay">--:--</span></p>
                    </div>
                </div>
            </div>
        `;

        // Timer di layar kunci tetap jalan biar tahu kapan ujian selesai
        setInterval(() => {
            const now = Date.now();
            let remainingMs = END_TIME_MS - now;

            if (remainingMs <= 0) {
                remainingMs = 0;
                submitFormOtomatis();
                return;
            }
            
            let menit = Math.floor(remainingMs / 30000);
            let detik = Math.floor((remainingMs % 30000) / 1000);
            menit = menit < 10 ? "0" + menit : menit;
            detik = detik < 10 ? "0" + detik : detik;
            
            const lockedTime = document.getElementById('lockedTimeDisplay');
            if (lockedTime) {
                lockedTime.innerText = `${menit}:${detik}`;
            }
        }, 1000);
    }

    // ========== BLUR BOMB FUNCTIONS (THE CRUEL PART) ==========
    const applyBlur = () => {
        document.body.classList.add('exam-blur-mode');
    };

    const removeBlur = () => {
        document.body.classList.remove('exam-blur-mode');
    };
    
    // ========== FUNGSI PELANGGARAN TERPUSAT ==========
    function handleViolation(jenisPelanggaran) {
        if (isDisqualified || isSubmitting || isInCountdown) return;
        
        if (isHandlingFullscreen) return;

        const now = Date.now();

        // Cek Cooldown
        if (now - lastViolationTime < COOLDOWN_TIME) {
            console.warn(`Pelanggaran (${jenisPelanggaran}) masuk cooldown.`);
            return;
        }

        violationCount++;
        lastViolationTime = now; 

        console.log(`Pelanggaran #${violationCount}: ${jenisPelanggaran}`);

        // **TAMBAHKAN INI:** Simpan status segera setelah pelanggaran bertambah
        saveSecurityState(); 

        if (violationCount <= MAX_VIOLATION) {
            showWarningToast(`<i class="fas fa-exclamation-triangle"></i> ${jenisPelanggaran}`);
            startCountdownTimer();
            
            setTimeout(() => {
                if (!isDisqualified && !isInCountdown) {
                    showSecurityOverlay(jenisPelanggaran, violationCount);
                }
            }, 3000);
        } 
        
        if (violationCount > MAX_VIOLATION) {
            // Ganti pemanggilan lockExamInterface biasa dengan forceLockScreen
            forceLockScreen(jenisPelanggaran);
            sendViolationToServer(jenisPelanggaran);
        }
    }

    // ========== FUNGSI SHOW SECURITY OVERLAY ==========
    function showSecurityOverlay(jenis, count) {
        isInCountdown = true;
        
        if (violationCountDisplay) {
            violationCountDisplay.innerText = `PELANGGARAN #${count}`;
        }
        
        if (warningMessage) {
            const messageMap = {
                'EXIT_FULLSCREEN': 'Anda keluar dari mode fullscreen.',
                'SWITCH_TAB': 'Anda berpindah ke tab lain.',
                'WINDOW_LOST_FOCUS_BLUR': 'Anda membuka aplikasi lain (Split Screen/Floating).',
                'COPY_CONTENT': 'Anda melakukan copy.',
                'PASTE_CONTENT': 'Anda melakukan paste.',
                'PASTE_ESSAY': 'Anda melakukan paste di kolom essay.',
                'CUT_CONTENT': 'Anda melakukan cut.',
                'DEV_TOOLS_SHORTCUT': 'Anda mencoba membuka DevTools.',
                'DEV_TOOLS_RESIZE': 'Jendela di-resize mencurigakan.',
                'CURSOR_EXIT_FULLSCREEN_AREA': 'Kursor keluar dari area fullscreen.',
                'CONTEXT_MENU': 'Anda membuka context menu (klik kanan).'
            };
            
            warningMessage.innerText = messageMap[jenis] || jenis;
        }
        
        if (securityOverlay) {
            securityOverlay.classList.add('active');
        }
    }

    // ========== FUNGSI COUNTDOWN TIMER ==========
    function startCountdownTimer() {
        stopCountdownTimer();
        
        let remaining = VIOLATION_COUNTDOWN;
        
        if (countdownNumber) {
            countdownNumber.innerText = remaining;
        }
        
        if (countdownTimerDisplay) {
            countdownTimerDisplay.classList.add('active');
        }
        
        countdownInterval = setInterval(() => {
            remaining--;
            
            if (countdownNumber) {
                countdownNumber.innerText = remaining;
            }
            
            if (countdownTimerElement) {
                countdownTimerElement.innerText = remaining;
            }
            
            if (remaining <= 0) {
                stopCountdownTimer();
                if (!isDisqualified) {
                    hideSecurityOverlay();
                }
            }
        }, 1000);
    }
    
    function stopCountdownTimer() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        
        if (countdownTimerDisplay) {
            countdownTimerDisplay.classList.remove('active');
        }
    }
    
    function hideSecurityOverlay() {
        isInCountdown = false;
        
        if (securityOverlay) {
            securityOverlay.classList.remove('active');
        }
        
        if (!isMobile && fullscreenLocked && !isFullscreen()) {
            enableFullscreen();
        }
    }
    
    function continueExam() {
        hideSecurityOverlay();
        stopCountdownTimer();
    }

    // ========== KIRIM PELANGGARAN KE SERVER ==========
    async function sendViolationToServer(jenis) {
        if (!UJIAN_ID || !SISWA_ID) return;
        try {
            await fetch('{{route("siswa.violation")}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ujian_id: UJIAN_ID,
                    siswa_id: SISWA_ID,
                    jenis_pelanggaran: jenis,
                    detail: `Siswa gagal ujian (Terlalu banyak pelanggaran)`,
                    timestamp: new Date().toISOString()
                })
            });
        } catch(error) { console.error('Gagal kirim pelanggaran:', error); }
    }
    
    function showWarningToast(message) {
        if (toastMessage) {
            toastMessage.innerHTML = message;
        }
        if (warningToast) {
            warningToast.classList.add('active');
        }
        setTimeout(() => {
            if (warningToast) {
                warningToast.classList.remove('active');
            }
        }, 5000);
    }
    
    // ========== TIMER REALTIME ==========
    function updateTimerDisplay() {
        if(!displayTimer) return;
        const now = Date.now();
        let remainingMs = END_TIME_MS - now;

        if (remainingMs <= 0) {
            remainingMs = 0;
            submitFormOtomatis();
            return;
        }
        
        let menit = Math.floor(remainingMs / 60000);
        let detik = Math.floor((remainingMs % 60000) / 1000);
        menit = menit < 10 ? "0" + menit : menit;
        detik = detik < 10 ? "0" + detik : detik;
        displayTimer.innerText = `${menit}:${detik}`;
        
        if(remainingMs <= 300000 && remainingMs > 0) {
            displayTimer.classList.add('has-text-danger', 'has-text-weight-bold');
            if(remainingMs <= 300000 && remainingMs > 299000) { 
                // Alert diganti Toast agar tidak terlalu mengganggu flow
                showWarningToast('<i class="fas fa-clock"></i> Waktu tersisa 5 menit!');
            }
        }
    }
    
    function startTimer() {
        if(timerInterval) clearInterval(timerInterval);
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            const now = Date.now();
            if (now >= END_TIME_MS) {
                clearInterval(timerInterval);
                submitFormOtomatis();
                return;
            }
            updateTimerDisplay();
        }, 1000);
    }
    
    function stopTimer() {
        if(timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }
    
    // ========== FORCE FULLSCREEN & ANTI ESCAPE ==========
    function isFullscreen() {
        return !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
    }
    
    function enableFullscreen() {
        const elem = document.documentElement;
        const requestMethod = elem.requestFullscreen || elem.webkitRequestFullscreen || elem.msRequestFullscreen;
        if(requestMethod) {
            requestMethod.call(elem).catch(() => {});
        }
    }
    
    function initFullscreenMode() {
        if (isMobile) return;
        
        document.addEventListener('keydown', (e) => {
            if (isSubmitting || isDisqualified || isInCountdown) return;

            const blockedKeys = [
                'Escape', 'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8',
                'F9', 'F10', 'F11', 'F12', 'Control', 'Alt', 'Tab', 'Meta', 'Command'
            ];
            
            if (blockedKeys.includes(e.key)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            if (e.ctrlKey || e.altKey || e.metaKey) {
                const dangerousCombos = ['c', 'v', 'x', 'u', 's', 'p', 'a', 'f', 'i', 'j', 'l', 'Tab'];
                if (dangerousCombos.includes(e.key.toLowerCase())) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleViolation('DEV_TOOLS_SHORTCUT');
                    return false;
                }
            }
        });
        
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('msfullscreenchange', handleFullscreenChange);
        
        function handleFullscreenChange() {
            if (!isSubmitting && !isDisqualified && !isInCountdown && fullscreenLocked) {
                if (!isFullscreen()) {
                    handleViolation('EXIT_FULLSCREEN');
                    if (!isDisqualified && !isInCountdown) {
                        setTimeout(() => {
                            if (!isFullscreen() && !isDisqualified && !isInCountdown) {
                                enableFullscreen();
                            }
                        }, 1000);
                    }
                } else {
                    lastFullscreenToggleTime = Date.now();
                    if (fullscreenLockWarning) {
                        fullscreenLockWarning.classList.add('active');
                    }
                    setTimeout(() => {
                        if (fullscreenLockWarning) {
                            fullscreenLockWarning.classList.remove('active');
                        }
                    }, 3000);
                }
            }
        }
        
        forceFullscreenInterval = setInterval(() => {
            if (!isSubmitting && !isDisqualified && !isInCountdown && fullscreenLocked && !isFullscreen()) {
                isHandlingFullscreen = true; 
                if (!isDisqualified && !isInCountdown) {
                    enableFullscreen();
                    if (fullscreenLockWarning) {
                        fullscreenLockWarning.classList.add('active');
                    }
                    setTimeout(() => {
                        if (fullscreenLockWarning) {
                            fullscreenLockWarning.classList.remove('active');
                        }
                        isHandlingFullscreen = false; 
                    }, 3000);
                }
            }
        }, 1000);
        
        setTimeout(enableFullscreen, 1000);
    }
    
    // ========== DETEKSI CONTEXT MENU ==========
    if (!isMobile) {
        document.addEventListener('contextmenu', (e) => {
            if (isSubmitting || isDisqualified) return;
            e.preventDefault();
            e.stopPropagation();
            handleViolation('CONTEXT_MENU');
            return false;
        });
    }
    
    // ========== DETEKSI VISIBILITY ==========
    const handleVisibilityChange = () => {
        if (isSubmitting || isDisqualified || isInCountdown) return;
        
        if (document.hidden) {
            handleViolation('SWITCH_TAB');
            document.body.style.opacity = '0.3';
        } else {
            removeBlur();
            document.body.style.opacity = '1';
        }
    };
    
    document.addEventListener('visibilitychange', handleVisibilityChange);
    
    // ========== DETEKSI COPY-PASTE ==========
    document.addEventListener('copy', (e) => {
        if (e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            handleViolation('COPY_CONTENT');
            return false;
        }
    });
    
    document.addEventListener('paste', (e) => {
        if (isSubmitting || isDisqualified || isInCountdown) return;
        if (e.target.tagName === 'TEXTAREA') {
            handleViolation('PASTE_ESSAY');
        } else {
            e.preventDefault();
            handleViolation('PASTE_CONTENT');
            return false;
        }
    });
    
    document.addEventListener('cut', (e) => {
        if (e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            handleViolation('CUT_CONTENT');
            return false;
        }
    });
    
    // ========== ANTI FLOATING SCREEN & BLUR (IMPLEMENTASI UTAMA) ==========
    if (!isMobile) {
        let mouseLeftWindow = false;
        
        const handleMouseLeave = () => {
            if (!isSubmitting && fullscreenLocked && !isInCountdown) {
                mouseLeftWindow = true;
                
                // 1. BLUR BOMB: KABURKAN LAYAR SEKARANG
                applyBlur();
                
                setTimeout(() => {
                    if (mouseLeftWindow && !isDisqualified && !isInCountdown) {
                        handleViolation('CURSOR_EXIT_FULLSCREEN_AREA');
                        mouseLeftWindow = false;
                    }
                }, 200);
            }
        };

        const handleMouseEnter = () => {
            mouseLeftWindow = false;
            // Hapus blur saat kembali
            removeBlur();
        };

        document.addEventListener('mouseleave', handleMouseLeave);
        document.addEventListener('mouseenter', handleMouseEnter);

        const handleWindowBlur = () => {
            if (isHandlingFullscreen || isSubmitting || isDisqualified || isInCountdown) return;
            
            // 1. BLUR BOMB: KABURKAN LAYAR SEKARANG (KLIK APP LAIN)
            applyBlur();
            
            // 2. LAPOR PELANGGARAN
            handleViolation('WINDOW_LOST_FOCUS_BLUR');
        };

        const handleWindowFocus = () => {
            // Hapus blur saat fokus kembali
            removeBlur();
        };

        window.addEventListener('blur', handleWindowBlur);
        window.addEventListener('focus', handleWindowFocus);
    }
    
    // ========== PROTEKSI REFRESH & BACK ==========
    window.addEventListener('beforeunload', (e) => {
        if(!isSubmitting) {
            const answered = TOTAL_SOAL - Object.keys(jawabanState || {}).length;
            if(answered > 0 || !isDisqualified) {
                e.preventDefault();
                e.returnValue = '⚠️ Ujian sedang berlangsung!';
                return e.returnValue;
            }
        }
    });
    
    // ========== MANAJEMEN JAWABAN ==========
    let jawabanState = {};
    
    function updateJawabanStatus() {
        let answeredCount = 0;
        jawabanState = {};
        
        navButtons.forEach(btn => btn.classList.remove('answered'));
        
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const match = radio.name.match(/\[(\d+)\]/);
            if(match) {
                const soalId = match[1];
                const btn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
                if(btn) {
                    btn.classList.add('answered');
                    jawabanState[soalId] = true;
                }
            }
        });
        
        document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
            if(textarea.value.trim() !== '') {
                const match = textarea.name.match(/\[(\d+)\]/);
                if(match) {
                    const soalId = match[1];
                    const btn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
                    if(btn) {
                        btn.classList.add('answered');
                        jawabanState[soalId] = true;
                    }
                }
            }
        });
        
        answeredCount = Object.keys(jawabanState).length;
        const percentage = (answeredCount / TOTAL_SOAL) * 100;
        
        if(progressBar) progressBar.value = percentage;
        if(progressText) progressText.innerText = `${answeredCount}/${TOTAL_SOAL} soal terjawab`;
        
        saveAnswersToLocalStorage();
        return answeredCount;
    }
    
    function saveAnswersToLocalStorage() {
        const answers = {};
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const match = radio.name.match(/\[(\d+)\]/);
            if(match) answers[match[1]] = radio.value;
        });
        document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
            const match = textarea.name.match(/\[(\d+)\]/);
            if(match && textarea.value.trim() !== '') {
                answers[match[1]] = textarea.value;
            }
        });
        localStorage.setItem(`ujian_jawaban_${UJIAN_ID}`, JSON.stringify(answers));
    }
    
    function loadAnswersFromLocalStorage() {
        const saved = localStorage.getItem(`ujian_jawaban_${UJIAN_ID}`);
        if(saved) {
            try {
                const answers = JSON.parse(saved);
                Object.keys(answers).forEach(soalId => {
                    const input = document.querySelector(`[name="jawaban[${soalId}]"]`);
                    if(input) {
                        if(input.type === 'radio') {
                            const radio = document.querySelector(`[name="jawaban[${soalId}]"][value="${answers[soalId]}"]`);
                            if(radio) radio.checked = true;
                        } else if(input.tagName === 'TEXTAREA') {
                            input.value = answers[soalId];
                        }
                    }
                });
                updateJawabanStatus();
            } catch(e) {}
        }
    }
    
    // ========== NAVIGASI & LOGIKA TOMBOL ==========
    function showSoal(index) {
        if(index < 0) index = 0;
        if(index >= TOTAL_SOAL) index = TOTAL_SOAL - 1;
        
        soalContainers.forEach(container => {
            container.classList.remove('active');
        });
        
        if(soalContainers[index]) {
            soalContainers[index].classList.add('active');
        }
        
        if(soalHeader) soalHeader.innerText = `Soal ${index + 1} dari ${TOTAL_SOAL}`;
        
        if(prevBtn) prevBtn.disabled = (index === 0);
        
        // LOGIKA BARU: Toggle tombol Next / Submit
        const isLastQuestion = (index === TOTAL_SOAL - 1);
        
        if (isLastQuestion) {
            // Di soal terakhir: Sembunyikan Next, Tampilkan Submit
            if (nextBtnContainer) nextBtnContainer.style.display = 'none';
            if (submitBtnContainer) submitBtnContainer.style.display = 'inline-block';
            // Disable tombol next di JS jaga-jaga
            if(nextBtn) nextBtn.disabled = true;
        } else {
            // Bukan soal terakhir: Tampilkan Next, Sembunyikan Submit
            if (nextBtnContainer) nextBtnContainer.style.display = 'inline-block';
            if (submitBtnContainer) submitBtnContainer.style.display = 'none';
            if(nextBtn) nextBtn.disabled = false;
        }
        
        navButtons.forEach((btn, i) => {
            btn.classList.remove('current');
            if(i === index) btn.classList.add('current');
        });
        
        currentSoalIndex = index;
        localStorage.setItem(`ujian_current_soal_${UJIAN_ID}`, index);
    }
    
    function loadCurrentSoal() {
        const saved = localStorage.getItem(`ujian_current_soal_${UJIAN_ID}`);
        if(saved && !isNaN(parseInt(saved))) {
            const index = parseInt(saved);
            if(index >= 0 && index < TOTAL_SOAL) {
                showSoal(index);
                return;
            }
        }
        showSoal(0);
    }
    
    // ========== SUBMIT UJIAN ==========
    function submitFormOtomatis() {
        if(isSubmitting || !form) return;
        isSubmitting = true;
        stopTimer();
        stopCountdownTimer();
        
        fullscreenLocked = false;
        if(forceFullscreenInterval) {
            clearInterval(forceFullscreenInterval);
        }
        
        if(document.exitFullscreen && !isMobile) {
            document.exitFullscreen().catch(() => {});
        }
        
        cleanupAndSubmit();
    }
    
    function cleanupAndSubmit() {
        localStorage.removeItem(`ujian_jawaban_${UJIAN_ID}`);
        localStorage.removeItem(`ujian_current_soal_${UJIAN_ID}`);
        if(form) {
            form.submit();
        }
    }
    
    // ========== INISIALISASI ==========
    function init() {
        console.log('🔒 Sistem keamanan ujian AKTIF');
        console.log('🚫 Dilarang: Fullscreen exit, Switch Tab, Copy/Paste, Alt+Tab');
        console.log('🌫️ BLUR BOMB: Aktif (Split Screen akan disamarkan)');
        console.log('⚠️ ' + MAX_VIOLATION + ' pelanggaran = Ujian Dikunci');
        
        // **TAMBAHKAN INI DI PALING ATAS INIT**
        // Cek apakah sebelumnya sudah kunci atau waktu habis
        if (loadSecurityState()) {
            // Jika function loadSecurityState return true, 
            // berarti sistem sudah dikunci atau waktu habis. Stop semua inisialisasi.
            return; 
        }
        
        initFullscreenMode();
        loadAnswersFromLocalStorage();
        loadCurrentSoal(); // Ini akan memanggil showSoal(0) atau index tersimpan
        updateJawabanStatus();
        startTimer();
        
        document.addEventListener('change', updateJawabanStatus);
        document.addEventListener('input', (e) => {
            if(e.target && e.target.matches && e.target.matches('textarea.jawaban-text, input[type="radio"]')) {
                updateJawabanStatus();
            }
        });
        
        navButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(index);
            });
        });
        
        if(prevBtn) {
            prevBtn.addEventListener('click', () => {
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex - 1);
            });
        }
        
        if(nextBtn) {
            nextBtn.addEventListener('click', () => {
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex + 1);
            });
        }
        
        if(form) {
            form.addEventListener('submit', (e) => {
                if(isSubmitting || isDisqualified) {
                    e.preventDefault();
                    return false;
                }
                
                const unanswered = TOTAL_SOAL - Object.keys(jawabanState).length;
                if(unanswered > 0) {
                    const confirmSubmit = confirm(`⚠️ Masih ada ${unanswered} soal belum dijawab.\n\nYakin submit?`);
                    if(!confirmSubmit) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                isSubmitting = true;
                stopTimer();
                stopCountdownTimer();
                return true;
            });
        }
    }
    
    if(document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
</script>
</body>
</html>