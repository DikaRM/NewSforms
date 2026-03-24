
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('bulma.min.css')}}">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

    /* Container utama */
    .exam-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    /* Card styling */
    .exam-card {
        margin-bottom: 20px;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .exam-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
    }
    
    /* Header card */
    .exam-card .media {
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .exam-card .title.is-4 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 5px !important;
    }
    
    .exam-card .subtitle.is-6 {
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .exam-card .subtitle.is-6 .icon {
        font-size: 0.9rem;
        color: #3498db;
    }
    
    /* Nilai container */
    .nilai-container {
        text-align: center;
        padding: 20px 0;
    }
    
    .nilai-container .title.is-3 {
        font-size: 4rem !important;
        font-weight: 800 !important;
        color: #27ae60 !important;
        margin: 10px 0 !important;
        text-shadow: 2px 2px 4px rgba(39, 174, 96, 0.2);
    }
    
    /* Button styling */
    .exam-card .button.is-primary {
        min-width: 200px;
        height: 50px;
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .exam-card .button.is-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    /* Tags */
    .exam-card .tag {
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 20px;
    }
    
    .exam-card .tag.is-info {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }
    
    .exam-card .tag.is-primary {
        background: linear-gradient(135deg, #27ae60, #229954);
    }
    
    .exam-card .tag.is-warning {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
    }
    
    /* Icon spacing */
    .exam-card .icon {
        margin-right: 5px;
    }
    
    /* Notification styling */
    .exam-card .notification {
        border-radius: 10px;
        text-align: center;
        margin: 10px 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .exam-container {
            padding: 10px;
        }
        
        .exam-card .title.is-4 {
            font-size: 1.2rem;
        }
        
        .exam-card .button.is-primary {
            min-width: 150px;
            height: 40px;
            font-size: 0.9rem;
        }
        
        .nilai-container .title.is-3 {
            font-size: 3rem !important;
        }
    }
    
    /* Animasi masuk */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .exam-card {
        animation: slideIn 0.5s ease forwards;
    }
    
    /* Stagger animation */
    .exam-card:nth-child(1) { animation-delay: 0.1s; }
    .exam-card:nth-child(2) { animation-delay: 0.2s; }
    .exam-card:nth-child(3) { animation-delay: 0.3s; }
    .exam-card:nth-child(4) { animation-delay: 0.4s; }
    .exam-card:nth-child(5) { animation-delay: 0.5s; }


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

.button-container{
  display: none;
}


.blue {
    background: #cfe2ff;
}
@media(max-width:768px){
  .sidebar{
    display:none;
  }
  .button-container {
    display: block;
  margin:10px auto;
  display: flex;
  background-color: rgba(0, 73, 144);
  width: 250px;
  height: 40px;
  align-items: center;
  justify-content: space-around;
  border-radius: 10px;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 7px,
    rgba(0, 73, 144, 0.5) 5px 8px 10px;
  transition: all 0.5s;
}
.button-container:hover {
  width: 300px;
  transition: all 0.5s;
}

.buttond {
  outline: 0 !important;
  border: 0 !important;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  transition: all ease-in-out 0.3s;
  cursor: pointer;
}

.buttond:hover {
  transform: translateY(-3px);
}

.icon {
  font-size: 20px;
}
  
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
<div class="button-container">
  <a href="{{route('siswa.index')}}"class="buttond">
    <svg
      class="icon"
      stroke="currentColor"
      fill="currentColor"
      stroke-width="0"
      viewBox="0 0 1024 1024"
      height="1em"
      width="1em"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M946.5 505L560.1 118.8l-25.9-25.9a31.5 31.5 0 0 0-44.4 0L77.5 505a63.9 63.9 0 0 0-18.8 46c.4 35.2 29.7 63.3 64.9 63.3h42.5V940h691.8V614.3h43.4c17.1 0 33.2-6.7 45.3-18.8a63.6 63.6 0 0 0 18.7-45.3c0-17-6.7-33.1-18.8-45.2zM568 868H456V664h112v204zm217.9-325.7V868H632V640c0-22.1-17.9-40-40-40H432c-22.1 0-40 17.9-40 40v228H238.1V542.3h-96l370-369.7 23.1 23.1L882 542.3h-96.1z"
      ></path>
    </svg>
  </a>
  
    <a href="{{route('siswa.riwayat')}}" class="buttond">
    <i class="icon fa fa-history"></i></a>
  <a href="{{route('siswa.jadwal')}}" class="buttond">
    
    <i class="icon fa fa-calendar"></i>
  </a>

 
</div>
<div class="container">
    
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li>
              <a href="{{route('siswa.index')}}" class="navbar-item">
                <i class="fa fa-home"></i> Dashboard
              </a>
              </li>
            <li>
              <a href="{{route('siswa.jadwal')}}" class="navbar-item">
                              <i class="fa fa-calendar"></i> Jadwal Ujian
              </a>
</li>
            <li>
              <a href="{{route('siswa.riwayat')}}" class="navbar-item">
                              <i class="fa fa-history"></i> Riwayat
              </li>
              </a>

            

        <div class="logout">
            <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit"> <i class="fa fa-sign-out-alt"></i> Logout</button>
            </form>
           
        </div>
    </div>

    <!-- Main Content -->
    <div class="main">
      <form action="{{ route('users.logout') }}" method="post">
                @csrf
                <button type="submit"> <i class="fa fa-sign-out-alt"></i> Logout</button>
            </form>
        <h1>Dashboard</h1>

        <div class="cards">
            <a href="{{route('siswa.jadwal')}}">
            <div class="card pink">
                <h3>Jadwal Ujian</h3>
                <p>Halaman untuk melihat jadwal ujian siswa.</p>
                <div class="arrow"><i class="fa fa-arrow-right"></i></div>
            </div>
            </a>
    <a href="{{route('siswa.riwayat')}}">
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
          @php 
           $peserta = $uj->peserta->first();
          @endphp
        
          
            <div class="exam">
              <div>
                <strong>{{$uj->nama_ujian}}</strong><br>
                <small>{{$uj->jadwal ? \Carbon\Carbon::parse($uj->jadwal->waktu_mulai)->format('D F Y H:i'):"Waktu Belum ditentukan"}}</small>
            </div>
        
    @php
        $peserta = $uj->peserta->first();
    @endphp
    
    <div class="card exam-card">
        <div class="card-content">
            {{-- Header Card --}}
            <div class="media">
                <div class="media-content">
                    <p class="title is-4">{{ $uj->mapels->nama_mapel }}</p>
                    <p class="subtitle is-6">
                        <span class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        {{$uj->jadwal ? \Carbon\Carbon::parse($uj->jadwal->waktu_mulai)->format('D F Y H:i'):"Waktu Belum ditentukan"}}
                    </p>
                </div>
            </div>

            {{-- Body Card --}}
            <div class="content">
                @if($uj->status === "ready")
                    @if(session("success"))
                        <div class="has-text-centered">
                            <span class="tag is-primary is-medium">
                                <span class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span>Done</span>
                            </span>
                        </div>
                    @else
                        <div class="has-text-centered">
                            <a href="{{ route('siswa.shop', $uj->id) }}" 
                               class="button is-primary is-medium">
                                <span class="icon">
                                    <i class="fas fa-play"></i>
                                </span>
                                <span>Mulai Ujian</span>
                            </a>
                        </div>
                    @endif
                    
                @elseif($uj->status === "done")
                    <div class="nilai-container">
                        @if($peserta && $peserta->nilai)
                            <h5 class="title is-3 has-text-success has-text-centered">
                                {{ $peserta->nilai }}
                            </h5>
                            <p class="has-text-centered">
                                <span class="tag is-info is-medium">
                                    <span class="icon">
                                        <i class="fas fa-flag-checkered"></i>
                                    </span>
                                    <span>Selesai</span>
                                </span>
                            </p>
                        @else
                            <div class="notification is-warning is-light">
                                Nilai belum tersedia
                            </div>
                        @endif
                    </div>
                    
                @else
                    <div class="has-text-centered">
                        <span class="tag is-warning is-medium">
                            <span class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </span>
                            <span>On Going</span>
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
       
        </div>

</div>
    </div>
    

</body>
</html>
