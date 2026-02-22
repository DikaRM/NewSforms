<!DOCTYPE html>
<html lang="en">
<head>
  <meta chars  et="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    
    .nav-btn.warning {
      background-color: #ffe08a;
      border-color: #ffdd57;
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
  </style>
</head>
<body>
  <div class="columns">
    {{-- Kolom Kiri: Navigasi Soal --}}
    <div class="column is-3">
      <div class="card soal-navigator">
        <div class="card-header">
          <p class="card-header-title">Navigasi Soal</p>
        </div>
        <div class="card-content">
          {{-- Informasi Siswa --}}
          <div class="info-panel">
            <p><strong>{{$ire->nama}}</strong></p>
            <p>Kelas: {{$sis->kelas->nama_kelas}}</p>
            <p>Mapel: {{$uji->mapels->nama_mapel}}</p>
            <p>Waktu: <span id="display" class="has-text-weight-bold"></span></p>
          </div>
          
          {{-- Grid Nomor Soal --}}
          <div class="nav-grid" id="soalNavGrid">
            @foreach($soal as $index => $s)
              @php
                $soalId = $s->id;
                $isAnswered = false;
                // Cek apakah soal ini sudah dijawab (akan diisi JavaScript)
              @endphp
              <button class="nav-btn" data-soal-id="{{$s->id}}" data-index="{{$index}}">
                {{$index + 1}}
              </button>
            @endforeach
          </div>
          
          {{-- Legenda --}}
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
          </div>
          
          {{-- Ringkasan Jawaban --}}
          <div class="mt-4">
            <progress class="progress is-success" id="progressBar" value="0" max="100">0%</progress>
            <p class="has-text-centered" id="progressText">0/{{count($soal)}} soal terjawab</p>
          </div>
        </div>
      </div>
    </div>
    
    {{-- Kolom Kanan: Soal --}}
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
            
            {{-- Container Soal --}}
            <div id="soalContainer">
              @foreach($soal as $index => $s)
                <div class="soal-container" data-soal-id="{{$s->id}}" data-index="{{$index}}">
                  <h5 class="subtitle">{{$index + 1}}. {{$s->soal}}</h5>
                  
                  @if($s->opsi_a != null)
                    <div class="control">
                      <label class="radio">
                        <input type="radio" name="jawaban[{{$s->id}}]" value="a" 
                               class="jawaban-radio" data-soal-id="{{$s->id}}">
                        {{$s->opsi_a}}
                      </label>
                      <label class="radio">
                        <input type="radio" name="jawaban[{{$s->id}}]" value="b"
                               class="jawaban-radio" data-soal-id="{{$s->id}}">
                        {{$s->opsi_b}}
                      </label>
                      <label class="radio">
                        <input type="radio" name="jawaban[{{$s->id}}]" value="c"
                               class="jawaban-radio" data-soal-id="{{$s->id}}">
                        {{$s->opsi_c}}
                      </label>
                      <label class="radio">
                        <input type="radio" name="jawaban[{{$s->id}}]" value="d"
                               class="jawaban-radio" data-soal-id="{{$s->id}}">
                        {{$s->opsi_d}}
                      </label>
                    </div>
                  @else
                    <input type="text" class="input jawaban-text" 
                           name="jawaban[{{$s->id}}]" 
                           placeholder="Jawaban Kamu"
                           data-soal-id="{{$s->id}}">
                  @endif
                </div>
              @endforeach
            </div>
            
            {{-- Navigasi Previous/Next --}}
            <div class="nav-controls">
              <button type="button" class="button is-info" id="prevBtn" disabled>
                Sebelumnya
              </button>
              <button type="button" class="button is-info" id="nextBtn">
                Berikutnya
              </button>
            </div>
            
            {{-- Tombol Submit --}}
            <div class="has-text-centered">
              <button type="submit" class="button is-success is-large" id="submitBtn">
                Submit Ujian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script>
    // ENHANCED UJIAN SYSTEM WITH NAVIGATION
(function() {
    'use strict';
    
    // ========== KONFIGURASI ==========
    let warn = 0;
    const maxwarn = 3;
    let timerInterval = null;
    let waktuTersisa = 0;
    let isSubmitting = false;
    let isFullscreenForced = false;
    let antiCheatInitialized = false;
    let currentSoalIndex = 0;
    const totalSoal = {{count($soal)}};
    let jawabanState = {};
    let isVisibilityCheckEnabled = true;
    
    // ========== ELEMEN DOM ==========
    const soalContainers = document.querySelectorAll('.soal-container');
    // PERBAIKAN 1: Selektor yang benar untuk tombol navigasi
    const navButtons = document.querySelectorAll('#soalNavGrid .nav-btn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const soalHeader = document.getElementById('soalHeader');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const form = document.getElementById("form");
    
    // ========== FUNGSI NAVIGASI ==========
    
    // Tampilkan soal berdasarkan index
    function showSoal(index) {
      // Validasi index
      if(index < 0) index = 0;
      if(index >= totalSoal) index = totalSoal - 1;
      
      // Sembunyikan semua soal
      soalContainers.forEach(container => {
        container.classList.remove('active');
      });
      
      // Tampilkan soal yang dipilih
      if(soalContainers[index]) {
        soalContainers[index].classList.add('active');
      }
      
      // Update header
      if(soalHeader) soalHeader.innerText = `Soal ${index + 1} dari ${totalSoal}`;
      
      // Update status tombol navigasi
      if(prevBtn) prevBtn.disabled = (index === 0);
      if(nextBtn) nextBtn.disabled = (index === totalSoal - 1);
      
      // Update highlight di grid navigasi
      navButtons.forEach((btn, i) => {
        btn.classList.remove('current');
        if(i === index) {
          btn.classList.add('current');
        }
      });
      
      currentSoalIndex = index;
    }
    
    // Update status jawaban di grid
    function updateJawabanStatus() {
      let answeredCount = 0;
      
      // Reset status answered dan warning
      navButtons.forEach(btn => {
        btn.classList.remove('answered', 'warning');
      });
      
      // Reset jawabanState
      jawabanState = {};
      
      // Cek radio buttons
      document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        const match = radio.name.match(/\[(\d+)\]/);
        if(match) {
          const soalId = match[1];
          const navBtn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
          if(navBtn) {
            navBtn.classList.add('answered');
            jawabanState[soalId] = true;
          }
        }
      });
      
      // Cek text inputs
      document.querySelectorAll('input[type="text"]').forEach(input => {
        if(input.value.trim() !== '') {
          const match = input.name.match(/\[(\d+)\]/);
          if(match) {
            const soalId = match[1];
            const navBtn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
            if(navBtn) {
              navBtn.classList.add('answered');
              jawabanState[soalId] = true;
            }
          }
        }
      });
      
      // Hitung yang sudah dijawab
      answeredCount = Object.keys(jawabanState).length;
      
      // Hitung persentase
      const percentage = totalSoal > 0 ? (answeredCount / totalSoal) * 100 : 0;
      
      // Update progress bar
      if(progressBar) progressBar.value = percentage;
      if(progressText) progressText.innerText = `${answeredCount}/${totalSoal} soal terjawab`;
      
      return answeredCount;
    }
    
    // Cek soal belum dijawab
    function cekSoalBelumDijawab() {
      updateJawabanStatus();
      return totalSoal - Object.keys(jawabanState).length;
    }
    
    // ========== TIMER SYSTEM ==========
    function startTimer(durasi, display) {
      if(timerInterval) clearInterval(timerInterval);
      
      waktuTersisa = parseInt(durasi) || 0;
      
      if(waktuTersisa <= 0) {
        if(display) display.innerText = "00:00";
        submitFormOtomatis();
        return;
      }
      
      timerInterval = setInterval(function() {
        if(waktuTersisa <= 0) {
          clearInterval(timerInterval);
          submitFormOtomatis();
          return;
        }
        
        let menit = parseInt(waktuTersisa / 60, 10);
        let detik = parseInt(waktuTersisa % 60, 10);
        menit = menit < 10 ? "0" + menit : menit;
        detik = detik < 10 ? "0" + detik : detik;
        
        if(display) display.innerText = menit + ":" + detik;
        waktuTersisa--;
        
      }, 1000);
    }
    
    function submitFormOtomatis() {
      if(isSubmitting || !form) return;
      
      isSubmitting = true;
      isVisibilityCheckEnabled = false;
      
      const unanswered = cekSoalBelumDijawab();
      
      if(unanswered > 0) {
        if(confirm(`Waktu habis! Masih ada ${unanswered} soal belum dijawab. Submit sekarang?`)) {
          localStorage.removeItem('ujian_' + {{$uji->id}});
          form.submit();
        } else {
          isSubmitting = false;
          isVisibilityCheckEnabled = true;
          alert("Segera selesaikan ujian Anda!");
        }
      } else {
        localStorage.removeItem('ujian_' + {{$uji->id}});
        form.submit();
      }
    }
    
    // ========== FULLSCREEN SYSTEM ==========
    async function forceFullscreen() {
      if(isFullscreenForced) return;
      isFullscreenForced = true;
      
      try {
        await document.documentElement.requestFullscreen();
      } catch(err) {
        console.log('Fullscreen tidak diizinkan:', err);
      }
      
      setTimeout(() => {
        isFullscreenForced = false;
      }, 1000);
    }
    
    // ========== ANTI CHEAT SYSTEM ==========
    function initAntiCheat() {
      if(antiCheatInitialized) return;
      antiCheatInitialized = true;
      
      let lastVisibilityTime = Date.now();
      
      document.addEventListener("visibilitychange", function() {
        if(isSubmitting || !isVisibilityCheckEnabled) {
          return;
        }
        
        const now = Date.now();
        
        if(document.visibilityState === "hidden") {
          if(now - lastVisibilityTime > 2000) {
            warn++;
            lastVisibilityTime = now;
            
            if(warn >= maxwarn) {
              alert("Peringatan maksimum! Ujian akan disubmit.");
              
              const uj = document.getElementById("ujian_id")?.value;
              const siswa = document.getElementById("siswa_id")?.value;
              
              if(uj && siswa) {
                fetch("{{ route('pengawas.pelanggaran.pen') }}", {
                  method: "POST",
                  headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                  },
                  body: JSON.stringify({
                    "ujian_id": uj,
                    "siswa_id": siswa,
                    "jenis_pelanggaran": "keluar Dari tab"
                  })
                })
                .then(res => res.json())
                .then(data => {
                  if(data.redirect && !isSubmitting) {
                    window.location.href = data.redirect;
                  }
                })
                .catch(err => console.error('Error:', err));
              }
              
              if(!isSubmitting) {
                submitFormOtomatis();
              }
            } else {
              alert(`Peringatan ${warn}/${maxwarn}: Jangan keluar dari halaman ujian!`);
            }
          }
        }
      });
      
      // Blok copy-paste
      document.addEventListener("copy", e => { 
        if(!isSubmitting) {
          e.preventDefault(); 
          alert("Tidak diperbolehkan menyalin!");
        }
      });
      document.addEventListener("paste", e => { 
        if(!isSubmitting) {
          e.preventDefault(); 
          alert("Tidak diperbolehkan menempel!");
        }
      });
      document.addEventListener("cut", e => { 
        if(!isSubmitting) {
          e.preventDefault(); 
          alert("Tidak diperbolehkan memotong!");
        }
      });
      
      // Blok klik kanan
      document.addEventListener("contextmenu", e => { 
        if(!isSubmitting) {
          e.preventDefault(); 
          alert("Klik kanan tidak diperbolehkan!");
        }
      });
      
      // Fullscreen handler
      document.addEventListener("fullscreenchange", function() {
        if(!document.fullscreenElement && !isFullscreenForced && !isSubmitting) {
          forceFullscreen();
        }
      });
      
      // Cegah keyboard shortcuts
      document.addEventListener("keydown", function(e) {
        if(!isSubmitting) {
          if(e.ctrlKey || e.metaKey) {
            switch(e.key.toLowerCase()) {
              case 'c': case 'v': case 'x': case 'p': case 's':
                e.preventDefault();
                break;
            }
          }
          
          if(e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
            e.preventDefault();
          }
        }
      });
    }
    
    // ========== LOAD JAWABAN TERSIMPAN ==========
    function loadJawabanTersimpan() {
      const savedAnswers = localStorage.getItem('ujian_' + {{$uji->id}});
      if(savedAnswers) {
        try {
          const answers = JSON.parse(savedAnswers);
          Object.keys(answers).forEach(soalId => {
            const input = document.querySelector(`[name="jawaban[${soalId}]"]`);
            if(input) {
              if(input.type === 'radio') {
                const radio = document.querySelector(`[name="jawaban[${soalId}]"][value="${answers[soalId]}"]`);
                if(radio) radio.checked = true;
              } else {
                input.value = answers[soalId];
              }
            }
          });
          updateJawabanStatus();
        } catch(e) {
          console.log('Error loading saved answers:', e);
        }
      }
    }
    
    // ========== AUTO-SAVE JAWABAN ==========
    function initAutoSave() {
      document.addEventListener('change', function(e) {
        if(e.target.matches('input[type="radio"], input[type="text"]')) {
          saveJawaban();
        }
      });
      
      let timeout;
      document.addEventListener('input', function(e) {
        if(e.target.matches('input[type="text"]')) {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            saveJawaban();
          }, 500);
        }
      });
    }
    
    function saveJawaban() {
      const answers = {};
      document.querySelectorAll('input[name^="jawaban"]').forEach(input => {
        const match = input.name.match(/\[(\d+)\]/);
        if(match) {
          const soalId = match[1];
          if(input.type === 'radio') {
            if(input.checked) {
              answers[soalId] = input.value;
            }
          } else if(input.type === 'text') {
            if(input.value.trim() !== '') {
              answers[soalId] = input.value;
            }
          }
        }
      });
      localStorage.setItem('ujian_' + {{$uji->id}}, JSON.stringify(answers));
      updateJawabanStatus();
    }
    
    // ========== FORM SUBMIT HANDLER ==========
    function initFormSubmit() {
      if(!form) return;
      
      form.addEventListener("submit", function(e) {
        if(isSubmitting) {
          e.preventDefault();
          return false;
        }
        
        isSubmitting = true;
        isVisibilityCheckEnabled = false;
        
        if(timerInterval) {
          clearInterval(timerInterval);
          timerInterval = null;
        }
        
        const unanswered = cekSoalBelumDijawab();
        
        if(unanswered > 0) {
          const confirmSubmit = confirm(`Masih ada ${unanswered} soal belum dijawab. Yakin submit?`);
          
          if(!confirmSubmit) {
            e.preventDefault();
            isSubmitting = false;
            isVisibilityCheckEnabled = true;
            return false;
          }
        }
        
        localStorage.removeItem('ujian_' + {{$uji->id}});
        return true;
      });
    }
    
    // ========== INITIALIZATION ==========
    window.addEventListener('DOMContentLoaded', function() {
      // Timer
      const display = document.getElementById("display");
      if(display) {
        const durasi = {{ $uji->durasi * 60 ?? 0 }};
        startTimer(durasi, display);
      }
      
      // Load jawaban tersimpan
      loadJawabanTersimpan();
      
      // Inisialisasi navigasi
      if(soalContainers.length > 0) {
        showSoal(0);
        updateJawabanStatus();
      }
      
      // Event listener untuk grid navigasi
      navButtons.forEach((btn, index) => {
        btn.addEventListener('click', function() {
          if(!isSubmitting) {
            showSoal(index);
          }
        });
      });
      
      // Event listener untuk tombol Previous/Next
      if(prevBtn) {
        prevBtn.addEventListener('click', function() {
          if(!isSubmitting) {
            showSoal(currentSoalIndex - 1);
          }
        });
      }
      
      if(nextBtn) {
        nextBtn.addEventListener('click', function() {
          if(!isSubmitting) {
            showSoal(currentSoalIndex + 1);
          }
        });
      }
      
      // Auto-save
      initAutoSave();
      
      // Anti cheat
      initAntiCheat();
      
      // Form submit handler
      initFormSubmit();
      
      // Fullscreen otomatis
      setTimeout(() => {
        forceFullscreen();
      }, 500);
    });
    
    // Cleanup
    window.addEventListener('beforeunload', function() {
      if(timerInterval) clearInterval(timerInterval);
    });
    
})();
</script>
</body>
</html>