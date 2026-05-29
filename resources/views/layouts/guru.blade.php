<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Guru - Sistem Ujian')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
    
    <style>
        /* Overlay (background gelap) */
        .overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 999;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: 0.3s;
        }
        .overlay.is-active { opacity: 1; visibility: visible; }

        /* Bottom Sheet */
        .bottom-sheet {
            width: 100%; max-width: 500px; background: white; border-radius: 20px 20px 0 0;
            transform: translateY(100%); transition: 0.4s ease; display: flex; flex-direction: column; max-height: 90vh;
        }
        .overlay.is-active .bottom-sheet { transform: translateY(0); }
        
        .sheet-handle { width: 40px; height: 4px; background: #ccc; margin: 10px auto; border-radius: 10px; cursor: pointer; }
        .sheet-header { padding: 16px; display: flex; justify-content: space-between; border-bottom: 1px solid #eee; }
        .sheet-title { font-weight: bold; }
        .sheet-body { padding: 16px; overflow-y: auto; }
        .sheet-footer { padding: 16px; border-top: 1px solid #eee; display: flex; gap: 10px; }
        
        .btn-submit { flex: 1; background: #3085d6; color: white; border: none; padding: 10px; border-radius: 8px; }
        .btn-cancel { flex: 1; background: #d33; border: none; padding: 10px; color:white; border-radius: 8px; }
        .form-input { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 8px; border: 1px solid #ddd; }

        /* Animasi masuk */
        .main-content { animation: pageEnter 0.3s ease-out forwards; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .main-content.page-leaving { animation: pageLeave 0.25s ease-in forwards !important; }
        @keyframes pageLeave { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(-12px); } }

        /* Animasi Lonceng Notifikasi */
        @keyframes bellShake {
            0% { transform: rotate(0); } 15% { transform: rotate(15deg); } 30% { transform: rotate(-10deg); }
            45% { transform: rotate(5deg); } 60% { transform: rotate(-5deg); } 75% { transform: rotate(2deg); } 100% { transform: rotate(0); }
        }
        .has-new-notif .fa-bell { animation: bellShake 2s ease-in-out infinite; color: #ffd700; }

        /* Reset & Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #f3f5f9; overflow-x: hidden; }

        /* ===== HEADER ===== */
        .header {
            background: #2e5b9a; color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header h2 { font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .header h2 img { height: 30px; object-fit: contain; }
        .header-actions { display: flex; align-items: center; gap: 15px; }

        /* ===== NOTIFIKASI ===== */
        .notif-wrapper { position: relative; }
        .notif-btn { position: relative; cursor: pointer; padding: 8px; border-radius: 50%; transition: background 0.3s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; }
        .notif-btn:hover { background: rgba(255,255,255,0.15); }
        .notif-btn i { font-size: 1.2rem; }
        .notif-badge { position: absolute; top: 2px; right: 2px; background: #dc3545; color: white; font-size: 0.65rem; font-weight: bold; height: 18px; min-width: 18px; padding: 0 4px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid #2e5b9a; z-index: 2; }
        .notif-badge.hidden { display: none; }
        .notif-dropdown { position: absolute; top: 120%; right: -10px; width: 320px; background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1002; }
        .notif-wrapper.active .notif-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .notif-header { padding: 12px 16px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .notif-header h4 { font-size: 0.9rem; color: #2e5b9a; font-weight: 700; }
        .notif-header .mark-read { font-size: 0.75rem; color: #5c6fa6; cursor: pointer; text-decoration: underline; }
        .notif-body { max-height: 350px; overflow-y: auto; }
        .notif-item { padding: 12px 16px; border-bottom: 1px solid #f5f5f5; display: flex; gap: 12px; transition: background 0.2s; cursor: pointer; }
        .notif-item:hover { background: #f8f9fa; }
        .notif-item.unread { background: #f0f7ff; border-left: 3px solid #2e5b9a; }
        .notif-icon-box { width: 36px; height: 36px; background: #e3eaf5; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2e5b9a; flex-shrink: 0; }
        .notif-content { flex: 1; }
        .notif-title { font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 2px; line-height: 1.3; }
        .notif-desc { font-size: 0.75rem; color: #666; margin-bottom: 4px; }
        .notif-time { font-size: 0.7rem; color: #999; }
        .notif-footer { padding: 10px; text-align: center; border-top: 1px solid #eee; }
        .notif-footer a { font-size: 0.8rem; color: #2e5b9a; text-decoration: none; font-weight: 500; }
        .empty-notif { padding: 30px; text-align: center; color: #999; }
        .empty-notif i { font-size: 2rem; margin-bottom: 10px; color: #ddd; }

        /* ===== USER DROPDOWN ===== */
        .user-dropdown { position: relative; cursor: pointer; }
        .user-info { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 8px; transition: background 0.3s ease; }
        .user-info:hover { background: rgba(255,255,255,0.15); }
        .user-avatar { width: 34px; height: 34px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e5b9a; font-weight: bold; }
        .user-name { font-weight: 500; font-size: 0.85rem; }
        .user-name i { font-size: 0.7rem; margin-left: 5px; }
        .dropdown-menu-custom { position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); min-width: 180px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1001; }
        .user-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item-custom { padding: 10px 16px; display: flex; align-items: center; gap: 12px; color: #333; text-decoration: none; transition: background 0.2s ease; border-bottom: 1px solid #eee; font-size: 0.85rem; }
        .dropdown-item-custom:last-child { border-bottom: none; }
        .dropdown-item-custom:hover { background: #f5f5f5; }
        .dropdown-item-custom i { width: 18px; color: #2e5b9a; }
        .dropdown-divider { height: 1px; background: #eee; margin: 4px 0; }
        .logout-btn { color: #dc3545; }
        .logout-btn i { color: #dc3545; }

        /* ===== LAYOUT ===== */
        .app-wrapper { display: flex; margin-top: 56px; min-height: calc(100vh - 56px); }

        /* ===== SIDEBAR ===== */
        .sidebar { width: 260px; background: #5c6fa6; position: fixed; left: 0; top: 56px; bottom: 0; z-index: 99; transition: transform 0.3s ease; overflow-y: auto; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; margin: 4px 12px; color: white; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
        .sidebar-item i { width: 22px; font-size: 1rem; }
        .sidebar-item span { font-size: 0.85rem; font-weight: 500; }
        .sidebar-item:hover { background: rgba(255,255,255,0.25);  color:#2e5b9a;border-left: 4px solid white;}
        .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 4px solid white; }
        
        .sidebar-logout { position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 12px; }
        .sidebar-logout .sidebar-item { color: white; }
        .sidebar-logout .sidebar-item:hover { background: #dc3545; }

        /* ===== MAIN CONTENT ===== */
        .main-content { flex: 1; margin-left: 260px; padding: 24px; transition: margin-left 0.3s ease; width: calc(100% - 260px); }

        /* Mobile Toggle */
        .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
        .mobile-toggle i { font-size: 22px; }
        .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
        .sidebar-overlay.active { display: block; }

        /* ===== COMPONENTS ===== */
        .cards { display: flex; gap: 25px; flex-wrap: wrap; margin-bottom: 30px; }
        .card { width: 300px; padding: 25px; border-radius: 15px; color: #333; position: relative; box-shadow: 0 10px 20px rgba(0,0,0,0.08); transition: 0.3s; text-decoration: none; display: block; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin-bottom: 10px; }
        .card p { font-size: 14px; color: #555; }
        .card .arrow { position: absolute; right: 20px; bottom: 20px; font-size: 20px; }
        .pink { background: #f8d7da; }
        .yellow { background: #fff3cd; }

        .exam-container { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-size: 1.1rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .exam-card { margin-bottom: 20px; border-radius: 12px !important; box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important; transition: all 0.3s ease; border: 1px solid #f0f0f0; }
        .exam-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important; }
        .exam-card .card-content { padding: 20px; }
        .exam-card .media { border-bottom: 2px solid #f5f5f5; padding-bottom: 15px; margin-bottom: 15px; }
        .exam-card .title.is-4 { color: #2e5b9a; font-weight: 700; font-size: 1rem; margin-bottom: 5px !important; }
        .exam-card .subtitle.is-6 { color: #7f8c8d; font-size: 0.75rem; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
        
        .tag-custom { display: inline-block; padding: 6px 14px; font-size: 0.75rem; font-weight: 500; border-radius: 20px; }
        .tag-success { background: #d4edda; color: #155724; }
        .tag-warning { background: #fff3cd; color: #856404; }
        .tag-info { background: #cfe2ff; color: #2e5b9a; }
        .tag-danger { background: #f8d7da; color: #721c24; }

        .btn-custom { background: #2e5b9a; color: white; border: none; padding: 8px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.8rem; }
        .btn-custom:hover { background: #1e3a6b; transform: scale(1.02); }

        .modal-card { border-radius: 12px; overflow: hidden; }
        .modal-card-head { background: #2e5b9a; color: white; }
        .modal-card-head .title { color: white; }

        .notification-toast { position: fixed; top: 70px; right: 20px; padding: 12px 18px; border-radius: 8px; color: white; z-index: 1100; animation: slideInRight 0.3s ease; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .notification-success { background: #28a745; }
        .notification-error { background: #dc3545; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Responsive */
        @media (max-width: 768px) {
            .header h2 span { display: inline; }
            .user-name span { display: none; }
            .user-name i { display: none; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
            .mobile-toggle { display: flex; }
            .cards { gap: 15px; }
            .card { width: calc(50% - 15px); min-width: 150px; padding: 20px; }
            .notif-dropdown { width: 280px; right: -20px; }
        }
        @media (max-width: 480px) {
            .cards { flex-direction: column; }
            .card { width: 100%; }
            .main-content { padding: 12px; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #5c6fa6; border-radius: 3px; }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <h2>
           <img src="{{asset('WhatsApp Image 2026-04-10 at 08.00.25.png')}}" class="image is-32x34" style="height:30px"/>
            <span>SMK NEGERI 1 CIOMAS</span>
        </h2>
        
        <div class="header-actions">
            
            <!-- NOTIFIKASI -->
            <div class="notif-wrapper" id="notifWrapper">
                <div class="notif-btn" id="notifBtn">
                    <i class="fas fa-bell"></i>
                    <span class="notif-badge hidden" id="notifCount">0</span>
                </div>
                
                <div class="notif-dropdown">
                    <div class="notif-header">
                        <h4><i class="fas fa-bell"></i> Notifikasi</h4>
                        <span class="mark-read" onclick="markAllRead()">Tandai dibaca</span>
                    </div>
                    <div class="notif-body" id="notifList">
                        <!-- Data Notifikasi akan dimuat via JS -->
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('guru.jadwal') }}">Lihat Semua Jadwal</a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="user-dropdown" id="userDropdown">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="user-name">
                        @if(isset($ire))
                            <span>{{ $ire->nama }}</span>
                        @else
                            <span>Guru</span>
                        @endif
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                
                <div class="dropdown-menu-custom">
                    <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item-custom logout-btn logout-button" style="width: 100%; background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-menu">
                {{-- Catatan: Anda bisa menggunakan variabel $activeMenu dari controller untuk menandai menu aktif --}}
                <a href="{{ route('guru.index') }}" class="sidebar-item @request()->is('guru/index') ? 'active' : ''">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('guru.jadwal') }}" class="sidebar-item @request()->is('guru/jadwal*') ? 'active' : ''">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadwal Ujian</span>
                </a>
                <a href="{{ route('guru.riwayat') }}" class="sidebar-item @request()->is('guru/riwayat*') ? 'active' : ''">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Ujian</span>
                </a>
                <a href="{{ route('guru.result') }}" class="sidebar-item @request()->is('guru/result*') ? 'active' : ''">
                    <i class="fas fa-chart-line"></i>
                    <span>Hasil Ujian</span>
                </a>
                <a href="{{ route('pengawas.index', isset($dt) ? $dt->id : '') }}" class="sidebar-item @request()->is('pengawas/*') ? 'active' : ''">
                    <i class="fas fa-users"></i>
                    <span>Pengawas</span>
                </a>
            </div>
            
            <div class="sidebar-logout">
                <form action="{{ route('users.logout') }}" method="post"  class="logout-form">
                    @csrf
                    <button type="submit" class="sidebar-item logout-button" style="width: 100%; background: none; border: none; cursor: pointer;">
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
            
            <!-- Komponen Global Sheet Container (Isi content disuntikkan via JS dari child view) -->
            <div class="overlay" id="global-sheet">
                <div class="bottom-sheet">
                    <div class="sheet-handle" onclick="closeSheet()"></div>
                    <div class="sheet-header">
                        <h3 class="sheet-title" id="sheet-title">Title</h3>
                        <button class="btn-close" onclick="closeSheet()" style="background:transparent;border:none;"></button>
                    </div>
                    <div class="sheet-body" id="sheet-body">
                        <!-- Content Disini -->
                    </div>
                    <div class="sheet-footer" id="sheet-footer">
                        <!-- Tombol Disini -->
                    </div>
                </div>
            </div>

            <!-- CONTENT DARI HALAMAN ANAK (CHILD VIEW) -->
            @yield('content')

        </main>
    </div>

    {{-- GLOBAL JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            // 1. LOGIKA NOTIFIKASI
            // ==========================================
            var notifData = @json($notifData ?? []);
            const notifWrapper = document.getElementById('notifWrapper');
            const notifBtn = document.getElementById('notifBtn');
            const notifList = document.getElementById('notifList');
            const notifCountBadge = document.getElementById('notifCount');

            function renderNotifications() {
                const unreadCount = notifData.filter(n => n.unread).length;
                notifCountBadge.innerText = unreadCount;
                if (unreadCount > 0) {
                    notifCountBadge.classList.remove('hidden');
                    notifWrapper.classList.add('has-new-notif');
                } else {
                    notifCountBadge.classList.add('hidden');
                    notifWrapper.classList.remove('has-new-notif');
                }
                if (notifData.length === 0) {
                    notifList.innerHTML = `<div class="empty-notif"><i class="fas fa-check-circle"></i><p>Tidak ada notifikasi baru</p></div>`;
                    return;
                }
                let html = '';
                notifData.forEach(notif => {
                    html += `<a href="{{ route('pengawas.index', isset($dt) ? $dt->id : '') }}" class="sidebar-item">
                        <div class="notif-item ${notif.unread ? 'unread' : ''}" onclick="openNotif(${notif.id})">
                            <div class="notif-icon-box"><i class="fas fa-clipboard-check"></i></div>
                            <div class="notif-content">
                                <div class="notif-title">${notif.title}</div>
                                <div class="notif-desc">${notif.kelas}</div>
                                <div class="notif-time">${notif.time}</div>
                            </div>
                        </div>
                    </a>`;
                });
                notifList.innerHTML = html;
            }

            if (notifBtn) {
                notifBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notifWrapper.classList.toggle('active');
                    document.getElementById('userDropdown').classList.remove('active');
                });
            }

            window.openNotif = function(id) {
                const notif = notifData.find(n => n.id === id);
                if(notif) notif.unread = false;
                renderNotifications();
            }

            window.markAllRead = function() {
                notifData.forEach(n => n.unread = false);
                renderNotifications();
            }

            renderNotifications();

            // ==========================================
            // 2. USER DROPDOWN & SIDEBAR
            // ==========================================
            var userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('active');
                    notifWrapper.classList.remove('active');
                });
            }
            
            document.addEventListener('click', function() {
                if (userDropdown) userDropdown.classList.remove('active');
                if (notifWrapper) notifWrapper.classList.remove('active');
            });
            
            var mobileToggle = document.getElementById('mobileToggle');
            var sidebar = document.getElementById('sidebar');
            var sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('active');
                var icon = mobileToggle.querySelector('i');
                if (sidebar.classList.contains('open')) {
                    icon.classList.remove('fa-bars'); icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times'); icon.classList.add('fa-bars');
                }
            }
            
            if (mobileToggle) { mobileToggle.addEventListener('click', toggleSidebar); }
            if (sidebarOverlay) { sidebarOverlay.addEventListener('click', toggleSidebar); }
        });

        // ==========================================
        // 3. GLOBAL SHEET FUNCTIONS
        // ==========================================
        function openSheet({ title, body, footer }) {
            document.getElementById('sheet-title').innerHTML = title;
            document.getElementById('sheet-body').innerHTML = body;
            document.getElementById('sheet-footer').innerHTML = footer;
            document.getElementById('global-sheet').classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }

        function closeSheet() {
            document.getElementById('global-sheet').classList.remove('is-active');
            document.body.style.overflow = 'auto';
        }

        // ==========================================
        // 4. SMOOTH TRANSITION LINKS
        // ==========================================
        let mainContent = document.querySelector(".main-content");
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return; 
            const href = link.getAttribute('href');
            const target = link.getAttribute('target');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || target === '_blank') return;
            if (link.classList.contains('no-transition') || link.getAttribute('data-turbolinks') === 'false') return;
            
            const isLocal = href.startsWith(window.location.origin) || href.startsWith('/');
            if (isLocal) {
                e.preventDefault();
                mainContent.classList.add('page-leaving');
                setTimeout(function() { window.location.href = href; }, 250);
            }
        });

        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                document.body.classList.add('page-leaving');
            });
        });
    </script>

    {{-- STACK SCRIPTS (UNTUK SCRIPT KHUSUS HALAMAN ANAK) --}}
    @stack('scripts')
</body>
</html>