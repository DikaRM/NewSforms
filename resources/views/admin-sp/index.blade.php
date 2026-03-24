<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Dashboard Panitia Ujian</title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4f46e5;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --info-color: #3b82f6;
    }

    body {
      background: #f3f4f6;
    }

    .navbar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 1rem 2rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .navbar h5 {
      color: white;
      margin: 0;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      border: 1px solid #e5e7eb;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
    }

    .stat-icon i {
      font-size: 24px;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #1f2937;
      line-height: 1.2;
    }

    .stat-label {
      color: #6b7280;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-title i {
      color: var(--primary-color);
    }

    .exam-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .exam-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      border: 1px solid #e5e7eb;
      position: relative;
    }

    .exam-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .exam-header {
      padding: 1rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .exam-status {
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-ready {
      background: var(--success-color);
      color: white;
    }

    .status-done {
      background: #9ca3af;
      color: white;
    }

    .exam-body {
      padding: 1.5rem;
    }

    .exam-info {
      margin-bottom: 1rem;
    }

    .exam-info-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      color: #4b5563;
    }

    .exam-info-item i {
      width: 20px;
      color: var(--primary-color);
    }

    .exam-footer {
      padding: 1rem;
      background: #f9fafb;
      border-top: 1px solid #e5e7eb;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .class-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .class-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      border: 1px solid #e5e7eb;
    }

    .class-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .class-header {
      padding: 1rem;
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
      font-weight: 600;
    }

    .class-body {
      padding: 1.5rem;
    }

    .class-stats {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .class-stat {
      text-align: center;
    }

    .class-stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1f2937;
    }

    .class-stat-label {
      font-size: 0.75rem;
      color: #6b7280;
    }

    .btn-set-schedule {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 0.75rem;
      border-radius: 8px;
      font-weight: 600;
      width: 100%;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      display: inline-block;
    }

    .btn-set-schedule:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
      color: white;
    }

    .progress-bar {
      width: 100%;
      height: 8px;
      background: #e5e7eb;
      border-radius: 4px;
      overflow: hidden;
      margin-top: 0.5rem;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--success-color), var(--primary-color));
      border-radius: 4px;
      transition: width 0.3s ease;
    }

    .empty-state {
      text-align: center;
      padding: 3rem;
      background: white;
      border-radius: 12px;
      color: #9ca3af;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .badge {
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .badge-primary {
      background: #e0e7ff;
      color: var(--primary-color);
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar is-flex">
    <h5 class="title is-family-monospace has-text-white">
      <i class="fas fa-chalkboard-teacher mr-2"></i>
      Operational Ujian
    </h5>
    
    <div class="navbar-end">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
        <button type="submit" class="button is-danger is-light">
          <i class="fas fa-sign-out-alt mr-2"></i>
          Logout
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-5">
    <!-- Statistics Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #e0e7ff;">
          <i class="fas fa-school" style="color: var(--primary-color);"></i>
        </div>
        <div class="stat-value">{{$kla->count()}}</div>
        <div class="stat-label">Total Kelas</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #d1fae5;">
          <i class="fas fa-book-open" style="color: var(--success-color);"></i>
        </div>
        <div class="stat-value">{{$uji->where('status', '!=', 'done')->count()}}</div>
        <div class="stat-label">Ujian Aktif</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2;">
          <i class="fas fa-check-circle" style="color: var(--danger-color);"></i>
        </div>
        <div class="stat-value">{{$uji->where('status', 'done')->count()}}</div>
        <div class="stat-label">Ujian Selesai</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #fff3cd;">
          <i class="fas fa-clock" style="color: var(--warning-color);"></i>
        </div>
        <div class="stat-value">{{$uji->where('status', 'ready')->count()}}</div>
        <div class="stat-label">Ready to Start</div>
      </div>
    </div>

    <!-- Ujian yang Siap Dilaksanakan -->
    <div class="section-title">
      <i class="fas fa-play-circle"></i>
      Ujian Siap Dilaksanakan (Status: Ready)
    </div>

    @php
      $readyExams = $uji->where('status', 'ready');
    @endphp

    @if($readyExams->count() > 0)
      <div class="exam-grid">
        @foreach($readyExams as $uj)
        <div class="exam-card">
          <div class="exam-header">
            <span>{{$uj->nama_ujian}}</span>
            <span class="exam-status status-ready">Ready</span>
          </div>
          <div class="exam-body">
            <div class="exam-info">
              <div class="exam-info-item">
                <i class="fas fa-book"></i>
                <span>{{$uj->mapels->nama_mapel}}</span>
              </div>
              <div class="exam-info-item">
                <i class="fas fa-hourglass-half"></i>
                <span>Durasi: {{$uj->durasi}} Menit</span>
              </div>
              <div class="exam-info-item">
                <i class="fas fa-calendar"></i>
                <span>Tanggal: {{ \Carbon\Carbon::parse($uj->tanggal ?? now())->isoFormat('D MMMM Y') }}</span>
              </div>
              <div class="exam-info-item">
                <i class="fas fa-users"></i>
                <span>Total Peserta: {{ $sis->where('kelas_id', $kla->pluck('id'))->count() ?? 0 }} Siswa</span>
              </div>
            </div>

            @php
              $totalSchedules = $jad->where('ujian_id', $uj->id)->count();
              $totalKelas = $kla->count();
              $progress = $totalKelas > 0 ? ($totalSchedules / $totalKelas) * 100 : 0;
            @endphp

            <div class="progress-bar">
              <div class="progress-fill" style="width: {{$progress}}%"></div>
            </div>
            <div class="is-flex is-justify-content-space-between mt-1">
              <span class="is-size-7 has-text-grey">Progress Penjadwalan</span>
              <span class="is-size-7 has-text-weight-bold">{{$totalSchedules}}/{{$totalKelas}} Kelas</span>
            </div>
          </div>
          <div class="exam-footer">
            <span class="badge badge-primary">
              <i class="fas fa-clock"></i> {{$uj->created_at->diffForHumans()}}
            </span>
            <button class="button is-small is-primary" onclick="quickSchedule({{$uj->id}})">
              <i class="fas fa-calendar-plus"></i> Atur Jadwal
            </button>
          </div>
        </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <i class="fas fa-hourglass"></i>
        <p>Tidak ada ujian dengan status ready</p>
        <p class="is-size-7">Semua ujian sudah dijadwalkan atau selesai</p>
      </div>
    @endif

    <!-- Semua Ujian -->
    <div class="section-title mt-5">
      <i class="fas fa-list"></i>
      Semua Ujian
    </div>

    <div class="exam-grid">
      @foreach($uji as $uj)
      <div class="exam-card">
        <div class="exam-header">
          <span>{{$uj->nama_ujian}}</span>
          <span class="exam-status {{$uj->status == 'ready' ? 'status-ready' : 'status-done'}}">
            {{$uj->status}}
          </span>
        </div>
        <div class="exam-body">
          <div class="exam-info">
            <div class="exam-info-item">
              <i class="fas fa-book"></i>
              <span>{{$uj->mapels->nama_mapel}}</span>
            </div>
            <div class="exam-info-item">
              <i class="fas fa-hourglass-half"></i>
              <span>Durasi: {{$uj->durasi}} Menit</span>
            </div>
            @if($uj->status == 'done')
            <div class="exam-info-item">
              <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
              <span>Selesai pada: {{ \Carbon\Carbon::parse($uj->updated_at)->isoFormat('D MMMM Y') }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Daftar Kelas -->
    <div class="section-title mt-5">
      <i class="fas fa-users"></i>
      Daftar Kelas
    </div>

    <div class="class-grid">
      @foreach($kla as $k)
      <div class="class-card">
        <div class="class-header">
          <i class="fas fa-door-open mr-2"></i>
          {{$k->nama_kelas}}
        </div>
        
        <div class="class-body">
          @php
            $totalSiswa = $sis->where("kelas_id", $k->id)->count();
            $jadwalKelas = $jad->where('kelas_id', $k->id);
            $totalUjianKelas = $jadwalKelas->count();
          @endphp
          
          <div class="class-stats">
            <div class="class-stat">
              <div class="class-stat-value">{{$totalSiswa}}</div>
              <div class="class-stat-label">Siswa</div>
            </div>
            <div class="class-stat">
              <div class="class-stat-value">{{$totalUjianKelas}}</div>
              <div class="class-stat-label">Ujian</div>
            </div>
            <div class="class-stat">
              <div class="class-stat-value">{{$readyExams->count()}}</div>
              <div class="class-stat-label">Ready</div>
            </div>
          </div>

          @if($totalUjianKelas > 0)
          <div class="mb-3">
            <div class="is-size-7 has-text-weight-bold mb-1">Jadwal Terdekat:</div>
            @foreach($jadwalKelas->take(2) as $jd)
            <div class="is-size-7 has-text-grey">
              <i class="fas fa-clock mr-1"></i>
              {{ \Carbon\Carbon::parse($jd->tanggal)->isoFormat('dddd, H:mm') }}
            </div>
            @endforeach
          </div>
          @endif

          <a href="{{route('admin-ops.set',$k->id)}}" class="btn-set-schedule">
            <i class="fas fa-calendar-alt mr-2"></i>
            Atur Jadwal
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <script>
    function quickSchedule(ujianId) {
      // Find kelas that don't have schedule for this exam
      // This is a simplified version - you might want to implement actual logic
      alert('Fitur quick schedule: Akan mengarahkan ke halaman penjadwalan untuk ujian ID: ' + ujianId);
      // window.location.href = '/admin-ops/quick-schedule/' + ujianId;
    }

    // Auto refresh data every 30 seconds (optional)
    setInterval(function() {
      // You can implement AJAX refresh here
      console.log('Refreshing data...');
    }, 30000);
  </script>
</body>
</html>