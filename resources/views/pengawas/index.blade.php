<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pengawas - Sistem Ujian</title>
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
    }

    .navbar-brand {
      padding: 8px 0;
    }

    .navbar-item {
      color: white !important;
      font-weight: 600;
      font-size: 1.1rem;
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

    /* ===== BUTTON CONTAINER MOBILE ===== */
    .button-container-mobile {
      display: none;
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #2e5b9a;
      width: 280px;
      height: 50px;
      align-items: center;
      justify-content: space-around;
      border-radius: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
      z-index: 99;
    }

    .button-container-mobile:hover {
      width: 300px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    }

    .btn-mobile {
      outline: 0 !important;
      border: 0 !important;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background-color: transparent;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      transition: all ease-in-out 0.3s;
      cursor: pointer;
      text-decoration: none;
    }

    .btn-mobile:hover {
      transform: translateY(-3px);
      background: rgba(255,255,255,0.1);
    }

    .icon-mobile {
      font-size: 20px;
    }

    /* ===== CARD STYLE ===== */
    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
      gap: 24px;
      margin-top: 20px;
    }

    .exam-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border: 1px solid #eef2f6;
    }

    .exam-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }

    .card-header-custom {
      background: linear-gradient(135deg, #2e5b9a 0%, #5c6fa6 100%);
      padding: 18px 20px;
      color: white;
    }

    .card-header-custom h3 {
      font-size: 1.1rem;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-header-custom h3 i {
      font-size: 1.2rem;
    }

    .card-body {
      padding: 20px;
    }

    .info-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .info-icon {
      width: 32px;
      height: 32px;
      background: #eef2ff;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #2e5b9a;
    }

    .info-content {
      flex: 1;
    }

    .info-label {
      font-size: 0.7rem;
      color: #7f8c8d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      font-size: 0.9rem;
      font-weight: 600;
      color: #1a2c3e;
    }

    .kelas-list {
      background: #f8f9fc;
      border-radius: 12px;
      padding: 12px;
      margin-top: 12px;
    }

    .kelas-title {
      font-size: 0.75rem;
      font-weight: 600;
      color: #2e5b9a;
      margin-bottom: 8px;
      text-transform: uppercase;
    }

    .kelas-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid #e9ecef;
    }

    .kelas-item:last-child {
      border-bottom: none;
    }

    .kelas-name {
      font-size: 0.85rem;
      font-weight: 500;
      color: #2c3e50;
    }

    .kelas-count {
      font-size: 0.75rem;
      background: #e9ecef;
      padding: 2px 10px;
      border-radius: 20px;
      color: #2e5b9a;
      font-weight: 600;
    }

    .card-footer-custom {
      padding: 16px 20px;
      background: #fafbfc;
      border-top: 1px solid #eef2f6;
    }

    .btn-awasi {
      background: #2e5b9a;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      justify-content: center;
    }

    .btn-awasi:hover {
      background: #1e3a6b;
      transform: translateY(-2px);
      color: white;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 16px;
      margin-top: 20px;
    }

    .empty-state i {
      font-size: 4rem;
      color: #cbd5e1;
      margin-bottom: 20px;
    }

    .empty-state h3 {
      font-size: 1.2rem;
      color: #475569;
      margin-bottom: 8px;
    }

    .empty-state p {
      color: #94a3b8;
    }

    /* Copyright */
    .copyright {
      text-align: center;
      padding: 30px 20px 20px;
      color: #94a3b8;
      font-size: 0.75rem;
      border-top: 1px solid #e2e8f0;
      margin-top: 40px;
    }

    /* Mobile Menu Toggle Button */
    .mobile-menu-toggle {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      background: #2e5b9a;
      border-radius: 50%;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 100;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      border: none;
      color: white;
    }

    .mobile-menu-toggle i {
      font-size: 22px;
    }

    /* Sidebar untuk mobile */
    .mobile-sidebar {
      position: fixed;
      bottom: 80px;
      right: 20px;
      background: #2e5b9a;
      border-radius: 20px;
      padding: 12px;
      display: none;
      flex-direction: column;
      gap: 8px;
      z-index: 99;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .mobile-sidebar.open {
      display: flex;
    }

    .mobile-sidebar .btn-mobile {
      width: 44px;
      height: 44px;
      background: rgba(255,255,255,0.15);
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

    /* Responsive */
    @media (max-width: 768px) {
      .main-container {
        margin-top: 60px;
        padding: 16px;
      }

      .cards-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .button-container-mobile {
        display: none;
      }

      .mobile-menu-toggle {
        display: flex;
      }

      .navbar-brand .navbar-item {
        font-size: 0.9rem;
      }

      .card-header-custom h3 {
        font-size: 0.95rem;
      }

      .info-value {
        font-size: 0.85rem;
      }
    }

    @media (min-width: 769px) {
      .mobile-menu-toggle {
        display: none;
      }
      
      .mobile-sidebar {
        display: none !important;
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
  <nav class="navbar" role="navigation" style="display:flex;flex-direction:rows;">
    <div class="navbar-brand">
      <a href="#" class="navbar-item has-text-white">
        <i class="fas fa-chalkboard-user"></i>
        Pengawas Ujian
      </a>
    </div>

    <div class="navbar-end">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
        <button type="submit" class="button is-danger">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </nav>

  <!-- Mobile Menu Toggle -->
  <button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Mobile Sidebar Menu -->
  <div class="mobile-sidebar" id="mobileSidebar">
    <a href="{{route('guru.index')}}" class="btn-mobile">
      <i class="fas fa-home icon-mobile"></i>
    </a>
    <a href="{{route('guru.result')}}" class="btn-mobile">
      <i class="fas fa-file-alt icon-mobile"></i>
    </a>
    <a href="{{route('guru.riwayat')}}" class="btn-mobile">
      <i class="fas fa-history icon-mobile"></i>
    </a>
    <a href="{{route('guru.jadwal')}}" class="btn-mobile">
      <i class="fas fa-calendar-alt icon-mobile"></i>
    </a>
    <a href="{{route('pengawas.index',$id ?? '')}}" class="btn-mobile">
      <i class="fas fa-user-check icon-mobile"></i>
    </a>
  </div>

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

    <!-- Title -->
    <div style="margin-bottom: 24px;">
      <h1 style="color: #2e5b9a; font-size: 1.5rem; font-weight: 700;">
        <i class="fas fa-clipboard-list"></i> Daftar Ujian yang Diawasi
      </h1>
      <p style="color: #64748b; font-size: 0.85rem; margin-top: 5px;">
        Kelola dan pantau jalannya ujian yang menjadi tanggung jawab Anda
      </p>
    </div>

    @if(isset($data) && $data->count() <= 0)
      <!-- Empty State -->
      <div class="empty-state">
        <i class="fas fa-hourglass-half"></i>
        <h3>Belum Ditugaskan</h3>
        <p>Anda belum mendapatkan tugas pengawasan ujian saat ini</p>
      </div>
    @elseif(isset($jads) && $jads->count() > 0)
      <!-- Cards Grid -->
      <div class="cards-grid">
        @foreach($jads as $jd)
          <div class="exam-card">
            <div class="card-header-custom">
              <h3>
                <i class="fas fa-book-open"></i>
                {{ $jd->ujian->nama_ujian ?? 'Ujian' }}
              </h3>
            </div>
            
            <div class="card-body">
              <!-- Durasi -->
              <div class="info-row">
                <div class="info-icon">
                  <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="info-content">
                  <div class="info-label">Durasi Ujian</div>
                  <div class="info-value">{{ $jd->ujian->durasi ?? 'Belum ditentukan' }} Menit</div>
                </div>
              </div>
              
              <!-- Waktu -->
              @if(isset($jd->waktu_mulai))
              <div class="info-row">
                <div class="info-icon">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                  <div class="info-label">Waktu Pelaksanaan</div>
                  <div class="info-value">
                    {{ \Carbon\Carbon::parse($jd->waktu_mulai)->isoFormat('dddd, D MMMM YYYY') }}
                    <br>
                    <small>{{ \Carbon\Carbon::parse($jd->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jd->waktu_selesai)->format('H:i') }} WIB</small>
                  </div>
                </div>
              </div>
              @endif
              
              <!-- Kelas yang mengikuti -->
              <div class="kelas-list">
                <div class="kelas-title">
                  <i class="fas fa-users"></i> Kelas yang mengikuti
                </div>
                @if(isset($jd->ujian->kelas) && $jd->ujian->kelas->count() > 0)
                  @foreach($jd->ujian->kelas as $kelas)
                    <div class="kelas-item">
                      <span class="kelas-name">{{ $kelas->nama_kelas ?? 'Kelas' }}</span>
                      <span class="kelas-count">{{ $kelas->siswa->count() ?? 0 }} siswa</span>
                    </div>
                  @endforeach
                @else
                  <div class="kelas-item">
                    <span class="kelas-name">Belum ada kelas terdaftar</span>
                  </div>
                @endif
              </div>
            </div>
            
            <div class="card-footer-custom">
              <a href="{{route('pengawas.show', $jd->id)}}" class="btn-awasi">
                <i class="fas fa-eye"></i>
                Awasi Sekarang
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <!-- Empty State -->
      <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h3>Tidak Ada Data Ujian</h3>
        <p>Belum ada ujian yang perlu diawasi</p>
      </div>
    @endif

    <!-- Copyright -->
    <div class="copyright">
      <i class="fas fa-copyright"></i> 2026 SMK NEGERI 1 CIOMAS - Sistem Ujian Online
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Mobile Menu Toggle
      const mobileToggle = document.getElementById('mobileMenuToggle');
      const mobileSidebar = document.getElementById('mobileSidebar');
      
      if (mobileToggle && mobileSidebar) {
        mobileToggle.addEventListener('click', function(e) {
          e.stopPropagation();
          mobileSidebar.classList.toggle('open');
          const icon = mobileToggle.querySelector('i');
          if (mobileSidebar.classList.contains('open')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
          } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
          }
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
          if (!mobileToggle.contains(event.target) && !mobileSidebar.contains(event.target)) {
            mobileSidebar.classList.remove('open');
            const icon = mobileToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
          }
        });
      }
      
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
      
      // Handle window resize - close mobile menu on desktop
      let resizeTimer;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          if (window.innerWidth > 768) {
            if (mobileSidebar) mobileSidebar.classList.remove('open');
            if (mobileToggle) {
              const icon = mobileToggle.querySelector('i');
              if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
              }
            }
          }
        }, 250);
      });
    });
  </script>
</body>
</html>