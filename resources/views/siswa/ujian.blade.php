<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ujian {{$uji->nama_ujian}}</title>
  
  <!-- Bulmaswatch CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulmaswatch/default/bulmaswatch.min.css">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* ============================
       1. CORE LAYOUT & COLORS
       ============================ */
    :root {
      --primary-bg: #f5f7fa;
      --nav-active: #3273dc;
      --nav-answered: #48c774;
      --essay-color: #ffdd57;
      --essay-text: #333;
      --danger-color: #f14668;
    }

    body {
      background-color: var(--primary-bg);
      min-height: 100vh;
      transition: filter 0.1s ease;
    }

    /* ============================
       2. NAVIGASI SOAL
       ============================ */
    .soal-navigator {
      position: sticky;
      top: 20px;
      max-height: calc(100vh - 40px);
      overflow-y: auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      border: 1px solid #eee;
      border-radius: 8px;
      background: white;
    }
    
    .nav-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      margin-bottom: 15px;
    }
    
    .nav-btn {
      width: 100%;
      aspect-ratio: 1;
      border-radius: 6px;
      border: 1px solid #dbdbdb;
      background: white;
      cursor: pointer;
      font-weight: 600;
      color: #4a4a4a;
      transition: all 0.2s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
    }
    
    .nav-btn:hover {
      border-color: var(--nav-active);
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .nav-btn.current {
      background-color: var(--nav-active);
      color: white;
      border-color: var(--nav-active);
      box-shadow: 0 0 0 3px rgba(50, 115, 220, 0.25);
    }
    
    .nav-btn.answered {
      background-color: var(--nav-answered);
      color: white;
      border-color: var(--nav-answered);
    }
    
    .nav-btn.essay {
      border: 2px solid var(--essay-color);
      background: #fffff0;
      color: #555;
    }
    
    .nav-btn.essay.answered {
      background-color: var(--essay-color);
      color: var(--essay-text);
      border-color: #f0c029;
    }
    
    .nav-btn i { font-size: 0.75em; margin-top: 2px; }

    .info-panel {
      background: #eff5fb;
      border-left: 4px solid var(--nav-active);
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .legend {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-top: 15px;
      font-size: 0.8rem;
      justify-content: center;
    }
    
    .legend-item { display: flex; align-items: center; gap: 5px; color: #666; }
    .legend-color { width: 16px; height: 16px; border-radius: 3px; border: 1px solid #ddd; }

    /* ============================
       3. KARTU SOAL
       ============================ */
    .soal-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      border: 1px solid #eee;
      margin-bottom: 20px;
    }
    
    .soal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .soal-nomor {
      background: var(--nav-active);
      color: white;
      padding: 6px 18px;
      border-radius: 50px;
      font-weight: bold;
      font-size: 0.9rem;
      box-shadow: 0 2px 5px rgba(50, 115, 220, 0.3);
    }
    
    .soal-tipe {
      font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #888; letter-spacing: 0.5px;
    }

    .soal-gambar {
      margin: 20px 0; text-align: center; background: #fafafa; border: 1px dashed #ccc; padding: 15px; border-radius: 8px;
    }
    .soal-gambar img { max-width: 100%; max-height: 350px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

    .opsi-item {
      display: flex; align-items: center; padding: 12px 15px; border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;
    }
    .opsi-item:hover { background: #f8fbff; border-color: var(--nav-active); }
    .opsi-item input[type="radio"] { margin-right: 15px; transform: scale(1.3); cursor: pointer; }
    .opsi-label {
      display: inline-flex; justify-content: center; align-items: center; width: 32px; height: 32px; background: #e6e6e6; color: #555; border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
    }

    .essay-container { margin: 20px 0; }
    .essay-label {
      background: var(--essay-color); color: #5a5100; padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 0.85rem; display: inline-block; margin-bottom: 10px;
    }
    .essay-input {
      width: 100%; padding: 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; font-family: inherit; min-height: 150px; transition: border-color 0.2s;
    }
    .essay-input:focus { border-color: var(--nav-active); outline: none; background: #fafcff; }

    /* ============================
       4. SECURITY & OVERLAY
       ============================ */
    .exam-blur-mode {
      filter: blur(15px) grayscale(100%) brightness(0.7) !important;
      pointer-events: none !important;
      user-select: none !important;
    }

    .nav-controls {
      display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;
    }

    #submitBtnContainer { display: none; animation: popIn 0.3s ease; }
    @keyframes popIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    /* ALERT OVERLAY STYLE */
    .security-overlay {
      position: fixed; inset: 0; background: rgba(220, 53, 69, 0.95); z-index: 999999; display: none; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; backdrop-filter: blur(8px);
    }
    .security-overlay.active { display: flex; animation: slideDown 0.3s ease; }
    @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .warning-content {
      background: white; padding: 40px 50px; border-radius: 16px; max-width: 600px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4); text-align: left;
    }
    .warning-icon { font-size: 50px; color: var(--danger-color); margin-bottom: 15px; display: block; text-align: center; }
    .warning-title { font-size: 28px; font-weight: 800; color: var(--danger-color); margin-bottom: 10px; text-align: center; }
    .warning-count { background: var(--danger-color); color: white; padding: 5px 20px; border-radius: 20px; font-size: 18px; font-weight: bold; margin-bottom: 20px; display: inline-block; width: 100%; text-align: center; }
    .warning-detail { background: #fff0f1; color: #c92a2a; padding: 15px; border-radius: 8px; font-size: 15px; margin-bottom: 20px; border-left: 4px solid var(--danger-color); }
    
    .warning-toast {
      position: fixed; top: 20px; right: 20px; background: #2d3436; color: white; padding: 12px 20px; border-radius: 8px; z-index: 99999; box-shadow: 0 5px 15px rgba(0,0,0,0.2); display: none; align-items: center; gap: 10px; animation: slideLeft 0.3s ease;
    }
    @keyframes slideLeft { from { transform: translateX(100%); } to { transform: translateX(0); } }

    .countdown-timer {
      position: fixed; top: 20px; left: 20px; background: #2d3436; color: white; padding: 10px 20px; border-radius: 30px; font-size: 16px; font-weight: bold; z-index: 100; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: none; align-items: center; gap: 10px;
    }
    .countdown-timer i { color: #ffdd57; }
    .fullscreen-lock-warning { position: fixed; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(220, 53, 69, 0.2), transparent); padding: 30px 20px 10px; text-align: center; z-index: 9998; pointer-events: none; display: none; }
    .fullscreen-lock-warning.active { display: block; }
    
    .upload-jawaban-box { margin-top: 10px; padding: 15px; background: #f8fbff; border: 1px dashed #3273dc; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; }
    
    /* Responsive */
    @media (max-width: 768px) {
      .columns { display: flex; flex-direction: column; }
      .column.is-3 { width: 100%; }
      .soal-navigator { position: relative; top: 0; max-height: none; margin-top: 20px; }
      .nav-grid { grid-template-columns: repeat(8, 1fr); gap: 5px; }
      .nav-controls { flex-direction: column-reverse; gap: 15px; }
      .nav-controls button { width: 100%; }
      .upload-jawaban-box{flex-direction:column;}
    }

    /* Soal Container Logic */
    .soal-container { display: none; animation: fadeInQuestion 0.3s ease; }
    .soal-container.active { display: block; }
    @keyframes fadeInQuestion { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .countdown-timer.active {
    background: #ff4757;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

  </style>
</head>
<body>
  
  <div class="countdown-timer" id="countdownTimerDisplay">
    <i class="fas fa-shield-alt"></i> <span id="countdownNumber">10</span>s
  </div>

  <div class="container" style="padding-top: 30px; padding-bottom: 50px;">
    <div class="columns">
      
      <!-- KOLOM KIRI: NAVIGASI -->
      <div class="column is-3">
        <div class="card soal-navigator">
          <div class="card-header" style="background: white; border-bottom: none; padding-bottom: 0;">
            <p class="card-header-title" style="font-size: 1.1rem;">
              <i class="fas fa-th-list" style="margin-right: 10px; color: #3273dc;"></i> Navigasi
            </p>
          </div>
          <div class="card-content">
            <div class="info-panel">
              <p><strong>{{$ire->nama}}</strong></p>
              <p><i class="fas fa-graduation-cap"></i> Kelas: {{$sis->kelas->nama_kelas}}</p>
              <p><i class="fas fa-book"></i> {{$uji->mapels->nama_mapel}}</p>
              <p style="margin-top: 5px; border-top: 1px solid #dbeafe; padding-top: 5px;">
                  @if($uji->mode === "praktik")
            Deadline: <span id="deadlineDisplay" class="has-text-weight-bold has-text-danger"></span><br>
            Sisa Waktu: <span id="sisaWaktuDisplay" class="has-text-weight-bold"></span>
        @else
            Waktu: <span id="display" class="has-text-weight-bold has-text-danger">00:00</span>
        @endif
                
              </p>
            </div>
            
            <div class="nav-grid" id="soalNavGrid">
              @foreach($soal as $index => $s)
                @php
                  $tipe = $s->tipe ?? 'pg';
                  $extraClass = $tipe == 'essay' || $tipe == 'upload' ? 'essay' : '';
                  $icon = ($tipe == 'essay' || $tipe == 'upload') ? '<i class="fas fa-pen"></i>' : '';
                @endphp
                <button class="nav-btn {{$extraClass}}" data-soal-id="{{$s->id}}" data-index="{{$index}}" data-tipe="{{$tipe}}">
                  {{$index + 1}} {!! $icon !!}
                </button>
              @endforeach
            </div>
            
            <div class="legend">
              <div class="legend-item"><div class="legend-color" style="background: var(--nav-active); border:none;"></div><span>Aktif</span></div>
              <div class="legend-item"><div class="legend-color" style="background: var(--nav-answered); border:none;"></div><span>Jawab</span></div>
              <div class="legend-item"><div class="legend-color" style="background: var(--essay-color); border: 1px solid #eec;"></div><span>Uraian/Praktik</span></div>
            </div>
            
            <div style="margin-top: 25px;">
              <progress class="progress is-primary" id="progressBar" value="0" max="100">0%</progress>
              <p class="has-text-centered is-size-7" style="margin-top: 5px; color:#666;" id="progressText">0/{{count($soal)}} soal terjawab</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- KOLOM KANAN: KONTEN SOAL -->
      <div class="column is-9">
        <div class="card" style="border: none; box-shadow: none; background: transparent;">
          <div class="card-header" style="background: white; border-radius: 8px; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <p class="card-header-title" style="font-size: 1.2rem; color: #333;">
              <i class="fas fa-file-alt" style="margin-right: 10px; color: #aaa;"></i>
              <span id="soalHeader">Soal 1 dari {{count($soal)}}</span>
            </p>
          </div>
          
          <div class="card-content" style="padding-top: 10px;">
            <!-- enctype="multipart/form-data" WAJIB -->
            <form action="{{route('siswa.save')}}" method="post" id="form" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="ujian_id" id="ujian_id" value="{{$uji->id}}">
              <input type="hidden" name="siswa_id" id="siswa_id" value="{{$sis->id_siswa}}">
              
              <div id="soalContainer">
                @foreach($soal as $index => $s)
                  @php
                    $tipe = $s->tipe ?? 'pg';
                  @endphp
                  <div class="soal-container" data-soal-id="{{$s->id}}" data-index="{{$index}}" data-tipe="{{$tipe}}">
                    <div class="soal-card">
                      <div class="soal-header">
                        <span class="soal-nomor">Soal {{$index + 1}}</span>
                        <span class="soal-tipe">
                          @if($tipe == 'essay') <i class="fas fa-pen-fancy"></i> Uraian 
                          @elseif($tipe == 'upload') <i class="fas fa-cloud-upload-alt"></i> Upload Praktik 
                          @elseif($tipe == 'av') <i class="fas fa-photo-video"></i> Audio Visual 
                          @else <i class="fas fa-list-ul"></i> Pilihan Ganda 
                          @endif
                        </span>
                      </div>
                      
                      <h5 class="subtitle is-5" style="margin-bottom: 15px;">{!! $s->soal !!}</h5>
                      
                      @if(!empty($s->gambar))
                      <div class="soal-gambar">
                          <img src="{{ asset('storage/' . $s->gambar) }}" alt="Gambar Soal">
                      </div>
                      @endif                   
                      
                      @if($tipe == 'pg')
                        <div class="opsi-container">
                          <label class="opsi-item"><input type="radio" name="jawaban[{{$s->id}}]" value="a"><span class="opsi-label">A</span><span>{!! $s->opsi_a !!}</span></label>
                          <label class="opsi-item"><input type="radio" name="jawaban[{{$s->id}}]" value="b"><span class="opsi-label">B</span><span>{!! $s->opsi_b !!}</span></label>
                          <label class="opsi-item"><input type="radio" name="jawaban[{{$s->id}}]" value="c"><span class="opsi-label">C</span><span>{!! $s->opsi_c !!}</span></label>
                          @if(!empty($s->opsi_d))<label class="opsi-item"><input type="radio" name="jawaban[{{$s->id}}]" value="d"><span class="opsi-label">D</span><span>{!! $s->opsi_d !!}</span></label>@endif
                          @if(!empty($s->opsi_e))<label class="opsi-item"><input type="radio" name="jawaban[{{$s->id}}]" value="e"><span class="opsi-label">E</span><span>{!! $s->opsi_e !!}</span></label>@endif
                        </div>
                      @elseif($tipe == 'upload')
                        <!-- MODE PRAKTIK -->
                        <div class="essay-container">
                            <div class="essay-label" style="background: #48c774; color: white;"><i class="fas fa-cloud-upload-alt"></i> UPLOAD TUGAS PRAKTIK</div>
                            <div class="upload-jawaban-box" style="margin-top: 20px;">
                                <div style="flex-grow:1;">
                                    <i class="fas fa-file-upload" style="color:#48c774; margin-right:10px;"></i>
                                    <div style="font-size:0.85rem; color:#555;">
                                        <strong>Upload File Jawaban</strong>
                                        <div style="font-size:0.8rem; color:#888;">Format: JPG, PNG, PDF, DOC, ZIP (Max 10MB)</div>
                                    </div>
                                </div>
                                <div class="file has-name is-success">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="file_jawaban[{{$s->id}}]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip" onchange="updateFileName(this)">
                                        <span class="file-cta"><span class="file-icon"><i class="fas fa-upload"></i></span><span class="file-label">Pilih File...</span></span>
                                        <span class="file-name" id="file-name-{{$s->id}}">Belum ada file</span>
                                    </label>
                                </div>
                            </div>
                            <textarea style="display:none;" name="jawaban[{{$s->id}}]" class="jawaban-text">File Uploaded</textarea>
                        </div>
                      @elseif($tipe == 'av')
    <!-- MODE AV / MEDIA -->
    @if(!empty($s->media_file))
        <div class="soal-gambar">
            @if(strpos($s->media_file, '.mp4') !== false || strpos($s->media_file, '.webm') !== false)
                <video controls style="max-width:100%; border-radius:10px;" preload="metadata">
                    <source src="{{ asset('storage/' . $s->media_file) }}" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>
            @elseif(strpos($s->media_file, '.mp3') !== false || strpos($s->media_file, '.wav') !== false)
                <audio controls style="width:100%;" preload="metadata">
                    <source src="{{ asset('storage/' . $s->media_file) }}" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            @else
                <img src="{{ asset('storage/' . $s->media_file) }}" alt="Media Soal">
            @endif
        </div>
    @endif

    @if(!empty($s->media_url))
        <div class="soal-gambar">
            @if(strpos($s->media_url, 'youtube.com') !== false || strpos($s->media_url, 'youtu.be') !== false)
                @php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $s->media_url, $matches);
                    $youtubeId = $matches[1] ?? '';
                @endphp
                @if($youtubeId)
                    <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                        <iframe style="position:absolute; top:0; left:0; width:100%; height:100%;" src="https://www.youtube.com/embed/{{$youtubeId}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            @else
                <a href="{{$s->media_url}}" target="_blank" class="button is-link"><i class="fas fa-play"></i> &nbsp;Buka Media</a>
            @endif
        </div>
    @endif
    
    {{-- ========== CEK APAKAH ADA OPSI (PILIHAN GANDA) ========== --}}
    @php
        $hasOptions = (!empty($s->opsi_a) || !empty($s->opsi_b) || !empty($s->opsi_c) || !empty($s->opsi_d) || !empty($s->opsi_e));
    @endphp
    
    @if($hasOptions)
        {{-- Mode Pilihan Ganda - Tampilkan opsi A/B/C/D/E --}}
        <div class="opsi-container" style="margin-top: 20px;">
            @if(!empty($s->opsi_a))
            <label class="opsi-item">
                <input type="radio" name="jawaban[{{$s->id}}]" value="a">
                <span class="opsi-label">A</span>
                <span>{!! $s->opsi_a !!}</span>
            </label>
            @endif
            @if(!empty($s->opsi_b))
            <label class="opsi-item">
                <input type="radio" name="jawaban[{{$s->id}}]" value="b">
                <span class="opsi-label">B</span>
                <span>{!! $s->opsi_b !!}</span>
            </label>
            @endif
            @if(!empty($s->opsi_c))
            <label class="opsi-item">
                <input type="radio" name="jawaban[{{$s->id}}]" value="c">
                <span class="opsi-label">C</span>
                <span>{!! $s->opsi_c !!}</span>
            </label>
            @endif
            @if(!empty($s->opsi_d))
            <label class="opsi-item">
                <input type="radio" name="jawaban[{{$s->id}}]" value="d">
                <span class="opsi-label">D</span>
                <span>{!! $s->opsi_d !!}</span>
            </label>
            @endif
            @if(!empty($s->opsi_e))
            <label class="opsi-item">
                <input type="radio" name="jawaban[{{$s->id}}]" value="e">
                <span class="opsi-label">E</span>
                <span>{!! $s->opsi_e !!}</span>
            </label>
            @endif
        </div>
        
        {{-- Label informasi --}}
        <div class="essay-label" style="background: #8b5cf6; margin-top: 15px;">
            <i class="fas fa-headphones"></i> Pilih jawaban berdasarkan media di atas
        </div>
    @else
        {{-- Mode Essay - Tampilkan textarea --}}
        <div class="essay-container" style="margin-top: 20px;">
            <div class="essay-label"><i class="fas fa-headphones"></i> JAWABAN BERDASARKAN MEDIA</div>
            <textarea class="textarea essay-input jawaban-text" name="jawaban[{{$s->id}}]" placeholder="Tulis jawaban Anda berdasarkan audio/video di atas..." rows="5"></textarea>
        </div>
    @endif
                      @else
                        <!-- DEFAULT ESSAY -->
                        <div class="essay-container">
                          <div class="essay-label"><i class="fas fa-align-left"></i> KETIK JAWABAN ANDA</div>
                          <textarea class="textarea essay-input jawaban-text" name="jawaban[{{$s->id}}]" placeholder="Tulis jawaban essay di sini..." rows="5"></textarea>
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              
              <!-- Navigasi Bawah -->
              <div class="nav-controls">
                <button type="button" class="button is-light" id="prevBtn" disabled>
                    <span class="icon"><i class="fas fa-chevron-left"></i></span>
                    <span>Sebelumnya</span>
                </button>

                <div id="nextBtnContainer">
                  <button type="button" class="button is-primary" id="nextBtn">
                    <span>Berikutnya</span>
                    <span class="icon"><i class="fas fa-chevron-right"></i></span>
                  </button>
                </div>

                <div id="submitBtnContainer">
                  <button type="submit" class="button is-danger is-medium" id="submitBtn">
                    <span class="icon"><i class="fas fa-paper-plane"></i></span>
                    <span>Kirim Jawaban (Selesai)</span>
                  </button>
                </div>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Security Warning Overlay -->
  <div class="security-overlay" id="securityOverlay">
    <div class="warning-content">
      <div class="warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
      <div class="warning-count" id="violationCountDisplay">PELANGGARAN #1</div>
      <h1 class="warning-title">PELANGGARAN TERDETEKSI!</h1>
      <p class="subtitle is-5" style="text-align: center; margin-bottom: 20px;" id="violationMessage">Anda terdeteksi keluar dari mode fullscreen.</p>
      <div class="warning-detail">
        <strong><i class="fas fa-info-circle"></i> Perhatian:</strong>
        <ul style="margin-top: 10px; margin-left: 20px;">
          <li>Dilarang keluar mode Fullscreen.</li>
          <li>Dilarang membuka tab/aplikasi lain.</li>
          <li>Dilarang melakukan Copy/Paste (kecuali di textarea).</li>
        </ul>
        <p style="margin-top: 10px; font-size: 14px;">Jika pelanggaran melebihi batas, ujian akan otomatis terkunci.</p>
      </div>
      <p style="text-align: center; color: #666; margin-bottom: 25px;" id="warningTimer">Anda dapat melanjutkan dalam: <strong><span id="countdownTimer">10</span> detik</strong></p>
      <button class="button is-danger is-fullwidth" onclick="continueExam()" style="padding: 15px; font-size: 18px;">
        <i class="fas fa-check-circle"></i> LANJUTKAN UJIAN
      </button>
    </div>
  </div>

  <div class="warning-toast" id="warningToast">
    <i class="fas fa-bell" style="color: #ffdd57; font-size: 20px;"></i>
    <span id="toastMessage">Peringatan!</span>
  </div>

  <div class="fullscreen-lock-warning" id="fullscreenLockWarning">
    <span style="color: #dc3545; font-weight: bold; font-size: 18px; background: rgba(255,255,255,0.9); padding:5px 15px; border-radius: 20px; display: inline-block;">
      <i class="fas fa-lock"></i> Mode Fullscreen Wajib Aktif
    </span>
  </div>

<script>
   @php 
    $mulai = \Carbon\Carbon::parse(now());
    $selesai = \Carbon\Carbon::parse($uji->jadwal->waktu_selesai);
    $durasiMenit = $mulai->diffInMinutes($selesai);
  @endphp

// ========== SISTEM UJIAN FINAL (CBT & PRAKTIK) ==========
(function() {
    'use strict';
    
    // KONFIGURASI
    const MODE_UJIAN = "{{ $uji->mode ?? 'cbt' }}";
    const IS_CBT = (MODE_UJIAN === 'cbt');
    
    const SERVER_START_TIME = "{{ $uji->mulai_pengerjaan ?? date('Y-m-d H:i:s') }}";
    const DURASI_MENIT = {{ $durasiMenit }};
    const UJIAN_ID = {{$uji->id ?? 0}};
    const SISWA_ID = {{$sis->id_siswa ?? 0}};
    
    const START_TIME_MS = new Date(SERVER_START_TIME).getTime();
    const END_TIME_MS = START_TIME_MS + (DURASI_MENIT * 60 * 1000);
    const TOTAL_SOAL = {{count($soal ?? [])}};
    const MAX_VIOLATION = 3; 
    const COOLDOWN_TIME = 10000; 
    const VIOLATION_COUNTDOWN = 10; 
    
    let isSubmitting = false;
    let currentSoalIndex = 0;
    let timerInterval = null;
    let countdownInterval = null;
    
    //let violationCount = 0;
    //let lastViolationTime = 0;
    let isDisqualified = false; 
    let isInCountdown = false;
    
    let fullscreenLocked = true;
    let forceFullscreenInterval = null;
    let jawabanState = {};
    let beforeUnloadHandler = null;
    
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // DOM ELEMENTS
    const displayTimer = document.getElementById("display");
    const form = document.getElementById("form");
    const soalContainers = document.querySelectorAll('.soal-container');
    const navButtons = document.querySelectorAll('#soalNavGrid .nav-btn');
    const prevBtn = document.getElementById('prevBtn');
    let isForcedSubmit = false;  // ← TAMBAHKAN BARIS INI
    const nextBtn = document.getElementById('nextBtn');
    const soalHeader = document.getElementById('soalHeader');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const securityOverlay = document.getElementById('securityOverlay');
    const warningMessage = document.getElementById('violationMessage');
    const violationCountDisplay = document.getElementById('violationCountDisplay');
    const countdownTimerElement = document.getElementById('countdownTimer');
    const countdownNumber = document.getElementById('countdownNumber');
    const countdownTimerDisplay = document.getElementById('countdownTimerDisplay');
    const toastMessage = document.getElementById('toastMessage');
    const warningToast = document.getElementById('warningToast');
    const fullscreenLockWarning = document.getElementById('fullscreenLockWarning');
    const nextBtnContainer = document.getElementById('nextBtnContainer');
    const submitBtnContainer = document.getElementById('submitBtnContainer');
    let isViolationCooldown = false;  // TAMBAHKAN INI
let cooldownTimeout = null;   
    const VIOLATIONS = {
     DEVTOOLS: 'DEVTOOLS',
    EXIT_FULLSCREEN: 'EXIT_FULLSCREEN',
    SWITCH_TAB: 'SWITCH_TAB',
    SPLIT_SCREEN: 'SPLIT_SCREEN',
    COPY: 'COPY',
    CUT : 'CUT',
    PASTE: 'PASTE',
    RIGHT_CLICK: 'RIGHT_CLICK'
};
const VIOLATION_LABELS = {
    DEVTOOLS:'membuka Developer Tools/shortcut',
    EXIT_FULLSCREEN: 'Keluar dari mode fullscreen',
    SWITCH_TAB: 'Berpindah tab/browser',
    SPLIT_SCREEN: 'Membuka split screen/aplikasi lain',
    COPY: 'Menyalin soal',
    PASTE: 'Menempel jawaban',
    CUT : 'Potong Area Soal',
    RIGHT_CLICK: 'Klik kanan terdeteksi'
};
const DEBUG_MODE = false;
// Tambahkan ini setelah deklarasi variabel lainnya
//let lastViolationType = '';
let lastViolationTimestamp = 0;
let fullscreenDebounceTimer = null;
    // HELPER UPDATE FILE NAME
    let deadlineTimestamp = null;
if (!IS_CBT && "{{ $uji->jadwal->waktu_selesai ?? '' }}") {
    deadlineTimestamp = new Date("{{ $uji->jadwal->waktu_selesai ?? '' }}").getTime();
}

// Tambah fungsi untuk update timer praktik
function updatePraktikTimer() {
    if (!deadlineTimestamp) return;
    
    const now = Date.now();
    let remaining = deadlineTimestamp - now;
    
    const deadlineDisplay = document.getElementById('deadlineDisplay');
    const sisaWaktuDisplay = document.getElementById('sisaWaktuDisplay');
    
    if (!deadlineDisplay || !sisaWaktuDisplay) return;
    
    // Tampilkan deadline
    const deadlineDate = new Date(deadlineTimestamp);
    deadlineDisplay.innerText = deadlineDate.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    if (remaining <= 0) {
        sisaWaktuDisplay.innerText = 'WAKTU HABIS!';
        sisaWaktuDisplay.classList.add('has-text-danger');
        
        // Disable semua input dan button
        document.querySelectorAll('input, textarea, button').forEach(el => {
            if (el.type !== 'submit') el.disabled = true;
        });
        
        // Tampilkan warning
        showWarningToast('⏰ Deadline telah lewat! Anda tidak dapat mengumpulkan tugas.');
        return;
    }
    
    const hours = Math.floor(remaining / (1000 * 60 * 60));
    const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
    
    let timeText = '';
    if (hours > 0) {
        timeText = `${hours} jam ${minutes} menit ${seconds} detik`;
        sisaWaktuDisplay.classList.remove('has-text-danger');
        sisaWaktuDisplay.classList.add('has-text-warning');
    } else if (minutes > 0) {
        timeText = `${minutes} menit ${seconds} detik`;
        if (minutes <= 5) {
            sisaWaktuDisplay.classList.add('has-text-danger');
        } else {
            sisaWaktuDisplay.classList.remove('has-text-danger');
            sisaWaktuDisplay.classList.add('has-text-warning');
        }
    } else {
        timeText = `${seconds} detik`;
        sisaWaktuDisplay.classList.add('has-text-danger');
        
        if (seconds <= 10) {
            sisaWaktuDisplay.classList.add('blink-text');
        }
    }
    
    sisaWaktuDisplay.innerText = timeText;
    
    // Auto submit jika deadline habis
    if (remaining <= 0) {
        setTimeout(() => {
            if (form && !isSubmitting) {
                alert('⏰ Waktu telah habis! Jawaban akan disubmit otomatis.');
                form.submit();
            }
        }, 1000);
    }
}

// Tambah CSS blink untuk efek kedip
const style = document.createElement('style');
style.textContent = `
    .blink-text {
        animation: blink 1s infinite;
    }
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
`;
document.head.appendChild(style);
    window.updateFileName = function(input) {
        const fileName = input.files[0]?.name || 'Belum ada file';
        const fileSpan = input.closest('.file-label')?.querySelector('.file-name');
        if (fileSpan) fileSpan.innerText = fileName;
        if (typeof updateJawabanStatus === 'function') {
            setTimeout(() => updateJawabanStatus(), 100);
        }
    }

    // SECURITY HELPERS
document.addEventListener('fullscreenchange', () => {

    const active = document.activeElement;

    // fullscreen video youtube
    if (
        active &&
        active.tagName === 'IFRAME'
    ) {
        return;
    }

    if (!document.fullscreenElement) {
        handleViolation(VIOLATIONS.EXIT_FULLSCREEN);
    }

});
// ========== FUNGSI PENTERJEMAH PELANGGARAN (MANUSIAWI) ==========
function getHumanReadableViolation(jenis) {
    const mapping = {
        'EXIT_FULLSCREEN': 'Keluar dari mode layar penuh saat ujian',
        'SWITCH_TAB': 'Berpindah ke tab atau aplikasi lain selama ujian',
        'SPLIT_SCREEN': 'Membuka aplikasi lain atau split screen',
        'DEVTOOLS': 'Mencoba membuka alat pengembang (Developer Tools)',
        'COPY': 'Mencoba menyalin konten soal',
        'PASTE': 'Mencoba menempelkan jawaban dari luar',
        'CUT': 'Mencoba memotong konten soal',
        'RIGHT_CLICK': 'Mengklik kanan pada halaman ujian',
        'CONTEXT_MENU': 'Membuka menu konteks (klik kanan)',
        'BLUR': 'Halaman kehilangan fokus (berpindah ke jendela lain)'
    };
    
    // Jika jenis pelanggaran ditemukan di mapping, kembalikan versi manusiawi
    if (mapping[jenis]) {
        return mapping[jenis];
    }
    
    // Fallback: jika tidak ada mapping, ubah teks menjadi lebih rapi (hilangkan underscore, lowercase)
    return jenis.replace(/_/g, ' ').toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
}

// ========== FUNGSI PELAPORAN KE SERVER (YANG DIPERBAIKI) ==========
async function reportViolationToServer(jenis, detail = '') {
    try {
        // Ubah jenis pelanggaran menjadi teks manusiawi untuk dilaporkan
        const humanReadableMessage = getHumanReadableViolation(jenis);
        
        // Kirim ke server dengan format yang lebih bersahabat
        const response = await fetch("{{ route('siswa.violation') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ujian_id: UJIAN_ID,
                siswa_id: SISWA_ID,
                // KIRIM VERSI MANUSIAWI KE SERVER
                jenis_pelanggaran: humanReadableMessage, 
                // Tetap sertakan kode asli untuk keperluan internal jika perlu
                kode_asli: jenis,
                detail: detail || `Pelanggaran: ${humanReadableMessage}`,
                user_agent: navigator.userAgent,
                screen_resolution: `${window.innerWidth}x${window.innerHeight}`,
                timestamp: new Date().toISOString()
            })
        });

        const data = await response.json();
        console.log(`📝 Pelanggaran tercatat: ${humanReadableMessage}`);

        if (data.is_blocked) {
            forceLockScreen(data.block_message || 'Anda diblokir dari ujian');
        }

    } catch (err) {
        console.error('❌ Gagal mengirim pelanggaran:', err);
    }
}
async function checkBlockStatus() {
    try {
        
        
        const response = await fetch(`/check-block/${SISWA_ID}/${UJIAN_ID}`);
        
        
        const data = await response.json();
        const serverViolationCount = data.total_violations;
        if (data.is_blocked) {
            forceLockScreen(data.block_message || 'Anda telah diblokir!',serverViolationCount);
            return;
        }
        
        
    } catch (err) {
        
        console.error('Gagal cek block status:', err);
        return false;
    }
}
function isUserHoveringMedia() {
    if (!IS_CBT) return false;
    const active = document.activeElement;

    return (
        active &&
        (
            active.tagName === 'IFRAME' ||
            active.tagName === 'VIDEO' ||
            active.tagName === 'AUDIO'
        )
    );
}
function isMediaFocused() {

    const active = document.activeElement;

    if (!active) return false;

    return (
        active.tagName === 'IFRAME' ||
        active.tagName === 'VIDEO' ||
        active.tagName === 'AUDIO'
    );
}

    


    
    const applyBlur = () => document.body.classList.add('exam-blur-mode');
    const removeBlur = () => document.body.classList.remove('exam-blur-mode');
    
    // PELANGGARAN
       // PELANGGARAN (REVISED - Tidak Blocking UI)
       

// ========== FUNGSI KIRIM PELANGGARAN KE SERVER ==========
async function handleViolation(jenisPelanggaran) {
    const now = Date.now();
    
    // CEK DOUBLE VIOLATION DALAM 1 DETIK
    if (window._lastViolationType === jenisPelanggaran && (now - window._lastViolationTime) < 1000) {
        console.log('⚠️ Double violation detected, ignored');
        return;
    }
    
    window._lastViolationType = jenisPelanggaran;
    window._lastViolationTime = now;
    
    if (!IS_CBT || isDisqualified || isSubmitting || isInCountdown) return;
    
    // CEK COOLDOWN 10 DETIK
    if (window._isViolationCooldown) {
        console.log(`⏳ Cooldown aktif, pelanggaran "${jenisPelanggaran}" diabaikan`);
        return;
    }
    
    // CEK EXCEPTION MEDIA (Youtube, Video, Audio)
    if (jenisPelanggaran === 'CONTEXT_MENU' || jenisPelanggaran === 'EXIT_FULLSCREEN') {
        if (isUserHoveringMedia()) return;
    }
    
    // AKTIFKAN COOLDOWN
    window._isViolationCooldown = true;
    if (window._cooldownTimeout) clearTimeout(window._cooldownTimeout);
    window._cooldownTimeout = setTimeout(() => {
        window._isViolationCooldown = false;
        console.log('✅ Cooldown selesai');
    }, 10000);
    
    // KIRIM KE SERVER
    try {
        const humanReadableMessage = getHumanReadableViolation(jenisPelanggaran);
        
        const response = await fetch("{{ route('siswa.violation') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ujian_id: UJIAN_ID,
                siswa_id: SISWA_ID,
                jenis_pelanggaran: humanReadableMessage,
                kode_asli: jenisPelanggaran,
                detail: `Pelanggaran: ${humanReadableMessage}`,
                timestamp: new Date().toISOString()
            })
        });
        
        const data = await response.json();
        
        const isBlocked = data.blocked || false;
        const totalViolations = data.violation_count || 0;
        const blockMessage = data.message || 'Anda telah diblokir oleh pengawas!';
        
        // TAMPILKAN TOAST
        showWarningToast(`⚠️ ${humanReadableMessage} (${totalViolations}/3)`, 8000);
        
        // CEK APAKAH LANGSUNG DIBLOKIR
        if (isBlocked) {
            forceLockScreen(blockMessage, totalViolations);
            return;
        }
        
        // ========== PERBAIKAN: TAMPILKAN OVERLAY SETIAP PELANGGARAN ==========
        // Tampilkan overlay untuk setiap pelanggaran (bukan hanya yang ke-2)
        applyBlur();
        showSecurityOverlay(jenisPelanggaran, totalViolations);
        startCountdownTimer();
        
    } catch (err) {
        console.error('❌ Gagal kirim pelanggaran:', err);
        showWarningToast(`⚠️ Gagal mencatat pelanggaran`, 5000);
    }
}
// ========== FUNGSI CEK STATUS BLOKIR LIVE DARI SERVER ==========
async function getViolationStatus() {
    try {
        const response = await fetch(`/check-block/${SISWA_ID}/${UJIAN_ID}`);
        const data = await response.json();
        
        console.log('📡 Status dari server:', data);
        
        // ========== KONVERSI total_violations KE NUMBER ==========
        let totalViolations = 0;
        if (data.violation_count !== undefined && data.violation_count !== null) {
            totalViolations = parseInt(data.violation_count) || 0;
        } else if (data.total_violations !== undefined && data.total_violations !== null) {
            totalViolations = parseInt(data.total_violations) || 0;
        }
        
        return {
            is_blocked: data.blocked === true || data.is_blocked === true || false,
            total_violations: totalViolations,
            block_message: data.message || data.block_message || ''
        };
    } catch (err) {
        console.error('❌ Gagal cek status blokir:', err);
        return { is_blocked: false, total_violations: 0 };
    }
}
// ========== PENGECEKAN PERIODIK KE SERVER ==========
let _blockCheckInterval = null;

function startBlockStatusChecker() {
    if (_blockCheckInterval) clearInterval(_blockCheckInterval);
    
    _blockCheckInterval = setInterval(async () => {
        if (isSubmitting) return;
        
        try {
            const status = await getViolationStatus();
            
            // Jika server bilang diblokir, tapi frontend belum
            if (status.is_blocked && !isDisqualified) {
                forceLockScreen(status.block_message || 'Ujian telah diblokir oleh pengawas!', status.total_violations);
            }
            
            // Jika frontend diblokir, tapi server bilang tidak (di-unlock)
            if (isDisqualified && !status.is_blocked) {
                showWarningToast('✅ Ujian telah dibuka kembali oleh pengawas. Halaman akan reload.', 5000);
                setTimeout(() => location.reload(), 3000);
            }
            
        } catch (err) {
            console.error('Gagal cek status periodik:', err);
        }
    }, 10000); // Cek setiap 10 detik
}

function stopBlockStatusChecker() {
    if (_blockCheckInterval) {
        clearInterval(_blockCheckInterval);
        _blockCheckInterval = null;
    }
}
    
    function showSecurityOverlay(jenis, count) {
        isInCountdown = true;
        if (violationCountDisplay) violationCountDisplay.innerText = `PELANGGARAN #${count}`;
        if (warningMessage) {
            const map = {
                'EXIT_FULLSCREEN': 'Anda keluar dari mode fullscreen.',
                'SWITCH_TAB': 'Anda berpindah ke tab lain.',
                'Buka Aplikasi Lain': 'Anda membuka aplikasi lain.',
                'COPY_CONTENT': 'Anda melakukan copy.',
                'PASTE_CONTENT': 'Anda melakukan paste.',
                'CONTEXT_MENU': 'Anda melakukan klik kanan.',
                'CUT_CONTENT': 'Anda melakukan cut.'
            };
            warningMessage.innerText =
    VIOLATION_LABELS[jenis] || jenis;
        }
        if (securityOverlay) securityOverlay.classList.add('active');
    }
    
    function startCountdownTimer() {
        stopCountdownTimer();
        let remaining = VIOLATION_COUNTDOWN;
        if (countdownNumber) countdownNumber.innerText = remaining;
        if (countdownTimerDisplay) countdownTimerDisplay.classList.add('active');
        
        countdownInterval = setInterval(() => {
            remaining--;
            if (countdownNumber) countdownNumber.innerText = remaining;
            if (countdownTimerElement) countdownTimerElement.innerText = remaining;
            if (remaining <= 0) { stopCountdownTimer(); if (!isDisqualified) hideSecurityOverlay(); }
        }, 1000);
    }
    
    function stopCountdownTimer() {
        if (countdownInterval) { clearInterval(countdownInterval); countdownInterval = null; }
        if (countdownTimerDisplay) countdownTimerDisplay.classList.remove('active');
    }
    
    function hideSecurityOverlay() {
        isInCountdown = false;
        if (securityOverlay) securityOverlay.classList.remove('active');
        if (!isMobile && fullscreenLocked && !isFullscreen()) enableFullscreen();
    }
    
    window.continueExam = function() {
        hideSecurityOverlay();
        stopCountdownTimer();
    };
    
   function showWarningToast(message, duration = 10000) {
    if (toastMessage) toastMessage.innerHTML = message;
    if (warningToast) {
        warningToast.style.display = 'flex';
        warningToast.classList.add('active');
    }
    
    // Hapus timeout sebelumnya
    if (window.toastHideTimeout) {
        clearTimeout(window.toastHideTimeout);
    }
    
    // Sembunyikan setelah duration
    window.toastHideTimeout = setTimeout(() => { 
        if (warningToast) {
            warningToast.style.display = 'none';
            warningToast.classList.remove('active');
        }
    }, duration);
}
    
    function updateTimerDisplay() {
        if(!displayTimer) return;
        const now = Date.now();
        let remaining = END_TIME_MS - now;
        if (remaining <= 0) { submitFormOtomatis(); return; }
        const menit = Math.floor(remaining / 60000);
        const detik = Math.floor((remaining % 60000) / 1000);
        displayTimer.innerText = `${menit.toString().padStart(2,'0')}:${detik.toString().padStart(2,'0')}`;
        if(remaining <= 300000) {
            displayTimer.classList.add('has-text-danger', 'has-text-weight-bold');
            if(remaining <= 300000 && remaining > 299000) { 
                showWarningToast('⏰ Waktu tersisa 5 menit!');
            }
        }
    }
    
    function startTimer() {
        if(timerInterval) clearInterval(timerInterval);
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            const now = Date.now();
            if (now >= END_TIME_MS) { clearInterval(timerInterval); submitFormOtomatis(); }
            else updateTimerDisplay();
        }, 1000);
    }
    
    function stopTimer() { if(timerInterval) { clearInterval(timerInterval); timerInterval = null; } }
    
    function isFullscreen() { return !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement); }
    
    function enableFullscreen() {
        if (isMobile) return;
        const elem = document.documentElement;
        const request = elem.requestFullscreen || elem.webkitRequestFullscreen || elem.msRequestFullscreen;
        if(request) request.call(elem).catch(() => console.log('Fullscreen tidak diizinkan'));
    }
    
    // HANYA JALANKAN KEAMANAN KETAT JIKA CBT
    function initCBTSecurity() {
        if (isMobile) return;
        
        document.addEventListener('keydown', (e) => {
            if (isSubmitting || isDisqualified || isInCountdown) return;
            
            if (!DEBUG_MODE) {
    const blockedKeys = ['Escape', 'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'Control', 'Alt', 'Tab', 'Meta'];

    if (blockedKeys.includes(e.key)) {
        e.preventDefault();
        return false;
    }
}
            
            // F12 (DevTools) DIBLOKIR DI SINI, TAPI ANDA BISA MENGHAPUSNYA JIKA INGIN DIBUKA
            // Jika ingin F12 bisa dibuka, hapus 'F12' dari array blockedKeys di atas.
            
            if (e.ctrlKey || e.altKey || e.metaKey) {
                const dangerous = ['c', 'v', 'x', 'u', 's', 'p', 'a', 'f', 'i', 'j', 'l'];
                if (dangerous.includes(e.key.toLowerCase())) { 
                    e.preventDefault(); 

                    if (!DEBUG_MODE) {
    handleViolation(VIOLATIONS.DEVTOOLS);
}
                    return false; 
                }
            }
        });
        
        document.addEventListener('fullscreenchange', () => {
            if (!isSubmitting && !isDisqualified && !isInCountdown && fullscreenLocked) {
                if (!isFullscreen()) {
                    handleViolation(VIOLATIONS.EXIT_FULLSCREEN);
                    if (!isDisqualified && !isInCountdown) {
                        setTimeout(() => { 
                            if (!isFullscreen() && !isDisqualified && !isInCountdown && !isUserHoveringMedia()) {
                                enableFullscreen();
                            }
                        }, 1000);
                    }
                } else {
                    if (fullscreenLockWarning) {
                        fullscreenLockWarning.classList.add('active');
                        setTimeout(() => fullscreenLockWarning.classList.remove('active'), 3000);
                    }
                }
            }
        });
        
        forceFullscreenInterval = setInterval(() => {
            if (!isSubmitting && !isDisqualified && !isInCountdown && fullscreenLocked && !isFullscreen()) {
                if (!isUserHoveringMedia()) {
                    enableFullscreen();
                }
            }
        }, 2000);
        
        setTimeout(enableFullscreen, 1000);
    }
    
    if (!isMobile && IS_CBT) {
        document.addEventListener('contextmenu', (e) => {
            if (isSubmitting || isDisqualified) return;
            // Izinkan klik kanan di iframe
            if (e.target.tagName === 'IFRAME' || e.target.closest('iframe')) return;
            e.preventDefault();
            handleViolation(VIOLATIONS.RIGHT_CLICK);
            return false;
        });
    }
    
    if (IS_CBT) {
        const handleVisibilityChange = () => {
            if (isSubmitting || isDisqualified || isInCountdown) return;
            if (document.hidden) {
                handleViolation(VIOLATIONS.SWITCH_TAB);
                applyBlur();
            } else {
                removeBlur();
            }
        };
        document.addEventListener('visibilitychange', handleVisibilityChange);
    }
    
    if (IS_CBT) {
        document.addEventListener('copy', (e) => {
            if (e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                handleViolation(VIOLATIONS.COPY);
                return false;
            }
        });
        
        document.addEventListener('paste', (e) => {
            if (isSubmitting || isDisqualified || isInCountdown) return;
            if (e.target.tagName === 'TEXTAREA') {
                handleViolation(VIOLATIONS.PASTE);
            } else {
                e.preventDefault();
                handleViolation(VIOLATIONS.PASTE);
                return false;
            }
        });
        
        document.addEventListener('cut', (e) => {
            if (e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                handleViolation(VIOLATIONS.CUT);
                return false;
            }
        });
    }
    
    if (IS_CBT && !isMobile) {
        let mouseLeftWindow = false;
        document.addEventListener('mouseleave', (e) => {

    const active = document.activeElement;

    if (
        active &&
        active.tagName === 'IFRAME'
    ) {
        return;
    }

    if (!isSubmitting && fullscreenLocked && !isInCountdown) {
        mouseLeftWindow = true;
        applyBlur();

        setTimeout(() => {
            if (
                mouseLeftWindow &&
                !isDisqualified &&
                !isInCountdown
            ) {
                handleViolation(VIOLATIONS.EXIT_FULLSCREEN);
                mouseLeftWindow = false;
            }
        }, 200);
    }
});

        document.addEventListener('mouseenter', () => { 
            mouseLeftWindow = false; 
            removeBlur(); 
        });
        
// Ganti event listener blur yang lama dengan yang ini
let ignoreBlurUntil = 0;

const handleWindowBlur = () => {
    if (isSubmitting || isDisqualified || isInCountdown) return;
    
    // ========== IGNORE BLUR DARI SWEETALERT ==========
    if (Date.now() < ignoreBlurUntil) {
        console.log('🔇 Blur ignored (SweetAlert active)');
        return;
    }
    
    // cek apakah focus ke iframe/video
    const active = document.activeElement;
    
    if (
        active &&
        (
            active.tagName === 'IFRAME' ||
            active.tagName === 'VIDEO' ||
            active.tagName === 'AUDIO'
        )
    ) {
        return;
    }
    
    if (!isUserHoveringMedia()) {
        applyBlur();
        handleViolation(VIOLATIONS.SPLIT_SCREEN);
    }
};

const handleWindowFocus = () => {
    removeBlur();
};

window.addEventListener('blur', () => {
    if (isSubmitting || isDisqualified || isInCountdown) return;
    
    setTimeout(() => {
        if (isMediaFocused()) return;
        if (document.hidden || !document.hasFocus()) {
            applyBlur();
            if (Date.now() >= ignoreBlurUntil) {
                handleViolation(VIOLATIONS.SPLIT_SCREEN);
            }
        }
    }, 300);
});
window.addEventListener('focus', handleWindowFocus);
    
    
    
    // JAWABAN
    function updateJawabanStatus() {
        let answeredCount = 0;
        jawabanState = {};
        navButtons.forEach(btn => btn.classList.remove('answered'));
        
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const match = radio.name.match(/\[(\d+)\]/);
            if(match) {
                const soalId = match[1];
                const btn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
                if(btn) { btn.classList.add('answered'); jawabanState[soalId] = true; }
            }
        });
        
        document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
            if(textarea.value.trim() !== '') {
                const match = textarea.name.match(/\[(\d+)\]/);
                if(match) {
                    const soalId = match[1];
                    const btn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
                    if(btn) { btn.classList.add('answered'); jawabanState[soalId] = true; }
                }
            }
        });
        
        document.querySelectorAll('input[type="file"]').forEach(fileInput => {
            if (fileInput.files && fileInput.files.length > 0) {
                const match = fileInput.name.match(/file_jawaban\[(\d+)\]/);
                if(match) {
                    const soalId = match[1];
                    const btn = document.querySelector(`#soalNavGrid .nav-btn[data-soal-id="${soalId}"]`);
                    if(btn) { btn.classList.add('answered'); jawabanState[soalId] = true; }
                }
            }
        });

        answeredCount = Object.keys(jawabanState).length;
        const percentage = (answeredCount / TOTAL_SOAL) * 100;
        
        if(progressBar) progressBar.value = percentage;
        if(progressText) progressText.innerText = `${answeredCount}/${TOTAL_SOAL} soal terjawab`;
        
        saveAnswersToLocalStorage();
        return answeredCount;
    }
    
    function saveAnswersToLocalStorage() {
        const answers = {};
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const match = radio.name.match(/\[(\d+)\]/);
            if(match) answers[match[1]] = radio.value;
        });
        document.querySelectorAll('textarea.jawaban-text').forEach(textarea => {
            const match = textarea.name.match(/\[(\d+)\]/);
            if(match && textarea.value.trim()) answers[match[1]] = textarea.value;
        });
        document.querySelectorAll('input[type="file"]').forEach(fileInput => {
             if (fileInput.files.length > 0) {
                 const match = fileInput.name.match(/file_jawaban\[(\d+)\]/);
                 if(match) answers[`file_${match[1]}`] = fileInput.files[0].name;
             }
        });
        localStorage.setItem(`ujian_jawaban_${UJIAN_ID}`, JSON.stringify(answers));
    }
    
    function loadAnswersFromLocalStorage() {
        const saved = localStorage.getItem(`ujian_jawaban_${UJIAN_ID}`);
        if(saved) {
            try {
                const answers = JSON.parse(saved);
                Object.keys(answers).forEach(key => {
                    const soalId = key.replace('file_', ''); 
                    const input = document.querySelector(`[name="jawaban[${soalId}]"]`);
                    if(input) {
                        if(input.type === 'radio') {
                            const radio = document.querySelector(`[name="jawaban[${soalId}]"][value="${answers[soalId]}"]`);
                            if(radio) radio.checked = true;
                        } else if(input.tagName === 'TEXTAREA') {
                            input.value = answers[soalId];
                        }
                    }
                    if(answers[`file_${soalId}`]) {
                        const fileInput = document.querySelector(`[name="file_jawaban[${soalId}]"]`);
                        const nameDisplay = fileInput ? fileInput.parentElement.querySelector('.file-name') : null;
                        if(nameDisplay) nameDisplay.innerText = answers[`file_${soalId}`];
                    }
                });
                updateJawabanStatus();
            } catch(e) {}
        }
    }
    
    function showSoal(index) {
        if(index < 0) index = 0;
        if(index >= TOTAL_SOAL) index = TOTAL_SOAL - 1;
        
        soalContainers.forEach(c => c.classList.remove('active'));
        if(soalContainers[index]) soalContainers[index].classList.add('active');
        
        if(soalHeader) soalHeader.innerText = `Soal ${index + 1} dari ${TOTAL_SOAL}`;
        if(prevBtn) prevBtn.disabled = (index === 0);
        
        const isLast = (index === TOTAL_SOAL - 1);
        if (nextBtnContainer) nextBtnContainer.style.display = isLast ? 'none' : 'inline-block';
        if(submitBtnContainer) submitBtnContainer.style.display = isLast ? 'inline-block' : 'none';
        
        navButtons.forEach((btn, i) => btn.classList.toggle('current', i === index));
        currentSoalIndex = index;
        localStorage.setItem(`ujian_current_soal_${UJIAN_ID}`, index);
    }
    
    function loadCurrentSoal() {
        const saved = localStorage.getItem(`ujian_current_soal_${UJIAN_ID}`);
        if(saved && !isNaN(parseInt(saved))) {
            const index = parseInt(saved);
            if(index >=0 && index < TOTAL_SOAL) { showSoal(index); return; }
        }
        showSoal(0);
    }
    
