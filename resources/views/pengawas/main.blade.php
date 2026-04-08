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
      display:flex;
      flex-direction:rows;
      justify-content:space-between;
      
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

      .info-card .exam-name {
        font-size: 0.9rem;
      }

      .stat-item {
        padding: 6px 12px;
      }

      .stat-item span {
        font-size: 0.75rem;
      }

      .table-container {
        overflow-x: auto;
      }

      .table {
        min-width: 600px;
      }

      .table thead th,
      .table tbody td {
        padding: 10px 12px;
        font-size: 0.8rem;
      }

      .form-card {
        padding: 18px;
      }

      .btn-report,
      .btn-back {
        width: 100%;
        justify-content: center;
      }

      .buttons-group {
        flex-direction: column;
        gap: 10px;
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
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar" role="navigation is-flex" >
    <div class="navbar-brand">
      <a href="{{ route('pengawas.index', $jadk->id ?? '') }}" class="navbar-item">
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
    <!-- Notification -->
    @if(session('success'))
      <div class="notification-toast notification-success" id="notification">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    
    @if(session('error'))
      <div class="notification-toast notification-error" id="notification">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
      <a href="{{ route('pengawas.index', $jadk->id ?? '') }}">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ujian
      </a>
    </div>

    <!-- Info Card -->
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

    <!-- Table Peserta -->
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
             <td>
               <strong>{{ $dt->nama }}</strong>
             </td>
             <td>{{ $dt->nisn }}</td>
             <td>
               @php
                 $pelanggaranSiswa = $pelan->where('siswa_id', $dt->id_siswa)->first();
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

    <!-- Form Report Pelanggaran -->
    <div class="form-card">
      <div class="form-title">
        <i class="fas fa-flag"></i>
        Laporkan Pelanggaran
      </div>
      
      <form action="{{ route('pengawas.store') }}" method="post">
        @csrf
        
        <div class="form-group">
          <label>
            <i class="fas fa-tag"></i> Mata Pelajaran
          </label>
          <input type="hidden" class="input-custom" name="ujian_id" value="{{ $jadk->ujian_id }}" 
                 placeholder="{{ $jadk->ujian->nama_ujian ?? 'Ujian' }}" readonly>
        </div>
        <p class="subtitle">{{$jadk->ujian->mapels->nama_mapel}}</p>
        <div class="form-group">
          <label>
            <i class="fas fa-user"></i> Pilih Siswa
          </label>
          <div class="select-custom">
            <select name="siswa_id" required>
              <option value="">-- Pilih Siswa --</option>
              @foreach($data as $dt)
                <option value="{{ $dt->id_siswa }}">{{ $dt->nama }} (NISN: {{ $dt->nisn }})</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label>
            <i class="fas fa-pen"></i> Catatan Pelanggaran
          </label>
          <textarea name="catatan" cols="30" rows="3" class="textarea-custom" 
                    placeholder="Isikan jenis pelanggaran yang dilakukan (contoh: Mencontek, Membuka HP, dll)" required></textarea>
        </div>
        
        <div class="buttons-group" style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
          <a href="{{ route('pengawas.index', $jadk->id ?? '') }}" class="btn-back">
            <i class="fas fa-times"></i> Batal
          </a>
          <button class="btn-report" type="submit">
            <i class="fas fa-paper-plane"></i> Laporkan Pelanggaran
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto hide notification
      const notification = document.getElementById('notification');
      if (notification) {
        setTimeout(function() {
          notification.style.opacity = '0';
          setTimeout(function() {
            notification.style.display = 'none';
          }, 300);
        }, 5000);
      }
      
      // Form validation
      const reportForm = document.querySelector('form');
      if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
          const siswaSelect = document.querySelector('select[name="siswa_id"]');
          const catatan = document.querySelector('textarea[name="catatan"]');
          
          if (!siswaSelect.value) {
            e.preventDefault();
            alert('Silakan pilih siswa terlebih dahulu');
            return false;
          }
          
          if (!catatan.value.trim()) {
            e.preventDefault();
            alert('Silakan isi catatan pelanggaran');
            return false;
          }
        });
      }
    });
  </script>
</body>
</html>