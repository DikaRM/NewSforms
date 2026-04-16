<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin Dashboard - Sistem Ujian</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

body {
    background: #f3f5f9;
    overflow-x: hidden;
}

/* ===== HEADER ===== */
.header {
    background: #2e5b9a;
    color: white;
    padding: 12px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.header h2 {
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header h2 i {
    font-size: 1.2rem;
}

/* User Dropdown */
.user-dropdown {
    position: relative;
    cursor: pointer;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 12px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.user-info:hover {
    background: rgba(255,255,255,0.15);
}

.user-avatar {
    width: 34px;
    height: 34px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e5b9a;
    font-weight: bold;
}

.user-name {
    font-weight: 500;
    font-size: 0.85rem;
}

.user-name i {
    font-size: 0.7rem;
    margin-left: 5px;
}

/* Dropdown Menu */
.dropdown-menu-custom {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1001;
}

.user-dropdown.active .dropdown-menu-custom {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item-custom {
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s ease;
    border-bottom: 1px solid #eee;
    font-size: 0.85rem;
}

.dropdown-item-custom:last-child {
    border-bottom: none;
}

.dropdown-item-custom:hover {
    background: #f5f5f5;
}

.dropdown-item-custom i {
    width: 18px;
    color: #5c6fa6;
}

.dropdown-divider {
    height: 1px;
    background: #eee;
    margin: 4px 0;
}

.logout-btn {
    color: #dc3545;
}

.logout-btn i {
    color: #dc3545;
}

/* ===== LAYOUT ===== */
.app-wrapper {
    display: flex;
    margin-top: 56px;
    min-height: calc(100vh - 56px);
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    background: #5c6fa6;
    position: fixed;
    left: 0;
    top: 56px;
    bottom: 0;
    z-index: 99;
    transition: transform 0.3s ease;
    overflow-y: auto;
}

.sidebar.closed {
    transform: translateX(-100%);
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    margin: 4px 12px;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.sidebar-item i {
    width: 20px;
    font-size: 1rem;
}

.sidebar-item span {
    font-size: 0.85rem;
    font-weight: 500;
}

.sidebar-item:hover {
    background: rgba(255,255,255,0.2);
}

.sidebar-item.active {
    background: rgba(255,255,255,0.25);
}

.sidebar-logout {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    padding: 0 12px;
}

.sidebar-logout .sidebar-item {
    color: white;
}

.sidebar-logout .sidebar-item:hover {
    background: rgba(220,53,69,0.8);
}

/* ===== MAIN CONTENT ===== */
.main-content {
    flex: 1;
    margin-left: 240px;
    padding: 24px;
    transition: margin-left 0.3s ease;
}

.main-content.full {
    margin-left: 0;
}

/* Mobile Menu Toggle */
.mobile-toggle {
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

.mobile-toggle i {
    font-size: 22px;
}

/* Overlay untuk mobile */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 56px;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 98;
}

.sidebar-overlay.active {
    display: block;
}

/* ===== CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.stat-info h3 {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 8px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2e5b9a;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: #e8eef5;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e5b9a;
    font-size: 1.4rem;
}

/* Warna Card Khusus (seperti desain) */
.card-pink {
    background: #f8d7da;
}
.card-yellow {
    background: #fff3cd;
}
.card-blue {
    background: #cfe2ff;
}
.card-green {
    background: #d1e7dd;
}

/* ===== SECTION ===== */
.section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-header h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #2e5b9a;
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.data-table th {
    background: #2e5b9a;
    color: white;
    font-weight: 500;
    font-size: 0.85rem;
}

.data-table tr:hover {
    background: #f8f9fa;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: #2e5b9a;
    color: white;
}

.btn-primary:hover {
    background: #1e3a6b;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

.btn-sm {
    padding: 4px 10px;
    font-size: 0.7rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
}

.modal.is-active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #2e5b9a;
    color: white;
    border-radius: 12px 12px 0 0;
}

.modal-header h5 {
    margin: 0;
    font-size: 1rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: white;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Form */
.field {
    margin-bottom: 15px;
}

.field label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    color: #333;
}

.field input,
.field select,
.field textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.85rem;
}

.field input:focus,
.field select:focus {
    outline: none;
    border-color: #2e5b9a;
}

/* Notification */
.notification-toast {
    position: fixed;
    top: 70px;
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

/* Badge */
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.badge-info {
    background: #cfe2ff;
    color: #2e5b9a;
}

/* Exam Item */
.exam-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.exam-item:last-child {
    border-bottom: none;
}

.exam-info {
    flex: 1;
}

.exam-title {
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 0.9rem;
}

.exam-meta {
    font-size: 0.7rem;
    color: #6c757d;
}

/* Level (flex utility) */
.level {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

/* Responsive Breakpoints */
@media (max-width: 768px) {
    .header h2 span {
        display: none;
    }
    
    .user-name span {
        display: none;
    }
    
    .user-name i {
        display: none;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
    }
    
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
        padding: 16px;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-card {
        padding: 15px;
    }
    
    .stat-number {
        font-size: 1.4rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .stat-info h3 {
        font-size: 0.7rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .section-header .btn {
        justify-content: center;
    }
    
    .data-table th,
    .data-table td {
        padding: 8px;
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .main-content {
        padding: 12px;
    }
    
    .section-card {
        padding: 15px;
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
.image{
height:35px;
}
</style>

@stack('styles')
</head>

<body>

<!-- Header -->
<header class="header">
    <h2>
        <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34"/>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name">
                @if($ire)
                    <span>{{ $ire->nama }}</span>
                @else
                    <span>Guest</span>
                @endif
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div class="dropdown-menu-custom">
            <div class="dropdown-item-custom">
                <i class="fas fa-user-circle"></i>
                <span>Profil Saya</span>
            </div>
            <div class="dropdown-divider"></div>
            <form action="{{ route('users.logout') }}" method="post" id="logoutForm">
                @csrf
                <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Menu Toggle -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('admin.index') }}" class="sidebar-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
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
        </div>
        
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
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
        
        @yield("content")
    </main>
</div>

<script>
    // User Dropdown Toggle
    const userDropdown = document.getElementById('userDropdown');
    
    if (userDropdown) {
        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', () => {
        if (userDropdown) userDropdown.classList.remove('active');
    });
    
    // Mobile Sidebar Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        
        const icon = mobileToggle.querySelector('i');
        if (sidebar.classList.contains('open')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }
    
    // Auto hide notification
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }, 5000);
    }
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                const icon = mobileToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }, 250);
    });
    
    // Active menu highlighting
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-item').forEach(item => {
        const href = item.getAttribute('href');
        if (href && href !== '#' && currentPath.includes(href) && href !== 'javascript:void(0)') {
            item.classList.add('active');
        }
    });
    
    // Close sidebar when clicking a link on mobile
    document.querySelectorAll('.sidebar-item').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    if (sidebar.classList.contains('open')) toggleSidebar();
                }, 150);
            }
        });
    });
    
    // Modal handlers
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('is-active');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('is-active');
    }
    
    // Close modal when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('is-active');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>