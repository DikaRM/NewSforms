<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

body{
    background:#f4f6f9;
    display:flex;
}

/* Sidebar */
.sidebar{
    width:220px;
    background:#0f172a;
    color:white;
    min-height:100vh;
    padding:20px;
}

.sidebar h2{
    margin-bottom:30px;
    text-align:center;
}

.sidebar a{
    display:block;
    color:#cbd5e1;
    text-decoration:none;
    padding:10px;
    border-radius:6px;
    margin-bottom:8px;
}

.sidebar a:hover{
    background:#1e293b;
    color:white;
}

/* Main */
.main{
    flex:1;
    padding:20px;
}

/* Header */
.header{
    background:white;
    padding:16px 20px;
    border-radius:8px;
    margin-bottom:20px;
}

.header h3{
    color:#0f172a;
}

/* Section */
.section{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

.section h4{
    margin-bottom:15px;
    color:#0f172a;
}

/* Exam list */
.exam{
    display:flex;
    justify-content:space-between;
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}

.exam:last-child{
    border-bottom:none;
}

.badge{
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    color:white;
}

.open{ background:#16a34a; }
.upcoming{ background:#2563eb; }
.done{ background:#6b7280; }

/* Table */
table{
    width:100%;
    border-collapse:collapse;
}

table th, table td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
    text-align:left;
}

table th{
    background:#f1f5f9;
}

/* Responsive */
@media(max-width:768px){
    .sidebar{
        display:none;
    }
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Siswa</h2>
    <a href="#">Dashboard</a>
    <a href="#">Ujian</a>
    <a href="#">Nilai</a>
    <a href="#">Berita Acara</a>
    <a href="#">Logout</a>
</div>

<!-- Main -->
<div class="main">

    <!-- Header -->
    <div class="header">
        <h3>Dashboard Siswa</h3>
        <p>Selamat datang, <strong>{{$ire->nama}}</strong> Kelas {{$data->kelas->nama_kelas}}</p>
    </div>

    <!-- Ujian Hari Ini -->
    <div class="section">
        <h4>Ujian Hari Ini {{\Carbon\Carbon::now()->format('d/m/Y')}}</h4>
        @foreach($uji as $uj)
          
            <div class="exam">
              <div>
                <strong>{{$uj->nama_ujian}}</strong><br>
                <small>{{\Carbon\Carbon::parse($uj->waktu_mulai)->format('D F Y H:i')}}</small>
            </div>
          @if($uj->status === "ready")
            <a href="{{route('siswa.shop',$uj->id)}}" class="badge open">{{$uj->status}}</a>
            </div>
          @elseif($uj->status === "done")
              <span class="badge">{{$uj->status}}</span>
          @else
           <h5 class="title">On Going</h5>
           
           @endif
        @endforeach
        </div>

       

    <!-- Berita Acara -->
    <div class="section">
        <h4>📢 Berita Acara</h4>
        <ul>
            <li>Ujian dilaksanakan secara tertib</li>
            <li>Tidak diperkenankan membawa HP</li>
            <li>Gunakan seragam lengkap</li>
        </ul>
    </div>

    <!-- Hasil Ujian -->
    <div class="section">
        <h4>📊 Hasil Ujian / Nilai</h4>

        <table>
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Matematika</td>
                    <td>85</td>
                    <td>Lulus</td>
                </tr>
                <tr>
                    <td>Bahasa Inggris</td>
                    <td>78</td>
                    <td>Lulus</td>
                </tr>
                <tr>
                    <td>Fisika</td>
                    <td>65</td>
                    <td>Remedial</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
