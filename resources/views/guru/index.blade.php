<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guru Teacher</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('bulma.min.css')}}">

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
  <a href="{{route('guru.index')}}"class="buttond">
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
   <a href="{{route('guru.result')}}" class="buttond">
    <i class="icon fa fa-file"></i>
  </a>
    <a href="{{route('guru.riwayat')}}" class="buttond">
    <i class="icon fa fa-history"></i></a>
  <a href="{{route('guru.jadwal')}}" class="buttond">
    
    <i class="icon fa fa-calendar"></i>
  </a>

  <a href="{{route('pengawas.index',$dt->id)}}" class="buttond">
              <i class="icon fa fa-person"></i>
  </a>
</div>

<div class="container">
    
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><i class="fa fa-home"></i> Dashboard</li>
            <li><a href="{{route('guru.jadwal')}}" class="has-text-light">
              <i class="fa fa-calendar"></i> Jadwal Ujian
              </a>
            </li>
            <li>
              <a href="{{route('guru.riwayat')}}">
                <i class="fa fa-history"></i> Riwayat Ujian
              </a>
            </li>
            <li>
              <a href="{{route('guru.result')}}">
                <i class="fa fa-file"></i> Hasil Ujian</li>
              </a>
              
            <li><a href="{{route('pengawas.index',$dt->id)}}" class="has-text-light">
              <i class="fa fa-person">Pengawas</i>
            </a></li>
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
        @if($ire)
   <div class="level is-mobile">
    <h5 class="level-left">
      Halo {{$ire->nama}}
    </h5>
    <div class="level-right">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
    <button class="button is-danger" type="submit">Logout</button>
      </form>
    </div>
  </div>
  @else
  <div class="level is-mobile">
    <h5 class="level-left">
      Halo Dika
    </h5>
    <div class="level-right">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
    <button class="button" type="submit">Logout</button>
      </form>
    </div>
  </div>
  @endif
  <div class="level is-mobile">
    <div class="level-left">
      <h5 class="subtitle has-text-weight-bold">Ujian </h5>
    </div>
    <div class="level-right">
      
  <button class="button is-info my-2" onclick="document.getElementById('cret').classList.add('is-active')">
    Create Ujian
  </button>
    </div>
  </div>
  <div class="modal" id="cret">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <h5 class="title">Create Ujian</h5>
      </header>
      <section class="modal-card-body">
        <form action="{{route('guru.store')}}" method="post">
          @csrf
         <div class="field">
          
         <div class="level is-mobile">
           <div class="level-left">
             <div class="field">
           <div class="select">
             <select name="mapel_id" id="mapel_id">
               <option value="">Pilih Mapel Anda</option>
               @foreach($map as $mp)
               <option value="{{$mp->id}}">{{$mp->nama_mapel}}</option>
               @endforeach
             </select>
           </div>
         </div>
           </div>
           <div class="level-right">
             
           </div>
         </div>
         
         
         <input type="text" class="input" name="nama" value="{{$ire->nama}}" readonly>
         <label for="" class="label">Nama Ujian</label>
         <div class="field">
           <div class="control">
         <input type="text" class="input" name="nama_ujian" placeholder="Nama Ujian ">
             
           </div>
         </div>
         <div class="field">
           <div class="control">
             <input type="number" name="durasi" id="durasi" class="input" placeholder="durasi">/menit
           </div>
         </div>
         <div class="field">
           <div class="control"><input type="text" class="input" name="grade" placeholder="Untuk Tingkatan Kelas">
           </div>
         </div>
         <div class="field">
           <div class="control"><input type="text" class="input" name="catatan" placeholder="Catatan Kelas Misal : Untuk XI PPLG 1 & 2">
           </div>
         </div>
         
      </section>
      <footer class="modal-card-foot">
        <div class="buttons">
          <button type="reset" class="button is-danger-dark" onclick="document.getElementById('cret').classList.remove('is-active')">Cancel</button>
          <button type="submit" class="button is-info">submit</button>
        </div>
        </form>
      </footer>
    </div>
  </div>
  @foreach($uji as $uj)
  <article class="message is-dark">
    
    <div class="message-body">
      {{$uj->durasi}} <br>
      {{$uj->jadwal ? \Carbon\Carbon::parse($uj->jadwal->waktu_mulai)->format("D F Y H:i") : "Belum Ditentukan"}} - {{$uj->jadwal ? \Carbon\Carbon::parse($uj->jadwal->waktu_selesai)->format("D F Y H:i") : "-"}} <br>
      {{$uj->id}} <br>
      
    </div>
    @if($uj->status === "draft")
    <div class="buttons is-centered">
      <form action="{{route('guru.soal.destroy',$uj->id)}}" method="post">
        @csrf
        @method("DELETE")
        <button type="submit" class="button is-danger">DELETE</button>
      </form>
      
        <a class="button is-primary" href="{{route('guru.create',$uj->id)}}">Manage</a>
        
        <button class="button is-info">Done</button>
        @else
        <span class="tag is-primary is-medium has-text-centered ">Tersedia</span>
        @endif
    </article>
 
  @endforeach
   </div>
  </div>
</body>
</html>