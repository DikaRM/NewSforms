
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #f3f5f9;
}

/* ===== HEADER ===== */
.header {
    background: #2e5b9a;
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h2 {
    font-size: 18px;
}

/* ===== LAYOUT ===== */
.container {
    display: flex;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 230px;
    background: #5c6fa6;
    min-height: 100vh;
    padding-top: 20px;
    color: white;
}

.sidebar ul {
    list-style: none;
}

.sidebar ul li {
    padding: 14px 25px;
    cursor: pointer;
    transition: 0.3s;
}

.sidebar ul li:hover {
    background: rgba(255,255,255,0.2);
}

.sidebar ul li i {
    margin-right: 10px;
}

.logout {
    position: absolute;
    bottom: 20px;
    left: 25px;
}
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

/* ===== MAIN CONTENT ===== */
.main {
    flex: 1;
    padding: 30px;
}

.main h1 {
    margin-bottom: 30px;
    color: #2e5b9a;
}

/* ===== CARDS ===== */
.cards {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
}

.card {
    width: 300px;
    padding: 25px;
    border-radius: 15px;
    color: #333;
    position: relative;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin-bottom: 10px;
}

.card p {
    font-size: 14px;
    color: #555;
}

.card .arrow {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 20px;
}

/* Warna Card */
.pink {
    background: #f8d7da;
}

.yellow {
    background: #fff3cd;
}

.blue {
    background: #cfe2ff;
}
</style>
</head>

<body>

<div class="header">
    <h2>SMK NEGERI 1 CIOMAS</h2>
    <div>
        {{$ire->nama}}<i class="fa fa-chevron-down"></i>
    </div>
</div>

<div class="container">
    
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><i class="fa fa-home"></i> Dashboard</li>
            <li><i class="fa fa-calendar"></i> Jadwal Ujian</li>
            <li><i class="fa fa-history"></i> Riwayat</li>
            <li><i class="fa fa-file"></i> Hasil Ujian</li>
        </ul>

        <div class="logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit"> <i class="fa fa-sign-out-alt"></i> Logout</button>
            </form>
           
        </div>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Dashboard</h1>

        <div class="cards">
            <a href="">
            <div class="card pink">
                <h3>Jadwal Ujian</h3>
                <p>Halaman untuk melihat jadwal ujian siswa.</p>
                <div class="arrow"><i class="fa fa-arrow-right"></i></div>
            </div>
            </a>
    <a href="">
            <div class="card yellow">
                <h3>Riwayat</h3>
                <p>Halaman untuk melihat riwayat ujian.</p>
                <div class="arrow"><i class="fa fa-arrow-right"></i></div>
            </div>
            </a>

        </div>
        <div class="section mt-2">
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
       @foreach($hasil as $sil)
        
            <h5 class="title">{{$sil->status}}</h5>
            <h5 class="title is-family-code">Nilai Kamu : {{$sil->nilai}}</h5>
          
        @endforeach
        </div>

</div>
    </div>
    

</body>
</html>
