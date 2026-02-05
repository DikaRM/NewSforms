<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="bulma.min.css">
</head>
<body>
  <div class="has-navbar-fixed-top">
  <div class="panel is-light">
    <div class="panel-heading ">
    <div class="level is-mobile">
    <div class="level-left">
      @if($ire)
  <h1 class="title">Welcome Your {{$ire->nama}}</h1>
  @else
  <h1 class="title">Welcome Dik</h1>
  @endif
    </div>
    <div class="level-right">
        <a href="{{route('users.logout')}}" class="button is-danger mt-2">Logout</a>
    </div>
  </div>
    </div>
    <div class="panel-tabs has-text-info">
      <a href="" class="has-text-info">Users</a>
      <a href="" class="panel-item">Ujian</a>
      <a href="">Bank Soal</a>
      <a href="">Laporan Hasil Ujian</a>
      <a href="">Materi</a>
      
    </div>
  </div>
  </div>
  <div class="container">
    @yield("content")
  </div>
</body>
</html>