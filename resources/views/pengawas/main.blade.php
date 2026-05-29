<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pengawasan Ujian - Sistem Ujian</title>
  
  <!-- Fonts & Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Library CSS (Bulma) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
  
  <!-- Library JS (SweetAlert2) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* --- 1. CONFIGURATION & VARIABLES --- */
    :root {
      --primary: #2e5b9a;
      --primary-hover: #264a82;
      --primary-soft: #ebf1f9;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --bg-page: #f8fafc;
      --radius-md: 12px;
      --radius-lg: 20px;
      --shadow-card: 0 10px 25px -5px rgba(46, 91, 154, 0.15);
      --focus-ring: 0 0 0 3px rgba(46, 91, 154, 0.2);
    }

    * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    body {
      background: var(--bg-page);
      color: var(--text-main);
      margin: 0;
      min-height: 100vh;
    }

    /* --- 2. LOGIN RUANGAN UI --- */
    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      padding: 20px;
    }

    .login-card {
      background: white;
      width: 100%;
      max-width: 400px;
      padding: 40px;
      border-radius: var(--radius-lg);
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      text-align: center;
      animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-icon {
      width: 80px; height: 80px;
      background: var(--primary-soft);
      color: var(--primary);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem; margin: 0 auto 24px;
    }

    /* --- 3. DASHBOARD UI --- */
    .navbar {
      background: var(--primary);
      padding: 0.8rem 1.5rem;
      box-shadow: 0 2px 10px rgba(46, 91, 154, 0.2);
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      display:flex;
      flex-direction:row;
      justify-content : space-between;
    }
    .navbar-item { color: white !important; font-weight: 600; font-size: 1.1rem; }
    .btn-logout {
      background: rgba(255,255,255,0.15); border: none; color: white;
      transition: all 0.2s;
    }
    .btn-logout:hover { background: rgba(255,255,255,0.25); }

    .main-container {
      margin-top: 90px;
      padding: 24px;
      max-width: 1000px;
      margin-left: auto; margin-right: auto;
      padding-bottom: 50px;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* --- 4. COMPONENTS --- */
    .info-card {
      background: linear-gradient(135deg, var(--primary) 0%, #4a76bc 100%);
      color: white;
      border-radius: var(--radius-lg);
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 10px 25px -5px rgba(46, 91, 154, 0.3);
      position: relative; overflow: hidden;
    }
    .info-card::after {
      content: ''; position: absolute; right: -30px; top: -30px;
      width: 180px; height: 180px; background: rgba(255,255,255,0.1);
      border-radius: 50%; pointer-events: none;
    }

    .section-card {
      background: white;
      border-radius: var(--radius-lg);
      padding: 24px;
      box-shadow: var(--shadow-card);
      margin-bottom: 24px;
      border: 1px solid var(--border);
    }
    
    .section-header {
      display: flex; align-items: center; gap: 12px;
      margin-bottom: 20px; padding-bottom: 16px;
      border-bottom: 2px solid var(--primary-soft);
    }
    .section-title {
      font-size: 1.25rem; font-weight: 700; color: var(--primary);
    }
    .section-icon {
      width: 40px; height: 40px;
      background: var(--primary-soft);
      color: var(--primary);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
    }

    /* --- 5. TABLES & BADGES --- */
    .table thead th {
      background: #f8fafc; color: var(--text-muted);
      font-weight: 700; font-size: 0.8rem; text-transform: uppercase;
      padding: 12px 16px; border: none;
    }
    .table tbody td {
      vertical-align: middle; padding: 14px 16px;
      border-bottom: 1px solid #f1f5f9;
    }
    .table tbody tr:hover { background: #f8fafc; }
    
    .badge {
      padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
      display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    /* --- 6. FORMS & BUTTONS --- */
    .form-control {
      width: 100%; padding: 12px 16px;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      font-size: 0.95rem; color: var(--text-main);
      outline: none; transition: all 0.2s;
      background: #fff;
    }
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: var(--focus-ring);
    }

    .btn {
      border: none; padding: 12px 24px; border-radius: var(--radius-md);
      font-weight: 600; cursor: pointer; transition: all 0.2s;
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    }
    
    .btn-primary { background: var(--primary); color: white; width: 100%; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }
    
    .btn-secondary { background: #f1f5f9; color: var(--text-main); }
    .btn-secondary:hover { background: #e2e8f0; }

    .status-box {
      padding: 20px; border-radius: var(--radius-md); text-align: center;
      background: #f8fafc; border: 2px dashed var(--border);
    }
    .status-done { background: #f0fdf4; border-color: #86efac; color: #14532d; }
    .status-warning { background: #fffbeb; border-color: #fde68a; color: #78350f; }

    @media (max-width: 768px) {
      .main-container { padding: 16px; margin-top: 70px; }
      .section-card { padding: 16px; }
      .login-card { padding: 24px; }
      .user-name span{
        display:none;
      }
    }
    /* ===== USER DROPDOWN ===== */
.user-dropdown { position: relative; cursor: pointer; }
.user-info { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 8px; }
.user-info:hover { background: rgba(255, 255, 255, 0.38); }
.user-avatar { width: 34px; height: 34px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e5b9a; }
.user-name { font-weight: 500; font-size: 0.85rem; color: white; }

.dropdown-menu-custom { 
    position: absolute; top: 100%; right: 0; margin-top: 8px;
    background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    min-width: 180px; opacity: 0; visibility: hidden; transform: translateY(-10px);
    transition: all 0.3s ease; z-index: 1001;
   
}
.user-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }

.dropdown-item-custom { 
    padding: 10px 16px; display: flex; align-items: center; gap: 12px;
    color: #2e5b9a; text-decoration: none; border-bottom: 1px solid #eee;
}
.dropdown-item-custom:hover { background: #f5f5f5; color:#2e5b9a;}
.logout-btn { color: #dc3545; border:none;width:100%;}

/* ===== NOTIFIKASI ===== */
.notif-wrapper { position: relative; }
.notif-btn { cursor: pointer; padding: 8px; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
.notif-btn:hover { background: rgba(255,255,255,0.15); }
.notif-badge { position: absolute; top: 2px; right: 2px; background: #dc3545; color: white; font-size: 0.65rem; border-radius: 10px; padding: 0 4px; min-width: 18px; text-align: center; }
.notif-badge.hidden { display: none; }

.notif-dropdown {
    position: absolute; top: 120%; right: -10px; width: 320px;
    background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    opacity: 0; visibility: hidden; transform: translateY(-10px);
    transition: all 0.3s ease; z-index: 1002;
}
.notif-wrapper.active .notif-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
/* Tambahan style untuk tombol unblock */
.btn-unblock {
    transition: all 0.2s ease;
}

.btn-unblock:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.btn-unblock:active {
    transform: translateY(0);
}
  </style>
</head>
<body>

  <!-- ========================================== -->
  <!-- BAGIAN 1: LOGIN RUANGAN (BELUM VALID)     -->
  <!-- ========================================== -->
  @if(!$ruanganValid)
    
  <div class="login-wrapper">
    <div class="login-card">
      <div class="login-icon">
        <i class="fas fa-door-open"></i>
      </div>
      
      <h2 style="color: var(--primary); margin-bottom: 8px;">Akses Ruangan</h2>
      <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
        Masukkan kode ruangan untuk memulai pengawasan.
      </p>

      <form id="formRuangan">
        @csrf
        <div style="margin-bottom: 16px; text-align: left;">
          <label style="font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; display: block;">Kode Ruangan</label>
          <div style="position: relative;">
            <span style="position: absolute; left: 12px; top: 12px; color: var(--text-muted);">
              <i class="fas fa-key"></i>
            </span>
            <input type="text" name="kode_ruangan" placeholder="Contoh: R-101" required
                   style="width: 100%; padding: 12px 12px 12px 40px; border: 1px solid var(--border); border-radius: 10px; font-size: 1rem; outline: none; transition: 0.2s;"
                   onfocus="this.style.borderColor='var(--primary)'" 
                   onblur="this.style.borderColor='var(--border)'">
          </div>
        </div>

        <button type="submit" class="btn btn-primary" id="btnLoginRuangan">
          <span class="text">Masuk Ruangan</span>
          <i class="fas fa-arrow-right icon"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- BAGIAN 2: DASHBOARD (SUDAH VALID)          -->
  <!-- ========================================== -->
  @else

  <!-- Navbar -->
  <header class="header" style="background: #2e5b9a; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000;">
    <h2 style="display: flex; align-items: center; gap: 10px; color: white;">
        <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" style="height:30px">
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="header-actions" style="display: flex; align-items: center; gap: 15px;">
        <!-- NOTIFIKASI DROPDOWN -->
        

        <!-- USER DROPDOWN -->
        <div class="user-dropdown" id="userDropdown">
            <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
                <div class="user-name">
                    <span>{{ Auth::user()->name ?? 'Pengawas' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="dropdown-menu-custom">
                <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
                <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                    @csrf
                    <button type="submit" class="dropdown-item-custom logout-btn logout-button">
                        <i class="fas fa-sign-out-alt"></i> Logout
</button>
                </form>
            </div>
        </div>
    </div>
</header>

  <div class="main-container">
    
    <!-- Flash Message Success -->
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({ 
                icon: 'success', 
                title: 'Berhasil!', 
                text: '{{ session('success') }}', 
                confirmButtonColor: '#2e5b9a',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
    @endif

    <!-- Info Card -->
    <div class="info-card">
      <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
        <div>
          <h2 style="font-size: 1.5rem; margin-bottom: 5px;">{{ $jadk->kelas->nama_kelas ?? '-' }}</h2>
          <div style="opacity: 0.9; margin-bottom: 12px; font-weight: 500; font-size: 1.1rem;">
            <i class="fas fa-book-open"></i> {{ $jadk->ujian->nama_ujian ?? '-' }}
          </div>
          <div style="font-size: 0.9rem; display: flex; gap: 15px; flex-wrap: wrap; opacity: 0.85;">
            <span><i class="fas fa-user-graduate"></i> {{ $data->count() }} Peserta</span>
            <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($jadk->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadk->waktu_selesai)->format('H:i') }} WIB</span>
          </div>
        </div>
        <a href="{{route('pengawas.index',$da->guru->id)}}" style="color: white; opacity: 0.8; text-decoration: none; background: rgba(0,0,0,0.2); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.3)'" onmouseout="this.style.background='rgba(0,0,0,0.2)'">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
    </div>

    <!-- Tabel Pelanggaran -->
<!-- Tabel Pelanggaran -->
<div class="section-card">
  <div class="section-header">
    <div class="section-icon"><i class="fas fa-exclamation-circle"></i></div>
    <h3 class="section-title">Status Pelanggaran & Aksi</h3>
  </div>
  <div style="overflow-x: auto;">
    <table class="table is-fullwidth is-hoverable">
      <thead>
        <tr>
          <th style="width: 50px;">No</th>
          <th>Nama Siswa</th>
          <th>NISN</th>
          <th>Status</th>
          <th style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @foreach($data as $dt)
        <tr id="row-siswa-{{ $dt->id_siswa }}">
          <td>{{ $no++ }}</td>
          <td><strong>{{ $dt->nama }}</strong></td>
          <td style="color: var(--text-muted);">{{ $dt->nisn }}</td>
          <td id="status-{{ $dt->id_siswa }}">
            @php
              $pelanSiswa = $pelan->where('siswa_id', $dt->id_siswa)->where("ujian_id", $jadk->ujian->id ?? 0)->first();
              $isBlocked = \App\Models\BlockSiswa::where('siswa_id', $dt->id_siswa)
    ->where('ujian_id', $jadk->ujian->id ?? 0)
    ->where('violation_count', '>=', 3)
    ->exists();
            @endphp
            
            @if($pelanSiswa)
              <span class="badge badge-danger">
                <i class="fas fa-exclamation-triangle"></i> {{ $pelanSiswa->jenis_pelanggaran }}
              </span>
              @if($isBlocked)
                <span class="badge badge-danger has-text-light" style="background: #991b1b; margin: 5px;">
                  <i class="fas fa-ban"></i> Diblokir
                </span>
              @endif
            @else
              <span class="badge badge-success">
                <i class="fas fa-check"></i> Aman
              </span>
            @endif
          </td>
          <td>
            @if($pelanSiswa && $isBlocked)
              <button type="button" 
                      class="btn-unblock" 
                      data-siswa-id="{{ $dt->id_siswa }}"
                      data-siswa-nama="{{ $dt->nama }}"
                      data-ujian-id="{{ $jadk->ujian->id ?? 0 }}"
                      style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 8px; cursor: pointer; font-size: 0.75rem; font-weight: 600;">
                <i class="fas fa-unlock-alt"></i> Unblock
              </button>
            @else
              <span class="badge" style="background: #e2e8f0; color: #64748b;">
                <i class="fas fa-check-circle"></i> Aktif
              </span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

    <!-- Form Absensi -->
    @php
      $sudahAbsen = \App\Models\Absensi::where('ujian_id', $jadk->ujian_id)
                                        ->where('kelas_id', $jadk->kelas_id)
                                        ->exists();
    @endphp

    @if(!$sudahAbsen)
      <div class="section-card" id="section-absensi">
        <div class="section-header">
          <div class="section-icon"><i class="fas fa-clipboard-check"></i></div>
          <div style="flex: 1;">
            <h3 class="section-title">Input Absensi</h3>
            <small style="color: var(--text-muted);">Pastikan status kehadiran siswa sesuai.</small>
          </div>
          <button type="button" onclick="setAllHadir()" style="color: var(--primary); background: none; border: none; font-weight: 600; cursor: pointer; font-size: 0.9rem;">
            <i class="fas fa-mouse-pointer"></i> Set Semua Hadir
          </button>
        </div>

        <form action="{{ route('pengawas.abcent.store') }}" method="post" id="formAbsensi" 
          @csrf
          <input type="hidden" name="ujian_id" value="{{ $jadk->ujian_id ?? '' }}">
          <input type="hidden" name="kelas_id" value="{{ $jadk->kelas_id ?? '' }}">
          
          <div style="max-height: 400px; overflow-y: auto; border: 1px solid var(--border); border-radius: 10px; margin-bottom: 20px;">
            <table class="table is-fullwidth is-striped" style="margin-bottom: 0;">
              <tbody>
                @foreach($data as $index => $dt)
                <tr>
                  <td style="width: 40px; text-align: center;">{{ $loop->iteration }}</td>
                  <td>
                    <div style="font-weight: 600;">{{ $dt->nama }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $dt->nisn }}</div>
                    <input type="hidden" name="siswa_id[{{ $index }}]" value="{{ $dt->id_siswa }}">
                  </td>
                  <td style="width: 140px;">
                    <select name="status[{{ $dt->id_siswa }}]" class="form-control" style="padding: 8px; font-size: 0.85rem;" required>
                      <option value="hadir">Hadir</option>
                      <option value="sakit">Sakit</option>
                      <option value="izin">Izin</option>
                      <option value="alfa">Alfa</option>
                    </select>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div style="display: flex; gap: 10px;">
            <button type="reset" class="btn btn-secondary" style="flex: 1;">Reset</button>
            <button type="submit" class="btn btn-primary" style="flex: 2;">
              <i class="fas fa-save"></i> Simpan Absensi
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="status-box status-done">
        <i class="fas fa-check-circle fa-2x" style="margin-bottom: 10px;"></i>
        <h4 style="margin-bottom: 5px; font-weight: 700;">Absensi Selesai</h4>
        <p style="font-size: 0.9rem;">Data absensi untuk ujian ini telah disimpan di sistem.</p>
      </div>
    @endif

    <!-- Form Berita Acara -->
    @php
      $sudahBerita = \App\Models\Berita::where('ujian_id', $jadk->ujian_id)
                                             ->where('kelas_id', $jadk->kelas_id)
                                             ->exists();
    @endphp

    @if(!$sudahBerita)
      <div class="section-card" id="section-berita">
        <div class="section-header">
          <div class="section-icon"><i class="fas fa-file-signature"></i></div>
          <h3 class="section-title">Berita Acara</h3>
        </div>

        <form action="{{ route('pengawas.store') }}" method="post" id="formBeritaAcara">
          @csrf
          <input type="hidden" name="ujian_id" value="{{ $jadk->ujian_id ?? '' }}">
          <input type="hidden" name="kelas_id" value="{{ $jadk->kelas_id ?? '' }}">

          <div style="margin-bottom: 16px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Mata Pelajaran</label>
            <input type="text" class="form-control" value="{{ $jadk->ujian->mapels->nama_mapel ?? '-' }}" readonly style="background: #f1f5f9; cursor: not-allowed;">
          </div>
          
          <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Catatan Kelas</label>
            <textarea name="catatan" class="form-control" rows="4" 
                      placeholder="Tuliskan kondisi ujian, kendala teknis, atau catatan penting lainnya..." required></textarea>
          </div>
          
          <div style="text-align: right;">
            <button type="submit" class="btn btn-primary" style="width: auto; padding-left: 32px; padding-right: 32px;">
              <i class="fas fa-paper-plane"></i> Kirim Laporan
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="status-box status-warning">
        <i class="fas fa-file-alt fa-2x" style="margin-bottom: 10px;"></i>
        <h4 style="margin-bottom: 5px; font-weight: 700;">Laporan Terkirim</h4>
        <p style="font-size: 0.9rem;">Berita acara ujian ini sudah dibuat dan tersimpan.</p>
      </div>
    @endif

  </div> <!-- End Container -->
  @endif <!-- End Else RuanganValid -->


  <!-- JAVASCRIPT LOGIC (FULL FIX) -->
  <script>
    
    document.addEventListener('DOMContentLoaded', function() {
      // ==========================================
// 5. HANDLE UNBLOCK SISWA
// ==========================================
document.querySelectorAll('.btn-unblock').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const siswaId = this.getAttribute('data-siswa-id');
        const siswaNama = this.getAttribute('data-siswa-nama');
        const ujianId = this.getAttribute('data-ujian-id');
        
        // Konfirmasi dengan SweetAlert
        const result = await Swal.fire({
            title: 'Konfirmasi Unblock',
            html: `Apakah Anda yakin ingin <strong>membuka blokir</strong> siswa:<br><br>
                   <span style="font-size: 1.2rem; font-weight: 700; color: #2e5b9a;">${siswaNama}</span><br><br>
                   Siswa akan dapat melanjutkan ujian kembali.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#dc3545',
            confirmButtonText: '<i class="fas fa-unlock-alt"></i> Ya, Unblock',
            cancelButtonText: '<i class="fas fa-times"></i> Batal'
        });
        
        if (!result.isConfirmed) return;
        
        // Tampilkan loading pada tombol
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        this.disabled = true;
        
        try {
            const response = await fetch(`/pengawas/unblock/${siswaId}/${ujianId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update tombol menjadi tidak perlu unblock lagi
                const statusCell = document.getElementById(`status-${siswaId}`);
                const row = document.getElementById(`row-siswa-${siswaId}`);
                
                // Update status menjadi Aman
                if (statusCell) {
                    statusCell.innerHTML = `
                        <span class="badge badge-success">
                            <i class="fas fa-check"></i> Aman (Sudah Diunblock)
                        </span>
                    `;
                }
                
                // Ganti tombol dengan badge
                const actionCell = this.parentElement;
                actionCell.innerHTML = `
                    <span class="badge" style="background: #dcfce7; color: #166534;">
                        <i class="fas fa-check-circle"></i> Diunblock
                    </span>
                `;
                
                // Tampilkan notifikasi sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#2e5b9a',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Optional: reload data atau update status
                setTimeout(() => {
                    location.reload();
                }, 2000);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat unblock',
                    confirmButtonColor: '#2e5b9a'
                });
                
                // Reset tombol
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal terhubung ke server',
                confirmButtonColor: '#2e5b9a'
            });
            
            // Reset tombol
            this.innerHTML = originalHtml;
            this.disabled = false;
        }
    });
});
         document.querySelectorAll('.logout-form').forEach(function(form) {

        let submitted = false;

        form.addEventListener('submit', function(e) {

            if (submitted) {
                e.preventDefault();
                return;
            }

            submitted = true;

            const btn = form.querySelector('.logout-button');

            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
            }
        });
    });
        // ==========================================
        // 1. HANDLE LOGIN RUANGAN
        // ==========================================
        // Contoh data notifikasi dari server
        // ===== USER DROPDOWN TOGGLE =====
var userDropdown = document.getElementById('userDropdown');
if(userDropdown) {
    userDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
        // Tutup notif dropdown jika terbuka
        document.getElementById('notifWrapper')?.classList.remove('active');
    });
}


// ===== TUTUP DROPDOWN SAAT KLIK DI LUAR =====
document.addEventListener('click', function() {
    userDropdown?.classList.remove('active');
   
});


        const formRuangan = document.getElementById('formRuangan');
        if (formRuangan) {
            formRuangan.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('btnLoginRuangan');
                const originalText = btn.innerHTML;
                
                // Set tombol ke mode loading
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';
                btn.disabled = true;
                btn.style.opacity = "0.7";

                let formData = new FormData(this);
                
                // Ambil URL Route (Pastikan $jadk->id tersedia dari Controller)
                // Jika $jadk id error, ganti string di bawah dengan URL hardcode sementara untuk test
                const urlCheck = "{{ route('ruangan.check', $jadk->id ?? 'ID_MISSING') }}";

                fetch(urlCheck, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(async res => {
                    // Cek apakah response JSON valid atau Error HTML
                    const text = await res.text();
                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        console.error("Server Error (Non-JSON):", text);
                        throw new Error("Server Error 500. Cek tab Console/Network.");
                    }
                })
                .then(data => {
                    // Jika sukses
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Akses Diterima',
                            text: 'Selamat bertugas, Pengawas.',
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#fff',
                            iconColor: '#2e5b9a'
                        }).then(() => {
                            location.reload(); 
                        });
                    } else {
                        // Jika kode salah/error dari validasi backend
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Kode ruangan salah.',
                            confirmButtonColor: '#2e5b9a'
                        });
                        
                        // Reset tombol
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.style.opacity = "1";
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error System', error.message, 'error');
                    
                    // Reset tombol
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.style.opacity = "1";
                });
            });
        }

        // ==========================================
        // 2. FUNGSI SET SEMUA HADIR (Global Scope)
        // ==========================================
        window.setAllHadir = function() {
            const selects = document.querySelectorAll('#formAbsensi select');
            selects.forEach(select => {
                select.value = 'hadir';
            });
            
            // Efek visual pada tombol
            const btn = document.querySelector('button[onclick="setAllHadir()"]');
            if(btn) {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Selesai';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 1000);
            }
        };

        // ==========================================
        // 3. HANDLE SUBMIT ABSENSI
        // ==========================================
        const formAbsensi = document.getElementById('formAbsensi');
        if (formAbsensi) {
            formAbsensi.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('button[type="submit"]');
                const originalHtml = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                btn.disabled = true;

                const formData = new FormData(this);
                
                fetch('{{ route("pengawas.abcent.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(async res => {
                    const text = await res.text();
                    try { return JSON.parse(text); } 
                    catch (e) { throw new Error("Server Error"); }
                })
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: 'Data absensi berhasil diupdate.',
                            confirmButtonColor: '#2e5b9a'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Koneksi bermasalah', 'error');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
            });
        }

        // ==========================================
        // 4. HANDLE SUBMIT BERITA ACARA
        // ==========================================
        const formBerita = document.getElementById('formBeritaAcara');
        if (formBerita) {
            formBerita.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('button[type="submit"]');
                const originalHtml = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                btn.disabled = true;

                const formData = new FormData(this);
                
                fetch('{{ route("pengawas.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(async res => {
                    const text = await res.text();
                    try { return JSON.parse(text); } 
                    catch (e) { throw new Error("Server Error"); }
                })
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Laporan Terkirim',
                            text: 'Terima kasih atas laporannya.',
                            confirmButtonColor: '#2e5b9a'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal mengirim data', 'error');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
            });
        }

    });
  </script>
</body>
</html>