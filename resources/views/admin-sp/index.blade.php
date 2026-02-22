<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="{{asset('bulma.min.css')}}">
</head>
<body>
  <nav class="navbar is-flex">
    <h5 class="title is-family-monospace">Operational ujian</h5>
    
    <div class="navbar-end">
      <form action="{{route('users.logout')}}" method="post">
        @csrf
        <button type="submit" class="button is-danger is-dark">Logout</button>
      </form>
    </div>
  </nav>
  <div class="columns m-3">
    <div class="column is-half-desktop">
      <div class="message is-info is-light">
        <div class="message-header">
          Total Kelas
        </div>
        <div class="message-body">
    Kelas : {{$kla->count()}}
          
        </div>
  </div>
    </div>
    <div class="column is-half-desktop">
            <div class="message is-secondary">
        <div class="message-header">
          Total Ujian
        </div>
        <div class="message-body">
    Ujian : {{$uji->count()}}
          
        </div>
  </div>
  </div>
  
  @foreach($uji as $uj)
  <div class="card">
    <div class="card-header">
      {{$uj->nama_ujian}}
    </div>
    <div class="card-content">
      <h5 class="title">{{$uj->mapels->nama_mapel}}</h5>
      <p class="subtitle">Waktu <br>
      {{$uj->durasi}} Menit
      <br>
      {{$uj->status}}
      </p>
      
    </div>
  </div>
  @endforeach
  
  <h5 class="title is-family-code">Set Jadwal Ujian</h5>
  @foreach($kla as $k)
  <div class="card mt-3">
    <div class="card-header">
      <h5 class="title">{{$k->nama_kelas}}</h5>
    </div>
    
    <div class="card-content">
      @php
      $mop = $sis->where("kelas_id",$k->id);
      @endphp
      {{$mop->count()}}Siswa/Siswi
    </div>
    
    <div class="card-footer has-text-centered">
      <a href="{{route('admin-ops.set',$k->id)}}" class="button is-info is-dark is-fullwidth mx-2">Set Jadwal</a>
    </div>
  </div>
  @endforeach
</body>
</html>