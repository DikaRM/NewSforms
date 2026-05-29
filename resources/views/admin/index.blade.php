@extends("layouts.blank")
@section("content")

{{-- STYLE TAMBAHAN --}}
<style>
     :root {
        --primary: #3085d6;
        --primary-soft: #EEF2FF;
        --text-main: #111827;
        --text-muted: #6B7280;
        --border: #E5E7EB;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --input-height: 48px;
        --shadow-sheet: 0 -4px 30px rgba(0, 0, 0, 0.1);
        --focus-ring: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    .stat-card {
    
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .chart-title {
        color: #2e5b9a;
        font-weight: bold;
        margin-bottom: 20px;
        border-left: 4px solid #2e5b9a;
        padding-left: 15px;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #2e5b9a 0%, #1e3d66 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: white;
    }
    
    .trend-up {
        color: #48c774;
        font-size: 0.9rem;
    }
    
    .trend-down {
        color: #ff3860;
        font-size: 0.9rem;
    }
    .text-error {
         display: block !important;
    color: red;
    font-size: 0.8rem;
    margin-top: 5px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33'
    });

</script>
@endif
@error('password')
<script>
Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Password Minimal 6',
        confirmButtonColor: '#d33'
    });
</script>

@enderror

{{-- DASHBOARD HEADER --}}


{{-- STATISTIK CARD DENGAN ICON --}}
<div class="columns is-multiline mb-5">
    {{-- Total Siswa --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
         Siswa
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalSiswa) }}
    </p>
</div>
    </div>
    
    {{-- Total Guru --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
         Guru
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalGuru) }}
    </p>
</div>
    </div>
    
    {{-- Total Kelas --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
        Kelas
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalKelas) }}
    </p>
</div>
    </div>
    
    {{-- Total Mapel --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
         Mapel
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalMapel) }}
    </p>
</div>
    </div>
</div>

{{-- ROW KEDUA STATISTIK --}}
<div class="columns is-multiline mb-5">
    {{-- Total Pelanggaran --}}
   <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
        Total Pelanggaran
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalPelanggaran) }}
    </p>
</div>
    </div>
    
    {{-- Total Bank Soal --}}
   <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
        Bank Soal
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($totalBankSoal) }}
    </p>
</div>
    </div>
    
    {{-- Total Ujian Aktif --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
        Ujian Aktif
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($ujianReady) }}
    </p>
</div>
    </div>
    
    {{-- Total Selesai --}}
    <div class="column is-3">
        <div class="card stat-card has-text-centered" style="background:white; border-left:3px solid #2e5b9a; border-radius:10px; display:block; padding:5px 20px;">
        <div class="card-header" style="border-bottom:3px solid #2e5b9a; border-radius:10px;">
    <h5 class="card-header-title" style="color:#2e5b9a;  display:inline-block; padding-bottom:5px;">
        Ujian Selesai
    </h5>
    </div>
    <p class="subtitle" style="font-size: 2.5rem; color:#2e5b9a; margin:10px 0; font-weight:600;">
        {{ number_format($ujianDone) }}
    </p>
</div>
    </div>
</div>

