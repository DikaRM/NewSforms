<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
    
    /* Style untuk gambar soal */
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
    
    /* Style untuk opsi jawaban */
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
    
    .opsi-label.selected {
      background: #48c774;
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
    
    /* Style untuk soal essay */
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
    
    .essay-input:focus {
      outline: none;
      box-shadow: 0 0 5px rgba(255, 221, 87, 0.5);
    }
    
    /* Style untuk card soal */
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
    
    .soal-tipe {
      background: #f0f0f0;
      padding: 3px 10px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: bold;
    }
    
    .tipe-pg {
      color: #3273dc;
    }
    
    .tipe-essay {
      color: #ffdd57;
    }
    
    /* Responsive */
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
            <p>Waktu: <span id="display" class="has-text-weight-bold has-text-danger"></span></p>
          </div>
          
          {{-- Grid Nomor Soal --}}
          <div class="nav-grid" id="soalNavGrid">
            @foreach($soal as $index => $s)
              @php
                $tipe = $s->tipe ?? 'pilihan_ganda';
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
            <div class="legend-item">
              <div class="legend-color" style="background: #ffdd57;"></div>
              <span>Essay</span>
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
                @php
                  $tipe = $s->tipe ?? 'pilihan_ganda';
                @endphp
                <div class="soal-container" data-soal-id="{{$s->id}}" data-index="{{$index}}" data-tipe="{{$tipe}}">
                  <div class="soal-card">
                    {{-- Header Soal --}}
                    <div class="soal-header">
                      <span class="soal-nomor">Soal {{$index + 1}}</span>
                      <span class="soal-tipe {{$tipe == 'essay' ? 'tipe-essay' : 'tipe-pg'}}">
                        @if($tipe == 'essay')
                          <i class="fas fa-pencil-alt"></i> Essay
                        @else
                          <i class="fas fa-check-circle"></i> Pilihan Ganda
                        @endif
                      </span>
                    </div>
                    
                    {{-- Pertanyaan --}}
                    <h5 class="subtitle is-5">{{$s->soal}}</h5>
                    
                    {{-- Gambar Soal (jika ada) --}}
                    @if($s->gambar)
                      <div class="soal-gambar">
                        <img src="{{ Storage::url($s->gambar) }}" 
                             alt="Gambar soal {{$index + 1}}"
                             onclick="showImageModal('{{ Storage::url($s->gambar) }}')"
                             style="cursor: pointer;">
                        <p class="is-size-7 has-text-grey mt-2">Klik gambar untuk memperbesar</p>
                      </div>
                    @endif
                    
                    {{-- Opsi Jawaban --}}
                    @if($tipe == 'pilihan_ganda')
                      <div class="opsi-container">
                        <p class="has-text-weight-bold mb-3">Pilih jawaban yang benar:</p>
                        
                        {{-- Opsi A --}}
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="a" 
                                 class="jawaban-radio" data-soal-id="{{$s->id}}">
                          <span class="opsi-label">A</span>
                          <span>{{$s->opsi_a}}</span>
                        </label>
                        
                        {{-- Opsi B --}}
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="b"
                                 class="jawaban-radio" data-soal-id="{{$s->id}}">
                          <span class="opsi-label">B</span>
                          <span>{{$s->opsi_b}}</span>
                        </label>
                        
                        {{-- Opsi C --}}
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="c"
                                 class="jawaban-radio" data-soal-id="{{$s->id}}">
                          <span class="opsi-label">C</span>
                          <span>{{$s->opsi_c}}</span>
                        </label>
                        
                        {{-- Opsi D --}}
                        <label class="radio opsi-item">
                          <input type="radio" name="jawaban[{{$s->id}}]" value="d"
                                 class="jawaban-radio" data-soal-id="{{$s->id}}">
                          <span class="opsi-label">D</span>
                          <span>{{$s->opsi_d}}</span>
                        </label>
                      </div>
                    @else
                      {{-- Essay --}}
                      <div class="essay-container">
                        <p class="has-text-weight-bold mb-3">Tulis jawaban Anda:</p>
                        <div class="essay-label">JAWABAN ESSAY</div>
                        <textarea class="textarea essay-input jawaban-text" 
                                  name="jawaban[{{$s->id}}]" 
                                  placeholder="Tulis jawaban essay di sini..."
                                  rows="5"
                                  data-soal-id="{{$s->id}}">{{ old('jawaban.'.$s->id) }}</textarea>
                      </div>
                    @endif
                    
                    {{-- Hidden data peserta --}}
                    <div style="display: none;" 
                         data-peserta="{{$sis->id_siswa}}"
                         data-ujian="{{$uji->id}}"
                         data-soal-index="{{$index}}">
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            
            {{-- Navigasi Previous/Next --}}
            <div class="nav-controls">
              <button type="button" class="button is-info" id="prevBtn" disabled>
                <i class="fas fa-arrow-left"></i> Sebelumnya
              </button>
              <button type="button" class="button is-info" id="nextBtn">
                Berikutnya <i class="fas fa-arrow-right"></i>
              </button>
            </div>
            
            {{-- Tombol Submit --}}
            <div class="has-text-centered mt-5">
              <button type="submit" class="button is-success is-large" id="submitBtn">
                <i class="fas fa-check-circle"></i> Submit Ujian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal untuk preview gambar --}}
  <div class="modal" id="imageModal">
    <div class="modal-background"></div>
    <div class="modal-content">
      <p class="image">
        <img src="" alt="Preview Gambar" id="modalImage">
      </p>
    </div>
    <button class="modal-close is-large" aria-label="close" onclick="closeImageModal()"></button>
  </div>

  {{-- Font Awesome --}}
  <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

  <script>
  // ========== UJIAN SYSTEM DENGAN PROTEKSI TOTAL ==========
  (function() {
      'use strict';
      
      // ========== DETEKSI DEVICE ==========
      const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
      
      // ========== KONFIGURASI ==========
      let warn = 0;
      const maxwarn = isMobile ? 8 : 3;
      let timerInterval = null;
      let waktuTersisa = 0;
      let isSubmitting = false;
      let currentSoalIndex = 0;
      const totalSoal = {{count($soal)}};
      let jawabanState = {};
      
      // ========== ELEMEN DOM ==========
      const soalContainers = document.querySelectorAll('.soal-container');
      const navButtons = document.querySelectorAll('#soalNavGrid .nav-btn');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const soalHeader = document.getElementById('soalHeader');
      const progressBar = document.getElementById('progressBar');
      const progressText = document.getElementById('progressText');
      const form = document.getElementById("form");
      const displayTimer = document.getElementById("display");
      
      // ========== FUNGSI PREVIEW GAMBAR ==========
      window.showImageModal = function(imageUrl) {
          const modal = document.getElementById('imageModal');
          const modalImg = document.getElementById('modalImage');
          if(modal && modalImg) {
              modalImg.src = imageUrl;
              modal.classList.add('is-active');
              document.documentElement.classList.add('is-clipped');
          }
      };
      
      window.closeImageModal = function() {
          const modal = document.getElementById('imageModal');
          if(modal) {
              modal.classList.remove('is-active');
              document.documentElement.classList.remove('is-clipped');
          }
      };
      
      // Close modal with escape key
      document.addEventListener('keydown', function(e) {
          if(e.key === 'Escape') {
              closeImageModal();
          }
      });
      
      // ========== FUNGSI NAVIGASI ==========
      function showSoal(index) {
          if(index < 0) index = 0;
          if(index >= totalSoal) index = totalSoal - 1;
          
          soalContainers.forEach(container => {
              container.classList.remove('active');
          });
          
          if(soalContainers[index]) {
              soalContainers[index].classList.add('active');
          }
          
          if(soalHeader) soalHeader.innerText = `Soal ${index + 1} dari ${totalSoal}`;
          
          if(prevBtn) prevBtn.disabled = (index === 0);
          if(nextBtn) nextBtn.disabled = (index === totalSoal - 1);
          
          navButtons.forEach((btn, i) => {
              btn.classList.remove('current');
              if(i === index) {
                  btn.classList.add('current');
              }
          });
          
          currentSoalIndex = index;
      }
      
      // ========== UPDATE STATUS JAWABAN ==========
      function updateJawabanStatus() {
          let answeredCount = 0;
          
          navButtons.forEach(btn => {
              btn.classList.remove('answered');
          });
          
          jawabanState = {};
          
          // Cek pilihan ganda
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
          
          // Cek essay
          document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
              if(textarea.value.trim() !== '') {
                  const match = textarea.name.match(/\[(\d+)\]/);
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
          
          answeredCount = Object.keys(jawabanState).length;
          const percentage = totalSoal > 0 ? (answeredCount / totalSoal) * 100 : 0;
          
          if(progressBar) progressBar.value = percentage;
          if(progressText) progressText.innerText = `${answeredCount}/${totalSoal} soal terjawab`;
          
          return answeredCount;
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
          
          if(timerInterval) {
              clearInterval(timerInterval);
              timerInterval = null;
          }
          
          const unanswered = totalSoal - Object.keys(jawabanState).length;
          
          if(unanswered > 0) {
              if(confirm(`⏰ WAKTU HABIS!\n\nMasih ada ${unanswered} soal belum dijawab.\nSubmit sekarang?`)) {
                  localStorage.removeItem('ujian_' + {{$uji->id}});
                  localStorage.removeItem('ujian_active_' + {{$uji->id}});
                  form.submit();
              } else {
                  isSubmitting = false;
                  alert("Segera selesaikan ujian Anda!");
              }
          } else {
              localStorage.removeItem('ujian_' + {{$uji->id}});
              localStorage.removeItem('ujian_active_' + {{$uji->id}});
              form.submit();
          }
      }
      
      // ========== CEK MULTI TAB ==========
      (function checkMultiTab() {
          const sessionKey = 'ujian_active_' + {{$uji->id}};
          const sessionId = Math.random().toString(36).substring(2);
          
          if (localStorage.getItem(sessionKey)) {
              alert('⚠️ Ujian sudah dibuka di tab lain!');
              window.location.href = '/dashboard';
              return;
          }
          
          localStorage.setItem(sessionKey, sessionId);
          
          window.addEventListener('beforeunload', function() {
              localStorage.removeItem(sessionKey);
          });
      })();
      
      // ========== PROTEKSI SEDERHANA ==========
      document.addEventListener('contextmenu', e => e.preventDefault());
      document.addEventListener('keydown', function(e) {
          if(e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || (e.ctrlKey && e.shiftKey && e.key === 'J') || (e.ctrlKey && e.key === 'U')) {
              e.preventDefault();
              warn++;
              alert(`⚠️ PERINGATAN ${warn}/${maxwarn}: Inspeksi elemen diblokir!`);
          }
          
          if(e.ctrlKey && (e.key === 'p' || e.key === 'P')) {
              e.preventDefault();
              warn++;
              alert(`⚠️ PERINGATAN ${warn}/${maxwarn}: Print tidak diizinkan!`);
          }
      });
      
      // ========== INITIALIZATION ==========
      window.addEventListener('DOMContentLoaded', function() {
          // Timer
          if(displayTimer) {
              const durasi = {{ $uji->durasi * 60 ?? 0 }};
              startTimer(durasi, displayTimer);
          }
          
          // Load jawaban tersimpan
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
              } catch(e) {}
          }
          
          // Inisialisasi navigasi
          if(soalContainers.length > 0) {
              showSoal(0);
              updateJawabanStatus();
          }
          
          // Event listener navigasi
          navButtons.forEach((btn, index) => {
              btn.addEventListener('click', function() {
                  if(!isSubmitting) {
                      showSoal(index);
                  }
              });
          });
          
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
          document.addEventListener('change', updateJawabanStatus);
          document.addEventListener('input', function(e) {
              if(e.target.matches('textarea.jawaban-text')) {
                  updateJawabanStatus();
                  
                  // Auto-save ke localStorage
                  const answers = {};
                  document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                      const match = radio.name.match(/\[(\d+)\]/);
                      if(match) answers[match[1]] = radio.value;
                  });
                  document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
                      if(textarea.value.trim() !== '') {
                          const match = textarea.name.match(/\[(\d+)\]/);
                          if(match) answers[match[1]] = textarea.value;
                      }
                  });
                  localStorage.setItem('ujian_' + {{$uji->id}}, JSON.stringify(answers));
              }
          });
          
          // Form submit
          if(form) {
              form.addEventListener("submit", function(e) {
                  if(isSubmitting) {
                      e.preventDefault();
                      return false;
                  }
                  
                  const unanswered = totalSoal - Object.keys(jawabanState).length;
                  
                  if(unanswered > 0) {
                      const confirmSubmit = confirm(`⚠️ Masih ada ${unanswered} soal belum dijawab.\n\nYakin submit?`);
                      if(!confirmSubmit) {
                          e.preventDefault();
                          return false;
                      }
                  }
                  
                  isSubmitting = true;
                  
                  if(timerInterval) {
                      clearInterval(timerInterval);
                      timerInterval = null;
                  }
                  
                  localStorage.removeItem('ujian_' + {{$uji->id}});
                  localStorage.removeItem('ujian_active_' + {{$uji->id}});
                  
                  return true;
              });
          }
      });
      
  })();
  </script>
</body>
</html>