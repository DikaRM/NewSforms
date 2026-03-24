<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Penjadwalan Ujian - {{$klas->nama_kelas}}</title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary: #667eea;
      --secondary: #764ba2;
      --success: #48bb78;
      --warning: #f59e0b;
      --danger: #f56565;
      --info: #4299e1;
    }

    body {
      background: #f7fafc;
    }

    .day-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      margin-bottom: 24px;
      border: 1px solid #e2e8f0;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .day-card:hover {
      box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    
    .day-header {
      padding: 15px 25px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .day-header:hover {
      filter: brightness(1.05);
    }
    
    /* Warna untuk setiap hari */
    .day-header.sunday { background: linear-gradient(135deg, #f56565 0%, #c53030 100%); }
    .day-header.monday { background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%); }
    .day-header.tuesday { background: linear-gradient(135deg, #48bb78 0%, #2f855a 100%); }
    .day-header.wednesday { background: linear-gradient(135deg, #ecc94b 0%, #b7791f 100%); }
    .day-header.thursday { background: linear-gradient(135deg, #9f7aea 0%, #6b46c1 100%); }
    .day-header.friday { background: linear-gradient(135deg, #ed8936 0%, #c05621 100%); }
    .day-header.saturday { background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%); }
    
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
      border-left: 5px solid var(--info);
      transition: all 0.3s ease;
      position: relative;
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
      box-shadow: 0 8px 12px rgba(0,0,0,0.1);
      background: white;
    }
    
    .schedule-item.conflict {
      border-left-color: var(--danger);
      background: #fff5f5;
    }
    
    .time-badge {
      background: #ebf8ff;
      color: #2b6cb0;
      padding: 6px 12px;
      border-radius: 30px;
      font-size: 0.85rem;
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
      border-color: var(--primary);
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
      background: var(--primary);
      color: white;
    }
    
    .day-tab-btn.active {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
    }
    
    .conflict-indicator {
      background: #fed7d7;
      color: #c53030;
      padding: 4px 12px;
      border-radius: 30px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: 10px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    
    .quick-add {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 8px 15px rgba(102, 126, 234, 0.4);
      transition: all 0.3s ease;
      z-index: 1000;
    }
    
    .quick-add:hover {
      transform: scale(1.1) rotate(90deg);
      box-shadow: 0 12px 20px rgba(102, 126, 234, 0.6);
    }
    
    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .filter-section {
      background: white;
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 25px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      align-items: center;
    }
    
    .toast-notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      border-radius: 8px;
      padding: 15px 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 9999;
      animation: slideInRight 0.3s ease;
      border-left: 4px solid var(--success);
    }
    
    .toast-notification.error {
      border-left-color: var(--danger);
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
      border-top: 3px solid var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <section class="section">
    <div class="container">
      <!-- Header dengan Statistik -->
      <div class="level mb-5">
        <div class="level-left">
          <div>
            <h1 class="title is-3">
              <i class="fas fa-calendar-alt text-primary"></i> 
              Penjadwalan Ujian - {{$klas->nama_kelas}}
            </h1>
            <p class="subtitle is-6">
              <i class="fas fa-clock"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </p>
          </div>
        </div>
        <div class="level-right">
          <div class="tags are-medium">
            <span class="tag is-primary">
              <i class="fas fa-book"></i> {{$uji->where('status', 'ready')->count()}} Ujian Ready
            </span>
            <span class="tag is-info">
              <i class="fas fa-chalkboard-teacher"></i> {{$gur->count()}} Pengawas
            </span>
            <span class="tag is-success">
              <i class="fas fa-check-circle"></i> {{$jad->count()}} Terjadwal
            </span>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="filter-section">
        <div class="field is-grouped is-grouped-multiline">
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
          $readyUjian = $uji->where('status', 'ready');
        @endphp

        @foreach($days as $enDay => $idDay)
        <div class="day-card" data-day="{{$enDay}}">
          <div class="day-header {{strtolower($enDay)}}" onclick="toggleDay('{{$enDay}}')">
            <div>
              <span class="is-size-4 has-text-weight-bold">{{$idDay}}</span>
              <span class="tag is-light ml-2">
                {{ isset($groupedJad[$enDay]) ? $groupedJad[$enDay]->count() : 0 }} Jadwal
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
                  if($other->id != $jd->id) {
                    $start1 = \Carbon\Carbon::parse($jd->tanggal);
                    $end1 = \Carbon\Carbon::parse($jd->waktu_selesai);
                    $start2 = \Carbon\Carbon::parse($other->tanggal);
                    $end2 = \Carbon\Carbon::parse($other->waktu_selesai);
                    
                    if($start1 < $end2 && $end1 > $start2) {
                      $bentrok = true;
                      break;
                    }
                  }
                }
              @endphp
              <div class="schedule-item {{$bentrok ? 'conflict' : ''}}" data-pengawas="{{$jd->pengawas->guru_id}}">
                <div class="level">
                  <div class="level-left">
                    <div style="flex: 1;">
                      <div class="time-badge mb-2">
                        <i class="fas fa-clock"></i> 
                        {{ \Carbon\Carbon::parse($jd->tanggal)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($jd->waktu_selesai)->format('H:i') }}
                        @if($bentrok)
                          <span class="conflict-indicator">
                            <i class="fas fa-exclamation-triangle"></i> Bentrok!
                          </span>
                        @endif
                      </div>
                      <h5 class="title is-5 mb-1">{{$jd->ujian->nama_ujian}}</h5>
                      <div class="tags">
                        <span class="tag is-info is-light">
                          <i class="fas fa-chalkboard-teacher"></i> {{$jd->pengawas->guru->nama}}
                        </span>
                        <span class="tag is-warning is-light">
                          <i class="fas fa-hourglass-half"></i> {{$jd->ujian->durasi}} Menit
                        </span>
                        <span class="tag is-primary is-light">
                          <i class="fas fa-sort-numeric-up"></i> Jam ke-{{$jd->jam_mapal}}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="level-right">
                    <div class="buttons are-small">
                      <button class="button is-warning" onclick="editSchedule({{$jd->id}})">
                        <i class="fas fa-edit"></i>
                      </button>
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
                <p class="is-size-7 mt-2">Klik tombol + di bawah untuk menambah jadwal</p>
              </div>
            @endif

            <!-- Add Schedule Form for this Day -->
            <div class="add-schedule-form">
              <h5 class="title is-6 mb-3">
                <i class="fas fa-plus-circle" style="color: var(--primary);"></i> 
                Tambah Jadwal {{$idDay}}
              </h5>
              
              <form action="{{route('admin-ops.sav')}}" method="post" onsubmit="return validateForm(this, '{{$enDay}}')">
                @csrf
                
                <div class="columns is-multiline is-variable is-3">
                  <div class="column is-3">
                    <div class="field">
                      <label class="label is-small">Jam Ke-</label>
                      <div class="control">
                        <input type="number" class="input is-small" name="jam_mapel" min="1" placeholder="Contoh: 1" required>
                      </div>
                    </div>
                  </div>

                  <div class="column is-3">
                    <div class="field">
                      <label class="label is-small">Jam Mulai</label>
                      <div class="control">
                        <input type="time" class="input is-small" name="waktu_mulai" 
                               value="08:00" required>
                      </div>
                    </div>
                  </div>

                  <div class="column is-3">
                    <div class="field">
                      <label class="label is-small">Tanggal</label>
                      <div class="control">
                        <input type="date" class="input is-small" name="tanggal" 
                               value="{{ now()->format('Y-m-d') }}" 
                               onchange="updateDay('{{$enDay}}', this.value)" required>
                      </div>
                    </div>
                  </div>

                  <div class="column is-3">
                    <div class="field">
                      <label class="label is-small">Pilih Ujian</label>
                      <div class="control">
                        <div class="select is-small is-fullwidth">
                          <select name="ujian_id" required>
                            <option value="">Pilih Ujian</option>
                            @foreach($readyUjian as $uj)
                              <option value="{{$uj->id}}" data-durasi="{{$uj->durasi}}">
                                {{$uj->nama_ujian}} ({{$uj->durasi}} menit) - {{$uj->mapels->nama_mapel}}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="column is-3">
                    <div class="field">
                      <label class="label is-small">Pengawas</label>
                      <div class="control">
                        <div class="select is-small is-fullwidth">
                          <select name="guru_id" required>
                            <option value="">Pilih Pengawas</option>
                            @foreach($gur as $gu)
                            <option value="{{$gu->id}}">{{$gu->nama}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12">
                    <input type="hidden" name="kelas_id" value="{{$klas->id}}">
                    <input type="hidden" name="hari" value="{{$enDay}}">
                    
                    <div class="field is-grouped is-grouped-right mt-3">
                      <div class="control">
                        <button type="reset" class="button is-small is-light">
                          <i class="fas fa-undo"></i> Reset
                        </button>
                      </div>
                      <div class="control">
                        <button type="submit" class="button is-small is-primary">
                          <i class="fas fa-save"></i> Simpan untuk {{$idDay}}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Quick Add Floating Button -->
  <div class="quick-add" onclick="quickAdd()" title="Tambah Jadwal Cepat">
    <i class="fas fa-plus fa-2x"></i>
  </div>

  <!-- Toast Notification Container -->
  <div id="toastContainer"></div>

  <script>
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
      // Update active tab
      document.querySelectorAll('.day-tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      element.classList.add('active');
      
      currentDay = day;
      
      // Show/hide day cards
      if (day === 'all') {
        document.querySelectorAll('.day-card').forEach(card => {
          card.style.display = 'block';
        });
      } else {
        document.querySelectorAll('.day-card').forEach(card => {
          if (card.dataset.day === day) {
            card.style.display = 'block';
            // Auto expand the day
            document.getElementById(`content-${day}`).classList.add('active');
          } else {
            card.style.display = 'none';
          }
        });
      }
      
      // Apply filters
      applyFilters();
    }
    
    // ========== FUNGSI FILTER ==========
    function applyFilters() {
      const showOnlyReady = document.getElementById('showOnlyReady')?.checked;
      const showConflicts = document.getElementById('showConflicts')?.checked;
      const filterPengawas = document.getElementById('filterPengawas')?.value;
      
      document.querySelectorAll('.schedule-item').forEach(item => {
        let show = true;
        
        // Filter bentrok
        if (showConflicts && !item.classList.contains('conflict')) {
          show = false;
        }
        
        // Filter pengawas
        if (filterPengawas && item.dataset.pengawas !== filterPengawas) {
          show = false;
        }
        
        item.style.display = show ? 'block' : 'none';
      });
    }
    
    // ========== VALIDASI TANGGAL ==========
    function updateDay(expectedDay, dateString) {
      const date = new Date(dateString + 'T12:00:00');
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const actualDay = days[date.getDay()];
      
      if (actualDay !== expectedDay) {
        if (!confirm(`Tanggal yang dipilih adalah hari ${getIndonesianDay(actualDay)}.\nApakah Anda yakin ingin menambah jadwal di hari ${getIndonesianDay(expectedDay)}?`)) {
          // Reset ke tanggal hari ini
          event.target.value = '{{ now()->format('Y-m-d') }}';
        }
      }
    }
    
    function getIndonesianDay(day) {
      const days = {
        'Sunday': 'Minggu',
        'Monday': 'Senin',
        'Tuesday': 'Selasa',
        'Wednesday': 'Rabu',
        'Thursday': 'Kamis',
        'Friday': 'Jumat',
        'Saturday': 'Sabtu'
      };
      return days[day] || day;
    }
    
    // ========== VALIDASI FORM ==========
    function validateForm(form, expectedDay) {
      const jamMapel = form.querySelector('[name="jam_mapal"]').value;
      const jamMulai = form.querySelector('[name="jam_mulai"]').value;
      const tanggal = form.querySelector('[name="tanggal"]').value;
      const ujian = form.querySelector('[name="ujian_id"]').value;
      const guru = form.querySelector('[name="guru_id"]').value;
      
      // Validasi field kosong
      if (!jamMapel || !jamMulai || !tanggal || !ujian || !guru) {
        showToast('Semua field harus diisi!', 'error');
        return false;
      }
      
      // Validasi jam mapel
      if (jamMapel < 1 || jamMapel > 20) {
        showToast('Jam ke- harus antara 1 - 20', 'error');
        return false;
      }
      
      // Validasi tanggal tidak boleh kurang dari hari ini
      const selectedDate = new Date(tanggal + 'T00:00:00');
      const today = new Date();
      today.setHours(0,0,0,0);
      
      if (selectedDate < today) {
        showToast('Tanggal tidak boleh kurang dari hari ini!', 'error');
        return false;
      }
      
      // Cek bentrok dengan jadwal existing
      const dayCard = form.closest('.day-card');
      const existingSchedules = dayCard.querySelectorAll('.schedule-item');
      const selectedDateTime = new Date(tanggal + 'T' + jamMulai + ':00');
      
      // Ambil durasi ujian
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
    function editSchedule(id) {
      // Implementasi edit bisa menggunakan modal atau redirect
      if (confirm(`Edit jadwal ID: ${id}?`)) {
        // Redirect ke halaman edit
        // window.location.href = `/admin-ops/jadwal/edit/${id}`;
        showToast('Fitur edit akan segera tersedia');
      }
    }
    
    function deleteSchedule(id) {
      if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        // Tampilkan loading
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span>';
        btn.disabled = true;
        
        // Kirim request delete via AJAX
        fetch(`/admin-ops/jadwal/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Hapus element dari DOM
            const scheduleItem = btn.closest('.schedule-item');
            scheduleItem.style.animation = 'slideIn 0.3s reverse';
            setTimeout(() => {
              scheduleItem.remove();
              showToast('Jadwal berhasil dihapus');
              
              // Update counter
              updateCounters();
            }, 300);
          } else {
            showToast('Gagal menghapus jadwal', 'error');
          }
        })
        .catch(error => {
          showToast('Terjadi kesalahan', 'error');
          console.error('Error:', error);
        })
        .finally(() => {
          btn.innerHTML = originalHtml;
          btn.disabled = false;
        });
      }
    }
    
    // ========== QUICK ADD ==========
    function quickAdd() {
      // Scroll ke hari yang sedang aktif atau hari pertama
      if (currentDay !== 'all') {
        const targetDay = document.querySelector(`.day-card[data-day="${currentDay}"]`);
        if (targetDay) {
          targetDay.scrollIntoView({ behavior: 'smooth' });
          // Expand dan scroll ke form
          const content = document.getElementById(`content-${currentDay}`);
          content.classList.add('active');
          const form = content.querySelector('.add-schedule-form');
          form.scrollIntoView({ behavior: 'smooth' });
          form.style.animation = 'pulse 1s';
          setTimeout(() => form.style.animation = '', 1000);
        }
      } else {
        // Scroll ke hari pertama (Minggu)
        const firstDay = document.querySelector('.day-card');
        if (firstDay) {
          firstDay.scrollIntoView({ behavior: 'smooth' });
          const firstDayId = firstDay.dataset.day;
          document.getElementById(`content-${firstDayId}`).classList.add('active');
        }
      }
    }
    
    // ========== UPDATE COUNTERS ==========
    function updateCounters() {
      // Update total jadwal di header
      const totalJadwal = document.querySelectorAll('.schedule-item').length;
      const counterTag = document.querySelector('.tag.is-success');
      if (counterTag) {
        counterTag.innerHTML = `<i class="fas fa-check-circle"></i> ${totalJadwal} Terjadwal`;
      }
      
      // Update counter per hari
      document.querySelectorAll('.day-card').forEach(card => {
        const day = card.dataset.day;
        const count = card.querySelectorAll('.schedule-item').length;
        const counter = card.querySelector('.tag.is-light');
        if (counter) {
          counter.innerHTML = `${count} Jadwal`;
        }
      });
    }
    
    // ========== CHECK FOR CONFLICTS ==========
    function checkAllConflicts() {
      document.querySelectorAll('.day-card').forEach(card => {
        const schedules = card.querySelectorAll('.schedule-item');
        const scheduleList = [];
        
        schedules.forEach(schedule => {
          const timeText = schedule.querySelector('.time-badge').innerText;
          const timeMatch = timeText.match(/(\d{2}:\d{2}) - (\d{2}:\d{2})/);
          
          if (timeMatch) {
            const [, start, end] = timeMatch;
            scheduleList.push({
              element: schedule,
              start: start,
              end: end
            });
          }
        });
        
        // Cek bentrok
        for (let i = 0; i < scheduleList.length; i++) {
          for (let j = i + 1; j < scheduleList.length; j++) {
            const a = scheduleList[i];
            const b = scheduleList[j];
            
            if (a.start < b.end && a.end > b.start) {
              a.element.classList.add('conflict');
              b.element.classList.add('conflict');
              
              // Tambah indicator jika belum ada
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
      // Auto expand all days
      document.querySelectorAll('.day-content').forEach(content => {
        content.classList.add('active');
      });
      
      // Check for conflicts
      checkAllConflicts();
      
      // Initialize filters
      document.getElementById('showOnlyReady')?.addEventListener('change', applyFilters);
      document.getElementById('showConflicts')?.addEventListener('change', applyFilters);
      document.getElementById('filterPengawas')?.addEventListener('change', applyFilters);
      
      // Set minimum date for date inputs
      const today = new Date().toISOString().split('T')[0];
      document.querySelectorAll('input[type="date"]').forEach(input => {
        input.min = today;
      });
      
      // Auto-set waktu selesai based on durasi
      document.querySelectorAll('select[name="ujian_id"]').forEach(select => {
        select.addEventListener('change', function() {
          const selected = this.options[this.selectedIndex];
          const durasi = selected.dataset.durasi;
          if (durasi && this.closest('form')) {
            const jamMulai = this.closest('form').querySelector('[name="jam_mulai"]').value;
            if (jamMulai) {
              // Hitung jam selesai (untuk info)
              const [hours, minutes] = jamMulai.split(':');
              const startTime = new Date();
              startTime.setHours(hours, minutes, 0);
              const endTime = new Date(startTime.getTime() + (parseInt(durasi) * 60000));
              const endHours = endTime.getHours().toString().padStart(2, '0');
              const endMinutes = endTime.getMinutes().toString().padStart(2, '0');
              
              // Tampilkan info durasi
              showToast(`Ujian akan selesai jam ${endHours}:${endMinutes}`, 'success');
            }
          }
        });
      });
    });
  </script>
</body>
</html>