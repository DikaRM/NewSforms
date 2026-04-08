<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Soal - Sistem Ujian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e5b9a;
            --primary-dark: #1e3f6a;
            --secondary: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .layout-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        #sidebar {
            width: 260px;
            background: var(--primary);
            color: white;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--primary-dark);
        }

        #sidebar .logo h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        #sidebar .logo p {
            font-size: 0.8rem;
            margin-top: 5px;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 0;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
        }

        /* Header */
        .header {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            margin-right: 15px;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .notification-icon {
            position: relative;
            margin-right: 20px;
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-dropdown {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .user-dropdown:hover {
            background: #f0f0f0;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }

        .user-info {
            margin-right: 8px;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #666;
        }

        .dropdown-arrow {
            font-size: 0.8rem;
            color: #666;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 200px;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }

        .user-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: #f5f7fa;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            color: #666;
        }

        .dropdown-divider {
            height: 1px;
            background: #eee;
            margin: 5px 0;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            background: var(--primary);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header-title {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-header-title i {
            margin-right: 10px;
        }

        .card-content {
            padding: 24px;
        }

        /* Soal Card */
        .soal-card {
            position: relative;
            padding: 20px;
            border: 1px solid #eaeaea;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #fafafa;
            transition: all 0.3s;
        }

        .soal-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-remove-soal {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-remove-soal:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .soal-number {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .preview-image {
            margin-top: 10px;
            max-width: 300px;
            display: none;
        }

        .preview-image img {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* Button Styles */
        .btn-primary, .btn-success, .btn-outline, .btn-danger {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Import Card */
        .import-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .import-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .import-header i {
            font-size: 1.5rem;
            margin-right: 12px;
        }

        .import-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.is-active {
            display: flex;
        }

        .modal-card {
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-card-head, .modal-card-foot {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-card-head {
            background: var(--primary);
            color: white;
        }

        .modal-card-title {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .modal-card-title i {
            margin-right: 10px;
        }

        .modal-card-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-card-foot {
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Notification Toast */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            z-index: 4000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s;
        }

        .notification-toast.notification-success {
            background: var(--success);
        }

        .notification-toast.notification-error {
            background: var(--danger);
        }

        .notification-toast i {
            margin-right: 10px;
        }

        /* Required field indicator */
        .label.required::after {
            content: " *";
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .mobile-toggle {
                display: block;
            }

            .header-title {
                font-size: 1.2rem;
            }

            .user-info {
                display: none;
            }

            .content {
                padding: 20px;
            }

            .modal-card {
                width: 95%;
                max-height: 95vh;
            }
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
<div class="layout-container">
    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="logo">
            <h2><i class="fas fa-graduation-cap"></i> Sistem Ujian</h2>
            <p>Portal Guru</p>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('guru.index') }}" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('guru.index') }}" class="sidebar-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Daftar Ujian</span>
            </a>
            <a href="{{ route('guru.index') }}" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Ujian Baru</span>
            </a>
           
            <a href="{{ route('guru.result') }}" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                <span>Result Siswa</span>
            </a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                <span>Jadwal</span>
            </a>
           
        </nav>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="header-title">Tambah Soal Ujian</h1>
            </div>
            <div class="header-right">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Guru</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                    <div class="dropdown-menu">
                        <a href="{{ route('guru.index') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('guru.index') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('users.logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar</span>
                        </a>
                        <form id="logout-form" action="{{ route('users.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Flash Notification -->
            @if(session('success'))
                <div id="notification" class="notification is-success" style="border-radius: 8px; margin-bottom: 20px;">
                    <button class="delete" onclick="this.parentElement.style.display='none'"></button>
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="notification" class="notification is-danger" style="border-radius: 8px; margin-bottom: 20px;">
                    <button class="delete" onclick="this.parentElement.style.display='none'"></button>
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Form Soal -->
            <form action="{{ route('guru.ujian.sold', $uji->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="formSoal">
                
                @csrf
                
                <input type="hidden" name="guru_id" value="{{ $uji->guru_id ?? '' }}">
                <input type="hidden" name="mapel_id" value="{{ $uji->mapel ?? '' }}">
                <input type="hidden" name="uji_id" value="{{ $uji->id }}">
                
                <div class="card" style="border-radius: 12px; overflow: hidden; margin-bottom: 24px;">
                    <div class="card-header" style="background: #2e5b9a; color: white;">
                        <p class="card-header-title" style="color: white; font-weight: 600;">
                            <i class="fas fa-pen-fancy" style="margin-right: 10px;"></i>
                            Tambah Soal untuk: {{ $uji->nama_ujian ?? 'Ujian' }}
                        </p>
                    </div>
                    
                    <div class="card-content" style="padding: 24px;">
                        <!-- Informasi Ujian -->
                        <div class="notification is-light" style="background: #eef2ff; border-radius: 12px; margin-bottom: 24px;">
                            <div class="level is-mobile" style="margin-bottom: 0;">
                                <div class="level-left">
                                    <div>
                                        <p class="has-text-weight-bold" style="color: #2e5b9a;">
                                            <i class="fas fa-info-circle"></i> Detail Ujian
                                        </p>
                                        <p class="is-size-7 mt-1">
                                            <i class="fas fa-clock"></i> Durasi: {{ $uji->durasi ?? '?' }} menit
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-graduation-cap"></i> Kelas: {{ $uji->grade ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="level-right">
                                    <span class="tag is-info is-light">
                                        <i class="fas fa-question-circle"></i> Buat soal dengan opsi A-D
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Container Soal -->
                        <div id="soalContainer">
                            <div class="soal-card" id="soal-0" data-soal-id="0">
                                <button type="button" class="btn-remove-soal" onclick="removeSoal(0)" style="display: none;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                
                                <div class="soal-number">Soal 1</div>
                                
                                <div class="field">
                                    <label class="label required">Pertanyaan</label>
                                    <div class="control">
                                        <textarea class="textarea" 
                                                  name="soal[0][soal]" 
                                                  rows="3" 
                                                  placeholder="Tuliskan pertanyaan di sini..."
                                                  required>{{ old('soal.0.soal') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="field">
                                    <label class="label">Gambar (opsional)</label>
                                    <div class="file has-name">
                                        <label class="file-label">
                                            <input class="file-input" 
                                                   type="file" 
                                                   name="soal[0][gambar]" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                                   onchange="previewImage(this, 0)">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </span>
                                                <span class="file-label">
                                                    Pilih gambar...
                                                </span>
                                            </span>
                                            <span class="file-name" id="file-name-0">
                                                Belum ada file
                                            </span>
                                        </label>
                                    </div>
                                    <div class="preview-image" id="preview-0">
                                        <img src="" alt="Preview">
                                    </div>
                                    <p class="help">Format: JPG, PNG, GIF (Max 2MB)</p>
                                </div>
                                
                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi A</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_a]" 
                                                       placeholder="Pilihan A"
                                                       value="{{ old('soal.0.opsi_a') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi B</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_b]" 
                                                       placeholder="Pilihan B"
                                                       value="{{ old('soal.0.opsi_b') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi C</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_c]" 
                                                       placeholder="Pilihan C"
                                                       value="{{ old('soal.0.opsi_c') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi D</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_d]" 
                                                       placeholder="Pilihan D"
                                                       value="{{ old('soal.0.opsi_d') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="field">
                                    <label class="label required">Jawaban Benar</label>
                                    <div class="control">
                                        <div class="select">
                                            <select name="soal[0][jawaban_benar]" required>
                                                <option value="">Pilih jawaban benar</option>
                                                <option value="a" {{ old('soal.0.jawaban_benar') == 'a' ? 'selected' : '' }}>A</option>
                                                <option value="b" {{ old('soal.0.jawaban_benar') == 'b' ? 'selected' : '' }}>B</option>
                                                <option value="c" {{ old('soal.0.jawaban_benar') == 'c' ? 'selected' : '' }}>C</option>
                                                <option value="d" {{ old('soal.0.jawaban_benar') == 'd' ? 'selected' : '' }}>D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="field has-addons" style="gap: 12px; flex-wrap: wrap; margin-top: 20px;">
                            <button type="button" class="btn-outline" id="tambahSoal">
                                <i class="fas fa-plus-circle"></i> Tambah Soal
                            </button>
                            <button type="submit" class="btn-success" id="submitBtn">
                                <i class="fas fa-save"></i> Simpan Semua Soal
                            </button>
                            <a href="{{ route('guru.index') }}" class="btn-outline">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- IMPORT EXCEL SECTION -->
            <div class="import-card">
                <div class="import-header">
                    <i class="fas fa-file-excel"></i>
                    <h3>Import Soal dari Excel/CSV</h3>
                </div>
                
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uji_id" value="{{ $uji->id }}">
                    <input type="hidden" name="mapel_id" value="{{ $uji->mapel ?? '' }}">
                    <input type="hidden" name="guru_id" value="{{ $uji->guru_id ?? '' }}">
                    
                    <div class="field">
                        <label class="label">Pilih File (Excel/CSV)</label>
                        <div class="file has-name is-info">
                            <label class="file-label">
                                <input class="file-input" 
                                       type="file" 
                                       name="file_excel" 
                                       accept=".xlsx,.xls,.csv"
                                       id="excelFileInput"
                                       required>
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </span>
                                    <span class="file-label">
                                        Cari file...
                                    </span>
                                </span>
                                <span class="file-name" id="importFileName">
                                    Belum ada file dipilih
                                </span>
                            </label>
                        </div>
                        <p class="help">
                            <i class="fas fa-info-circle"></i> 
                            Format header yang diperlukan: <strong>soal, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, tipe</strong>
                            <br>
                            <i class="fas fa-file-alt"></i> Tipe: <strong>pg</strong> (pilihan ganda) atau <strong>essay</strong>
                            <br>
                            <i class="fas fa-download"></i> Klik tombol "Download Template" untuk mendapatkan contoh file
                        </p>
                    </div>
                    
                    <div class="field" style="margin-top: 15px;">
                        <button type="button" class="btn-primary" id="previewBtn">
                            <i class="fas fa-eye"></i> Preview Soal
                        </button>
                        <button type="button" class="btn-outline" id="downloadTemplateBtn">
                            <i class="fas fa-download"></i> Download Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Modal Preview -->
<div id="previewModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head" style="background: #2e5b9a; color: white;">
            <p class="modal-card-title" style="color: white;">
                <i class="fas fa-file-alt"></i> Preview Soal dari Excel
            </p>
            <button class="delete" aria-label="close" onclick="closePreviewModal()"></button>
        </header>
        <section class="modal-card-body" id="previewContent">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Memuat data...</p>
            </div>
        </section>
        <footer class="modal-card-foot" style="justify-content: space-between;">
            <button class="btn-outline" onclick="closePreviewModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="btn-success" id="confirmImportBtn" onclick="confirmImport()">
                <i class="fas fa-check"></i> Konfirmasi Import
            </button>
        </footer>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <i class="fas fa-spinner fa-spin fa-3x" style="color: #2e5b9a;"></i>
        <p style="margin-top: 15px;">Sedang mengimport soal...</p>
    </div>
</div>

<script>
    // ==================== VARIABLES ====================
    let soalCounter = 1;
    const previewModal = document.getElementById('previewModal');
    const previewContent = document.getElementById('previewContent');
    let previewData = null;
    
    // ==================== FILE NAME DISPLAY ====================
    const excelFileInput = document.getElementById('excelFileInput');
    if (excelFileInput) {
        excelFileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Belum ada file dipilih';
            const importFileName = document.getElementById('importFileName');
            if (importFileName) importFileName.textContent = fileName;
        });
    }
    
    // ==================== DOWNLOAD TEMPLATE ====================
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    if (downloadTemplateBtn) {
        downloadTemplateBtn.addEventListener('click', function() {
            const templateData = [
                ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban_benar', 'tipe'],
                ['Apa manfaat PPKI?', 'Merumuskan dasar negara', 'Membuat UUD', 'Menjadikan Jakarta sebagai Ibukota', 'Mempertahankan kemerdekaan', 'A', 'pg'],
                ['1+1 berapa?', '1', '2', '3', '4', 'B', 'pg'],
                ['Jelaskan pengertian gotong royong?', '', '', '', '', 'Kerja sama antar warga', 'essay']
            ];
            
            let csvContent = templateData.map(row => row.join(',')).join('\n');
            const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'template_soal.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });
    }
    
    // ==================== PREVIEW IMPORT (FIXED) ====================
    const previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', async function() {
            const fileInput = document.getElementById('excelFileInput');
            if (!fileInput.files[0]) {
                showNotification('error', 'Pilih file terlebih dahulu!');
                return;
            }
            
            const formData = new FormData(document.getElementById('importForm'));
            
            previewModal.classList.add('is-active');
            previewContent.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memproses file...</p>
                </div>
            `;
            
            try {
                const response = await fetch('{{ route("import.preview") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                console.log('Response Preview:', result);
                
                if (result.success) {
                    // *** FIX: Handle different possible response structures ***
                    let soalData = [];
                    
                    // Check various possible structures
                    if (Array.isArray(result.data)) {
                        soalData = result.data;
                    } else if (result.data && Array.isArray(result.data.data)) {
                        soalData = result.data.data;
                    } else if (result.data && Array.isArray(result.data.soal)) {
                        soalData = result.data.soal;
                    } else if (result.data && typeof result.data === 'object') {
                        // If it's an object but not an array, try to extract array values
                        const values = Object.values(result.data);
                        const arrays = values.filter(v => Array.isArray(v));
                        if (arrays.length > 0) {
                            soalData = arrays[0]; // Use the first array found
                        } else {
                            // If no arrays found, convert the object to an array with one item
                            soalData = [result.data];
                        }
                    } else {
                        showNotification('error', 'Format data tidak dikenali. Silakan periksa file Anda.');
                        previewContent.innerHTML = `
                            <div class="notification is-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p><strong>Error:</strong> Format data tidak dikenali</p>
                                <p>Struktur response: ${JSON.stringify(result, null, 2)}</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Store the properly formatted data
                    previewData = { rows: soalData };
                    displayPreview(previewData);
                } else {
                    previewContent.innerHTML = `
                        <div class="notification is-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p><strong>Error:</strong> ${result.message}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error:', error);
                previewContent.innerHTML = `
                    <div class="notification is-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            }
        });
    }
    
    // ==================== DISPLAY PREVIEW (FIXED) ====================
    function displayPreview(data) {
        // *** FIX: Ensure data.rows is always an array ***
        let rows = [];
        
        if (data && data.rows) {
            if (Array.isArray(data.rows)) {
                rows = data.rows;
            } else if (typeof data.rows === 'object') {
                // Convert object to array if needed
                rows = Object.values(data.rows);
            }
        }
        
        if (rows.length === 0) {
            previewContent.innerHTML = `
                <div class="notification is-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Tidak ada data yang ditemukan di file.</p>
                    <p>Pastikan file memiliki header: <strong>soal, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, tipe</strong></p>
                </div>
            `;
            return;
        }
        
        let html = `
            <div class="notification is-info is-light">
                <i class="fas fa-info-circle"></i>
                <strong>${rows.length} soal</strong> ditemukan. Silakan periksa sebelum mengimport.
            </div>
            <div style="overflow-x: auto;">
                <table class="table is-fullwidth is-striped is-hoverable" style="font-size: 0.85rem;">
                    <thead>
                        <tr style="background: #2e5b9a; color: white;">
                            <th>#</th>
                            <th>Soal</th>
                            <th>Opsi A</th>
                            <th>Opsi B</th>
                            <th>Opsi C</th>
                            <th>Opsi D</th>
                            <th>Jawaban</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        rows.forEach((row, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${escapeHtml(row.soal || '-')}</strong></td>
                    <td>${escapeHtml(row.opsi_a || '-')}</td>
                    <td>${escapeHtml(row.opsi_b || '-')}</td>
                    <td>${escapeHtml(row.opsi_c || '-')}</td>
                    <td>${escapeHtml(row.opsi_d || '-')}</td>
                    <td>
                        <span class="tag ${row.jawaban_benar ? 'is-success' : 'is-danger'}">
                            ${row.jawaban_benar ? row.jawaban_benar.toUpperCase() : '-'}
                        </span>
                    </td>
                    <td>
                        <span class="tag ${row.tipe === 'pg' ? 'is-info' : 'is-warning'}">
                            ${row.tipe || 'pg'}
                        </span>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        previewContent.innerHTML = html;
    }
    

    function confirmImport() {
        if (!previewData || !previewData.rows || previewData.rows.length === 0) {
            showNotification('error', 'Tidak ada data untuk diimport!');
            return;
        }
        

        const rows = Array.isArray(previewData.rows) ? previewData.rows : [];
        
        if (rows.length === 0) {
            showNotification('error', 'Tidak ada data yang valid untuk diimport!');
            return;
        }
        

        const soalData = [];
        
        for (let i = 0; i < rows.length; i++) {
            const soal = rows[i];
            
            console.log(`Soal ke-${i+1} dari previewData:`, soal);
            
            soalData.push({
                soal: soal.soal || '',
                opsi_a: soal.opsi_a || '',  
                opsi_b: soal.opsi_b || '',  
                opsi_c: soal.opsi_c || '',  
                opsi_d: soal.opsi_d || '',  
                jawaban_benar: soal.jawaban_benar || '',
                tipe: soal.tipe || 'pg',
                gambar: soal.gambar || null
            });
        }
        

        const requestData = {
            uji_id: '{{ $uji->id }}',
            mapel_id: '{{ $uji->mapel ?? "" }}',
            guru_id: '{{ $uji->guru_id ?? "" }}',
            soal_data: soalData
        };
        
        // ========== TAMPILKAN ALERT KONFIRMASI DENGAN OPSI ==========
        const firstSoal = requestData.soal_data[0];
        let confirmMessage = `⚠️ KONFIRMASI IMPORT SOAL ⚠️\n\n`;
        confirmMessage += `Total soal: ${soalData.length}\n\n`;
        confirmMessage += `📝 CONTOH SOAL PERTAMA:\n`;
        confirmMessage += `Soal: ${firstSoal.soal.substring(0, 50)}...\n`;
        confirmMessage += `Opsi A: ${firstSoal.opsi_a || '(kosong)'}\n`;
        confirmMessage += `Opsi B: ${firstSoal.opsi_b || '(kosong)'}\n`;
        confirmMessage += `Opsi C: ${firstSoal.opsi_c || '(kosong)'}\n`;
        confirmMessage += `Opsi D: ${firstSoal.opsi_d || '(kosong)'}\n`;
        confirmMessage += `Jawaban Benar: ${firstSoal.jawaban_benar || '(kosong)'}\n`;
        confirmMessage += `Tipe: ${firstSoal.tipe}\n\n`;
        confirmMessage += `✅ Lanjutkan import?`;
        
        if (!confirm(confirmMessage)) {
            return;
        }
        
        // ========== KIRIM KE SERVER ==========
        console.log('=========================================');
        console.log('DATA YANG DIKIRIM KE CONFIRM:');
        console.log(JSON.stringify(requestData, null, 2));
        console.log('=========================================');
        console.log('CEK OPSI SOAL PERTAMA:');
        console.log('Opsi A:', requestData.soal_data[0].opsi_a);
        console.log('Opsi B:', requestData.soal_data[0].opsi_b);
        console.log('Opsi C:', requestData.soal_data[0].opsi_c);
        console.log('Opsi D:', requestData.soal_data[0].opsi_d);
        console.log('=========================================');
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.classList.add('active');
        
        // Close modal first
        closePreviewModal();
        
        fetch('{{ route("import.confirm") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(result => {
            console.log('RESPONSE DARI SERVER:', result);
            
            if (result.success) {
                showNotification('success', result.message);
                
                setTimeout(function() {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        location.reload();
                    }
                }, 1500);
            } else {
                showNotification('error', result.message || 'Gagal mengimport soal');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error: ' + error.message);
        })
        .finally(() => {
            if (loadingOverlay) loadingOverlay.classList.remove('active');
        });
    }
    
    // ==================== NOTIFICATION FUNCTION ====================
    function showNotification(type, message) {
        const existingNotif = document.querySelector('.notification-toast');
        if (existingNotif) existingNotif.remove();
        
        const notification = document.createElement('div');
        notification.className = `notification-toast notification-${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(notification);
        
        // Show notification with animation
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 100);
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // ==================== MODAL FUNCTIONS ====================
    function closePreviewModal() {
        if (previewModal) previewModal.classList.remove('is-active');
        // Don't reset previewData here, we need it for confirmImport
    }
    
    const modalBackground = document.querySelector('.modal-background');
    if (modalBackground) {
        modalBackground.addEventListener('click', closePreviewModal);
    }
    
    // ==================== HELPER FUNCTIONS ====================
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ==================== SOAL FUNCTIONS ====================
    const tambahSoalBtn = document.getElementById('tambahSoal');
    if (tambahSoalBtn) {
        tambahSoalBtn.addEventListener('click', function() {
            const container = document.getElementById('soalContainer');
            const template = document.getElementById('soal-0').cloneNode(true);
            
            template.id = 'soal-' + soalCounter;
            template.setAttribute('data-soal-id', soalCounter);
            
            const soalNumber = template.querySelector('.soal-number');
            if (soalNumber) soalNumber.textContent = 'Soal ' + (soalCounter + 1);
            
            const removeBtn = template.querySelector('.btn-remove-soal');
            if (removeBtn) {
                removeBtn.style.display = 'flex';
                removeBtn.setAttribute('onclick', 'removeSoal(' + soalCounter + ')');
            }
            
            template.querySelectorAll('[name]').forEach(el => {
                const name = el.getAttribute('name');
                if (name) el.setAttribute('name', name.replace('[0]', '[' + soalCounter + ']'));
            });
            
            template.querySelectorAll('input[type="text"], textarea').forEach(el => el.value = '');
            template.querySelectorAll('select').forEach(el => el.value = '');
            
            const previewDiv = template.querySelector('.preview-image');
            if (previewDiv) {
                previewDiv.id = 'preview-' + soalCounter;
                previewDiv.style.display = 'none';
                const img = previewDiv.querySelector('img');
                if (img) img.src = '';
            }
            
            const fileNameSpan = template.querySelector('.file-name');
            if (fileNameSpan) {
                fileNameSpan.id = 'file-name-' + soalCounter;
                fileNameSpan.textContent = 'Belum ada file';
            }
            
            const fileInput = template.querySelector('.file-input');
            if (fileInput) fileInput.setAttribute('onchange', 'previewImage(this, ' + soalCounter + ')');
            
            container.appendChild(template);
            soalCounter++;
        });
    }
    
    window.removeSoal = function(index) {
        const soalElement = document.getElementById('soal-' + index);
        if (soalElement) {
            soalElement.remove();
            renumberSoal();
        }
    };
    
    function renumberSoal() {
        const soalCards = document.querySelectorAll('.soal-card');
        soalCards.forEach((card, idx) => {
            const numberSpan = card.querySelector('.soal-number');
            if (numberSpan) numberSpan.textContent = 'Soal ' + (idx + 1);
            
            card.querySelectorAll('[name]').forEach(el => {
                const name = el.getAttribute('name');
                if (name && name.match(/soal\[\d+\]/)) {
                    el.setAttribute('name', name.replace(/soal\[\d+\]/, 'soal[' + idx + ']'));
                }
            });
        });
    }
    
    window.previewImage = function(input, index) {
        const preview = document.getElementById('preview-' + index);
        const fileName = document.getElementById('file-name-' + index);
        const file = input.files[0];
        
        if (file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('File harus berupa gambar (JPG, PNG, GIF)');
                input.value = '';
                if (fileName) fileName.textContent = 'Belum ada file';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                if (fileName) fileName.textContent = 'Belum ada file';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    const img = preview.querySelector('img');
                    if (img) img.src = e.target.result;
                    preview.style.display = 'block';
                }
                if (fileName) fileName.textContent = file.name;
            };
            reader.readAsDataURL(file);
        } else {
            if (preview) preview.style.display = 'none';
            if (fileName) fileName.textContent = 'Belum ada file';
        }
    };
    
    // ==================== FORM VALIDATION ====================
    const formSoal = document.getElementById('formSoal');
    if (formSoal) {
        formSoal.addEventListener('submit', function(e) {
            const soalCards = document.querySelectorAll('.soal-card');
            if (soalCards.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 soal!');
                return false;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.classList.add('is-loading');
                submitBtn.disabled = true;
            }
            
            return true;
        });
    }
    
    // ==================== USER DROPDOWN ====================
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }
    
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.classList.remove('active');
    });
    
    // ==================== MOBILE SIDEBAR ====================
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        if (sidebar && mobileToggle && sidebarOverlay) {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (icon) {
                if (sidebar.classList.contains('open')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }
    }
    
    if (mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    
    // ==================== NOTIFICATION AUTO HIDE ====================
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.style.display = 'none', 300);
        }, 5000);
    }
    
    // ==================== WINDOW RESIZE ====================
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 768 && sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                const icon = mobileToggle ? mobileToggle.querySelector('i') : null;
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }, 250);
    });
    
    // Close sidebar on mobile after clicking link
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('open')) {
                setTimeout(() => toggleSidebar(), 150);
            }
        });
    });
</script>

</body>
</html>