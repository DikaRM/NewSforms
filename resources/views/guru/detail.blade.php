<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Ujian - {{ $ujian->nama_ujian }}</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
    
    <style>
        /* ... (STYLE ANDA TETAP SAMA) ... */
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

        /* Notifications */
        .notification-toast { position: fixed; top: 70px; right: 20px; padding: 12px 18px; border-radius: 8px; color: white; z-index: 1100; animation: slideInRight 0.3s ease; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .notification-success { background: #28a745; }
        .notification-error { background: #dc3545; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Helper */
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .loading-overlay.active { display: flex; }
        .loading-content { background: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }

        /* Style Halaman Detail */
        .exam-container { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .section-title { font-size: 1.2rem; font-weight: 600; color: #2e5b9a; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }

        .soal-card { 
            background: #fff; 
            border: 1px solid #eee; 
            border-radius: 10px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            position: relative;
        }
        .soal-header { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: bold; color: #2e5b9a; }
        .badge-type { font-size: 0.75rem; padding: 4px 10px; border-radius: 12px; color: white; text-transform: uppercase; font-weight: bold; }
        .badge-pg { background: #3b82f6; }
        .badge-essay { background: #f59e0b; }
        
        .soal-text { font-size: 1rem; margin-bottom: 15px; color: #333; line-height: 1.6; white-space: pre-wrap; }
        
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .option-item { 
            display: flex; align-items: flex-start; gap: 10px; 
            padding: 10px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;
        }
        .option-item.correct { background: #dcfce7; border-color: #86efac; color: #166534; }
        .opt-key { font-weight: bold; min-width: 25px; }
        
        .essay-key { background: #fef3c7; color: #92400e; padding: 10px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #fcd34d; }
        .img-preview { max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0; border: 1px solid #ddd; display: block; }

        .btn-custom { background: #2e5b9a; color: white; border: none; padding: 8px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; }
        .btn-custom:hover { background: #1e3a6b; transform: scale(1.02); color:white;}
        .btn-outline-custom { background: white; color: #2e5b9a; border: 1px solid #2e5b9a; padding: 8px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; }
        .btn-outline-custom:hover { background: #2e5b9a; color: white; }

        .mobile-toggle { display: none; position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background: #2e5b9a; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: none; color: white; }
        .sidebar-overlay { display: none; position: fixed; top: 56px; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 98; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 16px; }
            .mobile-toggle { display: flex; }
            .options-grid { grid-template-columns: 1fr; }
            .user-name{ display:none; }
        }
        
        .dropdown-menu { display: block !important; }
        .dropdown-menu.show { opacity: 1 !important; visibility: visible !important; transform: translateY(0) !important; }
        .user-dropdown { position: relative; cursor: pointer; }
        .user-info { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 8px; transition: background 0.3s ease; }
        .user-info:hover { background: rgba(255,255,255,0.15); }
        .user-avatar { width: 34px; height: 34px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e5b9a; font-weight: bold; }
        .user-name { font-weight: 500; font-size: 0.85rem; }
        .user-name i { font-size: 0.7rem; margin-left: 5px; }
        .sidebar-item i { width: 22px; font-size: 1rem; }
        .sidebar-item span { font-size: 0.85rem; font-weight: 500; }
        input:disabled {
    background: #f1f1f1 !important;
    cursor: not-allowed;
    opacity: 0.7;
}
.plane-animation {
    position: fixed;
    top: 50%;
    left: -100px;
    font-size: 40px;
    color: white;
    z-index: 99999;
    animation: flyPlane 2.5s ease-in-out forwards;
}

@keyframes flyPlane {
    0% {
        transform: translateX(0) translateY(0) rotate(-10deg);
        opacity: 0;
    }

    10% {
        opacity: 1;
    }

    50% {
        transform: translateX(50vw) translateY(-40px) rotate(5deg);
    }

    100% {
        transform: translateX(120vw) translateY(-120px) rotate(15deg);
        opacity: 0;
    }
}
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
    <h2>
       <img src="{{ asset('WhatsApp Image 2026-04-10 at 08.00.25.png') }}" class="image is-32x34" style="height:30px" alt="Logo"/>
        <span>SMK NEGERI 1 CIOMAS</span>
    </h2>
    <div class="header-actions">
        <div class="user-dropdown" id="userDropdown" style="position:relative; cursor:pointer;">
             <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
                <div class="user-name">
                    @if(isset($ire))<span>{{ $ire->nama }}</span>@else<span>Guru</span>@endif
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="dropdown-menu" style="position:absolute; top:110%; right:0; background:white; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.15); min-width:180px; opacity:0; visibility:hidden; transform:translateY(-10px); transition:all .3s ease; z-index:99999; overflow:hidden;">
                <a href="{{ route('profile.index') }}" style="display:flex; gap:10px; padding:10px 16px; text-decoration:none; color: #2e5b9a;">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
                <div style="height:1px; background:#eee; margin:4px 0;"></div>
                <form action="{{ route('users.logout') }}" method="post" class="logout-form">
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
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div style="padding:20px 0;">
            <a href="{{ route('guru.index') }}" class="sidebar-item"><i class="fas fa-home"></i><span>Dashboard</span> </a>
            <a href="{{ route('guru.jadwal') }}" class="sidebar-item"><i class="fas fa-calendar-alt"></i> <span>Jadwal Ujian</span></a>
            @if($ujian->status === "draft")
                <a href="{{ route('guru.create', $ujian->id) }}" class="sidebar-item"><i class="fas fa-pen-fancy"></i> <span>Tambah Soal</span></a>
            @endif
            <a href="{{ route('guru.ujian.detail', $ujian->id) }}" class="sidebar-item active"><i class="fas fa-list"></i> <span>Daftar Soal</span></a>
            <a href="{{ route('guru.result') }}" class="sidebar-item"><i class="fas fa-file-alt"></i> <span>Hasil Ujian</span></a>
            <a href="{{route('pengawas.index', isset($gurus) ? $gurus->id : '')}}" class="sidebar-item">
                <i class="fas fa-user-check"></i><span>Pengawas</span>
            </a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('users.logout') }}" method="post" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-button" style="width:100%; background:none; border:none; cursor:pointer;"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </aside>
    
    <!-- MAIN CONTENT -->
    <main class="main-content" id="mainContent">
        <!-- Flash Notification -->
        @if(session('success'))<div class="notification-toast notification-success" id="notification"><i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span></div>@endif
        @if(session('error'))<div class="notification-toast notification-error" id="notification"><i class="fas fa-exclamation-circle"></i> <span>{{ session('error') }}</span></div>@endif

        <!-- Info Ujian -->
        <div class="exam-container">
            <div class="section-title">
                <span><i class="fas fa-file-signature"></i> Detail Ujian</span>
                <div style="font-size: 0.9rem; font-weight: normal; color: #666;">
                    Total Soal: <strong style="color:#2e5b9a">{{ $ujian->soals->count() }}</strong>
                </div>
            </div>
            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div style="flex:1; min-width:200px;">
                    <p style="color:#666; font-size:0.9rem;">Nama Ujian</p>
                    <h2 style="margin:5px 0 0 0; font-size:1.4rem;">{{ $ujian->nama_ujian }}</h2>
                </div>
                <div style="flex:1; min-width:200px;">
                    <p style="color:#666; font-size:0.9rem;">Durasi</p>
                    <h2 style="margin:5px 0 0 0; font-size:1.4rem;">{{ $ujian->durasi }} Menit</h2>
                </div>
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:20px;">
@if($ujian->status === "draft")
    <a href="{{ route('guru.create', $ujian->id) }}" class="btn-custom">
        <i class="fas fa-plus"></i> Tambah Soal
    </a>

    <form action="{{route('guru.publish',$ujian->id)}}" 
          method="post"
          style="margin:0;">
        @csrf

        <button class="btn-custom" 
                style="background:#16a34a;" 
                type="submit">
            <i class="fas fa-paper-plane"></i> Publish Ujian
        </button>
    </form>
@endif
</div>
            </div>
        </div>

        <!-- LIST SOAL -->
        @if($ujian->soals->count() > 0)
            @foreach($ujian->soals as $index => $soal)
                <div class="soal-card" id="card-{{ $soal->id }}">
                    <div class="soal-header">
                        <span>No. {{ $index + 1 }}</span>
                        <span class="badge-type {{ $soal->tipe == 'pg' ? 'badge-pg' : 'badge-essay' }}">
                            @if($soal->tipe === 'pg')
    Pilihan Ganda
@elseif($soal->tipe === 'av')
    Audio Visual
@elseif($soal->tipe === 'upload')
Upload 
@else
    Essay
@endif
                        </span>
                    </div>

                    @if($soal->gambar)
                        <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar Soal" class="img-preview">
                    @endif
                    @if($soal->tipe === 'av')

    {{-- FILE VIDEO / AUDIO --}}
    @if($soal->media_file)

        @php
            $ext = pathinfo($soal->media_file, PATHINFO_EXTENSION);
        @endphp

        {{-- VIDEO --}}
        @if(in_array(strtolower($ext), ['mp4','webm','ogg']))
            <video controls style="width:100%; border-radius:10px; margin:10px 0;">
                <source src="{{ asset('storage/' . $soal->media_file) }}" type="video/{{ $ext }}">
                Browser tidak mendukung video.
            </video>
        @endif

        {{-- AUDIO --}}
        @if(in_array(strtolower($ext), ['mp3','wav','mpeg']))
            <audio controls style="width:100%; margin:10px 0;">
                <source src="{{ asset('storage/' . $soal->media_file) }}">
                Browser tidak mendukung audio.
            </audio>
        @endif

    @endif

    {{-- LINK YOUTUBE / VIDEO --}}
    @if($soal->media_url)

    @php
        $youtube = $soal->media_url;

        // normal youtube
        if(str_contains($youtube, 'watch?v=')) {
            $youtube = str_replace('watch?v=', 'embed/', $youtube);
        }

        // short youtube
        if(str_contains($youtube, 'youtu.be/')) {
            $youtube = str_replace('youtu.be/', 'www.youtube.com/embed/', $youtube);
        }

        // youtube shorts
        if(str_contains($youtube, 'youtube.com/shorts/')) {
            $youtube = str_replace('youtube.com/shorts/', 'youtube.com/embed/', $youtube);
        }
    @endphp

    <div style="margin:15px 0;">
        <iframe 
            width="100%" 
            height="315"
            src="{{ $youtube }}"
            title="Video Soal"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            style="border-radius:10px;">
        </iframe>
    </div>

@endif

@endif
                    <!-- TAMPILAN SOAL DI HALAMAN DETAIL -->
<div class="soal-text">{{ $soal->soal }}</div>

@if($soal->tipe == 'pg')
    {{-- PG: Tampilkan opsi A/B/C/D/E --}}
    <div class="options-grid">
        @php $letters = ['a','b','c','d','e']; @endphp
        @foreach($letters as $letter)
            @php $opt = 'opsi_'.$letter; $optVal = $soal->$opt; @endphp
            @if($optVal)
                <div class="option-item {{ strtolower($soal->jawaban_benar) == $letter ? 'correct' : '' }}">
                    <div class="opt-key">{{ strtoupper($letter) }}.</div>
                    <div>{{ $optVal }}</div>
                    @if(strtolower($soal->jawaban_benar) == $letter)
                        <i class="fas fa-check-circle" style="margin-left:auto;"></i>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

@elseif($soal->tipe == 'av')
    {{-- ========== AV: Deteksi otomatis dari opsi ========== --}}
    @php
        // Cek apakah ada opsi yang diisi
        $hasOptions = ($soal->opsi_a || $soal->opsi_b || $soal->opsi_c || $soal->opsi_d || $soal->opsi_e);
    @endphp
    
    @if($hasOptions)
        {{-- Mode Pilihan Ganda - Tampilkan opsi --}}
        <div class="options-grid">
            @php $letters = ['a','b','c','d','e']; @endphp
            @foreach($letters as $letter)
                @php $opt = 'opsi_'.$letter; $optVal = $soal->$opt; @endphp
                @if($optVal)
                    <div class="option-item {{ strtolower($soal->jawaban_benar) == $letter ? 'correct' : '' }}">
                        <div class="opt-key">{{ strtoupper($letter) }}.</div>
                        <div>{{ $optVal }}</div>
                        @if(strtolower($soal->jawaban_benar) == $letter)
                            <i class="fas fa-check-circle" style="margin-left:auto;"></i>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @else
        {{-- Mode Essay - Tampilkan kunci jawaban --}}
        <div class="essay-key"><strong>Kunci Jawaban:</strong> {{ $soal->jawaban_benar }}</div>
    @endif

@elseif($soal->tipe == 'essay')
    <div class="essay-key"><strong>Kunci Jawaban:</strong> {{ $soal->jawaban_benar }}</div>

@elseif($soal->tipe == 'upload')
    <div class="essay-key"><strong>Status:</strong> Tugas Upload</div>
@endif
                    
                    <!-- TOMBOL AKSI (EDIT & DELETE) -->

                    @if($ujian->status === 'draft')
<div style="margin-top:15px; border-top:1px solid #eee; padding-top:10px; text-align:right;">
    
    <button type="button" 
            class="btn-outline-custom" 
            onclick="openEditModal({{ $soal->id }}, 
                '{{ addslashes($soal->soal) }}', 
                '{{ $soal->tipe }}', 
                '{{ $soal->jawaban_benar }}', 
                '{{ $soal->opsi_a ?? '' }}', 
                '{{ $soal->opsi_b ?? '' }}', 
                '{{ $soal->opsi_c ?? '' }}', 
                '{{ $soal->opsi_d ?? '' }}', 
                '{{ $soal->opsi_e ?? '' }}',
                '{{$soal->media_file ?? ''}}',
                '{{$soal->media_url ?? ''}}',
            )">
        <i class="fas fa-edit"></i> Edit
    </button>

    <button type="button" 
            class="btn-outline-custom" 
            style="color:#dc3545; border-color:#dc3545;" 
            onclick="deleteSoal({{ $soal->id }})">
        <i class="fas fa-trash"></i> Hapus
    </button>

</div>
@endif
                </div>
            @endforeach
        @else
            <div class="exam-container" style="text-align:center; padding:50px;">
                <i class="fas fa-box-open fa-3x" style="color:#ddd; margin-bottom:20px;"></i>
                <p style="color:#888;">Belum ada soal untuk ujian ini.</p>
                <br>
                <a href="{{ route('guru.create', $ujian->id) }}" class="btn-custom">Mulai Buat Soal</a>
            </div>
        @endif

    </main>
</div>

<!-- MODAL EDIT SOAL -->
<div id="editModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card" style="width: 85%; max-width: 420px;max-height:500px;">
        <header class="modal-card-head">
            <p class="modal-card-title">Edit Soal</p>
            <button class="delete" aria-label="close" onclick="closeEditModal()"></button>
        </header>
        <section class="modal-card-body">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                
                
                <div class="field">
                    <label class="label">Pertanyaan</label>
                    <textarea class="textarea" name="soal" rows="3" required></textarea>
                </div>

                <div class="field">
                    <label class="label">Tipe Soal</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="tipe" id="editTipe" onchange="toggleEditOptions()">
                                <option value="pg">Pilihan Ganda (PG)</option>
                                <option value="essay">Essay</option>
                                <option value="av">Audio Visual</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- OPSI (Ditampilkan jika PG) -->
                <div id="editOptionsWrapper">
                    <div class="columns">
                        <div class="column is-half"><label class="label">Opsi A</label><input class="input" type="text" name="opsi_a"></div>
                        <div class="column is-half"><label class="label">Opsi B</label><input class="input" type="text" name="opsi_b"></div>
                    </div>
                    <div class="columns">
                        <div class="column is-half"><label class="label">Opsi C</label><input class="input" type="text" name="opsi_c"></div>
                        <div class="column is-half"><label class="label">Opsi D</label><input class="input" type="text" name="opsi_d"></div>
                    </div>
                    <div class="columns">
                        <div class="column is-half"><label class="label">Opsi E</label><input class="input" type="text" name="opsi_e"></div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Jawaban Benar</label>
                    <!-- PG Select -->
                    <div id="editPgWrapper">
                        <div class="select is-fullwidth">
                            <select name="jawaban_benar" id="editJawabanPg">
                                <option value="a">A</option><option value="b">B</option>
                                <option value="c">C</option><option value="d">D</option><option value="e">E</option>
                            </select>
                        </div>
                    </div>
                    <!-- Essay Textarea -->
                    <div id="editEssayWrapper" style="display:none;">
                        <textarea class="textarea" name="jawaban_benar" id="editJawabanEssay"></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Ganti Gambar (Opsional)</label>
                    <div class="file has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="gambar" id="gambarInput">
                            <span class="file-cta"><span class="file-icon"><i class="fas fa-upload"></i></span><span class="file-label">Pilih gambar...</span></span>
                        </label>
                    </div>
                </div>
                <div id="editMediaWrapper" style="display: none;">
    <div class="field">
        <label class="label">Upload Media (Video/Audio)</label>
        <input type="file" name="media_file" class="input" accept="video/*,audio/*" id="mediaFileInput">
    </div>

    <div class="field">
        <label class="label">Atau URL Youtube</label>
        <input type="url" name="media_url" class="input" placeholder="https://youtube.com/..." id="mediaUrlInput">
    </div>
</div>
            </form>
        </section>
        <footer class="modal-card-foot" style="justify-content: flex-end;">
            <button class="button" onclick="closeEditModal()">Batal</button>
            <button class="button is-primary" onclick="submitEdit()">Simpan Perubahan</button>
        </footer>
    </div>
</div>
<!-- LOADING OVERLAY -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <i class="fas fa-spinner fa-spin fa-2x" style="color:#2e5b9a;"></i>
        <p style="margin-top:15px; font-weight:600;">
            Memproses perubahan...
        </p>
        <p style="font-size:0.85rem; color:#666;">
            Mohon tunggu sebentar
        </p>
    </div>
</div>
<script>
function launchPlaneAnimation() {

    const plane = document.createElement('div');

    plane.className = 'plane-animation';

    plane.innerHTML = '<i class="fas fa-paper-plane"></i>';

    document.body.appendChild(plane);

    setTimeout(() => {
        plane.remove();
    }, 2500);
}
</script>
<script>

function setupExclusiveMediaInputs() {

    const gambar = document.getElementById('gambarInput');
    const mediaFile = document.getElementById('mediaFileInput');
    const mediaUrl = document.getElementById('mediaUrlInput');

    function updateState() {

        const hasGambar = gambar.files.length > 0;
        const hasMediaFile = mediaFile.files.length > 0;
        const hasMediaUrl = mediaUrl.value.trim() !== '';

        // RESET DULU
        gambar.disabled = false;
        mediaFile.disabled = false;
        mediaUrl.disabled = false;

        // Jika gambar dipilih
        if(hasGambar) {
            mediaFile.disabled = true;
            mediaUrl.disabled = true;
        }

        // Jika media file dipilih
        else if(hasMediaFile) {
            gambar.disabled = true;
            mediaUrl.disabled = true;
        }

        // Jika URL diisi
        else if(hasMediaUrl) {
            gambar.disabled = true;
            mediaFile.disabled = true;
        }
    }

    gambar.addEventListener('change', updateState);
    mediaFile.addEventListener('change', updateState);
    mediaUrl.addEventListener('input', updateState);

    updateState();
}

document.addEventListener('DOMContentLoaded', setupExclusiveMediaInputs);

</script>
<script>
    // UI EVENTS (Sidebar, Header, etc.)
    const loadingOverlay = document.getElementById('loadingOverlay');

function showLoading(text = 'Memproses perubahan...') {
    loadingOverlay.classList.add('active');

    const p = loadingOverlay.querySelector('p');
    if(p) p.innerText = text;
}

function hideLoading() {
    loadingOverlay.classList.remove('active');
}
    document.querySelectorAll('.logout-form').forEach(function(form) {
        let submitted = false;
        form.addEventListener('submit', function(e) {
            if (submitted) { e.preventDefault(); return; }
            submitted = true;
            const btn = form.querySelector('.logout-button');
            if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; btn.style.pointerEvents = 'none'; }
        });
    });

    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = userDropdown.querySelector('.dropdown-menu');
            menu.classList.toggle('show');
        });
    }
    document.addEventListener('click', function() {
        if(userDropdown) { const menu = userDropdown.querySelector('.dropdown-menu'); if(menu) { menu.classList.remove('show'); } }
    });

    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function toggleSidebar() { 
        if(sidebar && mobileToggle && sidebarOverlay) { 
            sidebar.classList.toggle('open'); 
            sidebarOverlay.classList.toggle('active'); 
            const icon = mobileToggle.querySelector('i'); 
            if(icon) { icon.className = sidebar.classList.contains('open')?'fas fa-times':'fas fa-bars'; } 
        } 
    }
    if(mobileToggle) mobileToggle.addEventListener('click', toggleSidebar);
    if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

    const notification = document.getElementById('notification');
    if(notification) setTimeout(() => notification.remove(), 5000);

    // ================== EDIT & DELETE LOGIC ==================
    
    let currentEditId = null;
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    // 1. BUKA MODAL DAN ISI DATA
    window.openEditModal = function(id, soal, tipe, jawaban, a, b, c, d, e, media_file, media_url) {
    currentEditId = id;
    
    // Isi Form
    editForm.querySelector('[name="soal"]').value = soal;
    editForm.querySelector('[name="tipe"]').value = tipe;
    editForm.querySelector('[name="opsi_a"]').value = a;
    editForm.querySelector('[name="opsi_b"]').value = b;
    editForm.querySelector('[name="opsi_c"]').value = c;
    editForm.querySelector('[name="opsi_d"]').value = d;
    editForm.querySelector('[name="opsi_e"]').value = e;
    
    // Isi media fields (jika ada)
    const mediaFileInput = editForm.querySelector('[name="media_file"]');
    const mediaUrlInput = editForm.querySelector('[name="media_url"]');
    
    // Reset dulu
    if(mediaFileInput) mediaFileInput.value = '';
    if(mediaUrlInput) mediaUrlInput.value = '';
    
    // Isi data yang sudah ada (tampilkan sebagai info)
    if(media_file && media_file !== '') {
        // Tampilkan info file existing
        const fileInfo = document.createElement('div');
        fileInfo.className = 'help is-info';
        fileInfo.innerHTML = `<i class="fas fa-paperclip"></i> File saat ini: ${media_file.split('/').pop()}`;
        const parent = mediaFileInput.parentElement;
        const oldInfo = parent.querySelector('.existing-file-info');
        if(oldInfo) oldInfo.remove();
        parent.appendChild(fileInfo);
        fileInfo.classList.add('existing-file-info');
    }
    
    if(media_url && media_url !== '') {
        mediaUrlInput.value = media_url;
    }

    // Handle Toggle
    toggleEditOptions();
    
    // Isi Jawaban
    if(tipe === 'pg') {
        editForm.querySelector('#editJawabanPg').value = jawaban;
    } else {
        editForm.querySelector('#editJawabanEssay').value = jawaban;
    }

    // Set Action URL
    editForm.action = "{{ route('guru.soal.update', ':id') }}".replace(':id', id);
    
    editModal.classList.add('is-active');
}

    window.closeEditModal = function() {
        editModal.classList.remove('is-active');
    }

    // 2. TOGGLE OPSI PG / ESSAY DI MODAL
   // 2. TOGGLE OPSI PG / ESSAY / AV DI MODAL
window.toggleEditOptions = function() {
    const tipe = editForm.querySelector('[name="tipe"]').value;
    
    const optWrapper = document.getElementById('editOptionsWrapper');
    const pgWrapper = document.getElementById('editPgWrapper');
    const essayWrapper = document.getElementById('editEssayWrapper');
    
    // Ambil field media
    const mediaWrapper = document.getElementById('editMediaWrapper');
    
    const pgInput = document.getElementById('editJawabanPg');
    const essayInput = document.getElementById('editJawabanEssay');
    
    if(tipe === 'pg') {
        // Mode PG
        if(optWrapper) optWrapper.style.display = 'block';
        if(pgWrapper) pgWrapper.style.display = 'block';
        if(essayWrapper) essayWrapper.style.display = 'none';
        if(mediaWrapper) mediaWrapper.style.display = 'none'; // SEMBUNYIKAN MEDIA
        
        // aktifkan pg
        pgInput.name = 'jawaban_benar';
        
        // nonaktifkan essay & media
        essayInput.name = '';
        
        // Reset media fields agar tidak terkirim
        const mediaFile = editForm.querySelector('input[name="media_file"]');
        const mediaUrl = editForm.querySelector('input[name="media_url"]');
        if(mediaFile) mediaFile.disabled = true;
        if(mediaUrl) mediaUrl.disabled = true;
        
    } else if(tipe === 'av') {
        // Mode AUDIO VISUAL
        if(optWrapper) optWrapper.style.display = 'none';
        if(pgWrapper) pgWrapper.style.display = 'none';
        if(essayWrapper) essayWrapper.style.display = 'block';
        if(mediaWrapper) mediaWrapper.style.display = 'block'; // TAMPILKAN MEDIA
        
        // aktifkan essay untuk jawaban
        essayInput.name = 'jawaban_benar';
        
        // nonaktifkan pg
        pgInput.name = '';
        
        // Aktifkan media fields
        const mediaFile = editForm.querySelector('input[name="media_file"]');
        const mediaUrl = editForm.querySelector('input[name="media_url"]');
        if(mediaFile) mediaFile.disabled = false;
        if(mediaUrl) mediaUrl.disabled = false;
        
    } else {
        // Mode ESSAY
        if(optWrapper) optWrapper.style.display = 'none';
        if(pgWrapper) pgWrapper.style.display = 'none';
        if(essayWrapper) essayWrapper.style.display = 'block';
        if(mediaWrapper) mediaWrapper.style.display = 'none'; // SEMBUNYIKAN MEDIA
        
        // aktifkan essay
        essayInput.name = 'jawaban_benar';
        
        // nonaktifkan pg & media
        pgInput.name = '';
        
        const mediaFile = editForm.querySelector('input[name="media_file"]');
        const mediaUrl = editForm.querySelector('input[name="media_url"]');
        if(mediaFile) mediaFile.disabled = true;
        if(mediaUrl) mediaUrl.disabled = true;
    }
}

    // 3. SUBMIT EDIT (AJAX)
   window.submitEdit = function() {

    showLoading('Menyimpan perubahan...');

    const saveBtn = document.querySelector('.button.is-primary');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(editForm);

    fetch(editForm.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(async r => {

        const data = await r.json();

        if(!r.ok){
            throw new Error(data.message || 'Terjadi kesalahan');
        }

        return data;
    })
    .then(result => {

        showToast('success', result.message);

        closeEditModal();

        setTimeout(() => {
            location.reload();
        }, 1000);

    })
    .catch(e => {

        showToast('error', e.message);

    })
    .finally(() => {

        hideLoading();

        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Simpan Perubahan';

    });
}
    // 4. DELETE SOAL (AJAX)
    window.deleteSoal = function(id) {

    if(!confirm('Yakin ingin menghapus soal ini?')) return;

    showLoading('Menghapus soal...');

    fetch("{{ route('guru.soal.delete', ':id') }}".replace(':id', id), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(result => {

        if(result.success) {

            showToast('success', result.message);

            document.getElementById('card-' + id).style.display = 'none';

            setTimeout(() => {
                location.reload();
            }, 1000);

        } else {

            showToast('error', result.message);

        }

    })
    .catch(e => {

        showToast('error', 'Error: ' + e.message);

    })
    .finally(() => {

        hideLoading();

    });
}

    function showToast(type, message) {
        const n = document.createElement('div');
        n.className = `notification-toast notification-${type}`;
        n.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 3000);
    }
</script>
</body>
</html>