<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Soal - Sistem Ujian</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
    
    <style>
        /* =========================================
           STYLE UMUM & ANIMASI
           ========================================= */
        .main-content { animation: pageEnter 0.3s ease-out forwards; background: #f3f5f9; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .main-content.page-leaving { animation: pageLeave 0.25s ease-in forwards !important; }
        @keyframes pageLeave { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(-12px); } }

        /* Header & Nav */
        .header { background: #2e5b9a; color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header h2 { font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; margin: 0; }
        .header-actions { display: flex; align-items: center; gap: 15px; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #5c6fa6; position: fixed; left: 0; top: 56px; bottom: 0; z-index: 99; transition: transform 0.3s ease; overflow-y: auto; }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; margin: 4px 12px; color: white; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
         .sidebar-item:hover { background: rgba(255,255,255,0.25);  color:#2e5b9a;border-left: 4px solid white;}
        .sidebar-item.active { background: rgba(255,255,255,0.25); border-left: 4px solid white; }
        .sidebar-logout { position: absolute; bottom: 20px; left: 0; right: 0; padding: 0 12px; }
        
        /* Main Content */
        .app-wrapper { display: flex; margin-top: 56px; min-height: calc(100vh - 56px); }
        .main-content { flex: 1; margin-left: 260px; padding: 24px; transition: margin-left 0.3s ease; width: calc(100% - 260px); }

        /* Soal Card */
        .exam-container { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; }
        .section-title { font-size: 1.1rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }

        .soal-card { position: relative; padding: 20px; border: 1px solid #eaeaea; border-radius: 10px; margin-bottom: 20px; background: #f9fbfd; transition: all 0.3s; }
        .soal-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-color: #d1d9e6; }
        .btn-remove-soal { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; transition: all 0.2s; z-index: 10; }
        .btn-remove-soal:hover { background: #c82333; transform: scale(1.1); }
        .soal-number { font-weight: 700; color: #2e5b9a; margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; }
        
        /* Media & Image Preview */
        .preview-image { margin-top: 10px; max-width: 300px; display: none; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
        .preview-image img { width: 100%; display: block; }
        .media-preview-box { margin-top: 10px; max-width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; background: #000; display: none; text-align: center; color: white; padding: 10px; }
        .media-preview-box video, .media-preview-box audio { max-width: 100%; max-height: 300px; }

        /* Buttons */
        .btn-custom { background: #2e5b9a; color: white; border: none; padding: 8px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-custom:hover { background: #1e3a6b; transform: scale(1.02); color: white; }
        .btn-outline-custom { background: white; color: #2e5b9a; border: 1px solid #2e5b9a; padding: 8px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-outline-custom:hover { background: #2e5b9a; color: white; }
        .btn-add-option { font-size: 0.8rem; border: 1px dashed #2e5b9a; color: #2e5b9a; background: #f0f7ff; padding: 4px 10px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; margin-top: 5px; }
        .btn-remove-opt { background: #ffcccc; color: #cc0000; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; margin-left: 5px; }
        .btn-remove-opt:hover { background: #ff9999; }

        /* Notifications */
        .notification-toast { position: fixed; top: 70px; right: 20px; padding: 12px 18px; border-radius: 8px; color: white; z-index: 1100; animation: slideInRight 0.3s ease; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .notification-success { background: #28a745; }
        .notification-error { background: #dc3545; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        /* Modal */
        .modal-card { border-radius: 12px; overflow: hidden; }
        .modal-card-head { background: #2e5b9a; color: white; }
        .modal-card-head .title { color: white; font-weight: 600; }

        /* Helper */
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .loading-overlay.active { display: flex; }
        .loading-content { background: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .label.required::after { content: " *"; color: #dc3545; }
        
        /* Media Input Styling */
        .media-source-toggle { display: flex; gap: 15px; margin-bottom: 10px; background: #eee; padding: 5px; border-radius: 6px; width: fit-content; }
        .media-source-toggle label { cursor: pointer; padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; display: flex; align-items: center; gap: 5px; }
        .media-source-toggle input { display: none; }
        .media-source-toggle input:checked + span { font-weight: bold; color: #2e5b9a; }
        .media-source-toggle label:has(input:checked) { background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

        /* Mobile */
        .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
        .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
            .mobile-toggle { display: flex; }
        }
        .sidebar-item i { width: 22px; font-size: 1rem; }
        .sidebar-item span { font-size: 0.85rem; font-weight: 500; }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <h2>
       <!-- Placeholder Logo -->
       <div style="width:30px; height:30px; background:white; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#2e5b9a; font-weight:bold;"><i class="fas fa-graduation-cap"></i></div>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    <div class="header-actions">
        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown" style="position:relative; cursor:pointer;">
            <div style="display:flex; align-items:center; gap:10px; padding:6px 12px; border-radius:8px; transition:background 0.3s;">
                <div style="width:34px; height:34px; background:white; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#2e5b9a; font-weight:bold;"><i class="fas fa-user-tie"></i></div>
                <span style="font-weight:500; font-size:0.85rem;">
                    @if(isset($ire)) {{ $ire->nama }} @else @endif <i class="fas fa-chevron-down"></i>
                </span>
            </div>
            <div class="dropdown-menu" style="position:absolute; top:100%; right:0; margin-top:8px; background:white; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.1); min-width:180px; opacity:0; visibility:hidden; transform:translateY(-10px); transition:all 0.3s; z-index:1001;">
                <a href="{{ route('profile.index') }}" class="dropdown-item-custom" style="display:block; padding:10px 16px; text-decoration:none; color:#333; font-size:0.85rem;">
                    <i class="fas fa-user-circle"></i> <span>Profil Saya</span>
                </a>
                <div style="height:1px; background:#eee; margin:4px 0;"></div>
                <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                    @csrf
                    <button type="submit" style="width:100%; background:none; border:none; cursor:pointer; padding:10px 16px; display:flex; align-items:center; gap:12px; color:#dc3545; font-size:0.85rem;" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div style="padding:20px 0;">
            <a href="{{ route('guru.index') }}" class="sidebar-item"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item"><i class="fas fa-calendar-alt"></i> <span>Jadwal Ujian</span></a>
            <a href="{{ route('guru.create', $uji->id ?? '') }}" class="sidebar-item active"><i class="fas fa-pen-fancy"></i> <span>Tambah Soal</span></a>
            <a href="{{ route('guru.ujian.detail', $uji->id) }}" class="sidebar-item"><i class="fas fa-list"></i> <span>Daftar Soal</span></a>
            <a href="{{ route('guru.result') }}" class="sidebar-item"><i class="fas fa-file-alt"></i> <span>Hasil Ujian</span></a>
            <a href="{{route('pengawas.index', isset($gurus) ? $gurus->id : '')}}" class="sidebar-item">
                <i class="fas fa-user-check"></i>
                <span>Pengawas</span>
            </a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-button" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Flash Notification -->
        @if(session('success'))
            <div class="notification-toast notification-success" id="notification"><i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span></div>
        @endif
        @if(session('error'))
            <div class="notification-toast notification-error" id="notification"><i class="fas fa-exclamation-circle"></i> <span>{{ session('error') }}</span></div>
        @endif

        <!-- IMPORT SECTION -->
         
        <div class="exam-container">
            <div class="section-title">
                <span><i class="fas fa-file-excel" style="color: #28a745; margin-right: 8px;"></i> Import Soal dari Excel/CSV</span>
            </div>
            <form id="importForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="mode_ujian" id="modeUjian" value="{{ $uji->mode ?? 'cbt' }}">
                <input type="hidden" name="uji_id" value="{{ $uji->id ?? '' }}">
                <input type="hidden" name="mapel_id" value="{{ $uji->mapel ?? '' }}">
                <input type="hidden" name="guru_id" value="{{ $uji->guru_id ?? '' }}">
                
                <div class="field">
                    <label class="label">Pilih File (Excel/CSV)</label>
                    <div class="file has-name is-info">
                        <label class="file-label">
                            <input class="file-input" type="file" name="file_excel" accept=".xlsx,.xls,.csv" id="excelFileInput" required>
                            <span class="file-cta" style="background-color: #e3eaf5; color: #2e5b9a;">
                                <span class="file-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="file-label">Cari file...</span>
                            </span>
                            <span class="file-name" id="importFileName" style="border-color: #dbdbdb;">Belum ada file dipilih</span>
                        </label>
                    </div>
                    <p class="help" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle" style="color: #2e5b9a;"></i> 
                        <strong>PERHATIAN:</strong> Dalam satu file Excel, tipe soal harus homogen (hanya PG atau hanya Essay). Campuran tipe tidak diperbolehkan.<br>
                        Format header: <strong>soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban_benar, tipe</strong>
                    </p>
                </div>
                <div class="buttons" style="margin-top: 15px;">
                    <button type="button" class="btn-custom" id="previewBtn"><i class="fas fa-eye"></i> Preview Soal</button>
                    <button type="button" class="btn-outline-custom" id="downloadTemplateBtn"><i class="fas fa-download"></i> Download Template</button>
                </div>
            </form>
        </div>

        <!-- FORM SOAL SECTION -->
        <div class="exam-container">
            <div class="section-title">
                <span><i class="fas fa-pen-fancy"></i> Manajemen Soal</span>
                <div style="font-size: 0.9rem; font-weight: normal; color: #666;">
                    Ujian: <strong>{{ $uji->nama_ujian ?? 'Ujian' }}</strong> | Durasi: <strong>{{ $uji->durasi ?? '?' }} Menit</strong>
                </div>
            </div>
            @if ($errors->any())
                <div style="background:#ffe6e6; color: #d63031; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffcccc;">
                    <strong><i class="fas fa-exclamation-triangle"></i> ERROR VALIDASI:</strong>
                    <ul style="margin-top: 5px; margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @php
    $isPraktik = ($uji->mode ?? 'cbt') === 'praktik';
    $actionRoute = $isPraktik 
        ? route('guru.ujian.praktik', $uji->id) 
        : route('guru.ujian.sold', $uji->id);
@endphp
            <form action="{{ $actionRoute }}" method="POST" enctype="multipart/form-data" id="formSoal" novalidate>
                @csrf
                <input type="hidden" name="guru_id" value="{{ $uji->guru_id ?? '' }}">
                <input type="hidden" name="mapel_id" value="{{ $uji->mapel ?? '' }}">
                <input type="hidden" name="uji_id" value="{{ $uji->id ?? '' }}">
                
                <!-- Container Soal -->
                <div id="soalContainer">
                    
                    <!-- TEMPLATE SOAL (ID: soal-0) -->
                    <div class="soal-card" id="soal-0" data-soal-id="0">
                        <button type="button" class="btn-remove-soal" onclick="removeSoal(0)" style="display: none;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        
                        <div class="soal-number"><i class="fas fa-question-circle"></i> Soal 1</div>
                        
                        <div class="field">
                            <label class="label required">Pertanyaan</label>
                            <div class="control">
                                <textarea class="textarea" name="soal[0][soal]" rows="3" placeholder="Tuliskan pertanyaan di sini..." style="border-radius: 8px;" required>{{ old('soal.0.soal') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- TIPE SOAL (PG / ESSAY / AV) -->
                        <div class="field">
                            <label class="label required">Tipe Soal</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="soal[0][tipe]" id="select-tipe-0" onchange="toggleOptions(0)">
                                        <option value="pg" selected>Pilihan Ganda (PG)</option>
                                        <option value="essay">Essay</option>
                                        <option value="av">Audio / Visual (Media)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="av-mode-selector-0" style="display: none; margin-bottom: 15px; background: #e8f4fd; padding: 12px; border-radius: 8px; border-left: 4px solid #2e5b9a;">
    <label class="label" style="margin-bottom: 8px; color: #2e5b9a;">
        <i class="fas fa-question-circle"></i> Mode Jawaban untuk Soal AV
    </label>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
            <input type="radio" name="av_mode[0]" value="pg" class="av-mode-radio" data-index="0" checked>
            <span><i class="fas fa-list-ul"></i> Pilihan Ganda (Siswa memilih A/B/C/D/E)</span>
        </label>
        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
            <input type="radio" name="av_mode[0]" value="essay" class="av-mode-radio" data-index="0">
            <span><i class="fas fa-pen"></i> Essay (Siswa menulis jawaban langsung)</span>
        </label>
    </div>
    <input type="hidden" name="soal[0][av_sub_type]" id="av-sub-type-0" value="pg">
</div>
                        <!-- INPUT GAMBAR (STANDARD) -->
                        <div class="field" id="wrapper-gambar-0">
                            <label class="label">Gambar (opsional)</label>
                            <div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="soal[0][gambar]" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(this, 0)">
                                    <span class="file-cta"><span class="file-icon"><i class="fas fa-image"></i></span><span class="file-label">Pilih gambar...</span></span>
                                    <span class="file-name" id="file-name-0">Belum ada file</span>
                                </label>
                            </div>
                            <div class="preview-image" id="preview-0"><img src="" alt="Preview"></div>
                        </div>

                        <!-- INPUT MEDIA (BARU: VIDEO/AUDIO) - Hidden by default -->
                        <div class="field" id="wrapper-media-0" style="display:none; background: #f0f7ff; padding: 15px; border-radius: 8px; border: 1px solid #d1e3fa;">
                            <label class="label" style="color:#2e5b9a"><i class="fas fa-photo-video"></i> Media (Video/Audio)</label>
                            
                            <div class="media-source-toggle">
                                <label>
                                    <input type="radio" name="media_source_type_0" value="file" checked onchange="toggleMediaSource(0)">
                                    <span>Upload File</span>
                                </label>
                                <label>
                                    <input type="radio" name="media_source_type_0" value="url" onchange="toggleMediaSource(0)">
                                    <span>URL (YouTube/Link)</span>
                                </label>
                            </div>

                            <!-- File Media Input -->
                            <div class="control media-file-control-0">
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="soal[0][media_file]" accept="video/*,audio/*" onchange="previewMedia(this, 0)">
                                        <span class="file-cta"><span class="file-icon"><i class="fas fa-upload"></i></span><span class="file-label">Upload Video/Audio...</span></span>
                                        <span class="file-name" id="media-file-name-0">Belum ada file</span>
                                    </label>
                                </div>
                            </div>

                            <!-- URL Media Input -->
                            <div class="control media-url-control-0" style="display:none; margin-top:10px;">
                                <input class="input is-rounded" type="url" name="soal[0][media_url]" placeholder="Contoh: https://www.youtube.com/watch?v=..." id="media-url-0" oninput="previewMediaUrl(0)">
                            </div>

                            <!-- Preview Box -->
                            <div class="media-preview-box" id="media-preview-0">
                                <!-- Content injected via JS -->
                            </div>
                        </div>
                        
                        <!-- OPSI DINAMIS (A, B, C, D, E) -->
                        <div class="columns is-multiline" id="options-container-0">
                            <!-- OPSI A, B, C FIXED -->
                            <div class="column is-half col-fixed-0" data-opt="a">
                                <div class="field">
                                    <label class="label required">Opsi A</label>
                                    <div class="control"><input class="input is-rounded" type="text" name="soal[0][opsi_a]" placeholder="Pilihan A" required></div>
                                </div>
                            </div>
                            <div class="column is-half col-fixed-0" data-opt="b">
                                <div class="field">
                                    <label class="label required">Opsi B</label>
                                    <div class="control"><input class="input is-rounded" type="text" name="soal[0][opsi_b]" placeholder="Pilihan B" required></div>
                                </div>
                            </div>
                            <div class="column is-half col-fixed-0" data-opt="c">
                                <div class="field">
                                    <label class="label required">Opsi C</label>
                                    <div class="control"><input class="input is-rounded" type="text" name="soal[0][opsi_c]" placeholder="Pilihan C" required></div>
                                </div>
                            </div>
                            
                            <!-- Tombol Tambah D -->
                            <div class="column is-full" id="btn-add-d-wrapper-0">
                                <button type="button" class="btn-add-option" onclick="addOption(0, 'd')">
                                    <i class="fas fa-plus"></i> Tambah Opsi D
                                </button>
                            </div>
                        </div>

                        <!-- JAWABAN BENAR (SPLIT: PG vs ESSAY) -->
                        <div class="field">
                            <label class="label required">Jawaban Benar / Kunci</label>
                            
                            <!-- 1. Input untuk PG (Dropdown) -->
                            <div class="control input-pg-wrapper" id="input-pg-wrapper-0">
                                <div class="select is-fullwidth">
                                    <select name="soal[0][jawaban_benar]" id="select-jawaban-0">
                                        <option value="">Pilih jawaban benar</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 2. Input untuk Essay/AV (Textarea) -->
                            <div class="control input-essay-wrapper" id="input-essay-wrapper-0" style="display: none;">
                                <textarea class="textarea" 
                                          name="soal[0][jawaban_benar]" 
                                          rows="2" 
                                          placeholder="Tulis kunci jawaban essay di sini..."
                                          id="textarea-jawaban-0"></textarea>
                                <p class="help">Isi dengan jawaban benar atau kata kunci untuk penilaian.</p>
                            </div>
                        </div>
                    </div>
                    <!-- END TEMPLATE SOAL -->

                </div>
                
                <!-- Tombol Aksi -->
                <div class="buttons" style="margin-top: 20px;">
                    <button type="button" class="btn-outline-custom" id="tambahSoal"><i class="fas fa-plus-circle"></i> Tambah Soal</button>
                    <button type="submit" class="btn-custom" id="submitBtn"><i class="fas fa-save"></i> Simpan Semua Soal</button>
                    <a href="{{ route('guru.index') }}" class="btn-outline-custom" style="color: #666; border-color: #ccc;"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Modal Preview -->
<div id="previewModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title has-text-light"><i class="fas fa-file-alt"></i> Preview Soal dari Excel</p>
            <button class="delete" aria-label="close" onclick="closePreviewModal()"></button>
        </header>
        <section class="modal-card-body" id="previewContent" style="padding: 0;">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: #2e5b9a;"></i>
                <p>Memuat data...</p>
            </div>
        </section>
        <footer class="modal-card-foot" style="justify-content: space-between; border-top: 1px solid #eee; padding: 15px 20px;">
            <button class="btn-outline-custom" onclick="closePreviewModal()"><i class="fas fa-times"></i> Batal</button>
            <button class="btn-custom" id="confirmImportBtn" onclick="confirmImport()"><i class="fas fa-check"></i> Konfirmasi Import</button>
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
    // ==================== DETEKSI MODE UJIAN ====================
// ==================== DETEKSI MODE UJIAN ====================
const modeUjian = document.getElementById('modeUjian')?.value || 'cbt';
const IS_PRAKTIK = modeUjian === 'praktik';
let soalCounter = 1;
let mainContent = document.querySelector(".main-content");
const previewModal = document.getElementById('previewModal');
const previewContent = document.getElementById('previewContent');
let previewData = null;

// ==================== SETUP MODE PRAKTIK ====================
// Fungsi untuk toggle tombol tambah jadwal berdasarkan checkbox "Semua Kelas"
async function loadAvailableKelas() {
    const ujiId = document.querySelector('input[name="uji_id"]')?.value;
    if (!ujiId) return [];
    
    try {
        const response = await fetch(`/guru/ujian/${ujiId}/kelas`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error loading kelas:', error);
        return [];
    }
}
async function createJadwalPerKelas() {
    const kelasList = await loadAvailableKelas();
    const semuaKelasCheckbox = document.getElementById('semua_kelas_checkbox');
    const jadwalContainer = document.getElementById('jadwalContainer');
    
    if (!jadwalContainer) return;
    
    // Clear existing jadwal
    jadwalContainer.innerHTML = '';
    
    if (semuaKelasCheckbox && semuaKelasCheckbox.checked) {
        // Jika semua kelas dicentang, buat SATU jadwal untuk semua kelas
        createSingleJadwal(0, 'Semua Kelas');
    } else {
        // Jika tidak dicentang, buat jadwal PER KELAS
        if (kelasList.length === 0) {
            jadwalContainer.innerHTML = '<div class="notification is-warning">Tidak ada kelas yang tersedia untuk ujian ini.</div>';
            return;
        }
        
        kelasList.forEach((kelas, index) => {
            createSingleJadwal(index, kelas.nama_kelas, kelas.id);
        });
    }
}
function createSingleJadwal(index, kelasNama, kelasId = null) {
    const container = document.getElementById('jadwalContainer');
    if (!container) return;
    
    const jadwalItem = document.createElement('div');
    jadwalItem.className = 'jadwal-item';
    jadwalItem.style.cssText = 'padding: 15px; background: white; border-radius: 8px; margin-bottom: 15px; border: 1px solid #ddd; position: relative;';
    
    // Set nama field berdasarkan apakah ini untuk semua kelas atau per kelas
    const fieldPrefix = kelasId !== null ? `jadwal[${index}]` : `jadwal[0]`;
    
    jadwalItem.innerHTML = `
        <div class="columns is-multiline">
            <div class="column is-12">
                <h5 style="color: #2e5b9a; margin-bottom: 10px; font-weight: bold;">
                    <i class="fas fa-users"></i> Kelas: ${escapeHtml(kelasNama)}
                </h5>
            </div>
            <div class="column is-6">
                <label class="label is-small">Tanggal Ujian</label>
                <input type="date" name="${fieldPrefix}[tanggal]" class="input" required value="${new Date().toISOString().split('T')[0]}">
            </div>
            <div class="column is-6">
                <label class="label is-small">
                    <i class="fas fa-calendar-times"></i> Deadline Pengumpulan <span class="has-text-danger">*</span>
                </label>
                <input type="datetime-local" name="${fieldPrefix}[deadline]" class="input" required>
                <p class="help is-warning" style="font-size: 0.7rem;">
                    <i class="fas fa-exclamation-triangle"></i> Siswa HARUS mengumpulkan sebelum waktu ini
                </p>
            </div>
            ${kelasId !== null ? `
                <input type="hidden" name="${fieldPrefix}[kelas_id]" value="${kelasId}">
                <input type="hidden" name="${fieldPrefix}[is_specific]" value="1">
            ` : `
                <input type="hidden" name="${fieldPrefix}[is_all_kelas]" value="1">
            `}
        </div>
    `;
    
    container.appendChild(jadwalItem);
}
function createSingleJadwalSimple(index, isAllKelas = true) {
    const container = document.getElementById('jadwalContainer');
    if (!container) return;
    
    const jadwalItem = document.createElement('div');
    jadwalItem.className = 'jadwal-item';
    jadwalItem.style.cssText = 'padding: 15px; background: white; border-radius: 8px; margin-bottom: 15px; border: 1px solid #ddd;';
    
    jadwalItem.innerHTML = `
        <div class="columns is-multiline">
            <div class="column is-6">
                <label class="label is-small">Tanggal Ujian</label>
                <input type="date" name="jadwal[${index}][tanggal]" class="input" required value="${new Date().toISOString().split('T')[0]}">
            </div>
            <div class="column is-6">
                <label class="label is-small">
                    <i class="fas fa-calendar-times"></i> Deadline Pengumpulan <span class="has-text-danger">*</span>
                </label>
                <input type="datetime-local" name="jadwal[${index}][deadline]" class="input" required>
                <p class="help is-warning" style="font-size: 0.7rem;">
                    <i class="fas fa-exclamation-triangle"></i> Siswa HARUS mengumpulkan sebelum waktu ini
                </p>
            </div>
            ${isAllKelas ? `
                <div class="column is-12">
                    <div class="notification is-info is-light" style="padding: 8px; font-size: 0.8rem;">
                        <i class="fas fa-info-circle"></i> Berlaku untuk SEMUA KELAS
                    </div>
                </div>
                <input type="hidden" name="jadwal[${index}][is_all_kelas]" value="1">
            ` : ''}
        </div>
    `;
    
    container.appendChild(jadwalItem);
}
async function addJadwalPraktikSection() {
    const formSoal = document.getElementById('formSoal');
    const existingSection = document.getElementById('jadwalSection');
    
    if (existingSection) return;
    
    const jadwalHtml = `
        <div id="jadwalSection" class="box" style="margin-top: 20px; background: #f0f7ff; border-left: 4px solid #2e5b9a;">
            <h4 class="title is-6" style="color: #2e5b9a;">
                <i class="fas fa-calendar-alt"></i> Atur Jadwal Ujian Praktik
            </h4>
            <p class="help mb-3">Siswa hanya bisa mengupload tugas pada jadwal yang ditentukan</p>
            
            <div class="field" style="margin-bottom: 20px; background: white; padding: 15px; border-radius: 8px;">
                <label class="checkbox" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="semua_kelas_checkbox" checked>
                    <span style="font-weight: bold;">Semua Kelas</span>
                </label>
                <p class="help">Jika dicentang: 1 jadwal untuk semua kelas. Jika tidak: buat jadwal per kelas secara otomatis.</p>
            </div>
            
            <div id="jadwalContainer">
                <!-- Jadwal akan di-generate otomatis di sini -->
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data kelas...
                </div>
            </div>
        </div>
    `;
    
    const buttonsDiv = document.querySelector('#formSoal .buttons');
    if (buttonsDiv) {
        buttonsDiv.insertAdjacentHTML('beforebegin', jadwalHtml);
        
        // Load dan setup jadwal awal
        await createJadwalPerKelas();
        
        // Setup event listener untuk checkbox "Semua Kelas"
        const semuaKelasCheckbox = document.getElementById('semua_kelas_checkbox');
        if (semuaKelasCheckbox) {
            semuaKelasCheckbox.addEventListener('change', async function(e) {
                // Tampilkan loading
                const jadwalContainer = document.getElementById('jadwalContainer');
                if (jadwalContainer) {
                    jadwalContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Memperbarui jadwal...</div>';
                }
                
                // Regenerate jadwal berdasarkan pilihan
                await createJadwalPerKelas();
                
                // Scroll ke section jadwal
                const jadwalSection = document.getElementById('jadwalSection');
                if (jadwalSection) {
                    jadwalSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    }
}
function setupPraktikMode() {
    // Sembunyikan import section
    const allContainers = document.querySelectorAll('.exam-container');
    for (let container of allContainers) {
        const titleSpan = container.querySelector('.section-title span');
        if (titleSpan && titleSpan.innerText.includes('Import Soal dari Excel/CSV')) {
            container.style.display = 'none';
            break;
        }
    }
    const importSection = document.querySelector('.exam-container:first-child');
    if (importSection) importSection.style.display = 'none';
    
    // Ubah judul section
    const sectionTitle = document.querySelector('.section-title span');
    if (sectionTitle) sectionTitle.innerHTML = '<i class="fas fa-upload" style="color: #28a745;"></i> Upload Tugas Praktik';
    
    // Ubah deskripsi durasi
    const infoText = document.querySelector('.section-title div');
    if (infoText) infoText.innerHTML = `Ujian: <strong>{{ $uji->nama_ujian ?? 'Ujian' }}</strong> | Mode: <strong class="has-text-warning">PRAKTIK (Upload File)</strong>`;
    
    // Reset container soal
    const soalContainer = document.getElementById('soalContainer');
    soalContainer.innerHTML = '';
    
    // Tambah template soal praktik
    addPraktikSoalTemplate(0);
    soalCounter = 1;
    
    // Tambah section jadwal (versi baru)
    addJadwalPraktikSection();
    
    // Ubah teks tombol submit
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan & Atur Jadwal';
    
    // Sembunyikan tombol tambah soal
    const tambahSoalBtn = document.getElementById('tambahSoal');
    if (tambahSoalBtn) tambahSoalBtn.style.display = 'none';
}



function initJadwalEvents() {
    const tambahBtn = document.getElementById('tambahJadwalBtn');
    const semuaKelasCheckbox = document.getElementById('semua_kelas_checkbox');
    const kelasSelectWrapper = document.getElementById('kelas_select_wrapper');
    
    // Event untuk checkbox "Semua Kelas" utama
    if (semuaKelasCheckbox && tambahBtn) {
        semuaKelasCheckbox.addEventListener('change', function(e) {
            if (e.target.checked) {
                // Jika centang "Semua Kelas", sembunyikan tombol tambah jadwal
                tambahBtn.style.display = 'none';
                if (kelasSelectWrapper) kelasSelectWrapper.style.display = 'none';
                showToast('info', 'Karena memilih semua kelas, hanya 1 jadwal yang diperlukan');
            } else {
                // Jika tidak centang, tampilkan tombol tambah jadwal
                tambahBtn.style.display = 'inline-flex';
                if (kelasSelectWrapper) kelasSelectWrapper.style.display = 'block';
            }
        });
    }
    
    // Event listener untuk tombol tambah jadwal
    if (tambahBtn) {
        tambahBtn.removeEventListener('click', tambahJadwalHandler);
        tambahBtn.addEventListener('click', tambahJadwalHandler);
    }
    
    // Event delegation untuk checkbox "Semua Kelas" di setiap jadwal
    document.addEventListener('change', function(e) {
        if (e.target.classList && e.target.classList.contains('all-kelas-checkbox')) {
            const jadwalId = e.target.getAttribute('data-jadwal');
            const kelasList = document.getElementById(`kelas-list-${jadwalId}`);
            if (kelasList) {
                kelasList.style.display = e.target.checked ? 'none' : 'block';
            }
        }
    });
}
if (IS_PRAKTIK) {
    console.log('📝 Mode Praktik: Setup form upload file');
    setupPraktikMode();
} else {
    console.log('💻 Mode CBT: Setup form soal normal');
    setupCBTMode();
}

function setupPraktikMode() {
    // Sembunyikan import section
     const allContainers = document.querySelectorAll('.exam-container');
    for (let container of allContainers) {
        const titleSpan = container.querySelector('.section-title span');
        if (titleSpan && titleSpan.innerText.includes('Import Soal dari Excel/CSV')) {
            container.style.display = 'none';
            break;
        }
    }
    const importSection = document.querySelector('.exam-container:first-child');
    if (importSection) importSection.style.display = 'none';
    
    // Ubah judul section
    const sectionTitle = document.querySelector('.section-title span');
    if (sectionTitle) sectionTitle.innerHTML = '<i class="fas fa-upload" style="color: #28a745;"></i> Upload Tugas Praktik';
    
    // Ubah deskripsi durasi
    const infoText = document.querySelector('.section-title div');
    if (infoText) infoText.innerHTML = `Ujian: <strong>{{ $uji->nama_ujian ?? 'Ujian' }}</strong> | Mode: <strong class="has-text-warning">PRAKTIK (Upload File)</strong>`;
    
    // Reset container soal
    const soalContainer = document.getElementById('soalContainer');
    soalContainer.innerHTML = '';
    
    // Tambah template soal praktik
    addPraktikSoalTemplate(0);
    soalCounter = 1;
    
    // Tambah section jadwal (pakai fungsi yang benar)
    addJadwalPraktikSection();
    
    // Ubah teks tombol submit
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan & Atur Jadwal';
    
    // Sembunyikan tombol tambah soal
    const tambahSoalBtn = document.getElementById('tambahSoal');
    if (tambahSoalBtn) tambahSoalBtn.style.display = 'none';
}

// FUNGSI UTAMA UNTUK MEMBUAT SECTION JADWAL PRAKTIK


function initJadwalEvents() {
    const tambahBtn = document.getElementById('tambahJadwalBtn');
    if (tambahBtn) {
        tambahBtn.removeEventListener('click', tambahJadwalHandler);
        tambahBtn.addEventListener('click', tambahJadwalHandler);
    }
    
    // Event listener untuk checkbox "Semua Kelas"
    document.addEventListener('change', function(e) {
        if (e.target.classList && e.target.classList.contains('all-kelas-checkbox')) {
            const jadwalId = e.target.getAttribute('data-jadwal');
            const kelasList = document.getElementById(`kelas-list-${jadwalId}`);
            if (kelasList) {
                if (e.target.checked) {
                    kelasList.style.display = 'none'; // Sembunyikan pilihan kelas
                } else {
                    kelasList.style.display = 'block'; // Tampilkan pilihan kelas
                }
                console.log('Checkbox changed, kelasList display:', kelasList.style.display);
            }
        }
    });
}

let jadwalCounterGlobal = 1;

function tambahJadwalHandler() {
    const container = document.getElementById('jadwalContainer');
    if (!container) return;
    
    const currentId = jadwalCounterGlobal++;
    
    // Ambil data kelas dari jadwal pertama (yang sudah ada)
    const existingKelasSelect = document.querySelector('#kelas-list-0 select');
    let kelasOptions = '';
    
    if (existingKelasSelect) {
        // Clone options dari jadwal pertama
        kelasOptions = existingKelasSelect.innerHTML;
    } else {
        // Fallback: buat options dari data server (gunakan data dari Blade)
        kelasOptions = `@foreach($uji->kelas ?? [] as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                       @endforeach`;
    }
    
    const newJadwal = document.createElement('div');
    newJadwal.className = 'jadwal-item';
    newJadwal.style.cssText = 'padding: 15px; background: white; border-radius: 8px; margin-bottom: 15px; position: relative; border: 1px solid #ddd;';
    
    newJadwal.innerHTML = `
        <button type="button" class="delete is-small" style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; width: 25px; height: 25px; border-radius: 50%; cursor: pointer;" onclick="this.parentElement.remove()">
            ✕
        </button>
        <div class="columns is-multiline">
            <div class="column is-6">
                <label class="label is-small">Tanggal Ujian</label>
                <input type="date" name="jadwal[${currentId}][tanggal]" class="input" required value="${new Date().toISOString().split('T')[0]}">
            </div>
            <div class="column is-3">
                <label class="label is-small">Jam Mulai</label>
                <input type="time" name="jadwal[${currentId}][waktu_mulai]" class="input" value="08:00" required>
            </div>
            <div class="column is-3">
                <label class="label is-small">Jam Selesai</label>
                <input type="time" name="jadwal[${currentId}][waktu_selesai]" class="input" value="10:00" required>
            </div>
            <div class="column is-12">
                <label class="checkbox" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" class="all-kelas-checkbox" checked data-jadwal="${currentId}">
                    <span>Semua Kelas</span>
                </label>
            </div>
            <div class="column is-12 kelas-list" id="kelas-list-${currentId}" style="display: none;">
                <label class="label is-small">Pilih Kelas (jika tidak semua)</label>
                <select name="jadwal[${currentId}][kelas_id][]" multiple class="select is-fullwidth" size="3">
                    ${kelasOptions}
                </select>
                <p class="help is-small">Tekan Ctrl (Windows) atau Cmd (Mac) untuk pilih lebih dari satu</p>
            </div>
        </div>
    `;
    
    container.appendChild(newJadwal);
    
    // Re-attach event listener untuk checkbox yang baru
    const newCheckbox = newJadwal.querySelector('.all-kelas-checkbox');
    if (newCheckbox) {
        newCheckbox.addEventListener('change', function(e) {
            const kelasList = document.getElementById(`kelas-list-${currentId}`);
            if (kelasList) {
                kelasList.style.display = e.target.checked ? 'none' : 'block';
            }
        });
    }
}
function addPraktikSoalTemplate(index) {
    const container = document.getElementById('soalContainer');
    container.innerHTML = '';
    
    const template = document.createElement('div');
    template.className = 'soal-card';
    template.id = 'soal-' + index;
    template.setAttribute('data-soal-id', index);
    
    template.innerHTML = `
        <div class="soal-number"><i class="fas fa-upload"></i> Tugas Praktik</div>
        
        <div class="field">
            <label class="label required">Judul Tugas / Petunjuk</label>
            <div class="control">
                <textarea class="textarea" name="soal[0][soal]" rows="5" placeholder="Jelaskan tugas praktik yang harus dikerjakan siswa..." required></textarea>
            </div>
            <p class="help">Siswa akan mengupload file sesuai petunjuk di atas.</p>
        </div>
        
        <div class="field">
            <label class="label">Gambar Pendukung (opsional)</label>
            <div class="file has-name">
                <label class="file-label">
                    <input class="file-input" type="file" name="soal[0][gambar]" accept="image/*" onchange="previewImage(this, ${index})">
                    <span class="file-cta"><span class="file-icon"><i class="fas fa-image"></i></span><span class="file-label">Pilih gambar...</span></span>
                    <span class="file-name" id="file-name-${index}">Belum ada file</span>
                </label>
            </div>
            <div class="preview-image" id="preview-${index}" style="display: none;">
                <img src="" alt="Preview">
            </div>
        </div>
        
        <input type="hidden" name="soal[0][tipe]" value="upload">
        <input type="hidden" name="soal[0][jawaban_benar]" value="pending">
    `;
    
    container.appendChild(template);
}

function setupCBTMode() {
    console.log('💻 Mode CBT: Setup normal');
}

// ==================== SUBMIT HANDLER UNTUK PRAKTIK (HANYA SATU) ====================
if (IS_PRAKTIK) {
    const form = document.getElementById('formSoal');
    if (form) {
        const submitHandler = function(e) {
            e.preventDefault();
            
            // Validasi jadwal (cari di jadwalContainer)
            const jadwalItems = document.querySelectorAll('#jadwalContainer .jadwal-item');
            if (jadwalItems.length === 0) {
                showToast('error', 'Silakan atur jadwal ujian praktik terlebih dahulu');
                return;
            }
            
            let valid = true;
            let hasDeadline = false;
            
            jadwalItems.forEach((item, idx) => {
                const tanggal = item.querySelector('input[name*="[tanggal]"]')?.value;
                const deadline = item.querySelector('input[name*="[deadline]"]')?.value;
                
                if (!tanggal) {
                    showToast('error', `Jadwal ${idx + 1}: Tanggal ujian harus diisi!`);
                    valid = false;
                }
                
                if (!deadline) {
                    showToast('error', `Jadwal ${idx + 1}: Deadline pengumpulan harus diisi!`);
                    valid = false;
                } else {
                    hasDeadline = true;
                }
            });
            
            if (!hasDeadline) {
                showToast('error', 'Minimal satu jadwal harus memiliki deadline!');
                return;
            }
            
            // Validasi soal
            const soalText = document.querySelector('textarea[name="soal[0][soal]"]')?.value;
            if (!soalText || soalText.trim() === '') {
                showToast('error', 'Judul tugas/petunjuk tidak boleh kosong!');
                return;
            }
            
            if (!valid) return;
            
            if (confirm('Apakah Anda yakin ingin menyimpan ujian praktik ini?')) {
                // Tampilkan loading
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                }
                form.submit();
            }
        };
        
        form.addEventListener('submit', submitHandler);
    }
}

// Sisanya dari kode Anda (addOption, removeOption, toggleOptions, dll) tetap sama seperti sebelumnya
// ... (LANJUTKAN KODE JS ANDA YANG LAIN DI SINI, SEPERTI addOption, removeOption, dll)
     document.querySelectorAll('.logout-form').forEach(function(form) {
        let submitted = false;
        form.addEventListener('submit', function(e) {
            if (submitted) { e.preventDefault(); return; }
            submitted = true;
            const btn = form.querySelector('.logout-button');
            if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; btn.style.pointerEvents = 'none'; }
        });
    });

    // ==================== VARIABLES ====================
   
    
    // ==================== LOGIC OPSI DINAMIS (ADD/REMOVE) ====================
    window.addOption = function(soalIndex, opt) {
        const container = document.getElementById('options-container-' + soalIndex);
        const btnWrapperId = 'btn-add-' + opt + '-wrapper-' + soalIndex;
        const colId = 'col-' + opt + '-' + soalIndex;
        const inputName = 'soal[' + soalIndex + '][opsi_' + opt + ']';

        const btnWrapper = document.getElementById(btnWrapperId);
        if (btnWrapper) btnWrapper.remove();

        const col = document.createElement('div');
        col.className = 'column is-half';
        col.id = colId;
        col.innerHTML = `
            <div class="field">
                <label class="label required">Opsi ${opt.toUpperCase()}</label>
                <div style="display:flex; align-items:center; gap:5px;">
                    <div class="control" style="flex:1">
                        <input class="input is-rounded" type="text" name="${inputName}" placeholder="Pilihan ${opt.toUpperCase()}" required>
                    </div>
                    <button type="button" class="btn-remove-opt" onclick="removeOption(${soalIndex}, '${opt}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(col);

        if (opt === 'd') {
            const nextBtnWrapper = document.createElement('div');
            nextBtnWrapper.className = 'column is-full';
            nextBtnWrapper.id = 'btn-add-e-wrapper-' + soalIndex;
            nextBtnWrapper.innerHTML = `
                <button type="button" class="btn-add-option" onclick="addOption(${soalIndex}, 'e')">
                    <i class="fas fa-plus"></i> Tambah Opsi E
                </button>
            `;
            container.appendChild(nextBtnWrapper);
        }
        updateSelectOptions(soalIndex, opt, true);
    };

    window.removeOption = function(soalIndex, opt) {
        const colId = 'col-' + opt + '-' + soalIndex;
        const btnWrapperId = 'btn-add-' + opt + '-wrapper-' + soalIndex;
        const col = document.getElementById(colId);
        const container = document.getElementById('options-container-' + soalIndex);

        if (col) col.remove();

        if (!document.getElementById(btnWrapperId)) {
            const btnWrapper = document.createElement('div');
            btnWrapper.className = 'column is-full';
            btnWrapper.id = btnWrapperId;
            btnWrapper.innerHTML = `
                <button type="button" class="btn-add-option" onclick="addOption(${soalIndex}, '${opt}')">
                    <i class="fas fa-plus"></i> Tambah Opsi ${opt.toUpperCase()}
                </button>
            `;
            if (opt === 'd') {
                const colC = container.querySelector('.col-fixed-0[data-opt="c"]');
                if(colC) container.insertBefore(btnWrapper, colC.nextSibling);
            } else {
                const colD = document.getElementById('col-d-' + soalIndex);
                if(colD) container.insertBefore(btnWrapper, colD.nextSibling);
            }
        }

        if (opt === 'd') {
            const colE = document.getElementById('col-e-' + soalIndex);
            const btnE = document.getElementById('btn-add-e-wrapper-' + soalIndex);
            if(colE) colE.remove();
            if(btnE) btnE.remove();
        }
        updateSelectOptions(soalIndex, opt, false);
    };

    function updateSelectOptions(soalIndex, opt, isAdd) {
        const select = document.getElementById('select-jawaban-' + soalIndex);
        if (!select) return;
        const val = opt.toUpperCase();
        let optionExists = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === opt) {
                optionExists = true;
                if (!isAdd) select.remove(i);
                break;
            }
        }
        if (isAdd && !optionExists) {
            const optElement = document.createElement('option');
            optElement.value = opt;
            optElement.text = val;
            select.appendChild(optElement);
        }
    }

    // ==================== FUNGSI TOGGLE PG / ESSAY / AV (UPDATED) ====================
    // ==================== FUNGSI TOGGLE PG / ESSAY / AV (DENGAN DETEKSI OTOMATIS) ====================
// ==================== FUNGSI TOGGLE PG / ESSAY / AV ====================
// ==================== FUNGSI TOGGLE PG / ESSAY / AV ====================
// 
// ==================== FUNGSI TOGGLE PG / ESSAY / AV ====================
window.toggleOptions = function(index) {
    
    const soalCard = document.querySelector('#soal-' + index);
    const tipeSelect = soalCard.querySelector('#select-tipe-' + index);
    const optionsContainer = soalCard.querySelector('.columns.is-multiline');
    const pgWrapper = soalCard.querySelector('.input-pg-wrapper');
    const essayWrapper = soalCard.querySelector('.input-essay-wrapper');
    
    const mediaWrapper = soalCard.querySelector('#wrapper-media-' + index);
    const gambarWrapper = soalCard.querySelector('#wrapper-gambar-' + index);
    const avModeSelector = soalCard.querySelector('#av-mode-selector-' + index);

    const pgSelect = soalCard.querySelector('#select-jawaban-' + index);
    const essayText = soalCard.querySelector('#textarea-jawaban-' + index);
    const tipe = tipeSelect.value;

    // Reset semua visibility
    if (mediaWrapper) mediaWrapper.style.display = 'none';
    if (gambarWrapper) gambarWrapper.style.display = 'block';
    if (optionsContainer) optionsContainer.style.display = 'none';
    if (pgWrapper) pgWrapper.style.display = 'none';
    if (essayWrapper) essayWrapper.style.display = 'none';
    if (avModeSelector) avModeSelector.style.display = 'none';
    if (!soalCard) {
        console.warn('Soal card not found for index:', index);
        return;
    }
    
    
    if (!tipeSelect) {
        console.warn('Tipe select not found for index:', index);
        return;
    }
    if (tipe === 'pg') {
        if (optionsContainer) optionsContainer.style.display = 'block';
        if (pgWrapper) pgWrapper.style.display = 'block';
        
        if (pgSelect) {
            pgSelect.disabled = false;
            pgSelect.setAttribute('name', `soal[${index}][jawaban_benar]`);
        }
        if (essayText) {
            essayText.disabled = true;
            essayText.removeAttribute('name');
        }
        removeAvBadge(soalCard);
        if (avModeSelector) avModeSelector.style.display = 'none';

    } else if (tipe === 'av') {
        if (mediaWrapper) mediaWrapper.style.display = 'block';
        if (gambarWrapper) gambarWrapper.style.display = 'none';
        
        const mediaLabel = mediaWrapper?.querySelector('.label');
        if (mediaLabel) {
            mediaLabel.innerHTML = '<i class="fas fa-headphones"></i> Media Soal (Audio/Video)';
        }
        
        if (avModeSelector) avModeSelector.style.display = 'block';
        
        // Setup radio button
        setupAvModeRadios(index);
        
        // Cek nilai hidden input untuk menentukan mode awal
        const hiddenInput = soalCard.querySelector('#av-sub-type-' + index);
        const defaultMode = hiddenInput ? hiddenInput.value : 'pg';
        
        // Update radio checked state
        const radioPG = soalCard.querySelector(`.av-mode-radio[value="pg"]`);
        const radioEssay = soalCard.querySelector(`.av-mode-radio[value="essay"]`);
        if (radioPG && radioEssay) {
            if (defaultMode === 'pg') {
                radioPG.checked = true;
            } else {
                radioEssay.checked = true;
            }
        }
        
        applyAvMode(index, defaultMode);

    } else {
        if (essayWrapper) essayWrapper.style.display = 'block';
        
        if (essayText) {
            essayText.disabled = false;
            essayText.setAttribute('name', `soal[${index}][jawaban_benar]`);
        }
        if (pgSelect) {
            pgSelect.disabled = true;
            pgSelect.removeAttribute('name');
        }
        removeAvBadge(soalCard);
        if (avModeSelector) avModeSelector.style.display = 'none';
    }
};

// Fungsi untuk menerapkan mode AV yang dipilih
function applyAvMode(index, mode) {
    const soalCard = document.querySelector('#soal-' + index);
    if (!soalCard) return;
    
    const optionsContainer = soalCard.querySelector('.columns.is-multiline');
    const pgWrapper = soalCard.querySelector('.input-pg-wrapper');
    const essayWrapper = soalCard.querySelector('.input-essay-wrapper');
    const pgSelect = soalCard.querySelector('#select-jawaban-' + index);
    const essayText = soalCard.querySelector('#textarea-jawaban-' + index);
    const hiddenInput = soalCard.querySelector('#av-sub-type-' + index);
    
    if (mode === 'pg') {
        // ===== Mode Pilihan Ganda: TAMPILKAN opsi =====
        if (optionsContainer) optionsContainer.style.display = 'block';
        if (pgWrapper) pgWrapper.style.display = 'block';
        if (essayWrapper) essayWrapper.style.display = 'none';
        
        if (pgSelect) {
            pgSelect.disabled = false;
            pgSelect.setAttribute('name', `soal[${index}][jawaban_benar]`);
        }
        if (essayText) {
            essayText.disabled = true;
            essayText.removeAttribute('name');
        }
        
        updateAvBadge(soalCard, 'pg', 'Pilihan Ganda');
        
    } else {
        // ===== Mode Essay: SEMBUNYIKAN opsi =====
        if (optionsContainer) optionsContainer.style.display = 'none';
        if (pgWrapper) pgWrapper.style.display = 'none';
        if (essayWrapper) essayWrapper.style.display = 'block';
        
        if (pgSelect) {
            pgSelect.disabled = true;
            pgSelect.removeAttribute('name');
        }
        if (essayText) {
            essayText.disabled = false;
            essayText.setAttribute('name', `soal[${index}][jawaban_benar]`);
            essayText.placeholder = 'Tulis kunci jawaban atau kata kunci untuk penilaian essay...';
        }
        
        updateAvBadge(soalCard, 'essay', 'Essay');
    }
    
    // Update hidden input
    if (hiddenInput) hiddenInput.value = mode;
}

// Fungsi untuk setup radio button AV
function setupAvModeRadios(index) {
    const soalCard = document.querySelector('#soal-' + index);
    if (!soalCard) return;
    
    const radios = soalCard.querySelectorAll('.av-mode-radio');
    
    // Hapus listener lama
    radios.forEach(radio => {
        radio.removeEventListener('change', function() {});
    });
    
    // Tambah listener baru
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const idx = this.getAttribute('data-index');
            const mode = this.value;
            applyAvMode(idx, mode);
            showToast('info', mode === 'pg' ? 'Mode Pilihan Ganda aktif - Opsi akan ditampilkan' : 'Mode Essay aktif - Opsi akan disembunyikan');
        });
    });
}

// Fungsi update badge AV
function updateAvBadge(soalCard, mode, modeText) {
    const soalNumber = soalCard.querySelector('.soal-number');
    if (!soalNumber) return;
    
    removeAvBadge(soalCard);
    
    const badge = document.createElement('span');
    badge.className = `av-badge tag ml-2 ${mode === 'pg' ? 'is-info' : 'is-success'}`;
    badge.style.fontSize = '0.7rem';
    badge.style.padding = '0.2rem 0.6rem';
    badge.innerHTML = mode === 'pg' ? 
        '<i class="fas fa-list-ul"></i> AV - Pilihan Ganda' : 
        '<i class="fas fa-pen"></i> AV - Essay';
    
    soalNumber.appendChild(badge);
}

function removeAvBadge(soalCard) {
    const existingBadge = soalCard.querySelector('.av-badge');
    if (existingBadge) existingBadge.remove();
}

// Fungsi untuk memantau perubahan pada input opsi (real-time detection) - OPSIONAL, BISA DIHAPUS JIKA TIDAK DIPERLUKAN
function setupOptionWatcher(soalCard, index) {
    // Fungsi ini tidak digunakan lagi karena sudah ada mode selector
    // Biarkan kosong agar tidak error
    console.log('Option watcher disabled for AV mode');
}

// Fungsi update badge AV


// Fungsi untuk mengecek mode AV saat load data (untuk edit soal)
function checkAvModeOnLoad(soalCard, index) {
    const tipeSelect = soalCard.querySelector('#select-tipe-' + index);
    if (tipeSelect && tipeSelect.value === 'av') {
        // Trigger toggleOptions untuk mendeteksi mode
        window.toggleOptions(index);
    }
}

    
    
    
    
    // ==================== MEDIA TOGGLE LOGIC ====================
    window.toggleMediaSource = function(index) {
        const radios = document.getElementsByName('media_source_type_' + index);
        let selected = 'file';
        for(let r of radios) { if(r.checked) selected = r.value; }

        const fileControl = document.querySelector('.media-file-control-' + index);
        const urlControl = document.querySelector('.media-url-control-' + index);
        const previewBox = document.getElementById('media-preview-' + index);

        if(selected === 'file') {
            fileControl.style.display = 'block';
            urlControl.style.display = 'none';
            // Reset url input name to prevent submission confusion
            document.getElementById('media-url-' + index).setAttribute('disabled', 'true');
        } else {
            fileControl.style.display = 'none';
            urlControl.style.display = 'block';
            document.getElementById('media-url-' + index).removeAttribute('disabled');
        }
        previewBox.innerHTML = ''; // Clear preview when switching
        previewBox.style.display = 'none';
    };

    window.previewMedia = function(input, index) {
        const previewBox = document.getElementById('media-preview-' + index);
        const fileNameSpan = document.getElementById('media-file-name-' + index);
        const file = input.files[0];

        if (file) {
            fileNameSpan.textContent = file.name;
            const url = URL.createObjectURL(file);
            
            let html = '';
            if(file.type.startsWith('video')) {
                html = `<video controls src="${url}"></video>`;
            } else if (file.type.startsWith('audio')) {
                html = `<p>Audio: ${file.name}</p><audio controls src="${url}" style="margin-top:10px; width:100%"></audio>`;
            }
            previewBox.innerHTML = html;
            previewBox.style.display = 'block';
        } else {
            fileNameSpan.textContent = 'Belum ada file';
            previewBox.style.display = 'none';
        }
    };

    window.previewMediaUrl = function(index) {
        const input = document.getElementById('media-url-' + index);
        const previewBox = document.getElementById('media-preview-' + index);
        const url = input.value.trim();

        if(url) {
            // Simple YouTube embed logic or direct video link
            let embedCode = '';
            if(url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = '';
                if(url.includes('v=')) videoId = url.split('v=')[1].split('&')[0];
                else if(url.includes('youtu.be/')) videoId = url.split('youtu.be/')[1];
                embedCode = `<iframe width="100%" height="200" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
            } else {
                embedCode = `<a href="${url}" target="_blank">${url}</a>`;
            }
            previewBox.innerHTML = embedCode;
            previewBox.style.display = 'block';
        } else {
            previewBox.style.display = 'none';
        }
    };

    // ==================== TAMBAH SOAL LOGIC (UPDATED) ====================
    const tambahSoalBtn = document.getElementById('tambahSoal');
    if (tambahSoalBtn) {
        tambahSoalBtn.addEventListener('click', function() {
            const container = document.getElementById('soalContainer');
            const template = document.getElementById('soal-0').cloneNode(true);
            
            template.id = 'soal-' + soalCounter;
            template.setAttribute('data-soal-id', soalCounter);
            
            const soalNumber = template.querySelector('.soal-number');
            if (soalNumber) soalNumber.innerHTML = `<i class="fas fa-question-circle"></i> Soal ${soalCounter + 1}`;
            // Setup AV mode radios untuk soal baru
// Di dalam tambahSoalBtn, setelah clone template, SEBELUM window.toggleOptions(soalCounter)

// Update ID av-mode-selector
const avModeSelectorDiv = template.querySelector('#av-mode-selector-0');
if (avModeSelectorDiv) {
    avModeSelectorDiv.id = `av-mode-selector-${soalCounter}`;
}

const hiddenAvSub = template.querySelector('#av-sub-type-0');
if (hiddenAvSub) {
    hiddenAvSub.id = `av-sub-type-${soalCounter}`;
    hiddenAvSub.name = `soal[${soalCounter}][av_sub_type]`;
    hiddenAvSub.value = 'pg';
}

const avModeRadios = template.querySelectorAll('.av-mode-radio');
avModeRadios.forEach(radio => {
    radio.setAttribute('data-index', soalCounter);
    radio.setAttribute('name', `av_mode[${soalCounter}]`);
});

setupAvModeRadios(soalCounter);

// PASTIKAN toggleOptions dipanggil SETELAH semua ID diupdate

            const newTipeSelect = template.querySelector('#select-tipe-' + soalCounter);
if (newTipeSelect && newTipeSelect.value === 'av') {
    setupOptionWatcher(template, soalCounter);
}
            const removeBtn = template.querySelector('.btn-remove-soal');
            if (removeBtn) {
                removeBtn.style.display = 'flex';
                removeBtn.setAttribute('onclick', 'removeSoal(' + soalCounter + ')');
            }

            // Update IDs Gambar
            const fileNameSpan = template.querySelector('.file-name:not(#media-file-name-0)'); // exclude media name
            if (fileNameSpan) {
                fileNameSpan.id = 'file-name-' + soalCounter;
                fileNameSpan.textContent = 'Belum ada file';
            }
            const imgPreview = template.querySelector('.preview-image');
            if (imgPreview) {
                imgPreview.id = 'preview-' + soalCounter;
                imgPreview.querySelector('img').src = '';
                imgPreview.style.display = 'none';
            }
            const fileInput = template.querySelector('input[type="file"][name$="[gambar]"]');
            if (fileInput) {
    fileInput.value = '';  // 🔥 TAMBAHKAN INI!
    fileInput.setAttribute('onchange', 'previewImage(this, ' + soalCounter + ')');
}

            // Update IDs Media (NEW)
            const mediaWrapper = template.querySelector('#wrapper-media-0');
            if(mediaWrapper) {
                mediaWrapper.id = 'wrapper-media-' + soalCounter;
                
                // Update Radio Name
                const radios = mediaWrapper.querySelectorAll('input[type="radio"]');
                radios.forEach(r => r.setAttribute('name', 'media_source_type_' + soalCounter));
                radios.forEach(r => r.setAttribute('onchange', 'toggleMediaSource(' + soalCounter + ')'));

                // Update File Input
                const mediaFileInput = mediaWrapper.querySelector('input[type="file"][name$="[media_file]"]');
                if (mediaFileInput) {
    mediaFileInput.value = '';  // 🔥 TAMBAHKAN INI!
    mediaFileInput.setAttribute('onchange', 'previewMedia(this, ' + soalCounter + ')');
}
                ;
                const mediaFileName = mediaWrapper.querySelector('.media-file-control-0 .file-name');
                mediaFileName.classList.remove('media-file-control-0'); // Remove class helper
                mediaFileName.id = 'media-file-name-' + soalCounter;
                mediaFileName.textContent = 'Belum ada file';
                
                // Update URL Input
                const mediaUrlInput = mediaWrapper.querySelector('input[type="url"]');
                mediaUrlInput.id = 'media-url-' + soalCounter;
                mediaUrlInput.setAttribute('oninput', 'previewMediaUrl(' + soalCounter + ')');
                mediaUrlInput.value = '';

                // Update Preview
                const mediaPreview = mediaWrapper.querySelector('.media-preview-box');
                mediaPreview.id = 'media-preview-' + soalCounter;
                mediaPreview.innerHTML = '';
                
                // Update Control Wrappers (for toggle logic)
                const mediaFileControl = mediaWrapper.querySelector('.media-file-control-0');
                if(mediaFileControl) mediaFileControl.className = 'media-file-control-' + soalCounter;
                
                const mediaUrlControl = mediaWrapper.querySelector('.media-url-control-0');
                if(mediaUrlControl) mediaUrlControl.className = 'media-url-control-' + soalCounter;

                // Reset Toggle state
                mediaFileControl.style.display = 'block';
                mediaUrlControl.style.display = 'none';
            }
            
            // Update Gambar Wrapper ID
            const gambarWrapper = template.querySelector('#wrapper-gambar-0');
            if(gambarWrapper) gambarWrapper.id = 'wrapper-gambar-' + soalCounter;

            // Update ID Tipe
            const tipeSelect = template.querySelector('select[name$="[tipe]"]');
            if(tipeSelect) {
                tipeSelect.id = 'select-tipe-' + soalCounter;
                tipeSelect.value = 'pg';
                tipeSelect.setAttribute('onchange', 'toggleOptions(' + soalCounter + ')');
            }

            // Reset Opsi Dinamis
            const optionsContainer = template.querySelector('.columns.is-multiline');
            optionsContainer.id = 'options-container-' + soalCounter;
            optionsContainer.style.display = 'block'; 
            
            const toRemove = optionsContainer.querySelectorAll('[id^="col-d-"], [id^="col-e-"], [id^="btn-add-"]');
            toRemove.forEach(el => el.remove());

            const btnAddDWrapper = optionsContainer.querySelector('#btn-add-d-wrapper-0');
            if(btnAddDWrapper) {
                const btnAddD = btnAddDWrapper.querySelector('button');
                btnAddDWrapper.id = 'btn-add-d-wrapper-' + soalCounter;
                if(btnAddD) btnAddD.setAttribute('onclick', 'addOption(' + soalCounter + ', \'d\')');
            } else {
                const wrapper = document.createElement('div');
                wrapper.className = 'column is-full';
                wrapper.id = 'btn-add-d-wrapper-' + soalCounter;
                wrapper.innerHTML = `<button type="button" class="btn-add-option" onclick="addOption(${soalCounter}, 'd')"><i class="fas fa-plus"></i> Tambah Opsi D</button>`;
                optionsContainer.appendChild(wrapper);
            }

            // Update Dropdown & Textarea IDs and Names
            const select = template.querySelector('#select-jawaban-0'); 
            const essayTextarea = template.querySelector('#textarea-jawaban-0');

            if(select) {
                select.id = 'select-jawaban-' + soalCounter;
                select.setAttribute('name', 'soal[' + soalCounter + '][jawaban_benar]'); 
                select.value = "";
                select.innerHTML = `<option value="">Pilih jawaban benar</option><option value="a">A</option><option value="b">B</option><option value="c">C</option>`;
                const pgWrapper = template.querySelector('.input-pg-wrapper');
                if(pgWrapper) pgWrapper.id = 'input-pg-wrapper-' + soalCounter;
            }

            if(essayTextarea) {
                essayTextarea.id = 'textarea-jawaban-' + soalCounter;
                essayTextarea.value = '';
                essayTextarea.setAttribute('name', 'soal[' + soalCounter + '][jawaban_benar]');
                const essayWrapper = template.querySelector('.input-essay-wrapper');
                if(essayWrapper) essayWrapper.id = 'input-essay-wrapper-' + soalCounter;
            }

            // Rename Inputs
            template.querySelectorAll('[name^="soal[0]"]').forEach(el => {
                const name = el.getAttribute('name');
                if (name) el.setAttribute('name', name.replace('soal[0]', 'soal[' + soalCounter + ']'));
            });

            template.querySelectorAll('input[type="text"], textarea').forEach(el => el.value = '');
            template.querySelectorAll('input[name*="[opsi_]"]').forEach(input => {
                input.required = true;
                input.disabled = false;
            });

            container.appendChild(template);
            window.toggleOptions(soalCounter);
            soalCounter++;
            template.scrollIntoView({ behavior: 'smooth' });
        });
    
   window.removeSoal = function(index) {
    const soalElement = document.getElementById('soal-' + index);
    if (soalElement) {
        soalElement.remove();
        renumberSoal();
        // ===== TAMBAHKAN INI =====
        // Reset soalCounter berdasarkan jumlah soal yang tersisa
        const remainingSoal = document.querySelectorAll('.soal-card').length;
        soalCounter = remainingSoal;
    }
};
}
    
    function renumberSoal() {
    const soalCards = document.querySelectorAll('.soal-card');
    
    // ===== UPDATE SOAL COUNTER =====
    soalCounter = soalCards.length;
    
    soalCards.forEach((card, idx) => {
        const numberSpan = card.querySelector('.soal-number');
        if (numberSpan) numberSpan.innerHTML = `<i class="fas fa-question-circle"></i> Soal ${idx + 1}`;
        
        const oldId = card.getAttribute('data-soal-id');
        
        // Update nama attributes
        card.querySelectorAll('[name^="soal[' + oldId + ']"]').forEach(el => {
            const name = el.getAttribute('name');
            if (name) el.setAttribute('name', name.replace(/soal\[\d+\]/, 'soal[' + idx + ']'));
        });

        // Update onclick attributes
        const attributes = card.querySelectorAll('[onclick]');
        attributes.forEach(attr => {
            let val = attr.getAttribute('onclick');
            let newVal = val;
            newVal = newVal.replace(/addOption\((\d+),/g, `addOption(${idx},`);
            newVal = newVal.replace(/removeOption\((\d+),/g, `removeOption(${idx},`);
            newVal = newVal.replace(/previewImage\(this, (\d+)\)/g, `previewImage(this, ${idx})`);
            newVal = newVal.replace(/removeSoal\((\d+)\)/g, `removeSoal(${idx})`);
            newVal = newVal.replace(/toggleOptions\((\d+)\)/g, `toggleOptions(${idx})`);
            newVal = newVal.replace(/toggleMediaSource\((\d+)\)/g, `toggleMediaSource(${idx})`);
            newVal = newVal.replace(/previewMedia\(this, (\d+)\)/g, `previewMedia(this, ${idx})`);
            newVal = newVal.replace(/previewMediaUrl\((\d+)\)/g, `previewMediaUrl(${idx})`);
            if(newVal !== val) attr.setAttribute('onclick', newVal);
        });

        // Update IDs generic
        const container = card.querySelector('.columns.is-multiline');
        if(container) container.id = 'options-container-' + idx;
        const select = card.querySelector('select[name$="[jawaban_benar]"]');
        if(select) select.id = 'select-jawaban-' + idx;
        const tipeSelect = card.querySelector('select[name$="[tipe]"]');
        if(tipeSelect) tipeSelect.id = 'select-tipe-' + idx;
        const essayText = card.querySelector('#textarea-jawaban-' + idx);
        if(essayText) essayText.id = 'textarea-jawaban-' + idx;
        
        // Update Specific Wrappers IDs (Media, Gambar, AV Mode)
        const mediaWrap = card.querySelector('[id^="wrapper-media-"]');
        if(mediaWrap) mediaWrap.id = 'wrapper-media-' + idx;
        const gambarWrap = card.querySelector('[id^="wrapper-gambar-"]');
        if(gambarWrap) gambarWrap.id = 'wrapper-gambar-' + idx;
        const avModeSelectorWrap = card.querySelector('[id^="av-mode-selector-"]');
        if(avModeSelectorWrap) avModeSelectorWrap.id = 'av-mode-selector-' + idx;
        const avSubType = card.querySelector('[id^="av-sub-type-"]');
        if(avSubType) avSubType.id = 'av-sub-type-' + idx;

        const wrappers = card.querySelectorAll('[id^="btn-add-"], [id^="col-"], [id^="input-"]');
        wrappers.forEach(w => {
            const oldIdAttr = w.id;
            const newId = oldIdAttr.replace(/-\d+$/, `-${idx}`);
            if(newId !== oldIdAttr) w.id = newId;
        });
        
        // Update data-soal-id
        card.setAttribute('data-soal-id', idx);
        
        // Update radio buttons data-index
        const radios = card.querySelectorAll('.av-mode-radio');
        radios.forEach(radio => {
            radio.setAttribute('data-index', idx);
            radio.setAttribute('name', `av_mode[${idx}]`);
        });
        
        // Update hidden input name
        const hiddenAvSub = card.querySelector('[name$="[av_sub_type]"]');
        if (hiddenAvSub) {
            hiddenAvSub.name = `soal[${idx}][av_sub_type]`;
        }
    });
}
    
    window.previewImage = function(input, index) {
        const preview = document.getElementById('preview-' + index);
        const fileName = document.getElementById('file-name-' + index);
        const file = input.files[0];
        if (file) {
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

    // ==================== IMPORT LOGIC & VALIDATION ====================
    const excelFileInput = document.getElementById('excelFileInput');
    if (excelFileInput) {
        excelFileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Belum ada file dipilih';
            const importFileName = document.getElementById('importFileName');
            if (importFileName) importFileName.textContent = fileName;
        });
    }
    
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    if (downloadTemplateBtn) {
        downloadTemplateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban_benar', 'tipe'];
            const example1 = ['Apa manfaat PPKI?', 'Merumuskan dasar negara', 'Membuat UUD', 'Menjadikan Jakarta sebagai Ibukota', 'Mempertahankan kemerdekaan', 'Indonesia Merdeka', 'A', 'pg'];
            const templateData = [headers, example1];
            
            let csvContent = templateData.map(row => row.map(cell => {
                if (typeof cell === 'string' && (cell.includes(',') || cell.includes('"'))) return '"' + cell.replace(/"/g, '""') + '"';
                return cell;
            }).join(',')).join('\n');
            
            const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'template_soal.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            showToast('success', 'Template berhasil di download!');
        });
    }
    
    const previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', async function() {
            const fileInput = document.getElementById('excelFileInput');
            if (!fileInput.files[0]) { showToast('error', 'Pilih file terlebih dahulu!'); return; }
            
            const formData = new FormData(document.getElementById('importForm'));
            previewModal.classList.add('is-active');
            previewContent.innerHTML = `<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x" style="color: #2e5b9a;"></i><p>Memproses file...</p></div>`;
            
            try {
                const response = await fetch('{{ route("import.preview") }}', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    let soalData = Array.isArray(result.data) ? result.data : (result.data?.rows || result.data?.data || []);
                    previewData = { rows: soalData };
                    displayPreview(previewData);
                } else {
                    previewContent.innerHTML = `<div class="notification is-danger" style="margin:20px; border-radius:8px;"><p><strong>Error:</strong> ${result.message}</p></div>`;
                }
            } catch (error) {
                previewContent.innerHTML = `<div class="notification is-danger" style="margin:20px; border-radius:8px;"><p>Error: ${error.message}</p></div>`;
            }
        });
    }

function displayPreview(data) {
    let rows = (data && data.rows) ? (Array.isArray(data.rows) ? data.rows : Object.values(data.rows)) : [];
    
    if (rows.length === 0) { 
        previewContent.innerHTML = `<div class="notification is-warning" style="margin:20px;">Tidak ada data.</div>`; 
        return; 
    }
    
    // Debug di console
    console.log('Data preview:', rows);
    console.log('Sample row:', rows[0]);
    
    // Validasi tipe soal homogen
    const typesFound = new Set();
    rows.forEach(r => {
        const t = String(r.tipe || 'pg').toLowerCase();
        typesFound.add(t);
    });
    
    const detectedType = Array.from(typesFound)[0]?.toLowerCase() || 'pg';
    const isEssay = detectedType === 'essay';
    
    if (typesFound.size > 1) {
        previewContent.innerHTML = `
            <div class="notification is-danger" style="margin:20px;">
                <h4 class="title is-5"><i class="fas fa-exclamation-triangle"></i> Validasi Gagal</h4>
                <p>File Excel mengandung <strong>campuran tipe soal</strong>.</p>
                <p>Ditemukan: <strong>${Array.from(typesFound).join(', ')}</strong>.</p>
            </div>
        `;
        document.getElementById('confirmImportBtn').style.display = 'none';
        return;
    } else {
        document.getElementById('confirmImportBtn').style.display = 'inline-flex';
    }
    
    // ========== SESUAIKAN HEADER TABLE BERDASARKAN TIPE ==========
    let tableHeaders = '';
    if (isEssay) {
        // Untuk ESSAY: hanya tampilkan Soal, Jawaban, Tipe
        tableHeaders = `
            <th>#</th>
            <th>Soal</th>
            <th>Jawaban (Kunci)</th>
            <th>Tipe</th>
        `;
    } else {
        // Untuk PG: tampilkan semua opsi A-E
        tableHeaders = `
            <th>#</th>
            <th>Soal</th>
            <th>Opsi A</th>
            <th>Opsi B</th>
            <th>Opsi C</th>
            <th>Opsi D</th>
            <th>Opsi E</th>
            <th>Jawaban</th>
            <th>Tipe</th>
        `;
    }
    
    let html = `
        <div style="padding:15px; background:#f0f7ff; color:#2e5b9a; border-bottom:1px solid #ddd;">
            <strong>${rows.length} soal</strong> ditemukan 
            (Tipe: <strong>${detectedType.toUpperCase()}</strong>).
            ${data.header_mapping ? `<span class="tag is-light ml-2"><i class="fas fa-map-marker-alt"></i> Auto-mapping</span>` : ''}
        </div>
        <div style="overflow-x:auto; max-height:500px;">
            <table class="table is-fullwidth is-striped is-hoverable" style="font-size:0.85rem;">
                <thead>
                    <tr>
                        ${tableHeaders}
                    </tr>
                </thead>
                <tbody>
    `;
    
    rows.forEach((row, index) => {
        // Ambil data dengan aman
        let soalText = row.soal ? String(row.soal) : '-';
        let jawaban = row.jawaban_benar ? String(row.jawaban_benar) : '-';
        let tipe = row.tipe || 'pg';
        
        // Truncate soal
        if (soalText.length > 50) soalText = soalText.substring(0, 50) + '...';
        
        if (isEssay) {
            // ========== RENDER UNTUK ESSAY (Tanpa Opsi) ==========
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td title="${escapeHtml(String(row.soal || ''))}">${escapeHtml(soalText)}</td>
                    <td><span class="tag is-success">${escapeHtml(jawaban)}</span></td>
                    <td><span class="tag is-warning">ESSAY</span></td>
                </tr>
            `;
        } else {
            // ========== RENDER UNTUK PG (Dengan Opsi) ==========
            let opsiA = row.opsi_a ? String(row.opsi_a) : '-';
            let opsiB = row.opsi_b ? String(row.opsi_b) : '-';
            let opsiC = row.opsi_c ? String(row.opsi_c) : '-';
            let opsiD = row.opsi_d ? String(row.opsi_d) : '-';
            let opsiE = row.opsi_e ? String(row.opsi_e) : '-';
            let jawabanHuruf = row.jawaban_benar ? String(row.jawaban_benar).toUpperCase() : '-';
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td title="${escapeHtml(String(row.soal || ''))}">${escapeHtml(soalText)}</td>
                    <td>${escapeHtml(opsiA)}</td>
                    <td>${escapeHtml(opsiB)}</td>
                    <td>${escapeHtml(opsiC)}</td>
                    <td>${escapeHtml(opsiD)}</td>
                    <td>${escapeHtml(opsiE)}</td>
                    <td><span class="tag is-success">${escapeHtml(jawabanHuruf)}</span></td>
                    <td><span class="tag is-info">PG</span></td>
                </tr>
            `;
        }
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    // Tampilkan mapping info (hanya untuk PG karena essay ga perlu mapping opsi)
    if (data.header_mapping && !isEssay) {
        html += `
        <div class="notification is-info is-light" style="margin:15px; font-size:0.8rem;">
            <i class="fas fa-info-circle"></i> 
            <strong>Deteksi Mapping:</strong><br>
            Soal → kolom ${(data.header_mapping.soal || 0) + 1}<br>
            Opsi A → kolom ${(data.header_mapping.opsi_a || 0) + 1}<br>
            Opsi B → kolom ${(data.header_mapping.opsi_b || 0) + 1}<br>
            Opsi C → kolom ${(data.header_mapping.opsi_c || 0) + 1}<br>
            Opsi D → kolom ${(data.header_mapping.opsi_d || 0) + 1}<br>
            Opsi E → kolom ${(data.header_mapping.opsi_e || 0) + 1}<br>
            Jawaban → kolom ${(data.header_mapping.jawaban_benar || 0) + 1}
        </div>
        `;
    } else if (data.header_mapping && isEssay) {
        html += `
        <div class="notification is-info is-light" style="margin:15px; font-size:0.8rem;">
            <i class="fas fa-info-circle"></i> 
            <strong>Deteksi Mapping:</strong><br>
            Soal → kolom ${(data.header_mapping.soal || 0) + 1}<br>
            Jawaban → kolom ${(data.header_mapping.jawaban_benar || 0) + 1}
        </div>
        `;
    }
    
    previewContent.innerHTML = html;
}

// ==================== CONFIRM IMPORT ====================
function confirmImport() {
    if (!previewData || !previewData.rows || previewData.rows.length === 0) {
        showToast('error', 'Tidak ada data untuk diimport!');
        return;
    }
    
    const rows = Array.isArray(previewData.rows) ? previewData.rows : [];
    const totalSoal = rows.length;
    
    if (!confirm(`Konfirmasi import ${totalSoal} soal ke dalam ujian ini?`)) return;
    
    // Siapkan data
    const soalData = rows.map(s => ({
        soal: s.soal || '',
        opsi_a: s.opsi_a || null,
        opsi_b: s.opsi_b || null,
        opsi_c: s.opsi_c || null,
        opsi_d: s.opsi_d || null,
        opsi_e: s.opsi_e || null,
        jawaban_benar: s.jawaban_benar || '',
        tipe: s.tipe || 'pg',
        gambar: s.gambar || null
    }));
    
    const requestData = {
        uji_id: document.querySelector('input[name="uji_id"]')?.value || '',
        mapel_id: document.querySelector('input[name="mapel_id"]')?.value || '',
        guru_id: document.querySelector('input[name="guru_id"]')?.value || '',
        soal_data: soalData
    };
    
    // Loading
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) loadingOverlay.classList.add('active');
    closePreviewModal();
    
    fetch('{{ route("import.confirm") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(result => {
        if (loadingOverlay) loadingOverlay.classList.remove('active');
        
        if (result.success) {
            showToast('success', result.message || `✅ Berhasil import ${totalSoal} soal!`);
            setTimeout(() => {
                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    location.reload();
                }
            }, 1500);
        } else {
            showToast('error', result.message || 'Gagal mengimport soal');
        }
    })
    .catch(error => {
        if (loadingOverlay) loadingOverlay.classList.remove('active');
        console.error('Error:', error);
        showToast('error', 'Terjadi kesalahan: ' + error.message);
    });
}
    
    function showToast(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification-toast notification-${type}`;
        notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    document.addEventListener('DOMContentLoaded', function() {
    const initTipeSelect = document.getElementById('select-tipe-0');
    if (initTipeSelect) {
        window.toggleOptions(0);
        setupAvModeRadios(0);
    }
});
    function closePreviewModal() { if(previewModal) previewModal.classList.remove('is-active'); }
    function escapeHtml(text) { if(!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

    // ==================== UI EVENTS ====================
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) userDropdown.addEventListener('click', e => { e.stopPropagation(); userDropdown.querySelector('.dropdown-menu').style.opacity = userDropdown.querySelector('.dropdown-menu').style.opacity==='1'?'0':'1'; userDropdown.querySelector('.dropdown-menu').style.visibility = userDropdown.querySelector('.dropdown-menu').style.opacity==='1'?'hidden':'visible'; userDropdown.querySelector('.dropdown-menu').style.transform = userDropdown.querySelector('.dropdown-menu').style.opacity==='1'?'translateY(-10px)':'translateY(0)'; });
    document.addEventListener('click', () => { if(userDropdown) { const dm = userDropdown.querySelector('.dropdown-menu'); if(dm) { dm.style.opacity='0'; dm.style.visibility='hidden'; dm.style.transform='translateY(-10px)'; } } });

    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() { if(sidebar && mobileToggle && sidebarOverlay) { sidebar.classList.toggle('open'); sidebarOverlay.classList.toggle('active'); const icon = mobileToggle.querySelector('i'); if(icon) { icon.className = sidebar.classList.contains('open')?'fas fa-times':'fas fa-bars'; } } }
    if(mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

    const notification = document.getElementById('notification');
    if(notification) setTimeout(() => notification.remove(), 5000);

    document.querySelectorAll('a').forEach(link => { link.addEventListener('click', e => { const href = link.getAttribute('href'); if(href && !href.startsWith('#') && !href.startsWith('javascript:') && !href.startsWith('mailto:') && !link.getAttribute('target') && (href.startsWith(window.location.origin) || href.startsWith('/'))) { if(link.id==='tambahSoal') return; e.preventDefault(); mainContent.classList.add('page-leaving'); setTimeout(()=>window.location.href=href, 250); }}); });
</script>
</body>
</html>