{{-- GRAFIK DAN CHART --}}
<div class="columns">
    {{-- PIE CHART DISTRIBUSI USER --}}
    <div class="column is-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i> Distribusi Pengguna
            </h3>
            <canvas id="userDistributionChart" style="max-height: 300px;"></canvas>
            <div class="has-text-centered mt-3">
                <p class="has-text-grey">Total Pengguna: {{ number_format($totalSiswa + $totalGuru) }}</p>
            </div>
        </div>
    </div>
    
    {{-- DOUGHNUT CHART STATUS UJIAN --}}
    <div class="column is-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-donut"></i> Status Ujian
            </h3>
            <canvas id="ujianStatusChart" style="max-height: 300px;"></canvas>
            <div class="has-text-centered mt-3">
                <p class="has-text-grey">Total Ujian: {{ number_format($ujianReady + $ujianDraft + $ujianDone) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    {{-- BAR CHART PELANGGARAN PER BULAN --}}
    <div class="column is-12">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i> Tren Pelanggaran Per Bulan
            </h3>
            <canvas id="violationTrendChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

<div class="columns">
    {{-- PROGRESS BAR PEMBELAJARAN --}}
    <div class="column is-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-tasks"></i> Progress Sistem
            </h3>
            <div class="mb-4">
                <div class="level mb-2">
                    <span>Pengisian Bank Soal</span>
                    <span>{{ round(($totalBankSoal / 1000) * 100) }}%</span>
                </div>
                <progress class="progress is-info" value="{{ min(($totalBankSoal / 1000) * 100, 100) }}" max="100"></progress>
            </div>
            <div class="mb-4">
                <div class="level mb-2">
                    <span>Aktivasi Ujian</span>
                    <span>{{ $ujianReady > 0 ? round(($ujianReady / ($ujianReady + $ujianDraft + $ujianDone)) * 100) : 0 }}%</span>
                </div>
                <progress class="progress is-success" value="{{ $ujianReady > 0 ? round(($ujianReady / ($ujianReady + $ujianDraft + $ujianDone)) * 100) : 0 }}" max="100"></progress>
            </div>
            <div class="mb-4">
                <div class="level mb-2">
                    <span>Keamanan Sistem</span>
                    <span>95%</span>
                </div>
                <progress class="progress is-warning" value="95" max="100"></progress>
            </div>
        </div>
    </div>
    
    {{-- INFO CEPAT --}}
    <div class="column is-6">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-info-circle"></i> Informasi Cepat
            </h3>
            <div class="content">
                <div class="notification is-light">
                    <div class="level">
                        <div class="level-left">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-chalkboard-user"></i>
                                </span>
                                <span>Rasio Guru:Siswa</span>
                            </span>
                        </div>
                        <div class="level-right">
                            <strong>1 : {{ $totalSiswa > 0 ? round($totalSiswa / max($totalGuru, 1)) : 0 }}</strong>
                        </div>
                    </div>
                </div>
                <div class="notification is-light">
                    <div class="level">
                        <div class="level-left">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <span>Rata-rata Siswa per Kelas</span>
                            </span>
                        </div>
                        <div class="level-right">
                            <strong>{{ $totalKelas > 0 ? round($totalSiswa / $totalKelas) : 0 }} siswa</strong>
                        </div>
                    </div>
                </div>
                <div class="notification is-light">
                    <div class="level">
                        <div class="level-left">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <span>Tingkat Pelanggaran</span>
                            </span>
                        </div>
                        <div class="level-right">
                            <strong class="has-text-danger">{{ $totalSiswa > 0 ? min(100, round(($totalPelanggaran / $totalSiswa) * 100)) : 0 }}%</strong>
                        </div>
                    </div>
                </div>
                <div class="notification is-light">
                    <div class="level">
                        <div class="level-left">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-trophy"></i>
                                </span>
                                <span>Rata-rata Soal per Ujian</span>
                            </span>
                        </div>
                        <div class="level-right">
                            <strong>{{ $totalBankSoal > 0 ? round($totalBankSoal / max(($ujianReady + $ujianDraft + $ujianDone), 1)) : 0 }} soal</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL USERS (TIDAK BERUBAH) --}}
<div class="card mt-5" id="tabel-users">
    <div class="card-header" style="background: #2e5b9a;">
        <p class="card-header-title has-text-white">Manajemen Pengguna</p>
        <div class="card-header-icon">
            <button class="button is-light is-small" onclick="document.getElementById('modals').classList.add('is-active');">
                <i class="fas fa-plus" style="margin-right:6px;"></i> Tambah Users
            </button>
        </div>
    </div>
    <div class="card-content">
        {{-- Filter Role --}}
        <div class="level is-mobile mb-4">
            <div class="level-left">
                <form method="get" id="filterForm">
                    <div class="field has-addons">
                        <div class="control">
                            <div class="select">
                                <select name="role" class="select" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="pengawas" {{ request('role') == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                                    <option value="admin-ops" {{ request('role') == 'admin-ops' ? 'selected' : '' }}>Admin Operasional</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Tabel --}}
        <div class="table-container">
            <table class="table is-fullwidth is-hoverable is-striped">
                <thead>
                    <tr style="background: #2e5b9a;">
                        <th class="has-text-white">ID</th>
                        <th class="has-text-white">Nama Lengkap</th>
                        <th class="has-text-white">Role</th>
                        <th class="has-text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $d)
                    <tr>
                        <td>{{$index +1}}</td>
                        <td>{{$d->nama}}</td>
                        <td>
                            <span class="tag is-info is-light">
                                {{ ucfirst($d->role) }}
                            </span>
                        </td>
                        <td class="buttons">
                            <button class="button is-small is-warning is-light" onclick="document.getElementById('mod{{$d->id}}').classList.add('is-active')">
                                <i class="fas fa-edit" style="margin-right:6px;"></i> Edit
                            </button>
                            <form action="{{route('admin.destroy',$d->id)}}" method="post" style="display: inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="button is-small is-danger" style="gap:5px;"onclick="return confirm('Yakin dihapus ?')">
                                    <i class="fas fa-trash" style="margin-right:6px;"></></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:20px;">
  {{ $data->withQueryString()->fragment('tabel-users')->links() }}
