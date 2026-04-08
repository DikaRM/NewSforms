<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Sistem Ujian')</title>

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
    color: #2e5b9a;
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
    width: 260px;
    background: #5c6fa6;
    position: fixed;
    left: 0;
    top: 56px;
    bottom: 0;
    z-index: 99;
    transition: transform 0.3s ease;
    overflow-y: auto;
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
    width: 22px;
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
    border-left: 3px solid white;
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
    background: #dc3545;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    flex: 1;
    margin-left: 260px;
    padding: 24px;
    transition: margin-left 0.3s ease;
    width: calc(100% - 260px);
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
.cards {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.card {
    width: 300px;
    padding: 25px;
    border-radius: 15px;
    color: #333;
    position: relative;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
    text-decoration: none;
    display: block;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin-bottom: 10px;
}

.card p {
    font-size: 14px;
    color: #555;
}

.card .arrow {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 20px;
}

.pink {
    background: #f8d7da;
}

.yellow {
    background: #fff3cd;
}

/* ===== EXAM CARD ===== */
.exam-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2e5b9a;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.exam-card {
    margin-bottom: 20px;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.exam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.exam-card .card-content {
    padding: 20px;
}

.exam-card .media {
    border-bottom: 2px solid #f5f5f5;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.exam-card .title.is-4 {
    color: #2e5b9a;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 5px !important;
}

.exam-card .subtitle.is-6 {
    color: #7f8c8d;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}

.exam-card .subtitle.is-6 .icon {
    font-size: 0.75rem;
    color: #2e5b9a;
}

.tag-custom {
    display: inline-block;
    padding: 6px 14px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 20px;
}

.tag-success {
    background: #d4edda;
    color: #155724;
}

.tag-warning {
    background: #fff3cd;
    color: #856404;
}

.tag-info {
    background: #cfe2ff;
    color: #2e5b9a;
}

.tag-danger {
    background: #f8d7da;
    color: #721c24;
}

/* Button Custom */
.btn-custom {
    background: #2e5b9a;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.btn-custom:hover {
    background: #1e3a6b;
    transform: scale(1.02);
}

.btn-outline-custom {
    background: transparent;
    border: 1px solid #2e5b9a;
    color: #2e5b9a;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: #2e5b9a;
    color: white;
}

/* Modal Custom */
.modal-card {
    border-radius: 12px;
    overflow: hidden;
}

.modal-card-head {
    background: #2e5b9a;
    color: white;
}

.modal-card-head .title {
    color: white;
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

/* Responsive */
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
        margin-left: 0 !important;
        width: 100% !important;
        padding: 16px;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .cards {
        gap: 15px;
    }
    
    .card {
        width: calc(50% - 15px);
        min-width: 150px;
        padding: 20px;
    }
    
    .card h3 {
        font-size: 1rem;
    }
    
    .card p {
        font-size: 0.75rem;
    }
    
    .section-title {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .cards {
        flex-direction: column;
    }
    
    .card {
        width: 100%;
    }
    
    .main-content {
        padding: 12px;
    }
    
    .exam-card .card-content {
        padding: 15px;
    }
    
    .exam-card .buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .exam-card .buttons .button {
        margin: 5px;
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

@stack('styles')
</head>

<body>

<!-- Header -->
<header class="header">
    <h2>
        <i class="fas fa-chalkboard-user"></i>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="user-name">
                @if(isset($user))
                    <span>{{ $user->nama }}</span>
                @else
                    <span>{{ Auth::user()->name ?? 'User' }}</span>
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
            <form action="{{ route('users.logout') }}" method="post">
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
            @yield('sidebar')
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
        
        @yield('content')
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Toggle
    var userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.classList.remove('active');
    });
    
    // Mobile Sidebar Toggle
    var mobileToggle = document.getElementById('mobileToggle');
    var sidebar = document.getElementById('sidebar');
    var sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        var icon = mobileToggle.querySelector('i');
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
    var notification = document.getElementById('notification');
    if (notification) {
        setTimeout(function() {
            notification.style.opacity = '0';
            setTimeout(function() {
                notification.style.display = 'none';
            }, 300);
        }, 5000);
    }
    
    // Handle window resize
    var resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                var icon = mobileToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }, 250);
    });
    
    // Close sidebar on mobile after clicking link
    var sidebarItems = document.querySelectorAll('.sidebar-item');
    for (var i = 0; i < sidebarItems.length; i++) {
        sidebarItems[i].addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    if (sidebar.classList.contains('open')) toggleSidebar();
                }, 150);
            }
        });
    }
});
</script>

@stack('scripts')
</body>
</html>