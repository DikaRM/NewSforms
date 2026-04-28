<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    /* =========================================
       RESET & VARIABLES
       ========================================= */
    :root {
        --primary: #2e5b9a;
        --primary-hover: #1e3a6b;
        --primary-soft: #ebf1f9;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --bg-page: #f3f5f9;
        --radius: 12px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
    body { background: var(--bg-page); color: var(--text-main); overflow-x: hidden; }

    /* Animasi */
    body { animation: pageEnter 0.4s ease-out; }
    @keyframes pageEnter { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* =========================================
       HEADER & SIDEBAR (COPY DARI DASHBOARD)
       ========================================= */
    .header {
        background: var(--primary); color: white; padding: 12px 24px;
        display: flex; justify-content: space-between; align-items: center;
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        box-shadow: var(--shadow-sm); height: 60px;
    }
    .header h2 { font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 12px; margin: 0; }
    .header h2 img { height: 32px; object-fit: contain; }
    .header-actions { display: flex; align-items: center; gap: 20px; }

    /* Dropdown User */
    .user-dropdown { position: relative; cursor: pointer; }
    .user-info { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 50px; transition: background 0.3s; }
    .user-info:hover { background: rgba(255,255,255,0.15); }
    .user-avatar { width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: bold; font-size: 0.9rem; }
    .user-name { font-weight: 600; font-size: 0.85rem; color: white; }
    
    .dropdown-menu-custom {
        position: absolute; top: 100%; right: 0; margin-top: 10px;
        background: white; border-radius: 12px;
        box-shadow: var(--shadow-md); min-width: 200px;
        opacity: 0; visibility: hidden; transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1001;
    }
    .user-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }
    .dropdown-item-custom {
        padding: 12px 16px; display: flex; align-items: center; gap: 12px;
        color: var(--text-main); text-decoration: none; transition: all 0.2s;
        font-size: 0.9rem; border-bottom: 1px solid var(--border);
    }
    .dropdown-item-custom:hover { background: var(--bg-page); color: var(--primary); }
    .dropdown-item-custom i { width: 20px; color: var(--primary); text-align: center; }

    /* Layout */
    .app-wrapper { display: flex; margin-top: 60px; min-height: calc(100vh - 60px); }
    .sidebar {
        width: 260px; background: #4c6fa6; position: fixed; left: 0; top: 60px; bottom: 0;
        z-index: 99; transition: transform 0.3s ease; overflow-y: auto;
    }
    .sidebar-menu { padding: 24px 0; }
    .sidebar-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 20px; margin: 4px 12px;
        color: white; text-decoration: none;
        border-radius: 8px; transition: all 0.3s ease; font-weight: 500; font-size: 0.9rem;
    }
    .sidebar-item i { width: 22px; font-size: 1rem; }
    .sidebar-item:hover { background: rgba(255,255,255,0.2); color: white; transform: translateX(4px); }
    .sidebar-item.active { background: white; color: var(--primary); font-weight: 700; box-shadow: var(--shadow-sm); }

    .main-content { flex: 1; padding: 30px; transition: margin-left 0.3s ease; width: calc(100% - 260px); background: var(--bg-page); }

    /* Mobile Toggle */
    .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 56px; height: 56px; background: var(--primary); border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: var(--shadow-md); border: none; color: white; }
    .sidebar-overlay { display: none; position: fixed; top: 60px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
    .sidebar.open { transform: translateX(0); }
    .sidebar-overlay.active { display: block; }

    /* =========================================
       PROFILE COMPONENTS
       ========================================= */
    .profile-card {
        background: white; border-radius: 16px; padding: 30px;
        box-shadow: var(--shadow-sm); border: 1px solid var(--border);
        margin-bottom: 30px;
    }
    .profile-header {
        text-align: center; margin-bottom: 30px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 30px;
    }
    .profile-avatar-large {
        width: 120px; height: 120px;
        background: var(--primary-soft); color: var(--primary);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 3.5rem; margin: 0 auto 15px; border: 4px solid white;
        box-shadow: 0 4px 10px rgba(46, 91, 154, 0.15);
    }
    .profile-avatar-large i{
        color:var(--primary);
    }
    .profile-name { font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 5px; }
    .profile-role { 
        display: inline-block; padding: 6px 16px; border-radius: 20px; 
        font-size: 0.85rem; font-weight: 600; 
        background: var(--primary); color: white; text-transform: uppercase;
    }

    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .info-item { display: flex; flex-direction: column; }
    .info-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 5px; font-weight: 600; }
    .info-value { font-size: 1rem; color: var(--text-main); font-weight: 500; padding: 10px; background: #f8fafc; border-radius: 8px; border: 1px solid var(--border); }

    /* Form Styling */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 600; font-size: 0.9rem; color: var(--text-main); margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem; background: white; }
    .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(46, 91, 154, 0.1); }

    .btn-primary { background: var(--primary); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
    .btn-primary:hover { background: var(--primary-hover); }
    
    .btn-outline { background: white; color: var(--text-muted); padding: 12px 24px; border-radius: 8px; border: 1px solid var(--border); font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; margin-top: 10px; }
    .btn-outline:hover { background: #f1f5f9; color: var(--text-main); }

    /* Toast */
    .notification-toast { position: fixed; top: 80px; right: 30px; padding: 12px 20px; border-radius: 12px; color: white; z-index: 1100; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; box-shadow: var(--shadow-md); }
    .notification-success { background: #10b981; }
    .notification-error { background: #ef4444; }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; padding-top: 20px; }
        .mobile-toggle { display: flex; }
    }
</style>
<body>
    <header class="header">
    <h2>
        <img src="{{ asset('WhatsApp Image 2026-04-10 at 08.00.25.png') }}" alt="Logo" onerror="this.style.display='none'"/>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    
    <div class="header-actions">
        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown">
            <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
                <div class="user-name">
                    <span>{{ $user->nama ?? 'Guru' }}</span>
                    <i class="fas fa-chevron-down" style="margin-left:5px; font-size:0.7rem;"></i>
                </div>
            </div>
            
            <div class="dropdown-menu-custom">
                <a href="#" class="dropdown-item-custom" style="pointer-events: none; background: #f8fafc; color: var(--primary);">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil Saya</span>
                </a>
                <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                <form action="{{ route('users.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer; color: #ef4444;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu Toggle -->
<button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    
    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Toast Alerts -->
        @if(session('success'))
            <div class="notification-toast notification-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="notification-toast notification-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Breadcrumb -->
        <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
            <span>Dashboard</span> <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin: 0 8px;"></i> <span style="color: var(--primary); font-weight: 700;">Profil Saya</span>
        </div>

        <h2 style="margin-bottom: 24px; color: var(--text-main);">Profil Pengguna</h2>

        <!-- Card 1: Informasi Pribadi -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar-large">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="profile-name">{{ $user->nama }}</h3>
                <span class="profile-role">{{ $user->role }}</span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <div class="info-value">{{ $user->username }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Akun</span>
                    <div class="info-value" style="color: #10b981;">Aktif</div>
                </div>
            </div>
        </div>

        <!-- Card 2: Keamanan (Password) -->
        <div class="profile-card">
            <h3 style="margin-bottom: 20px; color: var(--text-main); font-size: 1.1rem;">
                <i class="fas fa-shield-alt" style="color: var(--primary); margin-right: 10px;"></i> Keamanan Akun
            </h3>

            <!-- Form Ganti Password -->
            <form action="{{ route('profile.password.update') }}" method="post" style="margin-bottom: 20px;">
                @csrf
                
                <h4 style="font-size: 0.95rem; color: var(--text-main); margin-bottom: 15px;">Ganti Password</h4>
                
                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="old_password" class="form-input" placeholder="Masukkan password lama Anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>

            <hr style="border: 0; border-top: 1px dashed var(--border); margin: 30px 0;">

            <!-- Fitur Reset Password -->
            

        </div>
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

        // Mobile Sidebar Toggle
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
        
        if (mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
        document.addEventListener('click', function() {
            if (userDropdown) userDropdown.classList.remove('active');
        });
    });
</script>
</body>
</html>

