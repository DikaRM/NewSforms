<!DOCTYPE html>
<html lang="en" class="has-navbar-fixed-top">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Pengawas</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<style>
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
<body>
  <nav class="navbar is-fixed-top" role="navigation">
    <div class="navbar-brand">
          <a href="#" class="navbar-item has-text-dark has-text-weight-bold">
      Pengawas {{$data->guru->nama}}
    </a>
    </div>

    <div class="navbar-end">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
        <button type="submit" class="button is-danger is-dark">Logout</button>
      </form>
    </div>
  </nav>
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

  <a href="{{route('pengawas.index')}}" class="buttond">
              <i class="icon fa fa-person"></i>
  </a>
</div>
  <div class="container">
    @foreach($jads as $jd)
    <div class="card">
      <div class="card-header">
        <h5 class="title">{{$jd->ujian->nama_ujian }}</h5>
        </div>
        <div class="card-container">
          <p class="subtitle">
           Untuk Kelas : {{$jd->ujian->kelas->nama_kelas}}
           <br>
           {{$jd->ujian->durasi}} Menit
          </p>
          <p>Total Siswa :{{$jd->kelas->siswa->count()}}</p>
      </div>
      <div class="card-footer">
        <div class="card-footer-item">
          <a href="{{route('pengawas.show',$jd->id)}}" class="button">Awasi Sekarang !</a>
        </div>
      </div>
    </div>
    @endforeach
    
  </div>
  <h5 class="text">Copyright 2026 sforms</h5>
  
  
</body>
</html>