</div>
        </div>

        @foreach($data as $d)
            <div class="modal" id="mod{{$d->id}}">
                        <div class="modal-background"></div>
                        <div class="modal-card">
                            <header class="modal-card-head" style="background: #2e5b9a;display:flex;flex-direction:row;justify-content:space-between;">
                                <h5 class="title has-text-white">Edit Users</h5>
                                <button class="delete mt-5" onclick="document.getElementById('mod{{$d->id}}').classList.remove('is-active')"></button>
                            </header>
                            <form action="{{route('admin.update',$d->id)}}" method="post">
                                @csrf
                                @method("PUT")
                                <section class="modal-card-body">
                                    <div class="field">
                                        <label class="label">Nama Lengkap</label>
                                        <div class="control">
                                            <input type="text" class="input" value="{{$d->nama}}" name="nama" required>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="label">Password</label>
                                    <div class="control">
                                        <input type="password" class="input password-input" name="password" placeholder="Masukkan password" required>
                                    </div>

                                    <small class="text-error password-error"></small>
                    
                                </div>
                                    <div class="field">
                                        <label class="label">Role</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="role" required>
                                                    <option value="siswa" {{ $d->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                                    <option value="guru" {{ $d->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                                    <option value="pengawas" {{ $d->role == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                                                    <option value="admin-ops" {{ $d->role == 'admin-ops' ? 'selected' : '' }}>Admin Operasional</option>
                                                    <option value="admin" {{ $d->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <footer class="modal-card-foot">
                                    <div class="buttons">
                                        <button type="reset" class="button is-danger">Reset</button>
                                        <button type="submit" class="button is-info" style="background: #2e5b9a;">Update</button>
                                    </div>
                                </footer>
                            </form>
                        </div>
                    </div>
        @endforeach
    </div>
</div>

{{-- Modal Tambah Users --}}
<div class="modal" id="modals">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head" style="background: #2e5b9a;display:flex;flex-direction:row;justify-content:space-between;">
            <h5 class="title has-text-white mt-5">Tambah Users</h5>
            <button class="delete " onclick="document.getElementById('modals').classList.remove('is-active');"></button>
        </header>
        <form action="{{route('admin.store')}}" method="post">
            @csrf
            <section class="modal-card-body">
                <div class="field">
                    <label class="label">Nama Lengkap</label>
                    <div class="control">
                        <input type="text" class="input" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input type="password" class="input" name="password" placeholder="Masukkan password" required>
                    </div>
                    <small class="text-error password-error"></small>
                </div>
                
                <div class="field">
                    <label class="label">Role</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="role" id="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin-ops">Admin Operasional</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="buttons is-centered">
                    <button type="reset" class="button is-danger">Reset</button>
                    <button type="submit" class="button is-info" style="background: #2e5b9a;">Create</button>
                </div>
            </footer>
        </form>
    </div>
</div>

@endsection

{{-- SCRIPT JQUERY DAN CHART.JS --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<script>
// Validasi password
document.addEventListener("DOMContentLoaded", function() {
    if (window.location.search.includes("page=")) {
        const el = document.getElementById("tabel-users");
        if (el) {
            el.scrollIntoView({ behavior: "smooth" });
        }
    }
});
document.addEventListener('input', function(e) {
    if (e.target.name !== 'password') return;

    let form = e.target.closest('form');
    if (!form) return;

    let error = form.querySelector('.text-error');
    if (!error) return;

    let value = e.target.value;

    if (value.length > 0 && value.length < 6) {
        error.innerText = "Password minimal 6 karakter";
        e.target.style.borderColor = "red";
    } else {
        error.innerText = "";
        e.target.style.borderColor = "#ddd";
    }
});

$(document).ready(function() {
    console.log('=== DEBUG CHART.JS ===');
    console.log('1. Cek library Chart.js:', typeof Chart);
    
    const userData = @json([
        'siswa' => $totalSiswa ?? 0,
        'guru' => $totalGuru ?? 0,
    ]);
    
    const ujianStatus = @json([
        'ready' => $ujianReady ?? 0,
        'draft' => $ujianDraft ?? 0,
        'selesai' => $ujianDone ?? 0
    ]);
    
    const pelanggaranData = @json($pelanggaranPerBulan ?? array_fill(0, 12, 0));
    
    console.log('2. Data dari controller:', {
        userData: userData,
        ujianStatus: ujianStatus,
        pelanggaranData: pelanggaranData
    });
    
    // Cek element canvas
    const canvasUser = document.getElementById('userDistributionChart');
    const canvasUjian = document.getElementById('ujianStatusChart');
    const canvasViolation = document.getElementById('violationTrendChart');
    
    console.log('3. Canvas elements:', {
        userChart: canvasUser ? 'Ditemukan' : 'TIDAK DITEMUKAN',
        ujianChart: canvasUjian ? 'Ditemukan' : 'TIDAK DITEMUKAN',
        violationChart: canvasViolation ? 'TIDAK DITEMUKAN' : 'TIDAK DITEMUKAN'
    });
    
    // 1. PIE CHART - Distribusi Pengguna
    if (canvasUser) {
        try {
            const totalUsers = userData.siswa + userData.guru;
            if (totalUsers > 0) {
                new Chart(canvasUser, {
                    type: 'pie',
                    data: {
                        labels: ['Siswa', 'Guru'],
                        datasets: [{
                            data: [userData.siswa, userData.guru],
                            backgroundColor: ['#2e5b9a', '#4a90e2'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('✓ Pie chart berhasil dibuat');
            } else {
                console.warn('Total users = 0, tidak membuat pie chart');
                canvasUser.parentElement.innerHTML = '<p class="has-text-centered has-text-grey">Belum ada data pengguna</p>';
            }
        } catch(e) {
            console.error('Error membuat pie chart:', e);
        }
    } else {
        console.error('Canvas userDistributionChart tidak ditemukan');
    }
    
    // 2. DOUGHNUT CHART - Status Ujian
    if (canvasUjian) {
        try {
            const totalUjian = ujianStatus.ready + ujianStatus.draft + ujianStatus.selesai;
            if (totalUjian > 0) {
                new Chart(canvasUjian, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ujian Sedia', 'Draft', 'Selesai'],
                        datasets: [{
                            data: [ujianStatus.ready, ujianStatus.draft, ujianStatus.selesai],
                            backgroundColor: ['#48c774', '#ffd43b', '#2e5b9a'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('✓ Doughnut chart berhasil dibuat');
            } else {
                console.warn('Total ujian = 0, tidak membuat doughnut chart');
                canvasUjian.parentElement.innerHTML = '<p class="has-text-centered has-text-grey">Belum ada data ujian</p>';
            }
        } catch(e) {
            console.error('Error membuat doughnut chart:', e);
        }
    } else {
        console.error('Canvas ujianStatusChart tidak ditemukan');
    }
    
    // 3. LINE CHART - Tren Pelanggaran
    if (canvasViolation) {
        try {
            const hasData = pelanggaranData.some(value => value > 0);
            if (hasData) {
                new Chart(canvasViolation, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Jumlah Pelanggaran',
                            data: pelanggaranData,
                            borderColor: '#ff6b6b',
                            backgroundColor: 'rgba(255, 107, 107, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#c92a2a',
                            pointBorderColor: '#fff',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Pelanggaran: ${context.raw} kali`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Pelanggaran',
                                    font: { weight: 'bold' }
                                },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                    font: { weight: 'bold' }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
                console.log('✓ Line chart berhasil dibuat');
            } else {
                console.warn('Data pelanggaran kosong, tidak membuat line chart');
                canvasViolation.parentElement.innerHTML = '<p class="has-text-centered has-text-grey">Belum ada data pelanggaran</p>';
            }
        } catch(e) {
            console.error('Error membuat line chart:', e);
        }
    } else {
        console.error('Canvas violationTrendChart tidak ditemukan');
    }
    
    // Animasi counter untuk card
    $('.stat-card .subtitle').each(function() {
        const $this = $(this);
        const targetText = $this.text();
        const target = parseInt(targetText.replace(/\./g, '')) || 0;
        if(!isNaN(target) && target > 0) {
            let current = 0;
            const increment = Math.ceil(target / 50);
            const timer = setInterval(function() {
                current += increment;
                if(current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                $this.text(current.toLocaleString('id-ID'));
            }, 20);
        }
    });
});
</script>
@endpush