// ========== FORCE LOCK SCREEN ==========
// ========== FORCE LOCK SCREEN ==========
function forceLockScreen(reason, totalViolations = 0) {
    if (isDisqualified) return;
    
    stopTimer();
    stopCountdownTimer();
    stopBlockStatusChecker();
    
    isDisqualified = true;
    
    // SEMBUNYIKAN KONTEN
    const container = document.querySelector('.container');
    if (container) {
        container.style.opacity = '0.3';
        container.style.pointerEvents = 'none';
    }
    
    // HAPUS LOCK SCREEN LAMA JIKA ADA
    const existingLock = document.querySelector('.sweet-lock-overlay');
    if (existingLock) existingLock.remove();
    
    // BUAT LOCK SCREEN
    const lockDiv = document.createElement('div');
    lockDiv.className = "sweet-lock-overlay";
    lockDiv.style = "position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(8px); z-index: 9999999; display: flex; justify-content: center; align-items: center; padding: 20px;";
    lockDiv.innerHTML = `
        <div style="background: #fff; padding: 40px; border-radius: 12px; text-align: center; max-width: 500px;">
            <div style="width: 80px; height: 80px; background: #f27474; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px;">
                <i class="fas fa-times"></i>
            </div>
            <h2 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px;">⚠️ Ujian Dikunci ⚠️</h2>
            <p style="font-size: 16px; color: #666; margin-bottom: 20px;">${reason}<br><br><strong style="color: #dc3545;">Total Pelanggaran: ${totalViolations}/3</strong></p>
            <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <i class="far fa-clock"></i> Sisa Waktu: <span id="lockedTimeDisplay">00:00</span>
            </div>
            <div style="margin-top: 20px;">
                <button onclick="manualSubmitExam()" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; margin-right: 10px;">
                    <i class="fas fa-paper-plane"></i> Submit Sekarang
                </button>
                <button onclick="location.reload()" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">Atau tunggu waktu habis untuk auto submit</p>
        </div>
    `;
    document.body.appendChild(lockDiv);
    
    // UPDATE SISA WAKTU DAN AUTO SUBMIT SAAT HABIS
    const lockInterval = setInterval(() => {
        const remaining = END_TIME_MS - Date.now();
        const el = document.getElementById('lockedTimeDisplay');
        
        if (remaining <= 0) { 
            clearInterval(lockInterval);
            if (el) el.innerText = '00:00';
            
            // ========== AUTO SUBMIT SAAT WAKTU HABIS ==========
            if (!isSubmitting) {
                console.log('⏰ Waktu habis di lock screen, auto submit...');
                submitFormOtomatis();
            }
            return; 
        }
        
        const menit = Math.floor(remaining / 60000);
        const detik = Math.floor((remaining % 60000) / 1000);
        if (el) el.innerText = `${menit.toString().padStart(2,'0')}:${detik.toString().padStart(2,'0')}`;
        
        // Tambahan: peringatan 1 menit sebelum auto submit
        if (remaining <= 60000 && remaining > 59000) {
            console.log('⚠️ 1 menit lagi akan auto submit');
            const warningDiv = document.querySelector('.auto-submit-warning');
            if (!warningDiv) {
                const warn = document.createElement('div');
                warn.className = 'auto-submit-warning';
                warn.style = "margin-top: 15px; padding: 8px; background: #ff9800; color: white; border-radius: 6px; font-size: 13px; animation: blink 1s infinite;";
                warn.innerHTML = '⚠️ 1 menit lagi! Jawaban akan otomatis disubmit. ⚠️';
                document.querySelector('.sweet-lock-overlay div').appendChild(warn);
            }
        }
    }, 1000);
}

