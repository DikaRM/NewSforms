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
      <form action="{{route('users.logout')}}" method="post">
        
        <button type="submit" class="button is-danger mt-2">Logout</button>
      </form>
    </div>
  </div>
    </div>
    <div class="panel-tabs has-text-info">
      <a href="{{route('admin.index')}}" class="has-text-info">Users</a>
      <a href="{{route('admin.siswa.index')}}" class="panel-item">Siswa</a>
      <a href="{{route('admin.guru.index')}}">Guru</a>
      <a href="{{route('admin.kelas')}}"> Kelas</a>
      <a href="{{route('admin.mapel')}}">Mapel</a>
      
    </div>
  </div>
  </div>
  <div class="container">
    @yield("content")
  </div>
</body>
</html>