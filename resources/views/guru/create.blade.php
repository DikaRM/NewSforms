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
        .sidebar-item:hover { background: rgba(255,255,255,0.25); border-left: 4px solid white; }
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
        .preview-image { margin-top: 10px; max-width: 300px; display: none; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
        .preview-image img { width: 100%; display: block; }
        
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

        /* Mobile */
        .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
        .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
            .mobile-toggle { display: flex; }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <h2>
       <img src="{{ asset('WhatsApp Image 2026-04-10 at 08.00.25.png') }}" class="image is-32x34" style="height:30px" alt="Logo"/>
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
                <a href="{{ route('profile.index') }}" class="dropdown-item-custom">
        <i class="fas fa-user-circle"></i>
        <span>Profil Saya</span>
    </a>
                <div style="height:1px; background:#eee; margin:4px 0;"></div>
                <form action="{{ route('users.logout') }}" method="post">
                    @csrf
                    <button type="submit" style="width:100%; background:none; border:none; cursor:pointer; padding:10px 16px; display:flex; align-items:center; gap:12px; color:#dc3545; font-size:0.85rem;"><i class="fas fa-sign-out-alt"></i> Logout</button>
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
            <a href="{{ route('guru.index') }}" class="sidebar-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item"><i class="fas fa-calendar-alt"></i> Jadwal Ujian</a>
            <a href="{{ route('guru.create', $uji->id ?? '') }}" class="sidebar-item active"><i class="fas fa-pen-fancy"></i> Tambah Soal</a>
            <a href="{{ route('guru.result') }}" class="sidebar-item"><i class="fas fa-file-alt"></i> Hasil Ujian</a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit" class="sidebar-item" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i> Logout</button>
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
                        Format header: <strong>soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban_benar, tipe</strong><br>
                        <i class="fas fa-file-alt"></i> Tipe: <strong>pg</strong> (pilihan ganda) atau <strong>essay</strong>
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
    <div style="background:red;color:white;padding:10px;">
        <strong>ERROR VALIDASI:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('guru.ujian.sold', $uji->id ?? 0) }}" method="POST" enctype="multipart/form-data" id="formSoal" novalidate>
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
                        
                        <!-- TIPE SOAL (PG / ESSAY) -->
                        <div class="field">
                            <label class="label required">Tipe Soal</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="soal[0][tipe]" id="select-tipe-0" onchange="toggleOptions(0)">
                                        <option value="pg" selected>Pilihan Ganda (PG)</option>
                                        <option value="essay">Essay</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- GAMBAR -->
                        <div class="field">
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
                                        <!-- Opsi D & E akan ditambah via JS -->
                                    </select>
                                </div>
                            </div>

                            <!-- 2. Input untuk Essay (Textarea) -->
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
            <p class="modal-card-title"><i class="fas fa-file-alt"></i> Preview Soal dari Excel</p>
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
    // ==================== VARIABLES ====================
    let soalCounter = 1;
    let mainContent = document.querySelector(".main-content");
    const previewModal = document.getElementById('previewModal');
    const previewContent = document.getElementById('previewContent');
    let previewData = null;
    
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

        // Tambah tombol berikutnya (E)
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

        // Update select jawaban benar (PG only)
        updateSelectOptions(soalIndex, opt, true);
    };

    window.removeOption = function(soalIndex, opt) {
        const colId = 'col-' + opt + '-' + soalIndex;
        const btnWrapperId = 'btn-add-' + opt + '-wrapper-' + soalIndex;
        const col = document.getElementById(colId);
        const container = document.getElementById('options-container-' + soalIndex);

        if (col) col.remove();

        // Tampilkan tombol tambah lagi
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

        // Hapus option dari select
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

    // ==================== FUNGSI TOGGLE PG / ESSAY (PERBAIKAN VALIDASI) ====================
  window.toggleOptions = function(index) {
    const soalCard = document.querySelector('#soal-' + index);

    const tipeSelect = soalCard.querySelector('#select-tipe-' + index);
    const optionsContainer = soalCard.querySelector('.columns.is-multiline');
    const pgWrapper = soalCard.querySelector('.input-pg-wrapper');
    const essayWrapper = soalCard.querySelector('.input-essay-wrapper');
    const pgSelect = soalCard.querySelector('#select-jawaban-' + index);
    const essayText = soalCard.querySelector('#textarea-jawaban-' + index);

    if (tipeSelect.value === 'essay') {
        optionsContainer.style.display = 'none';
        pgWrapper.style.display = 'none';
        essayWrapper.style.display = 'block';

        // 🔥 PENTING BANGET
        pgSelect.disabled = true;
        pgSelect.removeAttribute('name');

        essayText.disabled = false;
        essayText.setAttribute('name', `soal[${index}][jawaban_benar]`);

    } else {
        optionsContainer.style.display = 'block';
        pgWrapper.style.display = 'block';
        essayWrapper.style.display = 'none';

        // 🔥 PENTING BANGET
        essayText.disabled = true;
        essayText.removeAttribute('name');

        pgSelect.disabled = false;
        pgSelect.setAttribute('name', `soal[${index}][jawaban_benar]`);
    }
};

        const tambahSoalBtn = document.getElementById('tambahSoal');
    if (tambahSoalBtn) {
        tambahSoalBtn.addEventListener('click', function() {
            const container = document.getElementById('soalContainer');
            const template = document.getElementById('soal-0').cloneNode(true);
            
            // 1. RESET LOGIC (Ganti ID)
            template.id = 'soal-' + soalCounter;
            template.setAttribute('data-soal-id', soalCounter);
            
            const soalNumber = template.querySelector('.soal-number');
            if (soalNumber) soalNumber.innerHTML = `<i class="fas fa-question-circle"></i> Soal ${soalCounter + 1}`;
            
            const removeBtn = template.querySelector('.btn-remove-soal');
            if (removeBtn) {
                removeBtn.style.display = 'flex';
                removeBtn.setAttribute('onclick', 'removeSoal(' + soalCounter + ')');
            }

            // 2. Reset Gambar
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

            // 3. Update ID Tipe & Reset ke PG
            const tipeSelect = template.querySelector('select[name$="[tipe]"]');
            if(tipeSelect) {
                tipeSelect.id = 'select-tipe-' + soalCounter;
                tipeSelect.value = 'pg';
                tipeSelect.setAttribute('onchange', 'toggleOptions(' + soalCounter + ')');
            }

            // 4. Reset Opsi Dinamis (Hapus D/E jika ada)
            const optionsContainer = template.querySelector('.columns.is-multiline');
            optionsContainer.id = 'options-container-' + soalCounter;
            optionsContainer.style.display = 'block'; // Pastikan visible karena tipe default PG
            
            const toRemove = optionsContainer.querySelectorAll('[id^="col-d-"], [id^="col-e-"], [id^="btn-add-"]');
            toRemove.forEach(el => el.remove());

            // 5. Handle Tombol Tambah D (Fix Selector ID)
            const btnAddDWrapper = optionsContainer.querySelector('#btn-add-d-wrapper-0');
            if(btnAddDWrapper) {
                const btnAddD = btnAddDWrapper.querySelector('button');
                btnAddDWrapper.id = 'btn-add-d-wrapper-' + soalCounter;
                if(btnAddD) {
                    btnAddD.setAttribute('onclick', 'addOption(' + soalCounter + ', \'d\')');
                }
            } else {
                // Fallback
                const wrapper = document.createElement('div');
                wrapper.className = 'column is-full';
                wrapper.id = 'btn-add-d-wrapper-' + soalCounter;
                wrapper.innerHTML = `
                    <button type="button" class="btn-add-option" onclick="addOption(${soalCounter}, 'd')">
                        <i class="fas fa-plus"></i> Tambah Opsi D
                    </button>
                `;
                optionsContainer.appendChild(wrapper);
            }

            // 6. Update Jawaban Benar (PG Select)
            const select = template.querySelector('select[name$="[jawaban_benar]"]');
            if(select) {
                select.id = 'select-jawaban-' + soalCounter;
                select.value = "";
                select.innerHTML = `
                    <option value="">Pilih jawaban benar</option>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                `;
                const pgWrapper = template.querySelector('.input-pg-wrapper');
                if(pgWrapper) pgWrapper.id = 'input-pg-wrapper-' + soalCounter;
            }

            // 7. Update Jawaban Benar (Essay Textarea)
            const essayTextarea = template.querySelector('textarea[name$="[jawaban_benar]"]');
            if(essayTextarea) {
                essayTextarea.id = 'textarea-jawaban-' + soalCounter;
                essayTextarea.value = '';
                const essayWrapper = template.querySelector('.input-essay-wrapper');
                if(essayWrapper) essayWrapper.id = 'input-essay-wrapper-' + soalCounter;
            }

            // 8. Rename Inputs (Soal[0] -> Soal[index])
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
            
            // === FIX PENTING: PANGGIL TOGGLE OPTIONS ===
            // Memanggil fungsi ini akan mengatur ulang atribut 'name' dan 'disabled'
            // sehingga Dropdown PG aktif dan Textarea Essay tidak aktif.
           const currentIndex = soalCounter;
           window.toggleOptions(currentIndex);
           soalCounter++;
            template.scrollIntoView({ behavior: 'smooth' });
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
            if (numberSpan) numberSpan.innerHTML = `<i class="fas fa-question-circle"></i> Soal ${idx + 1}`;
            
            const oldId = card.getAttribute('data-soal-id');
            
            card.querySelectorAll('[name^="soal[' + oldId + ']"]').forEach(el => {
                const name = el.getAttribute('name');
                if (name) el.setAttribute('name', name.replace(/soal\[\d+\]/, 'soal[' + idx + ']'));
            });

            const attributes = card.querySelectorAll('[onclick]');
            attributes.forEach(attr => {
                let val = attr.getAttribute('onclick');
                let newVal = val;
                newVal = newVal.replace(/addOption\((\d+),/g, `addOption(${idx},`);
                newVal = newVal.replace(/removeOption\((\d+),/g, `removeOption(${idx},`);
                newVal = newVal.replace(/previewImage\(this, (\d+)\)/g, `previewImage(this, ${idx})`);
                newVal = newVal.replace(/removeSoal\((\d+)\)/g, `removeSoal(${idx})`);
                newVal = newVal.replace(/toggleOptions\((\d+)\)/g, `toggleOptions(${idx})`);
                if(newVal !== val) attr.setAttribute('onclick', newVal);
            });

            const container = card.querySelector('.columns.is-multiline');
            if(container) container.id = 'options-container-' + idx;

            const select = card.querySelector('select[name$="[jawaban_benar]"]');
            if(select) select.id = 'select-jawaban-' + idx;
            
            const tipeSelect = card.querySelector('select[name$="[tipe]"]');
            if(tipeSelect) tipeSelect.id = 'select-tipe-' + idx;

            const essayText = card.querySelector('#textarea-jawaban-' + idx);
            if(essayText) essayText.id = 'textarea-jawaban-' + idx;

            const wrappers = card.querySelectorAll('[id^="btn-add-"], [id^="col-"], [id^="input-"]');
            wrappers.forEach(w => {
                const oldId = w.id;
                const newId = oldId.replace(/-\d+$/, `-${idx}`);
                if(newId !== oldId) w.id = newId;
            });
            
            card.setAttribute('data-soal-id', idx);
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

    // ==================== IMPORT LOGIC ====================
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
            const example2 = ['1 + 1 = ?', '1', '2', '3', '4', '5', 'B', 'pg'];
            const example3 = ['Jelaskan pengertian gotong royong!', '', '', '', '', '', 'Kerja sama antar warga', 'essay'];
            const templateData = [headers, example1, example2, example3];
            
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
        if (rows.length === 0) { previewContent.innerHTML = `<div class="notification is-warning" style="margin:20px;">Tidak ada data.</div>`; return; }
        
        let html = `
            <div style="padding:15px; background:#f0f7ff; color:#2e5b9a; border-bottom:1px solid #ddd;"><strong>${rows.length} soal</strong> ditemukan.</div>
            <div style="overflow-x:auto; max-height:500px;">
                <table class="table is-fullwidth is-striped is-hoverable" style="font-size:0.85rem;">
                    <thead><tr><th>#</th><th>Soal</th><th>Jawaban</th><th>Tipe</th></tr></thead><tbody>
        `;
        rows.forEach((row, index) => {
            html += `<tr><td>${index+1}</td><td>${escapeHtml(row.soal ? row.soal.substring(0,50)+'...' : '-')}</td><td><span class="tag is-success">${row.jawaban_benar ? row.jawaban_benar.toUpperCase() : '-'}</span></td><td><span class="tag ${row.tipe==='pg'?'is-info':'is-warning'}">${row.tipe||'pg'}</span></td></tr>`;
        });
        html += `</tbody></table></div>`;
        previewContent.innerHTML = html;
    }
    
    function confirmImport() {
        if (!previewData || !previewData.rows || previewData.rows.length === 0) return;
        const rows = Array.isArray(previewData.rows) ? previewData.rows : [];
        const soalData = rows.map(s => ({ soal:s.soal, opsi_a:s.opsi_a, opsi_b:s.opsi_b, opsi_c:s.opsi_c, opsi_d:s.opsi_d, opsi_e:s.opsi_e, jawaban_benar:s.jawaban_benar, tipe:s.tipe||'pg', gambar:s.gambar }));
        const requestData = { uji_id:'{{ $uji->id ?? "" }}', mapel_id:'{{ $uji->mapel ?? "" }}', guru_id:'{{ $uji->guru_id ?? "" }}', soal_data: soalData };
        if (!confirm(`Konfirmasi import ${soalData.length} soal?`)) return;
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.classList.add('active');
        closePreviewModal();
        
        fetch('{{ route("import.confirm") }}', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(requestData)
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) { showToast('success', result.message); setTimeout(() => window.location.href = result.redirect_url || window.location.href, 1500); } 
            else { showToast('error', result.message || 'Gagal import'); }
        })
        .catch(e => showToast('error', 'Error: '+e.message))
        .finally(() => { if (loadingOverlay) loadingOverlay.classList.remove('active'); });
    }
    
    function showToast(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification-toast notification-${type}`;
        notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
        // === INISIALISASI PERTAMA ===
    document.addEventListener('DOMContentLoaded', function() {
        const initTipeSelect = document.getElementById('select-tipe-0');
        if (initTipeSelect) {
            window.toggleOptions(0);
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