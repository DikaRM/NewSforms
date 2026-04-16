<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pengawasan Ujian - Sistem Ujian</title>
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

    /* ===== NAVBAR ===== */
    .navbar {
      background: #2e5b9a;
      padding: 0 24px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
    }

    .navbar-brand {
      padding: 8px 0;
    }

    .navbar-item {
      color: white !important;
      font-weight: 600;
      font-size: 1rem;
    }

    .navbar-item i {
      margin-right: 8px;
    }

    .navbar-end {
      padding: 8px 0;
    }

    .navbar-end .button {
      background: #dc3545;
      border: none;
      color: white;
      transition: all 0.3s ease;
    }

    .navbar-end .button:hover {
      background: #c82333;
      transform: translateY(-2px);
    }

    /* ===== MAIN CONTAINER ===== */
    .main-container {
      margin-top: 70px;
      padding: 24px;
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
    }

    /* ===== BREADCRUMB ===== */
    .breadcrumb-custom {
      background: white;
      padding: 12px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .breadcrumb-custom a {
      color: #2e5b9a;
      text-decoration: none;
    }

    .breadcrumb-custom a:hover {
      text-decoration: underline;
    }

    /* ===== INFO CARD ===== */
    .info-card {
      background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
      color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .info-card h1 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .info-card .exam-name {
      font-size: 1.1rem;
      opacity: 0.95;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-stats {
      display: flex;
      gap: 24px;
      flex-wrap: wrap;
      margin-top: 16px;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255,255,255,0.15);
      padding: 8px 16px;
      border-radius: 30px;
    }

    .stat-item i {
      font-size: 1rem;
    }

    .stat-item span {
      font-size: 0.85rem;
    }

    /* ===== TABLE STYLES ===== */
    .table-container {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-bottom: 24px;
    }

    .table {
      margin-bottom: 0 !important;
    }

    .table thead {
      background: #f8f9fc;
    }

    .table thead th {
      color: #2e5b9a;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 16px;
      border-bottom: 2px solid #e5e7eb;
    }

    .table tbody td {
      padding: 14px 16px;
      vertical-align: middle;
      font-size: 0.9rem;
    }

    .table tbody tr:hover {
      background: #fafbff;
    }

    /* Badge Status */
    .badge-aman {
      display: inline-block;
      padding: 4px 12px;
      background: #d4edda;
      color: #155724;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .badge-pelanggaran {
      display: inline-block;
      padding: 4px 12px;
      background: #f8d7da;
      color: #721c24;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    /* ===== FORM CARD ===== */
    .form-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-bottom: 24px;
    }

    .form-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #2e5b9a;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid #e5e7eb;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 8px;
    }

    .input-custom,
    .select-custom select,
    .textarea-custom {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      background: white;
    }

    .input-custom:focus,
    .select-custom select:focus,
    .textarea-custom:focus {
      outline: none;
      border-color: #2e5b9a;
      box-shadow: 0 0 0 3px rgba(46, 91, 154, 0.1);
    }

    .input-custom[readonly] {
      background: #f8f9fc;
      cursor: not-allowed;
    }

    .select-custom {
      position: relative;
    }

    .select-custom select {
      appearance: none;
      background: white;
      cursor: pointer;
    }

    .select-custom::after {
      content: '\f107';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #94a3b8;
    }

    .textarea-custom {
      resize: vertical;
      min-height: 80px;
    }

    .btn-report {
      background: #dc3545;
      color: white;
      border: none;
      padding: 12px 28px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .btn-report:hover {
      background: #c82333;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .btn-report:disabled {
      background: #6c757d;
      cursor: not-allowed;
      transform: none;
    }

    /* Back Button */
    .btn-back {
      background: #6c757d;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 500;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }

    .btn-back:hover {
      background: #5a6268;
      transform: translateY(-2px);
      color: white;
    }

    /* Info Alert */
    .info-alert {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
    }

    .info-alert i {
      font-size: 1.2rem;
      margin-right: 10px;
    }

    .info-alert.warning {
      background: #fff3cd;
      color: #856404;
      border-color: #ffeeba;
    }

    .disabled-form {
      pointer-events: none;
      opacity: 0.6;
    }

    /* Notification */
    .notification-toast {
      position: fixed;
      top: 80px;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .main-container {
        margin-top: 60px;
        padding: 16px;
      }

      .info-card {
        padding: 18px;
      }

      .info-card h1 {
        font-size: 1.2rem;
      }

      .buttons-group {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-report, .btn-back {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-brand">
      <a href="#" class="navbar-item">
        <i class="fas fa-chalkboard-user"></i>
        Pengawas Ujian
      </a>
    </div>

    <div class="navbar-end">
      <form action="{{ route('users.logout') }}" method="post">
        @csrf
        <button type="submit" class="button is-danger">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Container -->
  <div class="main-container">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
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

    <div class="breadcrumb-custom">
      <a href="{{route('pengawas.index',$da->guru->id)}}">
        <i class="fas fa-arrow-left"></i> {{$da->guru->id}}Kembali ke Daftar Ujian
      </a>
    </div>

    <div class="info-card">
      <h1>
        <i class="fas fa-users"></i> 
        Kelas : {{ $jadk->kelas->nama_kelas ?? 'Tidak diketahui' }}
      </h1>
      <div class="exam-name">
        <i class="fas fa-book-open"></i>
        {{ $jadk->ujian->nama_ujian ?? 'Ujian' }}
      </div>
      <div class="info-stats">
        <div class="stat-item">
          <i class="fas fa-user-graduate"></i>
          <span>Jumlah Peserta: {{ $data->count() }} Siswa</span>
        </div>
        @if(isset($jadk->waktu_mulai))
        <div class="stat-item">
          <i class="fas fa-clock"></i>
          <span>{{ \Carbon\Carbon::parse($jadk->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadk->waktu_selesai)->format('H:i') }} WIB</span>
        </div>
        @endif
      </div>
    </div>

    <!-- Tabel Status Pelanggaran -->
    <div class="table-container">
      <table class="table is-fullwidth">
        <thead>
          <tr>
            <th>No</th>
            <th>ID Siswa</th>
            <th>Nama Siswa</th>
            <th>NISN</th>
            <th>Status Pelanggaran</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @foreach($data as $dt)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $dt->id_siswa }}</td>
              <td><strong>{{ $dt->nama }}</strong></td>
              <td>{{ $dt->nisn }}</td>
              <td>
                @php
                  $pelanggaranSiswa = $pelan->where('siswa_id', $dt->id_siswa)->where("ujian_id", $jadk->ujian->id ?? 0)->first();
                @endphp
                @if($pelanggaranSiswa)
                  <span class="badge-pelanggaran">
                    <i class="fas fa-exclamation-triangle"></i> 
                    {{ $pelanggaranSiswa->jenis_pelanggaran }}
                  </span>
                @else
                  <span class="badge-aman">
                    <i class="fas fa-check-circle"></i> Aman
                  </span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- ========== FORM ABSENSI ========== -->
    @php
      $absensiKey = 'absensi_' . ($jadk->id ?? 0);
      $sudahAbsen = session()->has($absensiKey);
    @endphp

    @if(!$sudahAbsen)
      <form method="post" action="{{ route('pengawas.abcent.store') }}" id="formAbsensi">
        @csrf
        <input type="hidden" name="ujian_id" value="{{ $jadk->ujian_id ?? '' }}">
        <input type="hidden" name="kelas_id" value="{{ $jadk->kelas_id ?? '' }}">
        
        <div class="table-container">
          <table class="table is-fullwidth is-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kehadiran</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $index => $dt)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  {{ $dt->nama }}
                  <input type="hidden" name="siswa_id[{{ $index }}]" value="{{ $dt->id_siswa }}">
                </td>
                <td>
                  <div class="select">
                    <select name="status[{{ $index }}]" class="status-select" required>
                      <option value="">Pilih Status</option>
                      <option value="hadir">Hadir</option>
                      <option value="sakit">Sakit</option>
                      <option value="izin">Izin</option>
                      <option value="alfa">Alfa</option>
                    </select>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <div class="buttons my-3 is-centered">
          <button type="submit" class="button is-info is-dark" id="btnSimpan">
            <span class="has-text-light">Simpan Absensi</span>
          </button>
          <button type="button" class="button is-link is-light" id="btnHadirSemua">
            <span class="has-text-info has-text-dark">Set Semua Hadir</span>
          </button>
        </div>
      </form>
    @else
      <div class="info-alert">
        <i class="fas fa-check-circle"></i>
        <strong>✓ Absensi Sudah Dilakukan</strong>
        <p style="margin-top: 8px;">Absensi untuk ujian ini sudah disimpan sebelumnya. Tidak dapat mengisi ulang.</p>
      </div>
    @endif

    <!-- ========== FORM BERITA ACARA ========== -->
    @php
      $beritaKey = 'berita_' . ($jadk->id ?? 0);
      $sudahBerita = session()->has($beritaKey);
    @endphp

    @if(!$sudahBerita)
      <div class="form-card">
        <div class="form-title">
          <i class="fas fa-flag"></i>
          Berita Acara
        </div>
        
        <form action="{{ route('pengawas.store') }}" method="post" id="formBeritaAcara">
          @csrf
          
          <div class="form-group">
            <label><i class="fas fa-tag"></i> Mata Pelajaran</label>
            <input type="hidden" name="ujian_id" value="{{ $jadk->ujian_id ?? '' }}">
            <input type="hidden" name="kelas_id" value="{{ $jadk->kelas_id ?? '' }}">
            <input type="text" class="input-custom" value="{{ $jadk->ujian->mapels->nama_mapel ?? '-' }}" readonly>
          </div>
          
          <div class="form-group">
            <label><i class="fas fa-pen"></i> Catatan Kelas</label>
            <textarea name="catatan" id="catatanBerita" rows="3" class="textarea-custom" 
                      placeholder="Isikan catatan pelaksanaan ujian (contoh: Ada siswa yang terlambat, suasana kelas kondusif, dll)" required></textarea>
          </div>
          
          <div class="buttons-group" style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
            <a href="#" class="btn-back">
              <i class="fas fa-times"></i> Batal
            </a>
            <button class="btn-report" type="submit">
              <i class="fas fa-flag"></i> Simpan Berita Acara
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="info-alert warning">
        <i class="fas fa-file-alt"></i>
        <strong>📋 Berita Acara Sudah Dibuat</strong>
        <p style="margin-top: 8px;">Berita acara untuk ujian ini sudah disimpan sebelumnya. Tidak dapat mengisi ulang.</p>
      </div>
    @endif
    
  </div>

  <script>
    // Key untuk localStorage (menggunakan ID jadwal)
    const absensiKey = 'absensi_{{ $jadk->id ?? 0 }}';
    const beritaKey = 'berita_{{ $jadk->id ?? 0 }}';
    
    // Cek localStorage saat load
    document.addEventListener('DOMContentLoaded', function() {
      // Cek absensi
      if (localStorage.getItem(absensiKey) === 'done') {
        const absensiForm = document.getElementById('formAbsensi');
        if (absensiForm) {
          absensiForm.style.display = 'none';
          // Tampilkan pesan jika belum ada
          if (!document.querySelector('.absensi-message')) {
            const container = document.getElementById('formAbsensi')?.parentNode;
            if (container) {
              const msgDiv = document.createElement('div');
              msgDiv.className = 'info-alert absensi-message';
              msgDiv.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <strong>Absensi Sudah Dilakukan</strong>
                <p>Absensi untuk ujian ini sudah disimpan sebelumnya.</p>
              `;
              container.insertBefore(msgDiv, document.getElementById('formAbsensi'));
            }
          }
        }
      }
      
      // Cek berita acara
      if (localStorage.getItem(beritaKey) === 'done') {
        const beritaForm = document.getElementById('formBeritaAcara');
        if (beritaForm) {
          beritaForm.style.display = 'none';
          if (!document.querySelector('.berita-message')) {
            const container = document.getElementById('formBeritaAcara')?.parentNode;
            if (container) {
              const msgDiv = document.createElement('div');
              msgDiv.className = 'info-alert warning berita-message';
              msgDiv.innerHTML = `
                <i class="fas fa-file-alt"></i>
                <strong>Berita Acara Sudah Dibuat</strong>
                <p>Berita acara untuk ujian ini sudah disimpan sebelumnya.</p>
              `;
              container.insertBefore(msgDiv, document.getElementById('formBeritaAcara'));
            }
          }
        }
      }
    });
    
    // Handle submit absensi
    const formAbsensi = document.getElementById('formAbsensi');
    if (formAbsensi) {
      formAbsensi.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let allFilled = true;
        const selects = document.querySelectorAll('.status-select');
        
        selects.forEach(select => {
          if (select.value === '') {
            allFilled = false;
          }
        });
        
        if (!allFilled) {
          Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Harap pilih status kehadiran untuk semua siswa!',
            confirmButtonColor: '#3085d6'
          });
          return;
        }
        
        Swal.fire({
          title: 'Konfirmasi',
          text: 'Apakah yakin ingin menyimpan absensi?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Simpan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Simpan ke localStorage
            localStorage.setItem(absensiKey, 'done');
            
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Absensi berhasil disimpan!',
              confirmButtonColor: '#28a745'
            }).then(() => {
              location.reload();
            });
          }
        });
      });
    }
    
    // Handle submit berita acara
    const formBerita = document.getElementById('formBeritaAcara');
    if (formBerita) {
      formBerita.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const catatan = document.getElementById('catatanBerita');
        
        if (!catatan.value.trim()) {
          Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Harap isi catatan berita acara!',
            confirmButtonColor: '#3085d6'
          });
          return;
        }
        
        Swal.fire({
          title: 'Konfirmasi',
          text: 'Apakah yakin ingin menyimpan berita acara?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Simpan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Simpan ke localStorage
            localStorage.setItem(beritaKey, 'done');
            localStorage.setItem(beritaKey + '_catatan', catatan.value);
            
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Berita acara berhasil disimpan!',
              confirmButtonColor: '#28a745'
            }).then(() => {
              location.reload();
            });
          }
        });
      });
    }
    
    // Tombol set semua hadir
    const btnHadirSemua = document.getElementById('btnHadirSemua');
    if (btnHadirSemua) {
      btnHadirSemua.addEventListener('click', function() {
        const selects = document.querySelectorAll('.status-select');
        selects.forEach(select => {
          select.value = 'hadir';
        });
        
        Swal.fire({
          icon: 'info',
          title: 'Informasi',
          text: 'Semua siswa diatur menjadi HADIR',
          timer: 1500,
          showConfirmButton: false
        });
      });
    }
  </script>
</body>
</html>