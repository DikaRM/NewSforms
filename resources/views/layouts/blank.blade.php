<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - Sistem Ujian</title>
  
  <!-- Bulma CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
  
  <!-- Font Awesome 6 (Icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Custom CSS -->
  <style>
    /* Custom Variables */
    :root {
      --primary-dark: #0a0c10;
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --sidebar-width: 280px;
    }
    
    /* Base Styles */
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Navbar Styles */
    .navbar {
      background: var(--primary-gradient);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }
    
    .navbar-brand .title {
      color: white;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    /* Sidebar Styles */
    .sidebar {
      position: fixed;
      top: 52px;
      left: 0;
      width: var(--sidebar-width);
      height: calc(100vh - 52px);
      background: white;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
      transition: all 0.3s ease;
      z-index: 999;
    }
    
    .sidebar-menu {
      padding: 20px 0;
    }
    
    .sidebar-item {
      display: flex;
      align-items: center;
      padding: 12px 24px;
      margin: 4px 12px;
      color: #4a5568;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
    }
    
    .sidebar-item i {
      width: 24px;
      margin-right: 12px;
      font-size: 1.2rem;
    }
    
    .sidebar-item:hover {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      transform: translateX(5px);
    }
    
    .sidebar-item.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Main Content */
    .main-content {
      margin-left: var(--sidebar-width);
      margin-top: 52px;
      padding: 24px;
      transition: all 0.3s ease;
    }
    
    /* Card Styles */
    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
      font-size: 2.5rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: #2d3748;
      margin-top: 10px;
    }
    
    .stat-label {
      color: #718096;
      font-size: 0.9rem;
      margin-top: 5px;
    }
    
    /* Table Styles */
    .table-container {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .table thead th {
      padding: 15px;
      border: none;
    }
    
    .table tbody tr:hover {
      background: #f7fafc;
      transition: background 0.3s ease;
    }
    
    /* Button Styles */
    .button {
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .button.is-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
    }
    
    .button.is-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Notification */
    .notification {
      border-radius: 12px;
      border-left: 4px solid;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .stat-card {
        margin-bottom: 15px;
      }
      
      .mobile-menu-toggle {
        display: block;
      }
    }
    
    @media (min-width: 769px) {
      .mobile-menu-toggle {
        display: none;
      }
    }
    
    /* User Info */
    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #667eea;
      font-weight: bold;
    }
    
    /* Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease;
    }
  </style>
  
  @stack('styles')
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-brand">
      <a class="navbar-item" href="{{ route('admin.index') }}">
        <span class="title is-4 has-text-white">
          <i class="fas fa-graduation-cap"></i> Sistem Ujian
        </span>
      </a>
      
      <a class="navbar-burger burger mobile-menu-toggle" id="mobileMenuToggle">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>
    
    <div class="navbar-menu">
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="user-info">
            <div class="user-avatar">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <strong class="has-text-white">
                @if($ire)
                  {{ $ire->nama }}
                @else
                  Guest
                @endif
              </strong>
              <br>
              <small class="has-text-white">Administrator</small>
            </div>
            <form action="{{ route('users.logout') }}" method="post">
              @csrf
              <button type="submit" class="button is-danger is-light">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>
  
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-menu">
      <a href="{{ route('admin.index') }}" class="sidebar-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
      
      <a href="{{ route('admin.siswa.index') }}" class="sidebar-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Data Siswa</span>
      </a>
      
      <a href="{{ route('admin.guru.index') }}" class="sidebar-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-user"></i>
        <span>Data Guru</span>
      </a>
      
      <a href="{{ route('admin.kelas') }}" class="sidebar-item {{ request()->routeIs('admin.kelas') ? 'active' : '' }}">
        <i class="fas fa-building"></i>
        <span>Data Kelas</span>
      </a>
      
      <a href="{{ route('admin.mapel') }}" class="sidebar-item {{ request()->routeIs('admin.mapel') ? 'active' : '' }}">
        <i class="fas fa-book"></i>
        <span>Data Mapel</span>
      </a>
      
      <a href="{{ route('admin.ujian.index') }}" class="sidebar-item {{ request()->routeIs('admin.ujian.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i>
        <span>Data Ujian</span>
      </a>
      
      <a href="{{ route('admin.jadwal.index') }}" class="sidebar-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
        <span>Jadwal Ujian</span>
      </a>
      
      <a href="{{ route('admin.bank.index') }}" class="sidebar-item {{ request()->routeIs('admin.bank.*') ? 'active' : '' }}">
        <i class="fas fa-database"></i>
        <span>Bank Soal</span>
      </a>
      
      <a href="{{ route('admin.pelanggaran.index') }}" class="sidebar-item {{ request()->routeIs('admin.pelanggaran.*') ? 'active' : '' }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Pelanggaran</span>
      </a>
      
      <hr class="my-3">
      
      <a href="#" class="sidebar-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
      
      <form id="logout-form" action="{{ route('users.logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </aside>
  
  <!-- Main Content -->
  <main class="main-content fade-in" id="mainContent">
    <div class="container is-fluid">
      @if(session('success'))
        <div class="notification is-success is-light">
          <button class="delete"></button>
          <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
      @endif
      
      @if(session('error'))
        <div class="notification is-danger is-light">
          <button class="delete"></button>
          <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
      @endif
      
      @yield("content")
    </div>
  </main>
  
  <!-- Scripts -->
  <script>
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileMenuToggle) {
      mobileMenuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
      });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });
    
    // Delete notifications
    document.querySelectorAll('.notification .delete').forEach(deleteButton => {
      deleteButton.addEventListener('click', () => {
        deleteButton.parentElement.style.display = 'none';
      });
    });
    
    // Auto hide notifications after 5 seconds
    setTimeout(() => {
      document.querySelectorAll('.notification').forEach(notification => {
        notification.style.opacity = '0';
        setTimeout(() => {
          notification.style.display = 'none';
        }, 300);
      });
    }, 5000);
    
    // Add active state to current menu
    const currentUrl = window.location.pathname;
    document.querySelectorAll('.sidebar-item').forEach(item => {
      const href = item.getAttribute('href');
      if (href && currentUrl.includes(href)) {
        item.classList.add('active');
      }
    });
  </script>
  
  @stack('scripts')
</body>
</html>