// Tambahkan fungsi manual submit global
window.manualSubmitExam = function() {
    if (isSubmitting) return;
    
    Swal.fire({
        title: 'Submit Ujian?',
        text: 'Apakah Anda yakin ingin mengumpulkan jawaban sekarang?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Submit!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitFormOtomatis();
        }
    });
};
    
    function submitFormOtomatis() {
        if(isSubmitting || !form) return;
        isSubmitting = true;
        isForcedSubmit = true; 
        stopTimer(); 
        stopCountdownTimer();
        fullscreenLocked = false;
        if (beforeUnloadHandler) {
        window.removeEventListener('beforeunload', beforeUnloadHandler);
        console.log('✅ beforeunload handler removed');
    }
    
    // Set flag bahwa ini adalah forced submit
    window._isAutoSubmitting = true;
        if(forceFullscreenInterval) clearInterval(forceFullscreenInterval);
        if(document.exitFullscreen && !isMobile) document.exitFullscreen().catch(() => {});
        cleanupAndSubmit();
    }
    
    function cleanupAndSubmit() {
        localStorage.removeItem(`ujian_jawaban_${UJIAN_ID}`);
        localStorage.removeItem(`ujian_current_soal_${UJIAN_ID}`);
        sessionStorage.removeItem(`exam_is_disqualified_${UJIAN_ID}`);
        sessionStorage.removeItem(`exam_violation_count_${UJIAN_ID}`);
         setTimeout(() => {
        if(form) {
            console.log('🚀 Submitting form...');
            form.submit();
        } else {
            console.log('⚠️ Form not found, redirecting manually');
            window.location.href = "{{ route('siswa.index') }}";
        }
    }, 100);
    }
    
    async function initCBT() {
         const initialStatus = await getViolationStatus();
    
    if (initialStatus.is_blocked) {
        forceLockScreen(initialStatus.block_message || 'Ujian telah diblokir!', initialStatus.total_violations);
        return;
    }
    beforeUnloadHandler = (e) => {
    if(!isSubmitting && !isDisqualified) {
        e.preventDefault();
        e.returnValue = '⚠️ Ujian sedang berlangsung!';
        return e.returnValue;
    }
};

window.addEventListener('beforeunload', beforeUnloadHandler);
        initCBTSecurity();
        loadAnswersFromLocalStorage();
        loadCurrentSoal();
        updateJawabanStatus();
        startTimer();
        startBlockStatusChecker();
        
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const display = this.parentElement.querySelector('.file-name');
                    if(display) display.innerText = this.files[0].name;
                    updateJawabanStatus();
                } else {
                    const display = this.parentElement.querySelector('.file-name');
                    if (display) display.innerText = 'Belum ada file';
                    updateJawabanStatus();
                }
            });
        });

        document.addEventListener('change', updateJawabanStatus);
        document.addEventListener('input', (e) => { 
            if(e.target && e.target.matches && e.target.matches('textarea.jawaban-text, input[type="radio"], input[type="file"]')) {
                updateJawabanStatus();
            }
        });
        
        navButtons.forEach((btn, idx) => { 
            btn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(idx); 
            }); 
        });
        
        if(prevBtn) {
            prevBtn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex - 1); 
            });
        }
        
        if(nextBtn) {
            nextBtn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex + 1); 
            });
        }
        
    if(form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if(isSubmitting || isDisqualified) { 
            return false; 
        }
        
        const unanswered = TOTAL_SOAL - Object.keys(jawabanState).length;
        
        // ========== SET FLAG IGNORE BLUR SEBELUM SWEETALERT ==========
        ignoreBlurUntil = Date.now() + 5000; // Ignore blur selama 5 detik
        
        const result = await Swal.fire({
            title: unanswered > 0 ? '⚠️ Perhatian!' : 'Konfirmasi Submit',
            html: unanswered > 0 
                ? `Masih ada <strong style="color:#dc3545">${unanswered}</strong> soal belum dijawab.<br><br>Yakin ingin mengumpulkan?`
                : 'Apakah Anda yakin ingin mengumpulkan semua jawaban?',
            icon: unanswered > 0 ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
        
        if (!result.isConfirmed) {
            // Reset flag jika batal
            ignoreBlurUntil = 0;
            return false;
        }
        
        // ========== TAMPILKAN LOADING ==========
        ignoreBlurUntil = Date.now() + 10000; // Perpanjang ignore saat loading
        
        Swal.fire({
            title: '⏳ Sedang Memproses...',
            html: 'Mohon tunggu, jawaban sedang disimpan.<br><small>Jangan tutup halaman ini.</small>',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit form setelah delay singkat
        setTimeout(() => {
            isSubmitting = true; 
            stopTimer(); 
            stopCountdownTimer();
            form.submit();
        }, 500);
    });
}
        
        console.log('✅ Mode CBT Siap (Sharp Mode)');
    }

    async function initPraktik() {
        console.log('📝 Mode Praktik: Setup form upload');
        
        loadAnswersFromLocalStorage();
        updatePraktikTimer();
        loadCurrentSoal();
        updateJawabanStatus();
        
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const display = this.parentElement.querySelector('.file-name');
                    if(display) display.innerText = this.files[0].name;
                    updateJawabanStatus();
                } else {
                    const display = this.parentElement.querySelector('.file-name');
                    if (display) display.innerText = 'Belum ada file';
                    updateJawabanStatus();
                }
            });
        });

        document.addEventListener('change', updateJawabanStatus);
        document.addEventListener('input', (e) => { 
            if(e.target && e.target.matches && e.target.matches('textarea.jawaban-text, input[type="radio"], input[type="file"]')) {
                updateJawabanStatus();
            }
        });
        
        navButtons.forEach((btn, idx) => { 
            btn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(idx); 
            }); 
        });
        
        if(prevBtn) {
            prevBtn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex - 1); 
            });
        }
        
        if(nextBtn) {
            nextBtn.addEventListener('click', () => { 
                if(!isSubmitting && !isDisqualified && !isInCountdown) showSoal(currentSoalIndex + 1); 
            });
        }
        
    if(form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if(isSubmitting || isDisqualified) { 
            return false; 
        }
        
        const unanswered = TOTAL_SOAL - Object.keys(jawabanState).length;
        
        // ========== SWEETALERT KONFIRMASI ==========
        const result = await Swal.fire({
            title: unanswered > 0 ? '⚠️ Perhatian!' : 'Konfirmasi Submit',
            html: unanswered > 0 
                ? `Masih ada <strong style="color:#dc3545">${unanswered}</strong> soal belum dijawab.<br><br>Yakin ingin mengumpulkan?`
                : 'Apakah Anda yakin ingin mengumpulkan semua jawaban?',
            icon: unanswered > 0 ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });
        
        if (!result.isConfirmed) {
            return false;
        }
        
        // ========== TAMPILKAN LOADING ==========
        Swal.fire({
            title: '⏳ Sedang Memproses...',
            html: 'Mohon tunggu, jawaban sedang disimpan.<br><small>Jangan tutup halaman ini.</small>',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            // Loading akan otomatis hilang saat halaman redirect
            // Tapi kita set timer 3 detik untuk jaga-jaga
            timer: 3000,
            timerProgressBar: true
        }).then(() => {
            // Setelah timer habis, submit form
            isSubmitting = true; 
            stopTimer(); 
            stopCountdownTimer();
            form.submit();
        });
        
        // Jangan submit dulu, tunggu timer loading
        // form.submit(); ← HAPUS INI
    });
}
        
        console.log('✅ Mode Praktik Siap');
    }

    // EKSEKUSI
    if(document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            if (IS_CBT) { initCBT(); } else { initPraktik(); }
        });
    } else {
        if (IS_CBT) { initCBT(); } else { initPraktik(); }
    }
    
})();
</script>
</body>
</html>