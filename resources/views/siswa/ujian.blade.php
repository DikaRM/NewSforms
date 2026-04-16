<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ujian {{$uji->nama_ujian}}</title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
  <style>
    /* Style tambahan untuk navigasi */
    .soal-navigator {
      position: sticky;
      top: 20px;
      max-height: calc(100vh - 40px);
      overflow-y: auto;
    }
    
    .soal-container {
      display: none;
    }
    
    .soal-container.active {
      display: block;
    }
    
    .nav-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 5px;
      margin-bottom: 15px;
    }
    
    .nav-btn {
      width: 40px;
      height: 40px;
      border-radius: 5px;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
      font-weight: bold;
      transition: all 0.2s;
    }
    
    .nav-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .nav-btn.answered {
      background-color: #48c774;
      color: white;
      border-color: #48c774;
    }
    
    .nav-btn.current {
      background-color: #3273dc;
      color: white;
      border-color: #3273dc;
    }
    
    .nav-btn.essay {
      border: 2px solid #ffdd57;
    }
    
    .nav-btn.essay.answered {
      background-color: #ffdd57;
      color: #333;
    }
    
    .nav-controls {
      display: flex;
      justify-content: space-between;
      margin: 20px 0;
    }
    
    .info-panel {
      margin-bottom: 15px;
      padding: 10px;
      background: #f5f5f5;
      border-radius: 5px;
    }
    
    .legend {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      margin-top: 10px;
      font-size: 0.8rem;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .legend-color {
      width: 20px;
      height: 20px;
      border-radius: 3px;
    }
    
    .soal-gambar {
      margin: 15px 0;
      text-align: center;
      border: 1px dashed #ddd;
      padding: 10px;
      border-radius: 5px;
      background: #fafafa;
    }
    
    .soal-gambar img {
      max-width: 100%;
      max-height: 300px;
      object-fit: contain;
      border-radius: 5px;
    }
    
    .opsi-container {
      margin: 20px 0;
    }
    
    .opsi-item {
      margin-bottom: 12px;
      padding: 10px;
      border-radius: 5px;
      transition: background 0.2s;
    }
    
    .opsi-item:hover {
      background: #f5f5f5;
    }
    
    .opsi-label {
      display: inline-block;
      width: 30px;
      height: 30px;
      line-height: 30px;
      text-align: center;
      background: #3273dc;
      color: white;
      border-radius: 50%;
      font-weight: bold;
      margin-right: 10px;
    }
    
    .radio {
      display: flex;
      align-items: center;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 8px;
      cursor: pointer;
    }
    
    .radio:hover {
      background: #f0f0f0;
    }
    
    .radio input[type="radio"] {
      margin-right: 15px;
      transform: scale(1.2);
    }
    
    .essay-container {
      margin: 20px 0;
    }
    
    .essay-label {
      font-weight: bold;
      color: #ffdd57;
      background: #333;
      display: inline-block;
      padding: 5px 10px;
      border-radius: 3px;
      margin-bottom: 10px;
    }
    
    .essay-input {
      width: 100%;
      padding: 15px;
      border: 2px solid #ffdd57;
      border-radius: 5px;
      font-size: 16px;
      min-height: 120px;
    }
    
    .soal-card {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .soal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .soal-nomor {
      background: #3273dc;
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-weight: bold;
    }
    
    .warning-toast {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #ff3860;
      color: white;
      padding: 12px 20px;
      border-radius: 5px;
      z-index: 10000;
      animation: slideIn 0.3s ease;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      max-width: 300px;
    }
    
    .fullscreen-warning {
      position: fixed;
      bottom: 20px;
      left: 20px;
      right: 20px;
      background: #ffdd57;
      color: #333;
      padding: 10px;
      text-align: center;
      z-index: 9999;
      border-radius: 5px;
      display: none;
      animation: bounce 0.5s ease;
    }
    
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    
    @media (max-width: 768px) {
      .columns {
        display: block;
      }
      .column.is-3 {
        width: 100%;
      }
      .soal-navigator {
        position: relative;
        top: 0;
        max-height: none;
        margin-bottom: 20px;
      }
      .nav-grid {
        grid-template-columns: repeat(8, 1fr);
      }
    }
  </style>
</head>
<body>
  <div class="columns">
    <div class="column is-3">
      <div class="card soal-navigator">
        <div class="card-header">
          <p class="card-header-title">Navigasi Soal</p>
        </div>
        <div class="card-content">
          <div class="info-panel">
            <p><strong>{{$ire->nama}}</strong></p>
            <p>Kelas: {{$sis->kelas->nama_kelas}}</p>
            <p>Mapel: {{$uji->mapels->nama_mapel}}</p>
            <p>Waktu: <span id="display" class="has-text-weight-bold has-text-danger"></span></p>
          </div>
          
          <div class="nav-grid" id="soalNavGrid">
            @foreach($soal as $index => $s)
              @php
                $tipe = $s->tipe ?? 'pg';
                $extraClass = $tipe == 'essay' ? 'essay' : '';
              @endphp
              <button class="nav-btn {{$extraClass}}" 
                      data-soal-id="{{$s->id}}" 
                      data-index="{{$index}}"
                      data-tipe="{{$tipe}}">
                {{$index + 1}}
                @if($tipe == 'essay')
                  <span style="font-size: 8px; display: block;">✍️</span>
                @endif
              </button>
            @endforeach
          </div>
          
          <div class="legend">
            <div class="legend-item">
              <div class="legend-color" style="background: #3273dc;"></div>
              <span>Sedang dikerjakan</span>
            </div>
            <div class="legend-item">
              <div class="legend-color" style="background: #48c774;"></div>
              <span>Sudah dijawab</span>
            </div>
            <div class="legend-item">
              <div class="legend-color" style="background: white; border: 1px solid #ddd;"></div>
              <span>Belum dijawab</span>
            </div>
            <div class="legend-item">
              <div class="legend-color" style="background: #ffdd57;"></div>
              <span>Essay</span>
            </div>
          </div>
          
          <div class="mt-4">
            <progress class="progress is-success" id="progressBar" value="0" max="100">0%</progress>
            <p class="has-text-centered" id="progressText">0/{{count($soal)}} soal terjawab</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="column is-9">
      <div class="card">
        <div class="card-header">
          <p class="card-header-title" id="soalHeader">Soal 1 dari {{count($soal)}}</p>
        </div>
        
        <div class="card-content">
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
                        @if($tipe == 'essay') Essay @else Pilihan Ganda @endif
                      </span>
                    </div>
                    
                    <h5 class="subtitle is-5">{{$s->soal}}</h5>
                    
                    @if($s->gambar)
                        <div class="soal-gambar">
                            <img src="{{ Storage::url($s->gambar) }}" 
                                 alt="Gambar soal {{$index + 1}}"
                                 onclick="showImageModal('{{ Storage::url($s->gambar) }}')"
                                 style="cursor: pointer; max-width: 100%;">
                            <p class="is-size-7 has-text-grey mt-2">Klik gambar untuk memperbesar</p>
                        </div>
                    @endif
                    
                    @if($tipe == 'pg')
                      <div class="opsi-container">
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="a" class="jawaban-radio">
                          <span class="opsi-label">A</span>
                          <span>{{$s->opsi_a}}</span>
                        </label>
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="b" class="jawaban-radio">
                          <span class="opsi-label">B</span>
                          <span>{{$s->opsi_b}}</span>
                        </label>
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="c" class="jawaban-radio">
                          <span class="opsi-label">C</span>
                          <span>{{$s->opsi_c}}</span>
                        </label>
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="d" class="jawaban-radio">
                          <span class="opsi-label">D</span>
                          <span>{{$s->opsi_d}}</span>
                        </label>
         @if(!empty($s->opsi_e))
        <label class="radio opsi-item">
            <input type="radio" name="jawaban[{{$s->id}}]" value="e" class="jawaban-radio">
          <span class="opsi-label">E</span>
          <span>{{$s->opsi_e}}</span>
        </label>
        @endif
                      </div>
                    @else
                      <div class="essay-container">
                        <div class="essay-label">JAWABAN ESSAY</div>
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
            
            <div class="nav-controls">
              <button type="button" class="button is-info" id="prevBtn" disabled>Sebelumnya</button>
              <button type="button" class="button has-text-light" style="background:#2e5b9a;" id="nextBtn">Berikutnya</button>
            </div>
            
            <div class="has-text-centered mt-5">
              <button type="submit" class="button is-large has-text-light" style="background:#2e5b9a;" id="submitBtn">
                Submit Ujian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="imageModal">
    <div class="modal-background"></div>
    <div class="modal-content">
      <p class="image">
        <img src="" alt="Preview Gambar" id="modalImage">
      </p>
    </div>
    <button class="modal-close is-large" aria-label="close" onclick="closeImageModal()"></button>
  </div>

  <div class="fullscreen-warning" id="fullscreenWarning">
    <i class="fas fa-expand"></i> MODE FULLSCREEN WAJIB! Jangan keluar dari mode fullscreen!
  </div>


<script>
// ========== SISTEM KEAMANAN UJIAN (FIX VERSION) ==========
(function() {
    'use strict';
    
    // ========== KONFIGURASI ==========
    const UJIAN_ID = {{$uji->id ?? 0}};
    const SISWA_ID = {{$sis->id_siswa ?? 0}};
    const DURASI_AWAL = {{($uji->durasi ?? 0) * 60}};
    const TOTAL_SOAL = {{count($soal ?? [])}};
    const MAX_WARN = 3;
    const MULAI_UJIAN = Date.now();
    
    let isSubmitting = false;
    let currentSoalIndex = 0;
    let timerInterval = null;
    let waktuTersisa = DURASI_AWAL;
    
    // Counter pelanggaran
    let tabSwitchCount = 0;
    let copyPasteCount = 0;
    let devToolsCount = 0;
    let blurCount = 0;
    let fullscreenExitCount = 0;
    
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
    
    // ========== FUNGSI KIRIM PELANGGARAN ==========
    async function sendViolation(jenisPelanggaran, detail = null) {
        if (!UJIAN_ID || !SISWA_ID) return;
        
        try {
            const response = await fetch('{{route("siswa.violation")}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ujian_id: UJIAN_ID,
                    siswa_id: SISWA_ID,
                    jenis_pelanggaran: jenisPelanggaran,
                    detail: detail || `${jenisPelanggaran} terdeteksi`,
                    timestamp: new Date().toISOString()
                })
            });
            return await response.json();
        } catch(error) {
            console.error('Gagal kirim pelanggaran:', error);
        }
    }
    
    function showWarning(jenisPeringatan, currentCount, maxWarn) {
        const sisa = maxWarn - currentCount;
        alert(`⚠️ PERINGATAN ${currentCount}/${maxWarn}!\n\n${jenisPeringatan}\n\nSisa peringatan: ${sisa} kali lagi sebelum pelanggaran dicatat.`);
        showWarningToast(`⚠️ Peringatan ${currentCount}/${maxWarn}: ${jenisPeringatan}`);
        document.body.style.opacity = '0.7';
        setTimeout(() => { document.body.style.opacity = '1'; }, 500);
    }
    
    function showWarningToast(message) {
        const toast = document.createElement('div');
        toast.className = 'warning-toast';
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            if(toast.parentNode) toast.remove();
        }, 3000);
    }
    
    // ========== TIMER ANTI-REFRESH ==========
    function saveTimerToStorage() {
        if (waktuTersisa > 0 && UJIAN_ID) {
            localStorage.setItem(`ujian_timer_${UJIAN_ID}`, JSON.stringify({
                remainingTime: waktuTersisa,
                lastUpdated: Date.now(),
                startTime: MULAI_UJIAN
            }));
        }
    }
    
    function loadTimerFromStorage() {
        if (!UJIAN_ID) return false;
        
        const saved = localStorage.getItem(`ujian_timer_${UJIAN_ID}`);
        if (saved) {
            try {
                const data = JSON.parse(saved);
                const elapsed = Math.floor((Date.now() - data.lastUpdated) / 1000);
                waktuTersisa = data.remainingTime - elapsed;
                
                // Deteksi manipulasi waktu
                if (elapsed < 0 || waktuTersisa > data.remainingTime) {
                    sendViolation('TIME_MANIPULATION');
                    waktuTersisa = data.remainingTime;
                }
                
                if (waktuTersisa < 0) waktuTersisa = 0;
                return true;
            } catch(e) {}
        }
        waktuTersisa = DURASI_AWAL;
        return false;
    }
    
    function updateTimerDisplay() {
        if(!displayTimer) return;
        let menit = Math.floor(waktuTersisa / 60);
        let detik = waktuTersisa % 60;
        menit = menit < 10 ? "0" + menit : menit;
        detik = detik < 10 ? "0" + detik : detik;
        displayTimer.innerText = `${menit}:${detik}`;
        
        if(waktuTersisa <= 300 && waktuTersisa > 0) {
            displayTimer.classList.add('has-text-danger', 'has-text-weight-bold');
            if(waktuTersisa === 300) {
                alert('⚠️ PERINGATAN! Waktu tersisa 5 menit!');
            }
        }
    }
    
    function startTimer() {
        if(timerInterval) clearInterval(timerInterval);
        loadTimerFromStorage();
        
        if(waktuTersisa <= 0) {
            if(displayTimer) displayTimer.innerText = "00:00";
            submitFormOtomatis();
            return;
        }
        
        updateTimerDisplay();
        
        timerInterval = setInterval(() => {
            if(waktuTersisa <= 0) {
                clearInterval(timerInterval);
                localStorage.removeItem(`ujian_timer_${UJIAN_ID}`);
                submitFormOtomatis();
                return;
            }
            
            waktuTersisa--;
            updateTimerDisplay();
            saveTimerToStorage();
        }, 1000);
    }
    
    function stopTimer() {
        if(timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }
    
    // ========== FORCE FULLSCREEN (DESKTOP ONLY) ==========
    function isFullscreen() {
        return !!(document.fullscreenElement || document.webkitFullscreenElement);
    }
    
    function enableFullscreen() {
        const elem = document.documentElement;
        const requestMethod = elem.requestFullscreen || elem.webkitRequestFullscreen;
        if(requestMethod && !isMobile) {
            requestMethod.call(elem).catch(() => {});
        }
    }
    
    function initFullscreenMode() {
        if (isMobile) return;
        
        // Force fullscreen setiap 2 detik
        forceFullscreenInterval = setInterval(() => {
            if (!isSubmitting && fullscreenLocked && !isFullscreen()) {
                enableFullscreen();
                const warning = document.getElementById('fullscreenWarning');
                if(warning) warning.style.display = 'block';
                setTimeout(() => {
                    const warning = document.getElementById('fullscreenWarning');
                    if(warning) warning.style.display = 'none';
                }, 3000);
            }
        }, 2000);
        
        // Lock fullscreen exit
        document.addEventListener('fullscreenchange', () => {
            if (!isSubmitting && fullscreenLocked && !isFullscreen()) {
                fullscreenExitCount++;
                if(fullscreenExitCount <= MAX_WARN) {
                    showWarning('Jangan keluar dari mode fullscreen!', fullscreenExitCount, MAX_WARN);
                }
                if(fullscreenExitCount >= MAX_WARN) {
                    sendViolation('EXIT_FULLSCREEN');
                }
                enableFullscreen();
            }
        });
        
        // Cegah ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !isSubmitting && fullscreenLocked) {
                e.preventDefault();
                sendViolation('ESC_FULLSCREEN');
                showWarningToast('⚠️ Tombol ESC tidak dapat digunakan!');
                return false;
            }
            if (e.key === 'F11' && !isSubmitting && fullscreenLocked) {
                e.preventDefault();
                return false;
            }
        });
        
        setTimeout(enableFullscreen, 1000);
    }
    
    // ========== DETEKSI SWITCH TAB ==========
    let lastActiveTime = Date.now();
    
    document.addEventListener('visibilitychange', () => {
        if(document.hidden) {
            lastActiveTime = Date.now();
            tabSwitchCount++;
            
            if(tabSwitchCount <= MAX_WARN) {
                showWarning('Jangan keluar dari halaman ujian!', tabSwitchCount, MAX_WARN);
            }
            if(tabSwitchCount === MAX_WARN) {
                sendViolation('SWITCH_TAB');
                alert('❌ PELANGGARAN TERCATAT! Anda terlalu sering keluar dari halaman ujian.');
            } else if(tabSwitchCount > MAX_WARN) {
                sendViolation('SWITCH_TAB_REPEATED');
            }
            document.body.style.opacity = '0.5';
        } else {
            const inactiveDuration = Math.floor((Date.now() - lastActiveTime) / 1000);
            document.body.style.opacity = '1';
            if(inactiveDuration > 0 && inactiveDuration < 60) {
                showWarningToast(`✅ Kembali ke ujian (${inactiveDuration} detik)`);
            }
            if(inactiveDuration > 30 && tabSwitchCount >= MAX_WARN) {
                sendViolation('LONG_ABSENCE', `${inactiveDuration} detik`);
            }
        }
    });
    
    // ========== DETEKSI COPY-PASTE ==========
    document.addEventListener('copy', (e) => {
        if(e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            copyPasteCount++;
            if(copyPasteCount <= MAX_WARN) {
                showWarning('Copy tidak diizinkan!', copyPasteCount, MAX_WARN);
            }
            if(copyPasteCount === MAX_WARN) {
                sendViolation('COPY_CONTENT');
            }
            return false;
        }
    });
    
    document.addEventListener('paste', (e) => {
        if(e.target.tagName === 'TEXTAREA') {
            copyPasteCount++;
            if(copyPasteCount === MAX_WARN) {
                sendViolation('PASTE_ESSAY');
            }
        } else {
            e.preventDefault();
            copyPasteCount++;
            if(copyPasteCount <= MAX_WARN) {
                showWarning('Paste tidak diizinkan!', copyPasteCount, MAX_WARN);
            }
            if(copyPasteCount === MAX_WARN) {
                sendViolation('PASTE_CONTENT');
            }
            return false;
        }
    });
    
    document.addEventListener('cut', (e) => {
        if(e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            copyPasteCount++;
            if(copyPasteCount === MAX_WARN) {
                sendViolation('CUT_CONTENT');
            }
            return false;
        }
    });
    
    // ========== DETEKSI DEV TOOLS (DESKTOP ONLY) ==========
    if (!isMobile) {
        setInterval(() => {
            const start = performance.now();
            debugger;
            const end = performance.now();
            if (end - start > 100) {
                devToolsCount++;
                if(devToolsCount <= MAX_WARN) {
                    showWarning('Developer tools terdeteksi!', devToolsCount, MAX_WARN);
                }
                if(devToolsCount === MAX_WARN) {
                    sendViolation('DEV_TOOLS_OPEN');
                }
            }
        }, 5000);
        
        document.addEventListener('keydown', (e) => {
            const isDevTool = (e.key === 'F12') || 
                             (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                             (e.ctrlKey && e.key === 'U');
            if (isDevTool) {
                e.preventDefault();
                devToolsCount++;
                if(devToolsCount <= MAX_WARN) {
                    showWarning('Developer tools tidak diizinkan!', devToolsCount, MAX_WARN);
                }
                if(devToolsCount === MAX_WARN) {
                    sendViolation('DEV_TOOLS_SHORTCUT');
                }
                return false;
            }
        });
    }
    
    // ========== DETEKSI KELUAR APLIKASI (DESKTOP ONLY) ==========
    if (!isMobile) {
        let lastBlurTime = null;
        
        window.addEventListener('blur', () => {
            lastBlurTime = Date.now();
            blurCount++;
            if(blurCount <= MAX_WARN) {
                showWarning('Jangan beralih ke aplikasi lain!', blurCount, MAX_WARN);
            }
            if(blurCount === MAX_WARN) {
                sendViolation('SWITCH_APP');
            }
        });
        
        window.addEventListener('focus', () => {
            if(lastBlurTime) {
                const duration = Math.floor((Date.now() - lastBlurTime) / 1000);
                if(duration > 10 && blurCount >= MAX_WARN) {
                    sendViolation('LONG_ABSENCE', `${duration} detik`);
                }
                showWarningToast(`✅ Kembali ke ujian`);
            }
            document.body.style.opacity = '1';
        });
    }
    
    // ========== PROTEKSI REFRESH & BACK ==========
    window.addEventListener('beforeunload', (e) => {
        if(!isSubmitting) {
            const unanswered = TOTAL_SOAL - Object.keys(jawabanState || {}).length;
            if(unanswered > 0) {
                e.preventDefault();
                e.returnValue = '⚠️ Ujian sedang berlangsung!';
                return e.returnValue;
            }
        }
    });
    
    document.addEventListener('keydown', (e) => {
        if(e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
            e.preventDefault();
            showWarningToast('⚠️ Refresh tidak diizinkan!');
            return false;
        }
    });
    
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', () => {
        history.pushState(null, null, location.href);
        showWarningToast('⚠️ Tombol back/forward tidak diizinkan!');
        sendViolation('BACK_FORWARD');
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
    
    // ========== NAVIGASI ==========
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
        if(nextBtn) nextBtn.disabled = (index === TOTAL_SOAL - 1);
        
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
        
        // Unlock fullscreen
        fullscreenLocked = false;
        if(forceFullscreenInterval) {
            clearInterval(forceFullscreenInterval);
        }
        
        // Keluar dari fullscreen
        if(document.exitFullscreen && !isMobile) {
            document.exitFullscreen().catch(() => {});
        }
        
        cleanupAndSubmit();
    }
    
    function cleanupAndSubmit() {
        localStorage.removeItem(`ujian_timer_${UJIAN_ID}`);
        localStorage.removeItem(`ujian_jawaban_${UJIAN_ID}`);
        localStorage.removeItem(`ujian_current_soal_${UJIAN_ID}`);
        
        if(form) {
            form.submit();
        }
    }
    
    // ========== PREVIEW GAMBAR ==========
    window.showImageModal = function(imageUrl) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        if(modal && modalImg) {
            modalImg.src = imageUrl;
            modal.classList.add('is-active');
        }
    };
    
    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        if(modal) {
            modal.classList.remove('is-active');
        }
    };
    
    // ========== INISIALISASI ==========
    function init() {
        console.log('🔒 Sistem keamanan ujian aktif');
        
        initFullscreenMode();
        loadAnswersFromLocalStorage();
        loadCurrentSoal();
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
                if(!isSubmitting) showSoal(index);
            });
        });
        
        if(prevBtn) {
            prevBtn.addEventListener('click', () => {
                if(!isSubmitting) showSoal(currentSoalIndex - 1);
            });
        }
        
        if(nextBtn) {
            nextBtn.addEventListener('click', () => {
                if(!isSubmitting) showSoal(currentSoalIndex + 1);
            });
        }
        
        if(form) {
            form.addEventListener('submit', (e) => {
                if(isSubmitting) {
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